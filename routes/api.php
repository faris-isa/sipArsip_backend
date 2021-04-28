<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\OfferController;
use App\Http\Controllers\OfferPurchaseController;
use App\Http\Controllers\UserController;
// use App\Http\Controllers\OfferDetailController;
use App\Http\Controllers\OfferStatusController;
use App\Http\Controllers\ProductStatusController;



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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::apiResources([
    'products' => ProductController::class,
    'purchases' => PurchaseController::class,
    'offers' => OfferController::class,
    'offpurs' => OfferPurchaseController::class,
    'users' => UserController::class,
    // 'offersdet' => OfferDetailController::class,
], ['except' => ['create', 'edit']]);

Route::patch('products/status/{id}', [ProductController::class, 'status']);
Route::patch('offers/status/{id}', [OfferController::class, 'status']);
Route::post("user-login", [UserController::class, 'userLogin']);
Route::get("user/{username}", [UserController::class, 'userDetail']);
Route::get("serials/", [PurchaseController::class, 'getSerials']);
Route::post("monthly/", [PurchaseController::class, 'getPurchase']);
Route::get("offers/export/{id}", [OfferController::class, 'exportWord']);
Route::get("graphs/export", [OfferController::class, 'exportGraph']);
