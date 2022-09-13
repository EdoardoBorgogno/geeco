<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;

class AuthController extends Controller
{
    public function auth(Request $request, $is_customer_value)
    {
        $is_customer = $is_customer_value == 'customer' ? true : false;
        
        // validate the request
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required'
        ]);
    
        //Get data for db connection 
        $db_host = config()['database']['connections']['mysql']['host'];
        $db_user = config()['database']['connections']['mysql']['username'];
        $db_pass = config()['database']['connections']['mysql']['password'];
        $db_name = config()['database']['connections']['mysql']['database'];

        // connect to db
        $db = new \mysqli($db_host, $db_user, $db_pass, $db_name);

        // check connection
        if ($db->connect_error) {
            return response()->json(['Message' => 'Sorry, Something went wrong'], 500);
        }
        
        //set data for db query
        $email = $request->input('email');
        $password = $request->input('password');
        $table = $is_customer ? 'customer' : 'shopusers';
        $column = $is_customer ? 'customerEmail' : 'userEmail';

        //Prepare query, bind parameters and execute
        try
        {
            $stmt = $db->prepare("SELECT * FROM $table WHERE $column = ?");    
            $stmt->bind_param('s', $email);
    
            $stmt->execute();

            $result = $stmt->get_result();

            //check if user exists
            if ($result->num_rows == 0) {
                return response()->json(['Message' => 'Invalid credentials'], 401);
            }
            else {
                
                $row = $result->fetch_assoc();
                
                $id = $is_customer ? $row["customerId"] : $row["userId"];
                $password_hash = $is_customer ? $row["customerPasswordHash"] : $row["userPasswordHash"];
                $drop_date = $is_customer ? $row["customerDropDate"] : $row["userDropDate"];
                
                //check if password is correct
                if(password_verify($password, $password_hash)) {

                    //check if user is active
                    if($drop_date == null && $row['emailChecked'] == true) {
                        $secret_key = $is_customer ? env('JWT_SECRET_KEY_CUSTOMER') : env('JWT_SECRET_KEY_USER');
                        $issuer_claim = "geeco.rocks"; 
                        $issuedat_claim = time(); 
                        $notbefore_claim = $issuedat_claim; 

                        $number_of_days = $is_customer ? 6 : 1;

                        $expire_claim = $issuedat_claim + (86400 * $number_of_days); 
                        
                        $token = array(
                            "iss" => $issuer_claim,
                            "iat" => $issuedat_claim,
                            "nbf" => $notbefore_claim,
                            "exp" => $expire_claim,
                            "data" => array(
                                "id" => $id,
                                "type" => $is_customer ? "customer" : "user",
                        ));

                        $jwt = JWT::encode($token, $secret_key, 'HS512');

                        return response()->json(['Message' => 'Successful login', 'token' => $jwt], 200);
                    }
                    else {
                        return response()->json(['Message' => 'User is not active or being pashed out'], 401);
                    }
                }
                else {
                    return response()->json(['Message' => 'Invalid credentials'], 401);
                }
            }
        }
        catch (Exception $e)
        {
            return response()->json(['Message' => 'Sorry, Something went wrong'], 500);
        }
    }

    public static function check(string $jwt, bool $is_customer = null): bool
    {
        try 
        {
            //Get data for db connection 
            $db_host = config()['database']['connections']['mysql']['host'];
            $db_user = config()['database']['connections']['mysql']['username'];
            $db_pass = config()['database']['connections']['mysql']['password'];
            $db_name = config()['database']['connections']['mysql']['database'];

            $tks = explode('.', $jwt);
            list($headb64, $bodyb64, $cryptob64) = $tks;
            $payload = JWT::jsonDecode(JWT::urlsafeB64Decode($bodyb64));

            if(isset($is_customer))
            {
                if($is_customer && $payload->data->type == 'customer')
                    $secret_key = env('JWT_SECRET_KEY_CUSTOMER');
                else if(!$is_customer && $payload->data->type == 'user')
                    $secret_key = env('JWT_SECRET_KEY_USER');
                else
                    return false;
            }
            else
                $secret_key = $payload->data->type == 'customer' ? env('JWT_SECRET_KEY_CUSTOMER') : env('JWT_SECRET_KEY_USER');

            // connect to db
            $db = new \mysqli($db_host, $db_user, $db_pass, $db_name);

            // check connection
            if ($db->connect_error) {
                return false;
            }
            
            if($jwt) {
                try 
                {
                    $decoded = JWT::decode($jwt, new Key($secret_key, 'HS512')); 
                    
                    $id = $decoded->data->id;
                    $table = $decoded->data->type == 'customer' ? 'customer' : 'shopusers';
                    $columnId = $decoded->data->type == 'customer' ? 'customerId' : 'userId';
                    $column = $decoded->data->type == 'customer' ? 'customerDropDate' : 'userDropDate';

                    $stmt = $db->prepare("SELECT * FROM $table WHERE $columnId = ?");
                    $stmt->bind_param('i', $id);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $row = $result->fetch_assoc();

                    $drop_date = $row[$column];

                    if($drop_date == null) {
                        return true;
                    }
                    else {
                        return false;
                    }
                }
                catch (\Exception $e)
                {
                    return false;
                }
            }
            else {
                return false;
            }
        }
        catch (\Exception $e)
        {
            return false;
        }
    }

    public function checkValidate(Request $request) 
    {
        $this->validate($request, [
            'token' => 'required'
        ]);

        $jwt = $request->input('token');

        if($this->check($jwt, $request->input('is_customer'))) {
            return response()->json(['Message' => 'Token is valid'], 200);
        }
        else {
            return response()->json(['Message' => 'Token is invalid'], 401);
        }
    }

    public static function getIdFromToken(string $jwt): int
    {
        if($jwt) {

            try 
            {
                $tks = explode('.', $jwt);
                list($headb64, $bodyb64, $cryptob64) = $tks;
                $payload = JWT::jsonDecode(JWT::urlsafeB64Decode($bodyb64));

                return $payload->data->id;
            }
            catch (\Exception $e)
            {
                return -1;
            }
        }

        return -1;
    }
}

?>