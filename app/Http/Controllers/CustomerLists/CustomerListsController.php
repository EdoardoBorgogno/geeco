<?php

namespace App\Http\Controllers\CustomerLists;

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Controller;
use App\Models\CustomerLists;
use Illuminate\Http\Request;

class CustomerListsController extends Controller
{
    public function index(Request $request)
    {
        if($request->header('Authorization') == null || $request->header('Authorization') == '') {
            return response()->json(['Message' => "Sorry, No jwt auth." ], 401);
        }   

        $token = $request->header('Authorization');
        $result = AuthController::check($token, true);

        if($result == false) {
            return response()->json(['Message' => "Sorry, You are not authorized." ], 401);
        }
        else {
            $id = AuthController::getIdFromToken($token);

            if($id == -1) {
                return response()->json(['Message' => "Sorry, Something went wrong." ], 500);
            }

            $list_type = $request->query('list');

            if($list_type == null || $list_type == '') {
                return response()->json(['Message' => "Sorry, No list type." ], 400);
            }

            $customerLists = CustomerLists::where('customerId', $id)
                                          ->where('listType', $list_type)
                                          ->get();

            return response()->json(['Products' => $customerLists ], 200);
        }
    }

    public function store(Request $request)
    {
        if($request->header('Authorization') == null || $request->header('Authorization') == '') {
            return response()->json(['Message' => "Sorry, No jwt auth." ], 401);
        }   

        $token = $request->header('Authorization');
        $result = AuthController::check($token, true);

        if($result == false) {
            return response()->json(['Message' => "Sorry, You are not authorized." ], 401);
        }
        else {
            $id = AuthController::getIdFromToken($token);

            if($id == -1) {
                return response()->json(['Message' => "Sorry, Something went wrong." ], 500);
            }

            //validate request
            $this->validate($request, [
                'productId' => 'required|integer|exists:products,productId',
                'quantity' => 'required|integer|min:1',
                'listType' => 'required|string|in:cart,dream'
            ]);

            //create new customer list
            $customerList = new CustomerLists;

            $customerList->customerId = $id;
            $customerList->productId = $request->input('productId');
            $customerList->productQuantity = $request->input('listType') == 'cart' ? $request->input('quantity') : 1;
            $customerList->listType = $request->input('listType');
            $customerList->insertionDate = date('Y-m-d H:i:s');

            try
            {
                $customerList->save();

                return response()->json(['Message' => "Added correctly!" ], 200);
            }
            catch (\Illuminate\Database\QueryException $e)
            {
                return response()->json(['Message' => "Sorry, You already have this product in your list." ], 409);
            }
            catch (Exception $e)
            {
                return response()->json(['Message' => "Sorry, Something went wrong." ], 500);
            }

        }
    }

    public function show($id)
    {
        
    }

    public function update(Request $request, $id)
    {
        
    }

    public function destroy($id)
    {
        
    }
}
