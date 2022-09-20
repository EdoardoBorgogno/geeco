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
                        <a class="nav-link" href="<?php echo $base ?>explore">Explore</a>
                    </li>
                </ul>

                <form action="javascript:void(0);" class="form-inline">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span id="search-btn" class="input-group-text btn btn-primary">
                                <i class="fas fa-search"></i>
                            </span>
                        </div>
                        <input type="text" class="form-control" placeholder="Search" id="search-input">
                    </div>
                </form>

                <div class="two-button">
                    <?php if (isset($_COOKIE['UusJval'])): ?>
                        <a href="<?php echo $base ?>cart" class="btn btn-primary" role="button" aria-pressed="true">
                            <i class="fas fa-shopping-cart"></i>
                        </a>
                    <?php endif ?>
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

        if($product_id == null) {
            header("Location: " . $base . "NotFound");
        }

        $curl = curl_init(); 

        curl_setopt($curl, CURLOPT_URL, $api_base . 'product/' . $product_id);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $result = curl_exec($curl);
        $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
                
        curl_close($curl);

        if ($httpcode == 200) {
            $result = json_decode($result, true);
            $shop_id = $result[0]['shopId'];

            $result = $result[0];            
        }
        else {
            header("Location: " . $base . "NotFound");
        }

        if (isset($_COOKIE['UusJval'])) {
            $curl_cart = curl_init();

            curl_setopt($curl_cart, CURLOPT_URL, $api_base . 'customerlists?list=cart');

            curl_setopt($curl_cart, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl_cart, CURLOPT_HTTPHEADER, [
                'Authorization:' . $_COOKIE['UusJval']
            ]);

            $result_cart = curl_exec($curl_cart);
            $httpcode_cart = curl_getinfo($curl_cart, CURLINFO_HTTP_CODE);

            curl_close($curl_cart);

            if ($httpcode_cart == 200) {
                $result_cart = json_decode($result_cart, true);

                //if $product_id is in cart
                $in_cart = false;

                foreach ($result_cart['Products'] as $key => $value) {
                    if ($value['productId'] == $product_id) {
                        $in_cart = true;
                        break;
                    }
                }
            }
        }

    ?>

    <!-- Container -->
    <div class="container">
        <div class="product-row mt-5">
            <div class="col-md-7 img-container">
                <div class="img-slide">
                    <?php foreach (explode(';', substr($result['productImages'], 0, strlen($result['productImages']) - 1)) as $image => $value):?>
                        <div class="img-slide-item selectable">
                            <img src="<?php echo $api_base . $public_folder . 'images/productImages/' . $value ?>" alt="product_image">
                        </div>
                    <?php endforeach ?>
                </div>
                <div id="img-div" style="background: url('<?php echo $api_base . $public_folder . 'images/productImages/' . explode(';', $result['productImages'])[0] ?>') center center no-repeat; background-size: contain;"></div>
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

                    <?php if ($result['availableFrom'] < date("Y-m-d H:i:s") && !isset($in_cart) || $in_cart == false): ?>
                        <div class="def-number-input number-input safari_only">
                            <button onclick="this.parentNode.querySelector('input[type=number]').stepDown()" class="minus"></button>
                            <input class="quantity" min="0" name="quantity" value="1" type="number" id="quantity-input">
                            <button onclick="this.parentNode.querySelector('input[type=number]').stepUp()" class="plus"></button>
                        </div>
                    <?php endif; ?>

                    <div class="product-button">
                        <?php if ($result['availableFrom'] < date("Y-m-d H:i:s") && $result['quantity'] > 0 && !isset($in_cart) || $in_cart == false): ?>
                            <button href="<?php echo $api_base ?>customerlists" class="btn btn-primary px-4" type="button" id="add-to-cart" product="<?php echo $product_id ?>">Add to cart</button>
                        <?php elseif ($result['quantity'] <= 0): ?>
                            <h5 class="mt-5 quantity mb-4">Sorry, product out of stock</h5>
                            <button class="btn btn-primary px-4" type="button">Notify when available</button>
                        <?php elseif (isset($in_cart) && $in_cart == true): ?>
                            <h5 class="mt-5 quantity mb-4">Product already in cart</h5>
                            <a href="<?php echo $base ?>cart" class="btn btn-primary px-4" type="button">Go to cart</a>
                        <?php else: ?>
                            <h5 class="mt-5 quantity mb-4">Available from <?php echo date_format(new DateTime($result['availableFrom']), 'd-m-Y H:i') ?></h5>
                            <button class="btn btn-primary px-4" type="button">Notify when available</button>
                        <?php endif ?>
                        <a class="d-none d-md-inline btn btn-primary px-4" href="<?php echo $site_base_url . 'shop?shpdid=' . $result['shopId'] ?>&frm=yprd" type="button">Discover this shop</a>
                        <button class="btn btn-outline px-4" type="button">
                            <i class="fas fa-heart"></i>
                        </button>                        
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="other-from-shop mt-5">
        <div class="container">

            <?php
                $curl_other_from_shop = curl_init(); 

                curl_setopt($curl_other_from_shop, CURLOPT_URL, $api_base . 'product?id=' . $result['shopId']);
                curl_setopt($curl_other_from_shop, CURLOPT_RETURNTRANSFER, true);

                $result = curl_exec($curl_other_from_shop);
                $httpcode = curl_getinfo($curl_other_from_shop, CURLINFO_HTTP_CODE);
                        
                curl_close($curl_other_from_shop);

                if ($httpcode == 200) {
                    $result = json_decode($result, true);

                    foreach ($result['products'] as $key => $value) {
                        if ($value['productId'] == $product_id) {
                            unset($result['products'][$key]);
                        }
                    }

                    $other_product_count = count(array_values($result['products']));;

                    if ($other_product_count > 3) {
                        $indexs = array_rand($result['products'], 3);
                        
                        $other_products = array();
                        foreach ($indexs as $index) {
                            array_push($other_products, $result['products'][$index]);
                        }
                    }
                    else {
                        $other_products = array_slice($result['products'], 0, $other_product_count);
                    }
                }
            ?>

            <?php if($other_product_count > 0): ?>
                <div class="other-product p-5">
                    <h3 class="mb-4">Other products from this shop</h3>
                    <div class="row">
                        <?php foreach ($other_products as $key => $value): ?>
                            <a class="col-md-3 card mt-3 mt-md-0" href="<?php echo $site_base_url . 'product?max=10&frm=home&prdid=' . $value['productId'] ?>">
                                <div class="image-content">
                                    <div class="card-image">
                                        <img class="card-img" src="<?php echo $api_base . $public_folder . 'images/productImages/' . explode(';', $value['productImages'])[0] ?>" alt="">
                                    </div>
                                </div>

                                <div class="card-content">
                                    <h2 class="name"><?php echo $value['productName'] ?></h2>
                                    <br>
                                    <h5><?php echo $value['productPrice'] ?>€</h5>
                                </div>
                            </a>
                            <div class="col-md-1"></div>
                        <?php endforeach ?>
                    </div>
                </div>
            <?php endif; ?>

        </div>
    </div>

    <div class="p-5 mt-5">
        <div class="container">

            <?php

                $curl_shop = curl_init(); 

                curl_setopt($curl_shop, CURLOPT_URL, $api_base . 'shop/' . $shop_id);
                curl_setopt($curl_shop, CURLOPT_RETURNTRANSFER, true);

                $result = curl_exec($curl_shop);
                $httpcode = curl_getinfo($curl_shop, CURLINFO_HTTP_CODE);
                        
                curl_close($curl_shop);

                if ($httpcode == 200) {
                    $result = json_decode($result, true);
                    $result = $result['Shop'];
                }

            ?>

            <?php if($result != null): ?>
                <div class="discover-shop p-4">
                    <div class="shop-images col-md-4" style="background: url('<?php echo $api_base . $public_folder . 'images/shopImages/' . $result['shopImage'] ?>') center center no-repeat; background-size: cover;"></div>
                    <div class="col-md-1"></div>
                    <div class="col-md-7">
                        <h6 style="margin-bottom: 0px;" class="quantity">Sold by:</h6>
                        <h2 class="mb-4"><?php echo $result['shopName'] ?></h2>
                        <p class="mb-4">
                            <?php 
                                if (strlen($result['shopDescription']) > 400) {
                                    echo substr($result['shopDescription'], 0, 400) . '...';
                                }
                                else {
                                    echo $result['shopDescription'];
                                }
                            ?>
                        </p>
                        <a class="btn btn-primary px-4" href="<?php echo $site_base_url . 'shop?shpdid=' . $result['shopId'] ?>&frm=yprd" type="button">Discover <?php echo $result['shopName'] ?></a>
                    </div>
                </div>
            <?php endif; ?>

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