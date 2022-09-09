<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SignIn - Geeco</title>

    <!-- Site icon -->
    <link rel="icon" type="image/png" href="<?php echo $base ?>Assets/icon/brand/brand.ico">

    <!-- Bootstrap -->
    <link rel="stylesheet" href="<?php echo $base ?>Assets/bootstrap/css/bootstrap.css">
    <link rel="stylesheet" href="<?php echo $base ?>Assets/bootstrap/js/bootstrap.js">

    <!-- Jquery -->
    <script src="<?php echo $base ?>Assets/jquery/JQuery-3.6.0.js"></script>

    <!-- Common -->
    <link rel="stylesheet" href="<?php echo $base ?>Features/Common/css/geeco.css">

    <!-- Sign In -->
    <link rel="stylesheet" href="<?php echo $base ?>Features/Pages/SignIn/css/signIn.css">
    <script src="<?php echo $base ?>Features/Pages/SignIn/js/signIn.js"></script>

</head>
<body>

    <!-- SignIn Page -->
    <div class="container">

        <div class="row">
            <div class="col-lg-4 col-md-3"></div>
            <div class="col-lg-4 col-md-6 form-geeco-login">

                <input type="text" placeholder="First Name" class="form-control input-geeco" id="firstName">
                <input type="text" placeholder="Last Name" class="form-control input-geeco" id="lastName">
                <input type="text" placeholder="Email" class="form-control input-geeco" id="email">
                <input type="text" placeholder="Phone" class="form-control input-geeco" id="phone">
                <input type="date" placeholder="Date of birth" class="form-control input-geeco" id="birthDate">  
                <input type="password" placeholder="Password" class="form-control input-geeco" id="password">
                <input type="password" placeholder="Confirm Password" class="form-control input-geeco" id="confirmPassword">

                <p class="text-justify">
                    By creating your account you declare that you have read and accept our General Conditions of Use and Sale. Read our Privacy Policy, our Cookie Policy and our Interest-Based Advertising Policy.
                </p>
                <p id="errorMessageText">ErrorMessage</p>
                <button class="btn btn-primary" id="signBtn" href="<?php echo $site_base_url ?>login" reqpath="<?php echo $api_base ?>customer/">Continue</button>
                <p>
                    Have an account?
                </p>
                <button class="btn btn-outline" onclick="window.location.href = '<?php echo $site_base_url ?>login'">Login</button>

            </div>
            <div class="col-lg-4 col-md-3"></div>
            
        </div>
        
    </div>

    
</body>
</html>