<?php

namespace App\Http\Controllers\ShopCategory;

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ShopCategory;
use App\Models\ShopUser;
use App\Models\Shop;

class ShopCategoryController extends Controller
{
    public function index(Request $request)
    {
        $shop_id = $request->query('id');

        $shopCategories = ShopCategory::select('shopCategoryId', 'categoryName', 'categoryShortDescription', 'categoryDescription', 'insertionDate', 'categoryImage')
                                      ->where('shopId', $shop_id)
                                      ->get();

        return response()->json(['shopCategories' => $shopCategories], 200);
    }

    public function store(Request $request)
    {
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

            $shop = Shop::where('shopOwner', $id)->first();

            if($shop == null) {
                return response()->json(['Message' => "Sorry, You are not authorized." ], 401);
            }

            $shop_id = $shop->shopId;

            //trim all request data
            foreach ($request->all() as $key => $value) {
                if (is_string($value)) {
                    $request[$key] = trim($value);
                }
            }

            //validate request
            $this->validate($request, [
                //categoryName unique name if same shopId
                'categoryName' => 'required|string|regex:/^[\pL\s\-]+$/u|max:150|unique:shopcategory,categoryName,NULL,shopCategoryId,shopId,'.$shop_id,
                'categoryShortDescription' => 'required|string|max:150',
                'categoryDescription' => 'nullable|string',
                'categoryImage' => 'required|image|mimes:jpeg,png,jpg|max:2048'
            ]);

            $shop_categories = new ShopCategory;

            $shop_categories->shopId = $shop_id;
            $shop_categories->categoryName = $request->categoryName;
            $shop_categories->categoryShortDescription = $request->categoryShortDescription;
            $shop_categories->categoryDescription = $request->categoryDescription;
            $shop_categories->insertionDate = date('Y-m-d H:i:s');
            
            try
            {
                //Save category image
                $image = $request->file('categoryImage');
                $imageName = $shop_id . '.' . $request->categoryName . '.' . $image->getClientOriginalExtension();
                $image->move('images\shopCategoryImages', $imageName);

                $shop_categories->categoryImage = $imageName;

                $shop_categories->save();

                return response()->json(['Message' => "Category created successfully." ], 200);
            }
            catch(\Exception $e)
            {
                return response()->json(['Message' => "Sorry, Something went wrong." ], 500);
            }
        }
    }

    public function show($id)
    {
        $shopCategory = ShopCategory::select('shopCategoryId', 'categoryName', 'categoryShortDescription', 'categoryDescription', 'insertionDate', 'categoryImage')
                                    ->where('shopCategoryId', $id)
                                    ->first();

        if($shopCategory == null) {
            return response()->json(['Message' => "Sorry, Category not found." ], 404);
        }

        return response()->json($shopCategory, 200);
    }

    public function update($category_id, Request $request)
    {
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

            $shop = Shop::where('shopOwner', $id)->first();

            if($shop == null) {
                return response()->json(['Message' => "Sorry, You are not authorized." ], 401);
            }

            $shop_id = $shop->shopId;

            $shop_categories = ShopCategory::where('shopCategoryId', $category_id)->where('shopId', $shop_id)->first();

            if($shop_categories == null) {
                return response()->json(['Message' => "Sorry, no category with this id." ], 404);
            }

            //trim all request data
            foreach ($request->all() as $key => $value) {
                if (is_string($value)) {
                    $request[$key] = trim($value);
                }
            }

            //validate request
            $this->validate($request, [
                'categoryName' => 'nullable|string|regex:/^[\pL\s\-]+$/u|max:150|unique:shopcategory,categoryName,NULL,shopCategoryId,shopId,'.$shop_id,
                'categoryShortDescription' => 'nullable|string|max:150',
                'categoryDescription' => 'nullable|string',
                'categoryImage' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
            ]);
            
            if($request->categoryName != null) {
                $shop_categories->categoryName = $request->categoryName;
            }

            if($request->categoryShortDescription != null) {
                $shop_categories->categoryShortDescription = $request->categoryShortDescription;
            }

            if($request->categoryDescription != null) {
                $shop_categories->categoryDescription = $request->categoryDescription;
            }

            if($request->file('categoryImage') != null) {

                //delete old image
                $oldImage = $shop_categories->categoryImage;
                $oldImagePath = 'images\shopCategoryImages\\' . $oldImage;

                if(file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }

                //Save category image
                $image = $request->file('categoryImage');
                $imageName = $shop_id . '.' . $shop_categories->categoryName . '.' . $image->getClientOriginalExtension();
                $image->move('images\shopCategoryImages', $imageName);

                $shop_categories->categoryImage = $imageName;
            }

            try
            {
                $shop_categories->save();

                return response()->json(['Message' => "Category updated successfully." ], 200);
            }
            catch(\Exception $e)
            {
                return response()->json(['Message' => "Sorry, Something went wrong." ], 500);
            }
        }
    }

    public function destroy($category_id, Request $request)
    {
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

            $shop = Shop::where('shopOwner', $id)->first();

            if($shop == null) {
                return response()->json(['Message' => "Sorry, You are not authorized." ], 401);
            }

            $shop_id = $shop->shopId;

            $shopCategory = ShopCategory::where('shopCategoryId', $category_id)->where('shopId', $shop_id)->first();

            if($shopCategory == null) {
                return response()->json(['Message' => "Sorry, no category with this id." ], 404);
            }

            try
            {
                //remove image
                $oldImage = $shopCategory->categoryImage;
                $oldImagePath = 'images\shopCategoryImages\\' . $oldImage;

                $shopCategory->delete();

                if(file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }

                return response()->json(['Message' => "Category deleted successfully." ], 200);
            }
            catch(\Illuminate\Database\QueryException $e)
            {
                return response()->json(['Message' => "Sorry, delete or move in other category all product in '" . $shopCategory->categoryName . "'." ], 500);
            }
            catch(\Exception $e)
            {
                return response()->json(['Message' => "Sorry, Something went wrong." ], 500);
            }
        }
    }
}
