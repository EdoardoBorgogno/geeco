<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Geeco</title>

    <!-- Site icon -->
    <link rel="icon" type="image/png" href="<?php echo $base ?>Assets/icon/brand/brand.ico">

    <!-- Bootstrap -->
    <link rel="stylesheet" href="<?php echo $base ?>Assets/bootstrap/css/bootstrap.css">
    <link rel="stylesheet" href="<?php echo $base ?>Assets/bootstrap/js/bootstrap.js">

    <!-- Jquery -->
    <script src="<?php echo $base ?>Assets/jquery/JQuery-3.6.0.js"></script>

    <!-- Common -->
    <link rel="stylesheet" href="<?php echo $base ?>Features/Common/css/geeco.css">

    <!-- Home -->
    <link rel="stylesheet" href="<?php echo $base ?>Features/Pages/Home/css/home.css">
    <script src="<?php echo $base ?>Features/Pages/Home/js/home.js"></script>

    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.js"></script>

</head>
<body>

    <!-- Navbar -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.8.2/css/fontawesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.8.2/css/all.min.css">
    <link rel="stylesheet" href="<?php echo $base ?>Features/Common/Components/CustomerNavbar/css/customerNavbar.css">

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
                        <a class="nav-link active" href="<?php echo $base ?>dashboard">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo $base ?>shopinfo">Shop</a>
                    </li>
                    <?php if (isset($_COOKIE['UusJval']) == false): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo $base ?>userLogin">Sell</a>
                        </li>
                    <?php endif ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo $base ?>analitycs">Explore</a>
                    </li>
                </ul>

                <form class="form-inline">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text btn btn-primary">
                                <i class="fas fa-search"></i>
                            </span>
                        </div>
                        <input type="text" class="form-control" placeholder="Search">
                    </div>
                </form>

                <div class="two-button">
                    <a href="<?php echo $base ?>cart" class="btn btn-primary" role="button" aria-pressed="true">
                        <i class="fas fa-shopping-cart"></i>
                    </a>
                    <?php if (isset($_COOKIE['UusJval'])): ?>
                        <a onclick="logout()" class="btn btn-primary" role="button" aria-pressed="true">
                        <i class="fas fa-sign-out-alt"></i>
                    <?php endif; ?>
                    </a>
                </div>

            </div>

        </div>

    </nav>

    <script src="<?php echo $base ?>Assets/bootstrap/js/bootstrap.min.js"></script>
    <script>

        // Logout function
        function logout() {
            document.cookie = "UusJval=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
            window.location.href = "<?php echo $base ?>login";
        }

    </script>

    <!-- Home Login -->

    <!-- If user is user type account -->
    <?php

        //If user is a user account
        if (isset($_COOKIE['UusJvalUs'])) {
            header("Location: " . $base . "dashboard");
        }

    ?>

    <!-- If user is customer type account -->
    <?php if(isset($_COOKIE['UusJval']) == false): ?>
        <div class="login-div p-5 container">
            <div class="lead">
                <p>
                    Login to enjoy our services, if you don't have an account, please register and have the best online experience.
                </p>
                <a href="<?php echo $base ?>login" class="btn btn-primary px-5" role="button" aria-pressed="true">Login</a>
            </div>
            <img src="https://media.istockphoto.com/vectors/secure-login-and-sign-up-concept-illustration-vector-id1336696139?k=20&m=1336696139&s=170667a&w=0&h=Px0mt6RIG6Eaqro1CVKQYmgFQkxfg_2wUycc5HnPi6c=" alt="">
        </div>
    <?php endif; ?>

    <!-- Home Content -->
    <div class="explore-div p-5">

        <div class="container">

            <div class="best-seller">

                <h4><span>Top Products</span></h4>
                <h6>of the week</h6>

                <?php 

                    /* Get the best seller products */
                    $curl = curl_init(); 

                    curl_setopt($curl, CURLOPT_URL, $api_base . 'product/?top=7');
                    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

                    $result = curl_exec($curl);
                    $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
                            
                    curl_close($curl);

                    if ($httpcode == 200) {
                        $result = json_decode($result, true);
                    }

                ?>

                <!-- Swiper CSS -->
                <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@8.3.2/swiper-bundle.min.css">

                <div class="slide-container swiper">
                    <div class="slide-products">
                        <div class="card-wrapper swiper-wrapper">
                            <?php foreach($result['products'] as $product): ?>
                                    <div class="card swiper-slide">
                                        <a href="<?php echo $site_base_url . 'product?max=10&catg=topweek&frm=home&prdid=' . $product['productId'] ?>">
                                            <div class="tag-container">
                                                <span class="tag top-tag">TOP</span>
                                            </div>
                                            <div class="image-content">
                                                <div class="card-image">
                                                    <img class="card-img" src="<?php echo $api_base . 'images/productImages/' . explode(';', $product['productImages'])[0] ?>" alt="">
                                                </div>
                                            </div>

                                            <div class="card-content">
                                                <h2 class="name"><?php echo $product['productName'] ?></h2>
                                                <br>
                                                <h5><?php echo $product['productPrice'] ?></h5>
                                            </div>
                                        </a>
                                    </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    
                    <div class="swiper-container">
                        <div class="swiper-wrapper"></div>
                        <div class="swiper-pagination"></div>
                    </div>

                </div>

            </div>

            <div class="best-shop">

                <h4><span>Top Shop</span></h4>
                <h6>of the month</h6>

                <?php 

                    /* Get the best seller products */
                    $curl = curl_init(); 

                    curl_setopt($curl, CURLOPT_URL, $api_base . 'shop/?top=31');
                    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

                    $result = curl_exec($curl);
                    $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
                            
                    curl_close($curl);

                    if ($httpcode == 200) {
                        $result = json_decode($result, true);
                    }

                ?>

                <div class="slide-container swiper">
                    <div class="slide-shops">
                        <div class="card-wrapper swiper-wrapper">
                            <?php foreach($result['Shops'] as $shop): ?>
                                    <div class="card swiper-slide">
                                        <a href="<?php echo $site_base_url . 'shop?catg=topweek&frm=home&shpdid=' . $shop['shopId'] ?>">
                                            <div class="image-content">
                                                <div class="shop-image">
                                                    <img class="card-img" src="<?php echo $api_base . 'images/shopImages/' . $shop['shopImage'] ?>" alt="">
                                                </div>
                                            </div>

                                            <div class="card-content">
                                                <h2 class="name"><?php echo $shop['shopName'] ?></h2>
                                                <br>
                                                <p class="p-3">
                                                    <?php echo strlen($shop['shopDescription']) < 180 ? $shop['shopDescription'] : substr($shop['shopDescription'], 0, 180) . '...'; ?>
                                                </p>
                                            </div>
                                        </a>
                                    </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    
                    <div class="swiper-container">
                        <div class="swiper-wrapper"></div>
                        <div class="swiper-pagination"></div>
                    </div>

                </div>

            </div>

        </div>

    </div>
    
    <?php if(isset($_COOKIE['UusJval']) == false): ?>
        <div class="container mt-5 mt-xl-0">
            <div class="container py-0 py-xl-5">
                <div class="row gy-4 gy-md-0">
                    <div class="col-md-6 text-center text-md-start d-flex d-sm-flex d-md-flex justify-content-center align-items-center justify-content-md-start align-items-md-center justify-content-xl-center">
                        <div style="max-width: 350px;">
                            <h2 class="text-uppercase fw-bold">Start your<br>Store on Geeco</h2>
                            <p class="my-3">Open your store on Geeco and start selling products. Use our service to improve your business, discover Analitycs and Template.</p><a class="btn btn-primary btn-lg me-2" role="button" href="<?php echo $base ?>userSign">Join now</a>
                        </div>
                    </div>
                    <div class="col-md-6 d-none d-md-block">
                        <div class="p-xl-5 m-xl-3"><img class="rounded img-fluid w-100 fit-cover" style="min-height: 300px;" src="https://flawless.life/wp-content/uploads/2019/02/east-market-shop-accessori.jpg"></div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

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