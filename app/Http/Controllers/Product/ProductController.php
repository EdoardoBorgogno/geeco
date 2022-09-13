<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ShopCategory;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\Shop;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $shop_id = $request->query('id');
        $key = $request->query('key');
        $top_in_days = $request->query('top');

        if ($shop_id != null){
            $products = Product::select('products.productId', 'products.productPrice', 'products.productName', 'products.productDescription', 'bookable', 'availableFrom', 'shop.shopName', 'products.quantity', 'products.productDiscount', 'products.insertionDate', 'products.productImages', 'shopcategory.categoryName')
                                ->join('shopcategory', 'shopcategory.shopCategoryId', '=', 'products.categoryId')
                                ->join('shop', 'shop.shopId', '=', 'products.shopId')
                                ->where('products.shopId', $shop_id)
                                ->get();
                                
            return response()->json(["products" => $products], 200);
        }

        if ($key != null){
            $products_ids = [];

            $products_ids = array_merge($products_ids, Product::select('products.productId')
                                ->limit(20)  
                                ->where('products.productName', 'like', '%'.$key.'%')
                                ->get()
                                ->toArray()
                            );

            $keys = explode(" ", $key);
            
            if (count($keys) > 15){
                $keys = array_slice($keys, 0, 15);
            }

            //remove element with length < 3
            $keys = array_filter($keys, function($value) {
                return strlen($value) >= 3;
            });

            foreach ($keys as $key){
                $products_ids = array_merge($products_ids, Product::select('productId')
                                                                    ->limit(4)
                                                                    ->where('productName', 'like', '%'.$key.'%')
                                                                    ->orWhere('productDescription', 'like', '%'.$key.'%')
                                                                    ->get()->toArray()
                                            );
            }

            $products_ids = array_unique($products_ids, SORT_REGULAR);
            $products_ids = array_slice($products_ids, 0, 30);

            $products = [];
            foreach ($products_ids as $product_id){
                $products = array_merge($products, Product::select('products.productId', 'products.productPrice', 'products.productName', 'products.productDescription', 'bookable', 'availableFrom', 'shop.shopName', 'products.quantity', 'products.productDiscount', 'products.insertionDate', 'products.productImages', 'shopcategory.categoryName')
                                                        ->join('shopcategory', 'shopcategory.shopCategoryId', '=', 'products.categoryId')
                                                        ->join('shop', 'shop.shopId', '=', 'products.shopId')
                                                        ->where('products.productId', $product_id['productId'])
                                                        ->get()
                                                        ->toArray()
                                        );
            }
                                
            return response()->json(["products" => $products], 200);
        }

        if ($top_in_days != null){
            
            $products_ids = OrderDetail::select('productId')
                                        ->join('orders', 'orders.orderId', '=', 'orderdetails.orderId')
                                        ->where('orderDate', '>=', date('Y-m-d', strtotime('-'.$top_in_days.' days')))
                                        ->groupBy('productId')
                                        ->orderByRaw('SUM(quantity) DESC')
                                        ->limit(10)
                                        ->get()
                                        ->toArray();

            $products = [];
            foreach ($products_ids as $product_id){
                $products = array_merge($products, Product::select('products.productId', 'products.productPrice', 'products.productName', 'products.productDescription', 'bookable', 'availableFrom', 'shop.shopName', 'products.quantity', 'products.productDiscount', 'products.insertionDate', 'products.productImages', 'shopcategory.categoryName')
                                                        ->join('shopcategory', 'shopcategory.shopCategoryId', '=', 'products.categoryId')
                                                        ->join('shop', 'shop.shopId', '=', 'products.shopId')
                                                        ->where('products.productId', $product_id['productId'])
                                                        ->get()
                                                        ->toArray()
                                        );
            }

            return response()->json(["products" => $products], 200);
        }
    }

    public function store(Request $request)
    {
        if($request->header('Authorization') == null || $request->header('Authorization') == '') {
            return response()->json(['Message' => "Sorry, No jwt auth." ], 401);
        }   

        $token = $request->header('Authorization');
        $result = AuthController::check($token);

        if($result == false) {
            return response()->json(['Message' => "Sorry, You are not authorized." ], 401);
        }
        else {
            $id = AuthController::getIdFromToken($token);

            if($id == -1) {
                return response()->json(['Message' => "Sorry, Something went wrong." ], 500);
            }

            $shop = Shop::where('shopOwner', $id)->first();

            if($shop == null) {
                return response()->json(['Message' => "Sorry, You are not authorized." ], 401);
            }

            $shop_id = $shop->shopId;
            
            //validate request
            $this->validate($request, [
                'productName' => 'required|string|regex:/^[\pL\s\-]+$/u|max:150',
                'productDescription' => 'required|string',
                'productPrice' => 'required|numeric',
                'quantity' => 'required|numeric',
                'productImage_1' => 'required|image|mimes:jpeg,png,jpg|max:2048',
                'productImage_2' => 'required|image|mimes:jpeg,png,jpg|max:2048',
                'productImage_3' => 'required|image|mimes:jpeg,png,jpg|max:2048',
                'productImage_4' => 'required|image|mimes:jpeg,png,jpg|max:2048',
                'productImage_5' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
                'category' => 'required|exists:shopcategory,categoryName,shopId,'.$shop_id,
                'bookable' => 'required|boolean',
                'availableFrom' => 'required|date_format:Y-m-d H:i:s|after:now'
            ]);

            //Create Product and set attributes
            $product = new Product;

            $product->shopId = $shop_id;
            $product->productName = $request->input('productName');
            $product->productDescription = $request->input('productDescription');
            $product->productPrice = $request->input('productPrice');
            $product->quantity = $request->input('quantity');
            $product->bookable = $request->input('bookable');
            $product->availableFrom = $request->input('availableFrom');
            $product->insertionDate = date('Y-m-d H:i:s');

            //Handle shop category 
            $category = ShopCategory::where('categoryName', $request->input('category'))
                                    ->where('shopId', $shop_id)
                                    ->first();

            if($category != null)
                $product->categoryId = $category->shopCategoryId;
            else
                return response()->json(['Message' => "Sorry, Category not found." ], 404);
            
            //save product
            try
            {
                $product->productImages = '';
                $product->save();

                $product_id = $product->productId;

                //Handle product images
                $images_str = "";
                foreach($request->all() as $key => $value) {
                    if(preg_match('/^productImage_[1-5]$/', $key)) {
                        $image = $request->file($key);

                        $image_number = preg_replace('/^productImage_/', '', $key);
                        $imageName = $product_id . '.' . $image_number . '.' . $image->getClientOriginalExtension();

                        $images_str .= $imageName . ';';

                        $image->move('images\productImages', $imageName);
                    }
                }

                $product->productImages = $images_str;

                $product->save();

                return response()->json(['Message' => "Product created successfully." ], 200);
            }
            catch(\Exception $e)
            {
                return response()->json(['Message' => "Sorry, Something went wrong." ], 500);
            }
        }
    }

    public function show($id)
    {
        $product = Product::select('products.productId', 'products.productPrice', 'products.productName', 'products.productDescription', 'bookable', 'availableFrom', 'shop.shopName', 'products.quantity', 'products.productDiscount', 'products.insertionDate', 'products.productImages', 'shopcategory.categoryName')
                            ->join('shopcategory', 'shopcategory.shopCategoryId', '=', 'products.categoryId')
                            ->join('shop', 'shop.shopId', '=', 'products.shopId')
                            ->where('products.productId', $id)
                            ->get();

        if($product == null) {
            return response()->json(['Message' => "Sorry, Product not found." ], 404);
        }

        return response()->json($product, 200);
    }

    public function update(Request $request, $id)
    {
        
    }

    public function destroy($id, Request $request)
    {
        if($request->header('Authorization') == null || $request->header('Authorization') == '') {
            return response()->json(['Message' => "Sorry, No jwt auth." ], 401);
        }   

        $token = $request->header('Authorization');
        $result = AuthController::check($token);

        if($result == false) {
            return response()->json(['Message' => "Sorry, You are not authorized." ], 401);
        }
        else {
            $user_id = AuthController::getIdFromToken($token);

            if($user_id == -1) {
                return response()->json(['Message' => "Sorry, Something went wrong." ], 500);
            }

            $shop = Shop::where('shopOwner', $user_id)->first();

            if($shop == null) {
                return response()->json(['Message' => "Sorry, You are not authorized." ], 401);
            }

            $shop_id = $shop->shopId;

            $product = Product::where('productId', $id)
                                ->where('shopId', $shop_id)
                                ->first();

            if($product == null) {
                return response()->json(['Message' => "Sorry, Product not found." ], 404);
            }

            try
            {
                $product->delete();

                return response()->json(['Message' => "Product deleted successfully." ], 200);
            }
            catch(\Exception $e)
            {
                return response()->json(['Message' => "Sorry, Something went wrong." ], 500);
            }
        }
    }
}
