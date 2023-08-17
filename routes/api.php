<?php

use App\Http\Controllers\API\PackageController;
use App\Models\UserPackage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\RegisterController;
use App\Http\Controllers\API\AdvertiserController;
use App\Http\Controllers\API\PostController;
use App\Http\Controllers\API\CommonController;

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

 

Route::controller(CommonController::class)->group(function(){
    Route::get('industries', 'industries');
    Route::get('countries', 'countries'); 
    Route::get('cities/{country_id}/{state_id}', 'cities'); 
    Route::get('states/{country_id}', 'states'); 
});


Route::controller(RegisterController::class)->group(function(){  
    Route::post('register', 'register');
    Route::post('login', 'login');  
    Route::get('verify-user/{token}',  'verify_user');
    Route::post('forgot-password', 'forgot_password'); 
});
       

//check if login or not
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::middleware('auth:sanctum')->group( function () {
    // Route::resource('products', ProductController::class); 




    Route::controller(PostController::class)->group(function(){
        Route::get('posts', 'index');
        Route::post('post', 'store');
        Route::get('delete-post/{id}', 'destroy');
        Route::get('view-post/{id}', 'show');
        Route::post('update-post/{id}', 'update');
    }); 

    Route::controller(PackageController::class)->group(function(){
        Route::get('packages', 'index');
        Route::get('package/{id}', 'show');
    });
 
    Route::controller(AdvertiserController::class)->group(function(){
        Route::post('purchase-package', 'purchase_package'); 
        Route::get('user-packages', 'user_packages'); 
        Route::get('view-profile', 'view_profile'); 
        Route::get('edit-profile', 'edit_profile'); 
        Route::post('update-profile', 'update_profile'); 
        Route::post('upload-profile-image', 'profile_image'); 
    });

    // Route::controller()
});

 







