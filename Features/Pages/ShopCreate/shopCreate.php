<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Shop - Geeco</title>

    <!-- Site icon -->
    <link rel="icon" type="image/png" href="<?php echo $base ?>Assets/icon/brand/brand.ico">

    <!-- Bootstrap -->
    <link rel="stylesheet" href="<?php echo $base ?>Assets/bootstrap/css/bootstrap.css">
    <link rel="stylesheet" href="<?php echo $base ?>Assets/bootstrap/js/bootstrap.js">

    <!-- Jquery -->
    <script src="<?php echo $base ?>Assets/jquery/JQuery-3.6.0.js"></script>

    <!-- Common -->
    <link rel="stylesheet" href="<?php echo $base ?>Features/Common/css/geeco.css">

    <!-- ShopCreate -->
    <link rel="stylesheet" href="<?php echo $base ?>Features/Pages/ShopCreate/css/shopCreate.css">
    <script src="<?php echo $base ?>Features/Pages/ShopCreate/js/shopCreate.js"></script>

</head>
<body>

    <?php

        $curl = curl_init(); 

        curl_setopt($curl, CURLOPT_URL, $api_base . 'shop/me/show');
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'Authorization: ' . $_COOKIE['UusJvalUs']
        ));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $result = curl_exec($curl);
        $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
                
        curl_close($curl);

        if ($httpcode == 200) {
            //sorry you already have a shop div
            echo    '<div class="container h-100" style="margin-top: 15%;">
                        <div class="text-white bg-orange border rounded border-0 p-4 py-5">
                            <div class="row h-100">
                                <div class="col-md-10 col-xl-8 text-center d-flex d-sm-flex d-md-flex justify-content-center align-items-center mx-auto justify-content-md-start align-items-md-center justify-content-xl-center">
                                    <div>
                                        <h1 class="text-uppercase fw-bold text-white mb-3">Sorry, You already have a shop</h1>
                                        <p class="mb-4">At this moment Geeco allows you to have only one Shop.<br></p>
                                        <button class="btn btn-white fs-5 py-2 px-4" type="button" onclick="window.location.href= \'' . $base . 'dashboard\'">Back to dashboard</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
            ';
            
            die();

        }

    ?>

    <link rel="stylesheet" href="<?php echo $base ?>Features/Common/Components/UserNavbar/css/userNavbar.css">

    <nav class="navbar navbar-light navbar-expand-md py-3">

        <div class="container">

            <a class="navbar-brand d-flex align-items-center" href="">
                <img src="<?php echo $base ?>Assets/images/brand/geeco.png" alt="geeco_logo" class="logo">
            </a>

            <button data-bs-toggle="collapse" class="navbar-toggler" data-bs-target="#navcol-1">
                <span class="visually-hidden">Toggle navigation</span>
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navcol-1">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="<?php echo $base ?>dashboard">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo $base ?>shopinfo">Information</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo $base ?>myreport">Report</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo $base ?>myanalitycs">Analitycs</a>
                    </li>
                </ul>

                <div class="two-button">
                    <button class="btn btn-primary" type="button">Geeco Shop</button>
                    <button class="btn btn-outline" type="button" onclick="logout()">Logout</button>
                </div>

            </div>

        </div>

    </nav>

    <script src="<?php echo $base ?>Assets/bootstrap/js/bootstrap.min.js"></script>
    <script>

        // Logout function
        function logout() {
            document.cookie = "UusJvalUs=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
            window.location.href = "<?php echo $base ?>userLogin";
        }

    </script>
    
    <!-- Content -->
    <div class="container form-geeco">
        <div class="row">
            <div class="col-12">
                <div>
                    <div class="form-group">
                        <input type="text" class="form-control" id="shopName" placeholder="Enter shop name" required>
                    </div>
                    <div class="form-group">
                        <textarea class="form-control" id="shopShortDescription" rows="2" placeholder="Enter shop short description (max 85 characters)" required></textarea>
                    </div>
                    <div class="form-group">
                        <textarea class="form-control" id="shopDescription" rows="3" placeholder="Enter shop description" required></textarea>
                    </div>
                    <div class="form-group">
                        <input class="form-control" type="file" id="shopImage" accept="image/png, image/jpg, image/jpeg" required>
                    </div>  
                    <div class="form-group mt-5">
                        <p>
                            Tags are used to categorize your shop. You can add up to 4 tags. With tags, you can make your shop easier to find.
                            Remember to use tags that are relevant to your shop. <br> Exaple: "Clothes", "Shoes", "Accessories", "Electronics", "Toys", "Books".
                        </p>
                        <input type="text" class="form-control tag" placeholder="Enter tag" required>
                        <input type="text" class="form-control tag" placeholder="Enter tag" required>
                        <input type="text" class="form-control tag" placeholder="Enter tag" required>
                        <input type="text" class="form-control tag" placeholder="Enter tag" required>
                    </div>
                    <div class="form-group mt-5">
                        <p>
                            You can add up to 3 categories to your shop. With categories, you can make your shop easier to find.
                        </p>
                        <?php 

                            $curl = curl_init(); 

                            curl_setopt($curl, CURLOPT_URL, $api_base . 'geecocategory');
                            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

                            $result = curl_exec($curl);
                            $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
                                    
                            curl_close($curl);

                            $shop = json_decode($result, true);

                            if ($httpcode == 200) {
                                for($i = 0; $i<3; $i++) {
                                    echo '<select class="form-control" id="shopCategory' . $i . '" required>';
                                    echo '<option value="" selected disabled>Select category</option>';
                                    foreach ($shop['GeecoCategories'] as $key => $value) {
                                        echo '<option>' . $value['categoryName'] . '</option>';
                                    }
                                    echo '</select>';
                                }
                            }

                        ?>
                    </div>
                    <p id="errorMessageText">ErrorMessage</p>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <button type="button" class="btn btn-primary" id="createShop" href="<?php echo $site_base_url ?>dashboard" reqpath="<?php echo $api_base ?>shop">Create Shop</button>
            </div>
        </div>
    </div>

</body>
</html>