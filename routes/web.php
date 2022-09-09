<?php

use Illuminate\Http\Request;

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
*/

$router->post('/auth/{is_customer_value}', 'Auth\AuthController@auth');
$router->post('/auth/token/validate', 'Auth\AuthController@checkValidate');

/*
|--------------------------------------------------------------------------
| Customer Routes
|--------------------------------------------------------------------------
*/

$router->get('/customer', 'Customer\CustomerController@index');
$router->get('/customer/{id}', 'Customer\CustomerController@show');
$router->post('/customer', 'Customer\CustomerController@store');
$router->patch('/customer', 'Customer\CustomerController@update');
$router->delete('/customer', 'Customer\CustomerController@destroy');
$router->get('/customer/me/show', 'Customer\CustomerController@showme');

/*
|--------------------------------------------------------------------------
| User Routes
|--------------------------------------------------------------------------
*/

$router->get('/user/{id}', 'ShopUser\UserShopController@show');
$router->post('/user', 'ShopUser\UserShopController@store');
$router->patch('/user', 'ShopUser\UserShopController@update');
$router->delete('/user', 'ShopUser\UserShopController@destroy');
$router->get('/user/me/show', 'ShopUser\UserShopController@showme');

/*
|--------------------------------------------------------------------------
| Shop Routes
|--------------------------------------------------------------------------
*/

$router->get('/shop', 'Shop\ShopController@index');
$router->get('/shop/{id}', 'Shop\ShopController@show');
$router->post('/shop', 'Shop\ShopController@store');
$router->patch('/shop', 'Shop\ShopController@update');
$router->delete('/shop', 'Shop\ShopController@destroy');
$router->get('/shop/me/show', 'Shop\ShopController@showme');

/*
|--------------------------------------------------------------------------
| Product Routes
|--------------------------------------------------------------------------
*/

$router->post('/product', 'Product\ProductController@store');
$router->get('/product', 'Product\ProductController@index');
$router->delete('/product/{id}', 'Product\ProductController@destroy');

/*
|--------------------------------------------------------------------------
| GeecoCategory Routes
|--------------------------------------------------------------------------
*/

$router->get('/geecocategory', 'GeecoCategory\GeecoCategoryController@index');

/*
|--------------------------------------------------------------------------
| ShopCategory Routes
|--------------------------------------------------------------------------
*/

$router->get('/shopcategory', 'ShopCategory\ShopCategoryController@index');
$router->post('/shopcategory', 'ShopCategory\ShopCategoryController@store');
$router->delete('/shopcategory/{category_id}', 'ShopCategory\ShopCategoryController@destroy');
$router->patch('/shopcategory/{category_id}', 'ShopCategory\ShopCategoryController@update');
$router->get('/shopcategory/{id}', 'ShopCategory\ShopCategoryController@show');

/*************************************/
$router->get('/', function () use ($router) {
    return $router->app->version();
});
