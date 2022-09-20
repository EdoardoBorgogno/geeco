<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Shop - Geeco</title>

    <!-- Site icon -->
    <link rel="icon" type="image/png" href="<?php echo $base ?>Assets/icon/brand/brand.ico">

    <!-- Bootstrap -->
    <link rel="stylesheet" href="<?php echo $base ?>Assets/bootstrap/css/bootstrap.css">
    <link rel="stylesheet" href="<?php echo $base ?>Assets/bootstrap/js/bootstrap.js">

    <!-- Jquery -->
    <script src="<?php echo $base ?>Assets/jquery/JQuery-3.6.0.js"></script>

    <!-- Common -->
    <link rel="stylesheet" href="<?php echo $base ?>Features/Common/css/geeco.css">

    <!-- MyShop -->
    <link rel="stylesheet" href="<?php echo $base ?>Features/Pages/MyShop/css/myShop.css">
    <script src="<?php echo $base ?>Features/Pages/MyShop/js/myShop.js"></script>

</head>
<body>

    <!-- Page access -->
    <?php 

        $id = $_GET['id'];
        if (!isset($id)) {
            // Redirect to page not found
            header("Location: " . $site_base_url . "notFound");
        }

        $curl = curl_init(); 

        curl_setopt($curl, CURLOPT_URL, $api_base . 'shop/me/show');
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'Authorization: ' . $_COOKIE['UusJvalUs']
        ));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $result = curl_exec($curl);
        $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
                
        curl_close($curl);

        $result = json_decode($result, true);
        
        if($httpcode == 200) {
            //get shop id 
            $shop = $result['Shop'][0];

            $shop_id = $shop['shopId'];

            if($id == $shop_id) {

            }
            else {
                // Redirect to page not found
                header("Location: " . $site_base_url . "notFound");
            }
        }
        else if ($httpcode == 404) {
             echo   '<div class="container h-100" style="margin-top: 15%;">
                    <div class="text-white bg-orange border rounded border-0 p-4 py-5">
                        <div class="row h-100">
                            <div class="col-md-10 col-xl-8 text-center d-flex d-sm-flex d-md-flex justify-content-center align-items-center mx-auto justify-content-md-start align-items-md-center justify-content-xl-center">
                                <div>
                                    <h1 class="text-uppercase fw-bold text-white mb-3">Sorry, you don\' have any shop</h1>
                                    <br>
                                    <button class="btn btn-white fs-5 py-2 px-4" type="button" onclick="window.location.href= \'' . $base . 'dashboard\'">Back to dashboard</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            ';
            
            die();
        }
        else {
            // Redirect to page not found
            header("Location: " . $site_base_url . "notFound");
        }
    ?>

    <!-- Navbar -->
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
                        <a class="nav-link" href="<?php echo $base ?>dashboard">Dashboard</a>
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

    <!-- Get shop enitities count -->
    <?php

        $curl = curl_init(); 

        curl_setopt($curl, CURLOPT_URL, $api_base . 'shopcategory?id=' . $id);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'Authorization: ' . $_COOKIE['UusJvalUs']
        ));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $result = curl_exec($curl);
        $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
                
        curl_close($curl);

        $result = json_decode($result, true);

        $category_count = 0;

        if($httpcode == 200) {
            $category_count = count($result['shopCategories']);

            $shop_categories = $result['shopCategories'];
        }

        curl_setopt($curl, CURLOPT_URL, $api_base . 'product?id=' . $id);

        $result = curl_exec($curl);
        $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
                
        curl_close($curl);

        $result = json_decode($result, true);

        $product_count = 0;

        if($httpcode == 200) {
            $product_count = count($result['products']);

            $products = $result['products'];
        }

    ?>

    <div class="container mt-5">
        <div class="row">
            <div class="col-md-3">
                <h3><?php echo $shop['shopName'] ?>:</h3>
            </div>
        </div>
        <div class="row text-center">
            <div class="col-xl-4 col-sm-6 mb-5">
                <div class="bg-white rounded shadow-sm py-5 px-4"><img src="<?php $base ?>Assets/images/style/product.png" alt="" width="70" class="img-fluid rounded-circle mb-3 img-thumbnail shadow-sm">
                    <h5 class="mb-0">Total Product</h5><span class="small text-uppercase text-muted"><?php echo $product_count ?></span>
                </div>
            </div>
            <div class="col-xl-4 col-sm-6 mb-5">
                <div class="bg-white rounded shadow-sm py-5 px-4"><img src="<?php $base ?>Assets/images/style/order.png" alt="" width="70" class="img-fluid rounded-circle mb-3 img-thumbnail shadow-sm">
                    <h5 class="mb-0">Total Orders</h5><span class="small text-uppercase text-muted">0</span>
                </div>
            </div>
            <div class="col-xl-4 col-sm-6 mb-5">
                <div class="bg-white rounded shadow-sm py-5 px-4"><img src="<?php $base ?>Assets/images/style/category.png" alt="" width="70" class="img-fluid rounded-circle mb-3 img-thumbnail shadow-sm">
                    <h5 class="mb-0">Total Category</h5><span class="small text-uppercase text-muted"><?php echo $category_count ?></span>
                </div>
            </div>
        </div>
    </div>

    <?php 

        require 'Components/geecoTemplateIntro/geecoTemplateIntro.php';

        $geecoTemplateIntro = new geecoTemplateIntro();
        $geecoTemplateIntro->render();

    ?>

    <!-- Category Table -->
    <?php if($category_count > 0): ?>
        <div class="container mt-5">
            
            <div class="row">
                <div class="col-md-12">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th scope="col">Category Name</th>
                                <th scope="col">Category Description</th>
                                <th class="d-none d-md-block" scope="col">Category Image</th>
                                <th scope="col"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($shop_categories as $category) { ?>
                                <tr>
                                    <td><?php echo $category['categoryName'] ?></td>
                                    <td><?php echo $category['categoryShortDescription'] ?></td>
                                    <td class="d-none d-md-block"><div style="height:120px; background: url('<?php echo $api_base . $public_folder . 'images/shopCategoryImages/' . $category['categoryImage'] ?>') center center no-repeat; width:120px; background-size: contain;" alt="" class="img-fluid rounded mb-3"></div></td>
                                    <td>
                                        <button reqpath="<?php echo $api_base ?>shopcategory" class="btn btn-primary px-4 editCategory_btn" type="button" category="<?php echo $category['shopCategoryId'] ?>">Edit</button>
                                        <button reqpath="<?php echo $api_base ?>shopcategory" class="btn btn-danger deleteCategory_btn" type="button" category="<?php echo $category['shopCategoryId'] ?>">Delete</button>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <p id="errorMessageText-category">errorMessageText-category</p>
            <div class="m-2 mb-5"><a class="btn btn-primary fs-5 py-2 px-5" role="button" id="add-category">Add Category</a></div>

            <!-- Edit Category -->
            <div class="container" style="display:none;" id="edit-category-form">
                <div class="row">
                    <div class="col-md-12">
                        <h3>Edit Category</h3>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group m-3">
                            <input type="text" class="form-control" id="edit-categoryName" name="categoryName" placeholder="Enter category name">
                        </div>
                        <div class="form-group m-3">
                            <textarea class="form-control" id="edit-categoryShortDescription"" name="categoryShortDescription" rows="1" placeholder="Category Short description (max. 65)"></textarea>
                        </div>
                        <div class="form-group m-3">
                            <textarea class="form-control" id="edit-categoryDescription" name="categoryDescription" rows="3" placeholder="Category description"></textarea>
                        </div>
                        <div class="form-group m-3">
                            <input class="form-control" type="file" id="edit-categoryImage" accept="image/png, image/jpg, image/jpeg" required>
                        </div>  

                        <p id="edit-errorMessageText">ErrorMessage</p>

                        <button id="edit-category-btn" type="mt-1" class="btn btn-primary" reqpath="<?php echo $api_base ?>shopcategory">Save Category</button>
                        <button id="edit-category-btn-remove-changes" type="mt-1" class="btn btn-outline" reqpath="<?php echo $api_base ?>shopcategory">Cancel</button>
                    </div>
                </div>
            </div>

        </div>
    <?php else : ?>
        <section class="py-4 py-xl-5">
            <div class="container" id="no-category-container">
                <div id="no-category" class="text-white bg-orange border rounded border-0 border-primary d-flex flex-column justify-content-between flex-lg-row p-4 p-md-5">
                    <div class="pb-2 pb-lg-1">
                        <h2 class="fw-bold mb-2">There are no categories in your shop</h2>
                        <p class="mb-0">Add categories to your store to better rank your products.</p>
                    </div>
                    <div class="my-2"><a class="btn btn-white fs-5 py-2 px-5" role="button" id="add-first-category">Add Category</a></div>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <!-- Add Category -->
    <div class="container" style="display:none;" id="add-category-form">
        <div class="row">
            <div class="col-md-12">
                <h3>Add Category</h3>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group m-3">
                    <input type="text" class="form-control" id="categoryName" name="categoryName" placeholder="Enter category name">
                </div>
                <div class="form-group m-3">
                    <textarea class="form-control" id="categoryShortDescription"" name="categoryShortDescription" rows="1" placeholder="Category Short description (max. 65)"></textarea>
                </div>
                <div class="form-group m-3">
                    <textarea class="form-control" id="categoryDescription" name="categoryDescription" rows="3" placeholder="Category description"></textarea>
                </div>
                <div class="form-group m-3">
                    <input class="form-control" type="file" id="categoryImage" accept="image/png, image/jpg, image/jpeg" required>
                </div>  

                <p id="errorMessageText">ErrorMessage</p>

                <button id="add-category-btn" type="mt-1" class="btn btn-primary" reqpath="<?php echo $api_base ?>shopcategory">Add Category</button>
            </div>
        </div>
    </div>

    <!-- Geeco Features -->
    <div class="geeco-features">
        <div style="max-width: 300px;">
            <h1 class="text-uppercase fw-bold"><span style="color: rgb(255, 132, 0);">discover Geeco analitycs</span></h1>
            <p class="my-3">Active geeco Analitycs and improve your geeco experience.&nbsp;Find out the most visited products in your store and anticipate trends using Geeco Upcoming.</p><a class="btn btn-primary btn-lg me-2" role="button" href="<?php echo $site_base_url ?>analitycs">Go to Analitycs</a>
        </div>
        <img class="d-block" src="https://files.quantzig.com/wp-content/uploads/2020/11/actionAnalytics.svg" alt="">
    </div>

    <!-- Product Table -->
    <?php if($category_count > 0 && $product_count > 0): ?>
        <div class="container" id="product-table">
            <div class="row">
                <div class="col-md-12">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th scope="col">Product Name</th>
                                <th class="d-none d-md-block" scope="col">Product Image</th>
                                <th scope="col">Product Price</th>
                                <th scope="col">Product Category</th>
                                <th scope="col"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($products as $product) { ?>
                                <tr>
                                    <td><?php echo $product['productName'] ?></td>
                                    <td class="d-none d-md-block"><div style="height:120px; background: url('<?php echo $api_base . $public_folder . 'images/productImages/' . explode(';', $product['productImages'])[0] ?>') center center no-repeat; width:120px; background-size: contain;" alt="" class="img-fluid rounded mb-3"></div></td>
                                    <td><?php echo $product['productPrice'] ?></td>
                                    <td><?php echo $product['categoryName'] ?></td>
                                    <td>
                                        <button reqpath="<?php echo $api_base ?>product" class="btn btn-primary px-4 editProduct_btn" type="button" product="<?php echo $product['productId'] ?>">Edit</button>
                                        <button reqpath="<?php echo $api_base ?>product" class="btn btn-danger deleteProduct_btn" type="button" product="<?php echo $product['productId'] ?>">Delete</button>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <p id="errorMessageText-product">errorMessageText-product</p>
            <div class="m-2 mb-5"><a class="btn btn-primary fs-5 py-2 px-5" role="button" id="add-product-btn">Add Product</a></div>
        </div>
    <?php elseif($category_count > 0): ?>
        <section class="py-4 py-xl-5">
            <div class="container" id="no-products-container">
                <div id="no-products" class="text-white bg-orange border rounded border-0 border-primary d-flex flex-column justify-content-between flex-lg-row p-4 p-md-5">
                    <div class="pb-2 pb-lg-1">
                        <h2 class="fw-bold mb-2">There are no products in your shop</h2>
                        <p class="mb-0">Add products and start earning on geeco.</p>
                    </div>
                    <div class="my-2"><a class="btn btn-white fs-5 py-2 px-5" role="button" id="add-first-product">Add Product</a></div>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <!-- Add Product -->
    <div class="container" style="display:none;" id="add-product-form">
        <div class="row">
            <div class="col-md-12">
                <h3>Add Product</h3>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group m-3">
                    <input type="text" class="form-control" id="productName" name="productName" placeholder="Enter product name">
                </div>
                <div class="form-group m-3">
                    <textarea class="form-control" id="productDescription" name="productDescription" rows="3" placeholder="Product description"></textarea>
                </div>
                <div class="form-group m-3">
                    <input type="number" class="form-control" id="productPrice" name="productPrice" placeholder="Enter product price">
                </div>
                <div class="form-group m-3">
                    <select class="form-select" id="productCategory" name="productCategory">
                        <option selected disabled>Select category</option>
                        <?php foreach($shop_categories as $category) { ?>
                            <option><?php echo $category['categoryName'] ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="form-group m-3">
                    <input type="number" class="form-control" id="productQuantity" name="productQuantity" placeholder="Enter product quantity">
                </div>

                <br>

                <div class="form-check m-3">
                    <input class="form-check-input" type="checkbox" value="" id="productBookable">
                    <label class="form-check-label" for="productBookable">
                        Bookable
                    </label>
                </div>
                <div class="form-group m-3">
                    <input class="form-control" id="productAvailableFrom" name="productAvailableFrom" placeholder="Available from Y-m-d H:m:s">
                </div>

                <br>
                <p>
                    First image will be used as product image cover. You can add up to 5 images (first 4 are mandatory).
                </p>     
                <div>
                    <div class="form-group m-3">
                        <input class="form-control" type="file" id="productImage1" accept="image/png, image/jpg, image/jpeg" required>
                    </div>
                    <div class="form-group m-3">
                        <input class="form-control" type="file" id="productImage2" accept="image/png, image/jpg, image/jpeg" required>
                    </div>
                    <div class="form-group m-3">
                        <input class="form-control" type="file" id="productImage3" accept="image/png, image/jpg, image/jpeg" required>
                    </div>
                    <div class="form-group m-3">
                        <input class="form-control" type="file" id="productImage4" accept="image/png, image/jpg, image/jpeg" required>
                    </div>
                    <div class="form-group m-3">
                        <input class="form-control" type="file" id="productImage5" accept="image/png, image/jpg, image/jpeg">
                    </div>
                </div>

                <p id="add-errorMessageText-product">ErrorMessage</p>
                <button id="add-product" type="mt-1" class="btn btn-primary" reqpath="<?php echo $api_base ?>product">Add Product</button>
                
            </div>
        </div>
    </div>
    
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