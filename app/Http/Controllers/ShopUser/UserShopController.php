<?php

namespace App\Http\Controllers\ShopUser;

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ShopUser;

class UserShopController extends Controller
{
    public function store(Request $request)
    {
        // Create new user inside the database

        //Validate request
        $this->validate($request, [
            'userFirstName' => 'required',
            'userLastName' => 'required',
            'userEmail' => 'required|email|unique:shopusers,userEmail',
            'userPhone' => 'required',
            'userPassword' => 'required|min:8',
            'userDateBirth' => 'required|date|before:today - 18 years',
            'userNationGuid' => 'required',
            'userGender' => 'required'
        ]);

        //Create user and set attributes
        $user = new ShopUser();

        $user->userFirstName = $request->input('userFirstName');
        $user->userLastName = $request->input('userLastName');
        $user->userEmail = $request->input('userEmail');
        $user->userPhone = $request->input('userPhone');
        $user->userPasswordHash = password_hash($request->input('userPassword'), PASSWORD_BCRYPT);
        $user->userDateBirth = date("Y-m-d", strtotime($request->input('userDateBirth')));
        $user->userNationGuid = $request->input('userNationGuid');
        $user->userGender = $request->input('userGender');
        $user->userCreationDate = date('Y-m-d H:i:s');
        $user->userDropDate = null;
        $user->emailChecked = 0;

        //Save user to database
        try
        {
            $user->save();
            return response()->json(['Message' => "User created successfully." ], 200);
        }
        catch (Exception $e)
        {
            return response()->json(['Message' => "Sorry, Something went wrong." ], 500);
        }
    }

    public function show($id)
    {
        //Get user from database
        
        try
        {
            $user = ShopUser::select('userFirstName', 'userLastName', 'userCreationDate')
                        ->find($id);
        }
        catch (Exception $e)
        {
            return response()->json(['Message' => "Sorry, Something went wrong." ], 500);
        }

        if (!$user) {
            return response()->json(['Message' => "Sorry, Customer not found." ], 404);
        }
        else {
            return response()->json(['Customer' => $user], 200);
        }
    }

    public function showme(Request $request)
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

            $user = ShopUser::find($id);

            if($user == null) {
                return response()->json(['Message' => "Sorry, User not found." ], 404);
            }
            else {
                unset($user->userPasswordHash);
                return response()->json($user, 200);
            }
        }
    }

    public function update(Request $request)
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
            else {
                try
                {
                    $user = ShopUser::find($id);
                }
                catch (Exception $e)
                {
                    return response()->json(['Message' => "Sorry, Something went wrong." ], 500);
                }

                if($user == null) {
                    return response()->json(['Message' => "Sorry, User not found." ], 404);
                }
                else {

                    //Validate request
                    $this->validate($request, [
                        'userFirstName' => 'nullable',
                        'userLastName' => 'nullable',
                        'userEmail' => 'nullable|email|unique:shopusers,userEmail',
                        'userPhone' => 'nullable',
                        'userDateBirth' => 'nullable|date',
                        'userNationGuid' => 'nullable',
                        'userGender' => 'nullable'
                    ]);

                    //foreach attribute in request, if there is any, update it
                    if($request->input('userFirstName') != null) {
                        $user->userFirstName = $request->input('userFirstName');
                    }

                    if($request->input('userLastName') != null) {
                        $user->userLastName = $request->input('userLastName');
                    }

                    if($request->input('userEmail') != null) {
                        $user->userEmail = $request->input('userEmail');
                    }

                    if($request->input('userPhone') != null) {
                        $user->userPhone = $request->input('userPhone');
                    }

                    if($request->input('userDateBirth') != null) {
                        $user->userDateBirth = date("Y-m-d", strtotime($request->input('userDateBirth')));
                    }

                    if($request->input('userNationGuid') != null) {
                        $user->userNationGuid = $request->input('userNationGuid');
                    }

                    if($request->input('userGender') != null) {
                        $user->userGender = $request->input('userGender');
                    }

                    //Save user to database
                    try
                    {
                        $user->save();
                        return response()->json(['Message' => "User updated successfully." ], 200);
                    }
                    catch (Exception $e)
                    {
                        return response()->json(['Message' => "Sorry, Something went wrong." ], 500);
                    }
                }
            }
        }

    }

    public function destroy(Request $request)
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
            try
            {
                $user = ShopUser::find($id);
            }
            catch (Exception $e)
            {
                return response()->json(['Message' => "Sorry, Something went wrong." ], 500);
            }

            if (!$user) {
                return response()->json(['Message' => "Sorry, User not found." ], 404);
            }
            else {

                //set user drop date to current date
                $user->userDropDate = date('Y-m-d H:i:s');
                $user->save();

                return response()->json(['Message' => "User will be deleted in 90 days." ], 200);
            }
        }
    }

}
