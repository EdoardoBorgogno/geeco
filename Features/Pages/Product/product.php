<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product - Geeco</title>

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
    <link rel="stylesheet" href="<?php echo $base ?>Features/Pages/Product/css/product.css">
    <script src="<?php echo $base ?>Features/Pages/Product/js/product.js"></script>

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
                        <a class="nav-link" href="<?php echo $base ?>">Home</a>
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
                        <a class="nav-link" href="<?php echo $base ?>">Explore</a>
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

    <!-- Product -->
    <?php

        /* Get product id */
        $product_id = $_GET['prdid'];

        $curl = curl_init(); 

        curl_setopt($curl, CURLOPT_URL, $api_base . 'product/' . $product_id);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $result = curl_exec($curl);
        $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
                
        curl_close($curl);

        if ($httpcode == 200) {
            $result = json_decode($result, true);
            $result = $result[0];
        }

    ?>

    <!-- Container -->
    <div class="container">
        <div class="product-row mt-5">
            <div class="col-md-7 img-container">
                <div class="img-slide">
                    <?php foreach (explode(';', substr($result['productImages'], 0, strlen($result['productImages']) - 1)) as $image => $value):?>
                        <div class="img-slide-item selectable">
                            <img src="<?php echo $api_base . 'images/productImages/' . $value ?>" alt="product_image">
                        </div>
                    <?php endforeach ?>
                </div>
                <div id="img-div" style="background: url('<?php echo $api_base . 'images/productImages/' . explode(';', $result['productImages'])[0] ?>') center center no-repeat; background-size: contain;"></div>
            </div>
            <div class="col-md-5">
                <div class="product-content lead">
                    
                    <h6 class="quantity"><?php echo $result['quantity'] ?> available | Sold by <?php echo $result['shopName'] ?></h6>

                    <div class="product-title">
                        <h1><?php echo $result['productName'] ?></h1>
                    </div>

                    <div class="product-price mt-3">
                        <h2><?php echo $result['productPrice'] ?> €</h2>
                    </div>

                    <div class="product-description mt-3">
                        <p><?php echo $result['productDescription'] ?></p>
                    </div>

                    <div class="def-number-input number-input safari_only">
                        <button onclick="this.parentNode.querySelector('input[type=number]').stepDown()" class="minus"></button>
                        <input class="quantity" min="0" name="quantity" value="1" type="number">
                        <button onclick="this.parentNode.querySelector('input[type=number]').stepUp()" class="plus"></button>
                    </div>

                    <div class="product-button">
                        <button class="btn btn-primary px-4" type="button">Add to cart</button>
                        <a href="<?php echo $site_base_url . 'shop?shpdid=' . $result['shopId'] ?>&frm=yprd" class="btn btn-primary px-4" type="button">Discover this shop</a>
                        <button class="btn btn-outline px-4" type="button">
                            <i class="fas fa-heart"></i>
                        </button>                        
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