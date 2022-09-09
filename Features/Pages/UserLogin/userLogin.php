<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login User - Geeco</title>



    <!-- Site icon -->

    <link rel="icon" type="image/png" href="<?php echo $base ?>Assets/icon/brand/brand.ico">



    <!-- Bootstrap -->

    <link rel="stylesheet" href="<?php echo $base ?>Assets/bootstrap/css/bootstrap.css">

    <link rel="stylesheet" href="<?php echo $base ?>Assets/bootstrap/js/bootstrap.js">



    <!-- Jquery -->

    <script src="<?php echo $base ?>Assets/jquery/JQuery-3.6.0.js"></script>



    <!-- Common -->

    <link rel="stylesheet" href="<?php echo $base ?>Features/Common/css/geeco.css">



    <!-- UserLogin -->

    <link rel="stylesheet" href="<?php echo $base ?>Features/Pages/UserLogin/css/userLogin.css">

    <script src="<?php echo $base ?>Features/Pages/UserLogin/js/userLogin.js"></script>



</head>

<body>



    <!-- trs is a parameter that indicates that the user has been forced logged out -->

    <?php $trs = $_GET['trs'] ?? null; ?>



    <!-- UserLogin Page -->

    <div class="container">



        <img src="<?php echo $base ?>Assets/images/brand/geeco.png" class="image">



        <?php if(!isset($_COOKIE['UusJvalUs']) || $_COOKIE['UusJvalUs'] == null): ?> <!-- If user is not logged in -->

        <div class="row login">

            <div class="col-lg-4 col-md-3"></div>

            <div class="col-lg-4 col-md-6">



                <p class="text-center lead">

                    Improve your business with Geeco. <br>

                    Login to your account to access your shop.

                </p>



                <?php if($trs != null && $trs == 1): ?>

                    <p class="error-trs">Your access is expired, please login.</p>

                <?php endif; ?>



                <div class="form-geeco-login">

                    <input type="email" placeholder="E-mail" class="form-control input-geeco" id="emailInput">  

                    <input type="password" placeholder="Password" class="form-control input-geeco" id="passwordInput"> 



                    <p class="info-policy text-justify">

                        By accessing your account, you declare that you have read and accept our General Conditions of Use and Sale.

                        Read our Privacy Policy, our Cookie Policy and our Shop Policy.

                    </p>



                    <p id="errorMessageText">ErrorMessage</p>



                    <button class="btn btn-primary" id="loginBtn" href="<?php echo $site_base_url ?>dashboard" reqpath="<?php echo $api_base ?>auth/user">Login</button>

                    <p>

                        Don't have an account?

                    </p>

                    

                    <button class="btn btn-outline" onclick="window.location.href = '<?php echo $site_base_url ?>userSign'">Sign In</button> 



                </div>



            </div>

            <div class="col-lg-4 col-md-3"></div>

        </div>

        <?php else: ?> <!-- If user is already logged in -->

        <div class="logout">

            

            <p class="text-center">

                You are already logged in. Click button below to logout.

            </p>



            <div class="group-btn">

                <a href="<?php echo $site_base_url ?>dashboard" class="btn btn-primary">Back to Dashboard</a>

                <button class="btn btn-outline" id="logoutBtn">Logout</button>

            </div>



        </div>

        <?php endif; ?>

        

    </div>



    

</body>

</html>

