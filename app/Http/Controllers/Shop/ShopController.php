<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Controller;
use App\Models\ShopGeecoCategory;
use App\Models\GeecoCategory;
use App\Models\CustomerShop;
use Illuminate\Http\Request;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\Shop;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        // Get 15 customer filtered by condition if there is any
        
        $top_shop_days = $request->query('top');

        if (isset($top_shop_days) == true && is_numeric($top_shop_days) == true) {
            $shop_ids = OrderDetail::select('shopId')
                                    ->join('orders', 'orders.orderId', '=', 'orderdetails.orderId')
                                    ->join('products', 'products.productId', '=', 'orderdetails.productId')
                                    ->where('orders.orderDate', '>=', date('Y-m-d', strtotime('-'.$top_shop_days.' days')))
                                    ->groupBy('shopId')
                                    ->orderByRaw('shopId DESC')
                                    ->limit(10)
                                    ->get()
                                    ->toArray();

            $shops = Shop::select('shopId', 'shopName', 'shopShortDescription', 'shopDescription', 'shopCreationDate', 'shopImage')
                         ->whereIn('shopId', $shop_ids)->get();

            return response()->json(['Shops' => $shops], 200);
        }

        try
        {
            $shop_id_array_1 = Shop::select('shopId')
                ->where('shopName', 'like', '%' . $request->input('key') . '%')
                ->orWhere('shopTag', 'like', '%' . $request->input('key') . '%')
                ->orWhere('shopShortDescription', 'like', '%' . $request->input('key') . '%')
                ->orWhere('shopDescription', 'like', '%' . $request->input('key') . '%')
                ->get()
                ->toArray();

            $shop_id_array_2 = ShopGeecoCategory::select('shopId')
                ->join('geecocategory', 'geecocategory.categoryId', '=', 'shopgeecocategories.categoryId')
                ->where('categoryName', 'like', '%' . $request->input('key') . '%')
                ->get()
                ->toArray();
            
            $key_array = $this->extractKeyWords($request->input('key'));
            $shop_id_array_3 = array();

            foreach ($key_array as $key) {
                $ids = Shop::select('shopId')
                    ->where('shopShortDescription', 'like', '%' . $key . '%')
                    ->orWhere('shopDescription', 'like', '%' . $key . '%')
                    ->orWhere('shopTag', 'like', '%' . $key . '%')
                    ->get();
                foreach ($ids as $id) {
                    array_push($shop_id_array_3, $id->shopId);
                }
            }

            $shop_id_array = array_unique(array_merge($shop_id_array_1, $shop_id_array_2, $shop_id_array_3), SORT_REGULAR); 

            $shops = Shop::select('shopId', 'shopName', 'shopShortDescription', 'shopDescription', 'shopCreationDate', 'shopImage')
                ->whereIn('shopId', $shop_id_array)
                ->get();
            
            
                // Return all shops
            return response()->json(['Shops' => $shops], 200);
        }
        catch (Exception $e)
        {
            return response()->json(['Message' => "Sorry, Something went wrong." ], 500);
        }
    }

    public function store(Request $request)
    {
        // Create new shop inside the database

        if($request->header('Authorization') == null || $request->header('Authorization') == '') {
            return response()->json(['Message' => "Sorry, No jwt auth." ], 401);
        }   

        $token = $request->header('Authorization');
        $result = AuthController::check($token, false);
        
        if($result == false) {
            return response()->json(['Message' => "Sorry, You are not authorized." ], 401);
        }
        else {
            $id = AuthController::getIdFromToken($token);

            if($id == -1) {
                return response()->json(['Message' => "Sorry, Something went wrong." ], 500);
            }

            $check_user = Shop::where('shopOwner', $id)->first();

            if($check_user != null) {
                return response()->json(['Message' => "Sorry, You already have a shop." ], 500);
            }

            //Validate the request
            $this->validate($request, [
                'shopName' => 'required|regex:/^[\pL\s\-]+$/u|max:42|unique:shop,shopName',
                'shopShortDescription' => 'required|max:85',
                'shopDescription' => 'required',
                'shopTag' => 'nullable',
                'shopImage' => 'required|image|mimes:jpeg,png,jpg|max:2048',
                'shopCategory_1nd' => 'required|exists:geecocategory,categoryName',
                'shopCategory_2nd' => 'nullable|exists:geecocategory,categoryName',
                'shopCategory_3nd' => 'nullable|exists:geecocategory,categoryName'
            ]);

            //check shop tag
            if($request->input('shopTag') != null) {
                $shop_tag = explode(';', rtrim($request->input('shopTag'), ';'));

                if(count($shop_tag) > 4) {
                    return response()->json(['Message' => "Sorry, You can only have 4 tags." ], 422);
                }
            }

            //Create shop and set attributes
            $shop = new Shop;

            $shop->shopName = $request->input('shopName');
            $shop->shopShortDescription = $request->input('shopShortDescription');
            $shop->shopDescription = $request->input('shopDescription');
            $shop->shopTag = $request->input('shopTag');
            $shop->shopCreationDate = date('Y-m-d H:i:s');
            $shop->shopOwner = $id;
            $shop->shopImage = str_replace(' ', '.', $request->input('shopName') . '.' . $request->file('shopImage')->getClientOriginalExtension());

            //Save shop in database
            try
            {
                $shop->save();

                //Save shop image
                $image = $request->file('shopImage');
                $imageName = str_replace(' ', '.', $request->input('shopName')) . '.' . $image->getClientOriginalExtension();
                $image->move('images\shopImages', $imageName);

                //shopCategory_1nd
                $this->addShopCategory($shop->shopId, $request->input('shopCategory_1nd'));

                //shopCategory_2nd
                if($request->input('shopCategory_2nd') != null) {
                    $this->addShopCategory($shop->shopId, $request->input('shopCategory_2nd'));
                }

                //shopCategory_3nd
                if($request->input('shopCategory_3nd') != null) {
                    $this->addShopCategory($shop->shopId, $request->input('shopCategory_3nd'));
                }

                return response()->json(['Message' => "Shop created successfully." ], 200);
            }
            catch (Exception $e)
            {
                return response()->json(['Message' => "Sorry, Something went wrong." ], 500);
            }
        }
    }

    public static function addShopCategory($shop_id, $category_name)
    {
        $category = GeecoCategory::select('categoryId')
                    ->where('categoryName', $category_name)
                    ->get();
        
        $category_id = $category[0]->categoryId;

        $shop_category = new ShopGeecoCategory();
        $shop_category->shopId = $shop_id;
        $shop_category->categoryId = $category_id;

        $shop_category->save();
    }

    function extractKeyWords($string) {
        mb_internal_encoding('UTF-8');
        
        $stopwords = array();
        $string = preg_replace('/[\pP]/u', '', trim(preg_replace('/\s\s+/iu', '', mb_strtolower($string))));
        $matchWords = array_filter(explode(' ',$string) , function ($item) use ($stopwords) { return !($item == '' || in_array($item, $stopwords) || mb_strlen($item) <= 3 || is_numeric($item));});
        $wordCountArr = array_count_values($matchWords);
        arsort($wordCountArr);

        return array_keys(array_slice($wordCountArr, 0, 10));
    }

    public function show(Request $request, $id)
    {
        try
        {
            $shop = Shop::select('shopId', 'shopName', 'shopShortDescription', 'shopDescription', 'shopCreationDate', 'shopImage')
                        ->find($id);
        }
        catch (Exception $e)
        {
            return response()->json(['Message' => "Sorry, Something went wrong." ], 500);
        }

        if($shop == null) {
            return response()->json(['Message' => "Sorry, Shop not found." ], 404);
        }
        else {
            if($request->header('Authorization') != null || $request->header('Authorization') != '') {
                $token = $request->header('Authorization');
                $result = AuthController::check($token, false);
    
                if($result != false) {
                    $customer_id = AuthController::getIdFromToken($token);
                    
                    if($id != -1) {

                        //if already exist a record
                        $row = CustomerShop::where('shopId', $id)
                                            ->where('customerId', $customer_id)
                                            ->first();
                        
                        if($row == null) {
                            $shop_customer = new CustomerShop();
                            $shop_customer->shopId = $id;
                            $shop_customer->customerId = $customer_id;
                            $shop_customer->isFollowing = 0;
                            $shop_customer->lastInteraction = date('Y-m-d H:i:s');

                            $shop_customer->save();
                        }
                        else {
                            $row->lastInteraction = date('Y-m-d H:i:s');
                            
                            $mysqli = new \mysqli(env('DB_HOST'), env('DB_USERNAME'), env('DB_PASSWORD'), env('DB_DATABASE'));
                            $mysqli->query("UPDATE customershop SET lastInteraction = '".$row->lastInteraction."' WHERE shopId = ".$row->shopId." AND customerId = ".$row->customerId);

                            $mysqli->close();
                        }
                    }
                }
            }  

            return response()->json(['Shop' => $shop], 200);
        }    
    }

    public function showme(Request $request)
    {
        if($request->header('Authorization') == null || $request->header('Authorization') == '') {
            return response()->json(['Message' => "Sorry, No jwt auth." ], 401);
        }  

        $token = $request->header('Authorization');
        $result = AuthController::check($token, false);

        if($result != false) {
            $id = AuthController::getIdFromToken($token);

            try
            {
                $shop = Shop::select('shopId', 'shopName', 'shopShortDescription', 'shopDescription', 'shopCreationDate', 'shopImage')
                            ->where('shopOwner', $id)
                            ->get();
            }
            catch (Exception $e)
            {
                return response()->json(['Message' => "Sorry, Something went wrong." ], 500);
            }

            if(count($shop) == 0) {
                return response()->json(['Message' => "Sorry, You don't have any shop." ], 404);
            }
            else {
                return response()->json(['Shop' => $shop], 200);
            }
        }
        else {
            return response()->json(['Message' => "Sorry, You are not authorized." ], 401);
        }
    }

    public function update(Request $request, $id)
    {
        
    }

    public function destroy($id)
    {
        
    }
}
