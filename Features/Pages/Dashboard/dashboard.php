<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Geeco</title>

    <!-- Site icon -->
    <link rel="icon" type="image/png" href="<?php echo $base ?>Assets/icon/brand/brand.ico">

    <!-- Bootstrap -->
    <link rel="stylesheet" href="<?php echo $base ?>Assets/bootstrap/css/bootstrap.css">
    <link rel="stylesheet" href="<?php echo $base ?>Assets/bootstrap/js/bootstrap.js">

    <!-- Jquery -->
    <script src="<?php echo $base ?>Assets/jquery/JQuery-3.6.0.js"></script>

    <!-- Common -->
    <link rel="stylesheet" href="<?php echo $base ?>Features/Common/css/geeco.css">

    <!-- Dashboard -->
    <link rel="stylesheet" href="<?php echo $base ?>Features/Pages/Dashboard/css/dashboard.css">

    <!-- ShopCard -->
    <link rel="stylesheet" href="<?php echo $base ?>Features/Pages/Dashboard/Components/ShopCard/css/shopCard.css">

</head>
<body>

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
                        <a class="nav-link" href="<?php echo $base ?>analitycs">Analitycs</a>
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
    <div class="hi-user container py-4">
        <?php 
            $curl = curl_init(); 

            curl_setopt($curl, CURLOPT_URL, $api_base . 'user/me/show');
            curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                'Authorization: ' . $_COOKIE['UusJvalUs']
            ));
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    
            $result = curl_exec($curl);
            $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
                    
            curl_close($curl);

            $result = json_decode($result, true);

            echo 'Hi ' . $result['userFirstName'] . ', welcome back!';

        ?>
    </div>

    <!-- Your Shop -->
    <?php 

        require 'Components/ShopCard/shopCard.php';
        require 'Components/NoShopYet/noShopYet.php';

        $curl = curl_init(); 

        curl_setopt($curl, CURLOPT_URL, $api_base . 'shop/me/show');
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'Authorization: ' . $_COOKIE['UusJvalUs']
        ));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $result = curl_exec($curl);
        $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
                
        curl_close($curl);

        $shop = json_decode($result, true);
        if ($httpcode == 200) {
            echo '<div class="container-fluid py-lg-5 max-width">
                    <div class="row">
                        <div class="d-lg-none col-md-3 col-sm-1"></div>
                            <div class="col-lg-3 col-md-6 col-sm-10">';  
                                $shop_name = $shop['Shop'][0]['shopName'];
                                $shop_description = strlen($shop['Shop'][0]['shopDescription']) < 180 ? $shop['Shop'][0]['shopDescription'] : substr($shop['Shop'][0]['shopDescription'], 0, 180) . '...';
                                $shop_image = $api_base . 'images/shopImages/' . str_replace(' ', '.', $shop['Shop'][0]['shopImage']);
                                $shop_id = $shop['Shop'][0]['shopId'];

                                $shopCard = new ShopCard($shop_name, $shop_description, $shop_image, $shop_id, $site_base_url . 'myshop?id=' . $shop_id);
                                $shopCard->render();
            echo '          </div>
                        <div class="d-lg-none col-md-3 col-sm-1"></div>
                    </div>
                </div>'; 

        } else if($httpcode == 404) {
            $noShopYet = new NoShopYet($site_base_url . 'shop/create');
            $noShopYet->render();
        }
        else if($httpcode == 401) {
            //remove cookie
            setcookie('UusJvalUs', '', time() - 3600, '/');
            setcookie('UusJval', '', time() - 3600, '/');

            header('Location: ' . $site_base_url . 'userLogin?trs=1');
        }
        else {
            echo 'Something went wrong';
        }

    ?>

    <!-- Geeco Analytics Tools -->
    <div class="container py-4 py-xl-5 mt-3">
        <div class="row">
            <div class="col-md-8 col-xl-6 mb-5 text-center mx-auto">
                <h2>Geeco Tools</h2>
                <p class="w-lg-50">Use our tools for improve your business, use Geeco Analytics&nbsp;<br>to discover how to maximize your profits.<br></p>
            </div>
        </div>
        <div class="row gy-4 row-cols-1 row-cols-md-2 row-cols-xl-3">
            <div class="col">
                <div class="text-center d-flex flex-column align-items-center align-items-xl-center">
                    <img src="<?php echo $base ?>Assets/images/ui/discover.png" class="mb-3">
                    <div class="px-3" style="text-align: center;">
                        <h4>Discovery</h4>
                        <p style="text-align: center;">Find out how many customers visit your shop, track their movements within your shop to provide the best online experience.</p>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="text-center d-flex flex-column align-items-center align-items-xl-center">
                    <img src="<?php echo $base ?>Assets/images/ui/trends.png" class="mb-3">
                    <div class="bs-icon-lg bs-icon-rounded bs-icon-primary d-flex flex-shrink-0 justify-content-center align-items-center d-inline-block mb-3 bs-icon lg" style="background: rgb(255,255,255);"></div>
                    <div class="px-3">
                        <h4>Upcoming</h4>
                        <p style="text-align: center;">Use geeco upcoming to anticipate future&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;trends, better prepare your store.&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</p>
                    </div>
                </div>
            </div>
			<div class="col">
                <div class="text-center d-flex flex-column align-items-center align-items-xl-center">
                    <img src="<?php echo $base ?>Assets/images/ui/chart.png" class="mb-3">
                    <div class="bs-icon-lg bs-icon-rounded bs-icon-primary d-flex flex-shrink-0 justify-content-center align-items-center d-inline-block mb-3 bs-icon lg" style="background: rgb(255,255,255);"></div>
                    <div class="px-3">
                        <h4>Analytics</h4>
                        <p style="text-align: center;">Use Analitycs to analyze your sales and orders. Find out how to maximize your ecommerce earnings.&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Template Geeco -->
    <div class="container d-flex flex-column align-items-center py-4 py-xl-5">
        <div class="row gy-4 row-cols-1 row-cols-md-2 w-100" style="max-width: 800px;">
            <div class="col order-md-first">
                <div class="card"><img class="card-img w-100 d-block" src="https://d85wutc1n854v.cloudfront.net/live/products/icons/WB08T854C.jpg?v=2.0.6">
                    <div class="card-img-overlay text-center d-flex flex-column justify-content-center align-items-center p-4">
                    </div>
                </div>
            </div>
            <div class="col d-md-flex order-first justify-content-md-start align-items-md-end order-md-1">
                <div style="width: 80%;">
                    <h2>Discover Geeco Templates</h2>
                    <p class="w-lg-50">Take advantage of our templates to customize your online store. Offer the best online experience.</p>
                </div>
            </div>
            <div class="col order-md-2">
                <div class="card"><img class="card-img w-100 d-block" src="https://templatepocket.com/wp-content/uploads/2022/04/aaaaaaaaaaaaa.jpg">
                    <div class="card-img-overlay text-center d-flex flex-column justify-content-center align-items-center p-4">
                    </div>
                </div>
            </div>
            <div class="col order-md-2">
                <div class="card"><img class="card-img w-100 d-block" src="https://www.templateshub.net/uploads/1537207366electrothumb.jpg">
                    <div class="card-img-overlay text-center d-flex flex-column justify-content-center align-items-center p-4">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <link rel="stylesheet" href="<?php echo $base ?>Features/Common/Components/UserNavbar/css/userNavbar.css">

	 <!-- Footer -->
    <footer class="text-white bg-dark mt-5 no-max-width" style="background: rgb(33, 37, 41); max-width: none;">
        <div class="container py-4 py-lg-5">
            <div class="row justify-content-center">
                <div class="col-sm-4 col-md-3 text-center text-lg-start d-flex flex-column item">
                    <h3 class="fs-6 text-white">Services</h3>
                    <ul class="list-unstyled">
                        <li><a class="link-light" href="#">Open your shop</a></li>
                        <li><a class="link-light" href="#">Geeco Templates</a></li>
                        <li><a class="link-light" href="#">Analitycs</a></li>
                    </ul>
                </div>
                <div class="col-sm-4 col-md-3 text-center text-lg-start d-flex flex-column item">
                    <h3 class="fs-6 text-white">About</h3>
                    <ul class="list-unstyled">
                        <li><a class="link-light" href="#">Geeco</a></li>
                        <li><a class="link-light" href="#">Contact</a></li>
                        <li><a class="link-light" href="#">Policy</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 text-center text-lg-start d-flex flex-column align-items-center order-first align-items-lg-start order-lg-last item social">
                    <div class="fw-bold d-flex align-items-center mb-2">
                        <span class="bs-icon-sm bs-icon-rounded bs-icon-primary d-flex justify-content-center align-items-center bs-icon me-2" style="color: rgb(0,0,0);background: url('<?php echo $base ?>Assets/images/brand/geeco.png') center center; background-size: cover; width: 40px; height: 40px;"></span>
                        <span>Geeco</span>
                    </div>
                    <p class="text-muted copyright">Discover a new way of doing ecommerce. Designed for your shop.</p>
                </div>
            </div>
            <hr>
            <div class="d-flex justify-content-between align-items-center pt-3">
                <p class="mb-0">Copyright © 2022 Geeco</p>
                <ul class="list-inline mb-0">
                    <li class="list-inline-item"></li>
                </ul>
            </div>
        </div>
    </footer>
    
</body>
</html>