<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
// use App\Http\Controllers\ProductController;
// use App\Http\Controllers\PurchaseController;
// use App\Http\Controllers\OfferController;
// use App\Http\Controllers\OfferPurchaseController;
// use App\Http\Controllers\UserController;
// use App\Http\Controllers\OfferDetailController;
// use App\Http\Controllers\OfferStatusController;
// use App\Http\Controllers\ProductStatusController;



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
//protected routes
Route::group(['middleware' => ['auth:sanctum']],  function () {
    //AUTH
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::apiResources([
            'products' => ProductController::class,
            'purchases' => PurchaseController::class,
            'offers' => OfferController::class,
        'offpurs' => OfferPurchaseController::class,
        'users' => UserController::class,
        // 'offersdet' => OfferDetailController::class,
    ], ['except' => ['create', 'edit', 'destroy']]);
    Route::get("user/{username}", [UserController::class, 'userDetail']);
    Route::patch('products/status/{id}', [ProductController::class, 'status']);
    Route::patch('offers/status/{id}', [OfferController::class, 'status']);
    Route::get("serials/", [PurchaseController::class, 'getSerials']);
    Route::post("monthly/", [PurchaseController::class, 'getPurchase']);
    Route::get("offers/export/{id}", [OfferController::class, 'exportWord']);
    Route::get("graphs/export", [OfferController::class, 'exportGraph']);
});

//unprotected cause login
Route::post('/login', [AuthController::class, 'login']);
Route::get('/welcome', [AuthController::class, 'welcome'])->name('login');
