<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
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

// User information
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:api');;
Route::get('/user-profile', [AuthController::class, 'userProfile'])->middleware('auth:api');
Route::get('/user-products', [AuthController::class, 'productFavorite'])->middleware('auth:api');
Route::post('/change-info', [AuthController::class, 'changeInfo'])->middleware('auth:api');

// Home page
Route::get('/home/hot',[HomeController::class,'hotProduct']);
Route::get('/home/categories',[HomeController::class,'categoryList']);
Route::get('/home/freeshipping',[HomeController::class,'freeShippingProduct']);
Route::get('/home/gift',[HomeController::class,'giftProduct']);

// Sort, Search, Products lists
//Route::get('/products',[ProductController::class,'index']); // pagination, get all, sort

// Product detail, Category detail
Route::get('/products/{id}',[ProductController::class,'show']);
Route::get('/categories/{id}',[CategoryController::class,'show']);

// Admin
// Products
Route::post('/products',[ProductController::class,'create']);
Route::put('/products/{id}',[ProductController::class,'update']);

// Soft delete Product
Route::get('/products',[ProductController::class,'index']);
Route::delete('/products/{id}',[ProductController::class,'destroy']);
Route::post('/products/trash',[ProductController::class,'trash']);
Route::post('/products/restore/{id}',[ProductController::class,'restore']);
Route::post('/products/destroy/{id}',[ProductController::class,'forceDelete']);

// Category
Route::get('/category',[CategoryController::class, 'index']);
Route::post('/category',[CategoryController::class, 'create']);
Route::post('/category/{id}',[CategoryController::class, 'update']);
