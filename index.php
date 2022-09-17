<?php



    //Index page

    //This page is root of the application

    //Handles the request and redirects to the appropriate page



	declare (strict_types = 1);

    
    // Refresh always  //
    header("Expires: Tue, 01 Jan 2000 00:00:00 GMT");
    header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");
    

    // Work on config file


    $config = json_decode(file_get_contents('config.json'), true);



    $base = $config['webAppRoot'];

    $api_base = $config['apiBaseUrl'];

    $index_base = $config['indexBase'];

    $site_base_url = $config['siteBaseUrl'];

	

    // Work on URL



    $url = explode("?", $_SERVER['REQUEST_URI'], 2);

    $url_parts = explode("/", $url[0], 2 + $index_base);


    //remove last '/' from url

    if (substr($url_parts[1 + $index_base], -1) == "/") 

        $url_parts[1 + $index_base] = substr($url_parts[1 + $index_base], 0, -1);

        

    //array of possible pages

    $dir_array = [

        "" => "Features/Pages/Home/home.php",

        "login" => "Features/Pages/Login/login.php",

        "sign" => "Features/Pages/SignIn/signIn.php",

        "userLogin" => "Features/Pages/UserLogin/userLogin.php",

        "userSign" => "Features/Pages/UserSign/userSign.php",

        "dashboard" => "Features/Pages/Dashboard/dashboard.php",

        "shop/create" => "Features/Pages/ShopCreate/shopCreate.php",

        "myshop" => "Features/Pages/MyShop/myShop.php",

        "analitycs" => "Features/Pages/Analitycs/analitycs.php",

        "product" => "Features/Pages/Product/product.php"

    ];



    //array of pages that require login to access, and wich type of login (user or customer)

    $access_array = [

        "" => 'public',

        "login" => 'login',

        "sign" => 'no-login',

        "userLogin" => 'login',

        "userSign" => 'no-login',

        "dashboard" => 'UusJvalUs',

        "shop/create" => 'UusJvalUs',

        "myshop" => 'UusJvalUs',

        "analitycs" => 'UusJvalUs',

        "product" => 'public'

    ];

    $page = array_key_exists($url_parts[1 + $index_base], $dir_array) ? $dir_array[$url_parts[1 + $index_base]] : "Features/Pages/NotFound/notFound.php";

    $access = array_key_exists($url_parts[1 + $index_base], $access_array) ? $access_array[$url_parts[1 + $index_base]] : null;



    $UusJvalUs = $_COOKIE['UusJvalUs'] ?? null;

    $UusJval = $_COOKIE['UusJval'] ?? null;


    if($UusJval != null && $UusJvalUs != null){

        setcookie('UusJvalUs', '', time() - 3600, '/');

        setcookie('UusJval', '', time() - 3600, '/');



        header("Location: $site_base_url");

        die();

    }

    else if($UusJval != null){

        $curl = curl_init(); 



        curl_setopt($curl, CURLOPT_URL, $api_base . 'auth/token/validate');

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);



        curl_setopt($curl, CURLOPT_POST, true);

        curl_setopt($curl, CURLOPT_POSTFIELDS, array(

            'token' => $_COOKIE['UusJval'],
            'is_customer' => true

        ));



        $result = curl_exec($curl);

        $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

                

        curl_close($curl);



        if($httpcode != 200) {

            setcookie('UusJval', '', time() - 3600, '/');

            $url = $site_base_url . 'login?trs=1';

            header("Location: $url");

        }

    }

    else if($UusJvalUs != null){

        $curl = curl_init(); 



        curl_setopt($curl, CURLOPT_URL, $api_base . 'auth/token/validate');

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);



        curl_setopt($curl, CURLOPT_POST, true);

        curl_setopt($curl, CURLOPT_POSTFIELDS, array(

            'token' => $_COOKIE['UusJvalUs'],
            'is_customer' => false

        ));



        $result = curl_exec($curl);

        $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

                

        curl_close($curl);

        if($httpcode != 200) {

            setcookie('UusJvalUs', '', time() - 3600, '/');

            $url = $site_base_url . 'userLogin?trs=1';

            header("Location: $url");

        }

    }

    require_once 'pageAccessHandler.php';

?>