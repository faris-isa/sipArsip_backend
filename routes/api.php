<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
// use App\Http\Controllers\UserController;
// use App\Http\Controllers\ProductController;
// use App\Http\Controllers\PurchaseController;
// use App\Http\Controllers\OfferController;
// use App\Http\Controllers\OfferPurchaseController;
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
    Route::get('/logout', [AuthController::class, 'logout'])->name('auth.logout');

    // Route For Data
    Route::apiResources([
        'products' => ProductController::class,
        'purchases' => PurchaseController::class,
        'offers' => OfferController::class,
        'offpurs' => OfferPurchaseController::class,
        'users' => UserController::class,
        'types'=> ProductTypeController::class,
        'manufactures'=> ProductManufactureController::class,
        'locations'=> PurchaseLocationController::class,
    ], ['except' => ['create', 'edit']]);

    Route::patch('products/status/{id}', 'ProductController@status');
    Route::post('offer-status/{id}', 'OfferController@status');
    Route::get("serials", 'PurchaseController@getSerials');
    Route::post("monthly", 'PurchaseController@getPurchase');
    Route::get("offers/export/{id}", 'OfferController@exportWord');
    Route::get("graphs/export", 'OfferController@exportGraph');
});

//unprotected cause login
Route::post('/login', 'AuthController@login')->name('auth.login');
Route::get('/401', 'AuthController@welcome')->name('login');
