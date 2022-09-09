<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Geeco</title>

    <!-- Site icon -->
    <link rel="icon" type="image/png" href="<?php echo $base ?>Assets/icon/brand/brand.ico">

    <!-- Bootstrap -->
    <link rel="stylesheet" href="<?php echo $base ?>Assets/bootstrap/css/bootstrap.css">
    <link rel="stylesheet" href="<?php echo $base ?>Assets/bootstrap/js/bootstrap.js">

    <!-- Jquery -->
    <script src="<?php echo $base ?>Assets/jquery/JQuery-3.6.0.js"></script>

    <!-- Common -->
    <link rel="stylesheet" href="<?php echo $base ?>Features/Common/css/geeco.css">

    <!-- Login -->
    <link rel="stylesheet" href="<?php echo $base ?>Features/Pages/Login/css/login.css">
    <script src="<?php echo $base ?>Features/Pages/Login/js/login.js"></script>

</head>
<body>

    <!-- trs is a parameter that indicates that the user has been forced logged out -->
    <?php $trs = $_GET['trs'] ?? null; ?>

    <!-- Login Page -->
    <div class="container">

        <img src="<?php echo $base ?>Assets/images/brand/geeco.png" class="image">

        <?php if(!isset($_COOKIE['UusJval']) || $_COOKIE['UusJval'] == null): ?> <!-- If customer is not logged in -->
        <div class="row login">
            <div class="col-lg-4 col-md-3"></div>
            <div class="col-lg-4 col-md-6 form-geeco-login">
                
                <?php if($trs != null && $trs == 1): ?>
                    <p class="error-trs">Your access is expired, please login.</p>
                <?php endif; ?>

                <input type="email" placeholder="E-mail" class="form-control input-geeco" id="emailInput">  
                <input type="password" placeholder="Password" class="form-control input-geeco" id="passwordInput"> 
                <p class="text-justify">
                    By accessing your account, you declare that you have read and accept our General Conditions of Use and Sale. Read our Privacy Policy, our Cookie Policy and our Interest-Based Advertising Policy.
                </p>
                <p id="errorMessageText">ErrorMessage</p>
                <button class="btn btn-primary" id="loginBtn" href="<?php echo $site_base_url ?>" reqpath="<?php echo $api_base ?>auth/customer">Login</button>
                <p>
                    Don't have an account?
                </p>
                
                <button class="btn btn-outline" onclick="window.location.href = '<?php echo $site_base_url ?>sign'">Sign In</button> 
                
            </div>
            <div class="col-lg-4 col-md-3"></div>
        </div>
        <?php else: ?> <!-- If customer is already logged in -->
        <div class="logout">
            <p class="text-justify">
                You are already logged in. Click button below to logout.
            </p>

            <div class="group-btn">
                <a href="<?php echo $site_base_url ?>" class="btn btn-primary">Back to Home</a>
                <button class="btn btn-outline" id="logoutBtn">Logout</button>
            </div>

        </div>
        <?php endif; ?>
        
    </div>

    
</body>
</html>