<?php

use App\Http\Controllers\API\ContentPageController;
use App\Http\Controllers\API\FaqController;
use App\Http\Controllers\API\PackageController;
use App\Http\Controllers\API\SurveyController;
use App\Models\UserPackage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\RegisterController;
use App\Http\Controllers\API\AdvertiserController;
use App\Http\Controllers\API\PostController;
use App\Http\Controllers\API\CommonController;
use App\Http\Controllers\API\RolesController;
use App\Http\Controllers\API\PermissionsController;
use App\Http\Controllers\API\SwitchRoleController;
use App\Http\Controllers\API\TwofaController;

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

 

    // Route::get('csrf-token', function () {
    //     return response()->json(['csrf_token' => session()]);
    // });

    // Route::get('token', function (Request $request) {
    //     $token_session = $request->session()->token(); 
    //     $token = csrf_token();

    //     return response()->json(['token' => $token, 'token_session' => $token_session ]); 
      
    // });
 

 
    Route::controller(FaqController::class)->group(function(){
        Route::get('faq-advertiser', 'advertiser_faq'); 
        Route::get('faq-publisher', 'publisher_faq');
        Route::get('faq-survey', 'survey_faq');    
    });

    Route::controller(ContentPageController::class)->group(function(){
        Route::get('pages', 'pages'); 
        Route::get('page-content/{slug}', 'page_content');   
    });

    Route::controller(CommonController::class)->group(function(){
        Route::get('send-email', 'sendemail'); 
        Route::get('industries', 'industries');
        Route::get('countries', 'countries'); 
        Route::get('cities/{country_id}/{state_id}', 'cities'); 
        Route::get('states/{country_id}', 'states');   
    });

    Route::controller(RegisterController::class)->group(function(){  
        Route::post('register', 'register');
        Route::post('login', 'login');  
        Route::post('logout', 'logout');  
        Route::get('verify-user/{token}',  'verify_user');
        Route::post('forgot-password', 'forgot_password'); 
    });
    

    //check if login or not
    Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
        return $request->user();
    });

    // Route::get('switch-role/{role}', SwitchRoleController::class);
    
    // Route::middleware(['auth', 'auth.session'])->group(function () {
    //     Route::get('/', function () {
    //         // ...
    //     });
    // });

    //  'auth.session'
    //  'permission'
    Route::group(['middleware' => ['auth:sanctum']], function() {

        // Route::middleware('auth:sanctum',)->group( function () { 
  
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
            Route::get('dashboard', 'index'); 
            Route::post('switch-profile', 'switch_profile'); 
            Route::post('purchase-package', 'purchase_package'); 
            Route::get('user-packages', 'user_packages'); 
            Route::get('view-profile', 'view_profile'); 
            Route::get('edit-profile', 'edit_profile'); 
            Route::post('update-profile', 'update_profile'); 
            Route::post('upload-profile-image', 'profile_image'); 
        });


        Route::controller(SurveyController::class)->group(function(){
            Route::get('surveys', 'index');  
            Route::post('survey', 'store');  
            Route::post('survey-question', 'survey_question');  
            Route::post('survey-answer', 'survey_answer');  
        });

        
        Route::resource('roles', RolesController::class);
        Route::resource('permissions', PermissionsController::class);

        // Route::controller(RolesController::class)->group(function(){
        //     Route::get('index', 'index');   
        // });

        Route::controller(TwofaController::class)->group(function(){
            Route::post('twofa-status', 'twofa_status');   
        });
        

        // Route::controller()
    });

 
    // Route::middleware(['cors'])->group(function () {});






