<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\SlideController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Register, Login
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::middleware('auth:api')->group(function () {
// User information
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user-profile', [AuthController::class, 'userProfile']);
    Route::get('/user-products', [AuthController::class, 'productFavorite']);
    Route::put('/change-info', [AuthController::class, 'changeInfo']);
// Order
    Route::post('/order', [OrderController::class, 'create']);
    Route::get('/order', [OrderController::class, 'index']);
});

// Home page
Route::get('/home',[HomeController::class,'index']);
Route::get('/home/hot',[HomeController::class,'hotProduct']);
Route::get('/home/categories',[HomeController::class,'categoryList']);
Route::get('/home/freeshipping',[HomeController::class,'freeShippingProduct']);
Route::get('/home/gift',[HomeController::class,'giftProduct']);

// Product detail, Category detail
Route::get('/products/{id}',[ProductController::class,'show']);
Route::get('/categories/{id}',[CategoryController::class,'show']);
Route::get('/slides/{id}',[SlideController::class,'show']);
Route::get('/slides',[SlideController::class,'index']);

//Route::middleware(['auth:api','admin'])->group(function () {
    //// Admin
    // Products
    Route::get('/products',[ProductController::class,'index']);
    Route::post('/products',[ProductController::class,'create']);
    Route::put('/products/{id}',[ProductController::class,'update']);
    // Soft delete Product
    Route::delete('/products/{id}',[ProductController::class,'destroy']);
    Route::post('/products/trash',[ProductController::class,'trash']);
    Route::post('/products/restore/{id}',[ProductController::class,'restore']);
    Route::post('/products/destroy/{id}',[ProductController::class,'forceDelete']);

    // Category
    Route::get('/categories',[CategoryController::class, 'index']);
    Route::post('/categories',[CategoryController::class, 'create']);
    Route::put('/category/{id}',[CategoryController::class, 'update']);
    // Soft delete Category
    Route::delete('/category/{id}',[CategoryController::class,'destroy']);
    Route::post('/category/trash',[CategoryController::class,'trash']);
    Route::post('/category/restore/{id}',[CategoryController::class,'restore']);
    Route::post('/category/destroy/{id}',[CategoryController::class,'forceDelete']);
//});
