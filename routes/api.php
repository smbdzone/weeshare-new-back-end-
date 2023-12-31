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
use App\Http\Controllers\API\AdvertisementController;
use App\Http\Controllers\API\PostController;
use App\Http\Controllers\API\CommonController;
use App\Http\Controllers\API\RolesController;
use App\Http\Controllers\API\PermissionsController;
use App\Http\Controllers\API\SwitchRoleController;
use App\Http\Controllers\API\TwofaController; 
use App\Http\Controllers\API\ReferralController;

use App\Http\Controllers\API\PublisherController;

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


    // Route::get('/2fa/enable', 'Google2FAController@enableTwoFactor');
    // Route::get('/2fa/disable', 'Google2FAController@disableTwoFactor');
    // Route::get('/2fa/validate', 'Auth\AuthController@getValidateToken');
    // Route::post('/2fa/validate', ['middleware' => 'throttle:5', 'uses' => 'Auth\AuthController@postValidateToken']);
    

    // 2fa middleware
    // Route::middleware(['2fa'])->group(function () {

    //     // HomeController
    //     Route::get('/dashboard', [AdvertiserController::class, 'index'])->name('home');

    //     Route::post('/2fa', function () {
    //         return redirect(route('dashboard'));
    //     })->name('2fa');

    // });


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
    //  '2fa'

    // Route::group(['middleware' => ['auth:sanctum','isAdvertiser']], function() 


    Route::prefix('advertiser')->middleware(['auth:sanctum','isAdvertiser'])->group(function () {



        // Route::middleware('auth:sanctum',)->group( function () { 
  
        Route::controller(PostController::class)->group(function(){
            Route::post('posts', 'index');
            Route::post('post', 'store');
            Route::post('upload-video', 'upload_video');
            Route::get('delete-post/{id}', 'destroy');
            Route::get('view-post/{id}', 'show');
            Route::post('update-post/{id}', 'update');
        }); 

        Route::controller(PackageController::class)->group(function(){
            Route::get('packages', 'index');
            Route::get('package/{id}', 'show');
        });
    
        Route::controller(AdvertiserController::class)->group(function(){
            Route::get('dashboard', 'index')->name('advertiser.dashboard'); 
            Route::post('switch-profile', 'switch_profile'); 
            Route::post('purchase-package', 'purchase_package'); 
            Route::get('user-packages', 'user_packages'); 
            Route::get('view-profile', 'view_profile'); 
            Route::get('edit-profile', 'edit_profile'); 
            Route::post('update-profile', 'update_profile'); 
            Route::post('upload-profile-image', 'profile_image'); 
            Route::post('generate-referral-code', 'generate_referral_code'); 

            

            Route::get('/referral', [ReferralController::class, 'index'])->name('referral.index');
            Route::post('/referral/generate', [ReferralController::class, 'generate'])->name('referral.generate');
            Route::post('/referral/use/{code}', [ReferralController::class, 'use'])->name('referral.use');

        });


        Route::controller(SurveyController::class)->group(function(){
            Route::get('surveys', 'index');  // All survey records
            Route::post('survey', 'store');  // Insert new survey topic
            
            // Route::get('survey-details/{id}', 'show'); // View survey details with all questions and answers
            Route::get('survey-details/{id}', [SurveyController::class, 'show'])->name('advertiser.surveys.details')->middleware('isAdvertiser');;
            Route::get('survey-edit/{id}', 'edit');   // Edit survey topic
            Route::put('survey-update/{id}', 'update');   // update survey topic
            Route::delete('survey-delete/{id}', 'destroy');  // Delete Survey Topic with all questions and answers

            Route::post('survey-question', 'survey_question');  // insert survey question
            Route::get('view-survey-question/{id}', 'show_question'); // View survey details
            Route::get('edit-survey-question/{id}', 'edit_question'); // edit survey details
            Route::put('update-survey-question/{id}', 'update_question'); // update survey details
            Route::delete('delete-survey-question/{id}', 'delete_question'); // delete survey details
            
            Route::post('survey-answer', 'survey_answer');  // insert survey answer
            Route::get('view-survey-answer/{id}', 'show_answer'); // View survey answer  
            Route::get('edit-survey-answer/{id}', 'edit_answer'); // edit survey details
            Route::put('update-survey-answer/{id}', 'update_answer'); // update survey details
            Route::delete('delete-survey-answer/{id}', 'delete_answer'); // delete survey details

            
            
            // Route::get('survey-edit/{id}', 'edit');   // Edit survey topic
            // Route::delete('survey-delete/{id}', 'destroy');  // Delete Survey Topic


            // Route::post('survey-answer', 'survey_answer');  // insert survey answer
            // Route::get('survey-details/{id}', 'show'); // View survey details
            // Route::get('survey-edit/{id}', 'edit');   // Edit survey topic
            // Route::delete('survey-delete/{id}', 'destroy');  // Delete Survey Topic
            
        });


        Route::controller(AdvertisementController::class)->group(function(){
            
            
            Route::post('add-balance', 'add_balance');  
            Route::post('add-advertisement', 'add_advertisement');  
            Route::get('balance', 'getAvailableBalance')->middleware('isAdvertiser');
            

        });


        // Route::controller()
        
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

    // Route::group(['middleware' => ['auth:sanctum','isPublisher']], function() 


    Route::prefix('publisher')->middleware(['auth:sanctum','isPublisher'])->group(function () {

  
        Route::controller(PublisherController::class)->group(function(){
            Route::get('dashboard', 'index')->name('publisher.dashboard');   

            // Route::get('/referral', [ReferralController::class, 'index'])->name('referral.index');
            // Route::post('/referral/generate', [ReferralController::class, 'generate'])->name('referral.generate');
            // Route::post('/referral/use/{code}', [ReferralController::class, 'use'])->name('referral.use');

        });

        Route::controller(PublisherController::class)->group(function(){
            Route::get('surveys', [SurveyController::class, 'survey_list'])->name('publisher.surveys.list')->middleware('isPublisher');;
            Route::get('survey/{id}', [SurveyController::class, 'survey_details'])->name('publisher.surveys.details')->middleware('isPublisher');
            Route::post('survey-answer', [SurveyController::class, 'submit_survey_answer'])->name('publisher.surveys.submitanswer')->middleware('isPublisher');;
        });
        

        Route::controller(AdvertisementController::class)->group(function(){
            // Route::get('surveys', [SurveyController::class, 'survey_list'])->name('publisher.surveys.list')->middleware('isPublisher');
            Route::get('view-advertisement/{id}/{view}', 'add_transaction')->middleware('isPublisher');
            Route::get('click-advertisement/{id}/{click}', 'add_transaction')->middleware('isPublisher');
           

        });


    });