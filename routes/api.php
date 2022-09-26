<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return 'Hey, API is working.';
});

Route::post('test-upload', [ProductController::class, 'testUpload']);

Route::group(['prefix' => 'user'], function () use ($router) {
    $router->post('/registration', [UserController::class, 'registration']);
    $router->get('/get-user', [UserController::class, 'getUser']);
});

Route::group(['middleware' => 'auth:api' , ], function () use ($router) {

    $router->group(['prefix' => 'product'], function () use ($router) {
        $router->get('/show', [ProductController::class, 'showProduct']);
        $router->get('/show-single-product', [ProductController::class, 'showSingleProduct']);
        $router->post('/create', [ProductController::class, 'createProduct']);
        $router->post('/update', [ProductController::class, 'updateProduct']);
        $router->delete('/delete/{id}', [ProductController::class, 'delete']);
    });

    $router->group(['prefix' => 'product-category'], function () use ($router) {
        $router->get('/show', [ProductController::class, 'showCategories']);
        $router->post('/create', [ProductController::class, 'createProductCategories']);
    });

});