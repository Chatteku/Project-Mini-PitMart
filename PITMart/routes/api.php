<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\API\EmailVerificationController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\API\FrontEndController;
use App\Http\Controllers\API\OrderController;
use App\Http\Controllers\CheckOutController;
use App\Http\Controllers\API\FacebookController;





/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->group(function() {
    Route::post('logout' , [AuthController::class,'logout']);


    // Category
Route::post('view-category' , [CategoryController::class, 'index']);
Route::post('insert-category' , [CategoryController::class, 'store']);
Route::post('update-category/{id}' , [CategoryController::class, 'update']);
Route::delete('delete-category/{id}' , [CategoryController::class, 'destroy']);
Route::delete('all-category' , [CategoryController::class, 'allcategory']);
Route::post('find-category/{id}' , [CategoryController::class, 'findcategory']);

// Orders
Route::get('admin/orders' , [OrderController::class, 'index']);

// Product
Route::get('index-product' , [ProductController::class, 'index']);
Route::post('store-product' , [ProductController::class, 'store']);
Route::post('update-product/{id}' , [ProductController::class, 'update']);
Route::delete('delete-product/{id}' , [ProductController::class, 'destroy']);
Route::post('find-product/{id}' , [ProductController::class, 'findproduct']);
});

// Login & Register
Route::post('register' , [AuthController::class,'register']);
Route::post('login' , [AuthController::class,'login']);

//verifikasi
Route::post('verifikasi', [EmailVerificationController::class, 'sendVerificationEmail']);
Route::get('verify-email/{id}/{hash}', [EmailVerificationController::class, 'verify'])->name('verification.verify');

// AddCart
Route::post('add-to-cart' , [CartController::class, 'addtocart']);
Route::get('showcart' , [CartController::class, 'showcart']);
Route::post('updateqty/{cart_id}/{scope}' , [CartController::class, 'updateqty']);
Route::delete('delete-cart/{id}' , [CartController::class, 'delete']);

// FrontEnd Category View
Route::get('getCategory' , [FrontEndController::class, 'category']);


// OrderPlace
Route::post('place-order' , [CheckOutController::class, 'placeorder']);

// Login Facebook
Route::get('login/facebook',  [FacebookController::class, 'redirectToFacebook']);
Route::get('callback', [FacebookController::class, 'handleFacebookCallback']);
