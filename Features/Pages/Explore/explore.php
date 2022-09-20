<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Explore - Geeco</title>

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
    <link rel="stylesheet" href="<?php echo $base ?>Features/Pages/Explore/css/explore.css">
    <script src="<?php echo $base ?>Features/Pages/Explore/js/explore.js"></script>

</head>
<body>

    <!-- Explore -->
    <?php $search_key = isset($_GET['q']) ? $_GET['q'] : ''; ?>
    <?php $category_key = isset($_GET['category']) ? $_GET['category'] : '' ?>

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
                        <a class="nav-link active" href="<?php echo $base ?>explore">Explore</a>
                    </li>
                </ul>

                <form action="javascript:void(0);" class="form-inline">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span id="search-btn" class="input-group-text btn btn-primary">
                                <i class="fas fa-search"></i>
                            </span>
                        </div>
                        <input type="text" class="form-control" placeholder="Search" id="search-input" value="<?php echo $search_key ?>">
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

    <?php if($search_key != ''): ?>
        
        <?php

            /* Get products */
            $curl = curl_init(); 

            $search_key = str_replace(' ', '%20', $search_key);

            curl_setopt($curl, CURLOPT_URL, $api_base . 'product?key=' . $search_key);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

            $result = curl_exec($curl);
            $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
                    
            curl_close($curl);

            if ($httpcode == 200) {
                $result = json_decode($result, true);

                $products = $result['products'];
            }

        ?>

        <?php

            /* Get products */
            $curl_shop = curl_init(); 

            curl_setopt($curl_shop, CURLOPT_URL, $api_base . 'shop?key=' . $search_key);
            curl_setopt($curl_shop, CURLOPT_RETURNTRANSFER, true);

            $result = curl_exec($curl_shop);
            $httpcode = curl_getinfo($curl_shop, CURLINFO_HTTP_CODE);
                    
            curl_close($curl_shop);

            if ($httpcode == 200) {
                $result = json_decode($result, true);

                $shops = $result['Shops'];
            }

        ?>

        <?php

            /* Get products */
            $curl_category = curl_init(); 

            curl_setopt($curl_category, CURLOPT_URL, $api_base . 'geecocategory?name=' . $search_key);
            curl_setopt($curl_category, CURLOPT_RETURNTRANSFER, true);

            $result = curl_exec($curl_category);
            $httpcode = curl_getinfo($curl_category, CURLINFO_HTTP_CODE);
                    
            curl_close($curl_category);

            if ($httpcode == 200) {
                $result = json_decode($result, true);

                $categories = $result['GeecoCategories'];
            }

        ?>

        <div class="container">
            <?php $product_index = 0; ?>

            <?php if(count($products) > 0): ?>

                <?php foreach($products as $product): ?>

                    <?php if($product_index % 3 == 0): ?>
                        <div class="row">
                    <?php endif; ?>

                    <a class="col-md-3 card mt-3 mt-md-0" href="<?php echo $site_base_url . 'product?max=10&frm=home&prdid=' . $product['productId'] ?>">
                        <div class="image-content">
                            <div class="card-image">
                                <img class="card-img" src="<?php echo $api_base . $public_folder . 'images/productImages/' . explode(';', $product['productImages'])[0] ?>" alt="">
                            </div>
                        </div>

                        <div class="card-content">
                            <h2 class="name"><?php echo $product['productName'] ?></h2>
                            <br>
                            <h5><?php echo $product['productPrice'] ?>€</h5>
                        </div>
                    </a>
                    <div class="col-md-1"></div>

                    <?php $product_index++; ?>

                    <?php if($product_index % 3 == 0): ?>
                        </div>
                    <?php endif; ?>

                <?php endforeach; ?>

                </div>

            <?php else: ?>

                <div class="container h-100 mt-5 mb-5">
                    <div class="text-white bg-orange border rounded border-0 p-4 py-5">
                        <div class="row h-100">
                            <div class="col-md-10 col-xl-8 text-center d-flex d-sm-flex d-md-flex justify-content-center align-items-center mx-auto justify-content-md-start align-items-md-center justify-content-xl-center">
                                <div>
                                    <h1 class="text-uppercase fw-bold text-white mb-3">There is no products related to your search</h1>
                                    <p class="mb-4">Please rephrase your search.<br></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            <?php endif; ?>

        </div>

        <?php if(count($categories) > 0): ?>
        
            <div class="container">
                <?php foreach($categories as $category): ?>
                    <div class="row">

                        <div class="col-md-1"></div>
                        <div class="col-md-10 px-5" style="border-radius: 10px; background: linear-gradient( rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.7) ), url('<?php echo $api_base . $public_folder . "images/categoryImages/" . $category['categoryImage'] ?>') center center no-repeat; background-size: cover;">
                            <a href="<?php echo $site_base_url . 'explore?category=' . $category['categoryId'] ?>&frm=search&searchque=<?php echo $search_key ?>" style="text-decoration: none; text-align: center;">
                                <div class="row p-5">
                                    <div class="col-2"></div>
                                    <div class="col-8">
                                        <h3 class="text-white" style="font-weight: bold;"> <?php echo $category['categoryName'] ?> </h3>
                                    </div>
                                    <div class="col-2"></div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-1"></div>

                    </div>
                <?php endforeach; ?>
            </div>

        <?php endif; ?>
        
        <?php if(count($shops) > 0): ?>
            <div class="container">

                <div class="row mb-3 mt-5">
                    <h4>Shop related to your search</h4>
                </div>

                <?php $shop_index = 0; ?>

                <?php foreach($shops as $shop): ?>

                    <?php if($shop_index % 3 == 0): ?>
                        <div class="row">
                    <?php endif; ?>

                    <a class="col-md-3 card shop-card mt-3 mt-md-0" href="<?php echo $site_base_url . 'shop?shpid=' . $shop['shopId'] ?>">
                        <div class="image-content">
                            <div class="card-image">
                                <img class="card-img" src="<?php echo $api_base . $public_folder . 'images/shopImages/' . $shop['shopImage'] ?>" alt="">
                            </div>
                        </div>

                        <div class="card-content">
                            <h2 class="name"><?php echo $shop['shopName'] ?></h2>
                            <br>
                            <p><?php echo strlen($shop['shopDescription']) < 180 ? $shop['shopDescription'] : substr($shop['shopDescription'], 0, 180) . '...'; ?></p>
                        </div>
                    </a>
                    <div class="col-md-1"></div>

                    <?php $shop_index++; ?>

                    <?php if($shop_index % 3 == 0): ?>
                        </div>
                    <?php endif; ?>

                <?php endforeach; ?>

                </div>

            </div>
        <?php endif; ?>

    <?php elseif($category_key != '' && is_numeric($category_key)): ?>
        
        <?php

            /* Get category */
            $curl_category = curl_init(); 

            curl_setopt($curl_category, CURLOPT_URL, $api_base . 'geecocategory/' . $category_key);
            curl_setopt($curl_category, CURLOPT_RETURNTRANSFER, true);

            $result = curl_exec($curl_category);
            $httpcode = curl_getinfo($curl_category, CURLINFO_HTTP_CODE);
                    
            curl_close($curl_category);

            if ($httpcode == 200) {
                $result = json_decode($result, true);

                $category = $result['GeecoCategory'];
            }

        ?>

        <div class="container">

            <div class="row">
                <div class="col-md-1"></div>
                <div class="col-md-10 px-5" style="border-radius: 12px; background: linear-gradient( rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.7) ), url('<?php echo $api_base . $public_folder . "images/categoryImages/" . $category['categoryImage'] ?>') center center no-repeat; background-size: cover;">
                    <div class="row p-3">
                        <div class="col-md-12 d-flex justify-content-center align-items-center" style="flex-direction: column;">
                            <h1 class="text-white mb-3 mt-2" style="font-weight: bold;"><?php echo $category['categoryName'] ?></h1>
                            <div class="row">
                                <div class="col-2"></div>
                                <div class="col-8">
                                    <p class="lead text-white">
                                        <?php echo $category['categoryDescription'] ?>
                                    </p>
                                </div>
                                <div class="col-2"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-1"></div>
            </div>

        </div>

    <?php else: ?>

        <div class="container">

            <div class="row">
                <div class="col-md-1"></div>
                <div class="col-md-10 px-5" style="border-radius: 12px; background: linear-gradient( rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.7) ), url('<?php echo $base ?>Assets/images/style/world.jpg') center center no-repeat; background-size: cover;">
                    <div class="row p-5">
                        <div class="col-md-12 d-flex justify-content-center align-items-center" style="flex-direction: column; text-align: center;">
                            <h1 class="text-white mb-3 mt-2" style="font-weight: bold;">Explore</h1>
                                <p class="lead text-white">
                                    Explore the world of Geeco, all you want, when you want.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-1"></div>
            </div>

        </div>

    <?php endif ?>

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