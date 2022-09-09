<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        // Get 5 customer filtered by condition if there is any
        
        try
        {
            $customers = Customer::select('customerFisrtName', 'customerLastName', 'customerCreationDate')
            ->limit(5)
            ->where('customerLastName', 'like', '%' . $request->input('customerLastName') . '%')
            ->where('customerFisrtName', 'like', '%' . $request->input('customerFirstName') . '%')
            ->get();

            // Return all customers
            return response()->json(['Customers' => $customers], 200);
        }
        catch (Exception $e)
        {
            return response()->json(['Message' => "Sorry, Something went wrong." ], 500);
        }

    }

    public function store(Request $request)
    {
        // Create new customer inside the database

        //Validate request
        $this->validate($request, [
            'customerFirstName' => 'required',
            'customerLastName' => 'required',
            'customerEmail' => 'required|email|unique:customer,customerEmail',
            'customerPhone' => 'required',
            'customerPassword' => 'required|min:8',
            'customerDateBirth' => 'required|date'
        ]);

        //Create customer and set attributes
        $customer = new Customer();

        $customer->customerFisrtName = $request->input('customerFirstName');
        $customer->customerLastName = $request->input('customerLastName');
        $customer->customerEmail = $request->input('customerEmail');
        $customer->customerPhone = $request->input('customerPhone');
        $customer->customerPasswordHash = password_hash($request->input('customerPassword'), PASSWORD_BCRYPT);
        $customer->customerDateBirth = $request->input('customerDateBirth');
        $customer->customerCreationDate = date('Y-m-d H:i:s');
        $customer->emailChecked = 0;
        $customer->customerDropDate = null;

        //Save customer in database
        try
        {
            $customer->save();
            return response()->json(['Message' => "Customer created successfully." ], 200);
        }
        catch (Exception $e)
        {
            return response()->json(['Message' => "Sorry, Something went wrong." ], 500);
        }
    }

    public function show($id)
    {
        // Show customer by id

        try
        {
            $customer = Customer::select('customerFisrtName', 'customerLastName', 'customerCreationDate')
                        ->find($id);
        }
        catch (Exception $e)
        {
            return response()->json(['Message' => "Sorry, Something went wrong." ], 500);
        }

        if (!$customer) {
            return response()->json(['Message' => "Sorry, Customer not found." ], 404);
        }
        else {
            return response()->json(['Customer' => $customer], 200);
        }
    }

    public function showme(Request $request)
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

            $customer = Customer::find($id);

            if($customer == null) {
                return response()->json(['Message' => "Sorry, Customer not found." ], 404);
            }
            else {
                unset($customer->customerPasswordHash);
                return response()->json($customer, 200);
            }
        }
    }

    public function update(Request $request)
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
            
            // Update customer by id
            $id = AuthController::getIdFromToken($token);

            if($id == -1) {
                return response()->json(['Message' => "Sorry, Something went wrong." ], 500);
            }

            try
            {
                $customer = Customer::find($id);
            }
            catch (Exception $e)
            {
                return response()->json(['Message' => "Sorry, Something went wrong." ], 500);
            }

            if (!$customer) {
                return response()->json(['Message' => "Sorry, Customer not found." ], 404);
            }
            else {

                //Validate request
                $this->validate($request, [
                    'customerFirstName' => 'nullable',
                    'customerLastName' => 'nullable',
                    'customerPhone' => 'nullable',
                    'customerDateBirth' => 'nullable|date'
                ]);

                //for each attribute in request, if there is any, update it
                if($request->input('customerFirstName') != null) {
                    $customer->customerFisrtName = $request->input('customerFirstName');
                }

                if($request->input('customerLastName') != null) {
                    $customer->customerLastName = $request->input('customerLastName');
                }

                if($request->input('customerPhone') != null) {
                    $customer->customerPhone = $request->input('customerPhone');
                }

                if($request->input('customerDateBirth') != null) {
                    $customer->customerDateBirth = $request->input('customerDateBirth');
                }

                $customer->save();

                return response()->json(['Message' => "Customer updated successfully." ], 200);
            }
        }
    }

    public function destroy(Request $request)
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
            try
            {
                $customer = Customer::find($id);
            }
            catch (Exception $e)
            {
                return response()->json(['Message' => "Sorry, Something went wrong." ], 500);
            }

            if (!$customer) {
                return response()->json(['Message' => "Sorry, Customer not found." ], 404);
            }
            else {

                //set customer drop date to current date
                $customer->customerDropDate = date('Y-m-d H:i:s');
                $customer->save();

                return response()->json(['Message' => "Customer will be deleted in 30 days." ], 200);
            }
        }
    }
}
