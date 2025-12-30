<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\ApiDataLogger;
use App\Http\Middleware\Localization;

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


    Route::group(['prefix' => 'V1','middleware' => [ApiDataLogger::class,Localization::class]], function () {
        Route::post('/preRegister', 'App\Http\Controllers\API\V1\AuthController@preRegister');
        Route::post('/register', 'App\Http\Controllers\API\V1\AuthController@register');
        Route::any('/login', 'App\Http\Controllers\API\V1\AuthController@login');
        Route::post('/getAccessToken', 'App\Http\Controllers\API\V1\AuthController@getAccessToken');
        Route::post('/UpdatePassword', 'App\Http\Controllers\API\V1\AuthController@UpdatePassword');
        Route::post('/sendEmailOtp', 'App\Http\Controllers\API\V1\AuthController@sendEmailOtp');
        Route::post('/verifyEmailOtp', 'App\Http\Controllers\API\V1\AuthController@verifyEmailOtp');
        Route::get('change-language/{locale}', 'App\Http\Controllers\API\V1\AuthController@changeLanguage');
        Route::post('/resendMobileOtp', 'App\Http\Controllers\API\V1\ApiController@resendMobileOtp');
        Route::get('getPage', 'App\Http\Controllers\API\V1\ApiController@getCms');
        Route::post('ocr_image', 'App\Http\Controllers\API\V1\ApiController@ocr_image');
       
       


    });
    Route::group(['prefix' => 'V1','middleware' => [ApiDataLogger::class,Localization::class,'auth:api']], function () {

        Route::post('logout', 'App\Http\Controllers\API\V1\AuthController@logout');
        Route::post('/updateMobileNo', 'App\Http\Controllers\API\V1\ApiController@updateMobileNo');
        Route::post('/verifyMobileOtp', 'App\Http\Controllers\API\V1\ApiController@verifyMobileOtp');
        Route::post('/changePassword', 'App\Http\Controllers\API\V1\ApiController@changePassword');
        Route::post('/updateProfile', 'App\Http\Controllers\API\V1\ApiController@updateProfile');
        Route::post('/updateNotificationStatus', 'App\Http\Controllers\API\V1\ApiController@updateNotificationStatus');

        //vehicle Routes
        Route::post('addVehicle', 'App\Http\Controllers\API\V1\ApiController@addVehicle');
        Route::post('getVehicleDetail', 'App\Http\Controllers\API\V1\ApiController@getVehicleDetail');
        Route::get('getAllVehicles', 'App\Http\Controllers\API\V1\ApiController@getAllVehicles');


        //Add service
        Route::post('addService', 'App\Http\Controllers\API\V1\ApiController@addService');
        Route::post('getAllServices', 'App\Http\Controllers\API\V1\ApiController@getAllServices');
        Route::post('deleteServices', 'App\Http\Controllers\API\V1\ApiController@deleteServices');

        Route::post('addFuelRefill', 'App\Http\Controllers\API\V1\ApiController@addFuelRefill');
        Route::post('getAllFuelRefill', 'App\Http\Controllers\API\V1\ApiController@getAllFuelRefill');
        Route::post('getFuelRefillGraphData', 'App\Http\Controllers\API\V1\ApiController@getFuelRefillGraphData');
        Route::post('addSupport', 'App\Http\Controllers\API\V1\ApiController@addSupport');

        Route::post('addInsuranceRenewalRequest', 'App\Http\Controllers\API\V1\ApiController@addInsuranceRenewalRequest');
        Route::post('getInsurancePeriod', 'App\Http\Controllers\API\V1\ApiController@getInsurancePeriod');
        Route::post('getInsuranceRenewal', 'App\Http\Controllers\API\V1\ApiController@getInsuranceRenewal');
        Route::post('InsuranceRenewalProceed', 'App\Http\Controllers\API\V1\ApiController@InsuranceRenewalProceed');

        Route::post('addExpenses', 'App\Http\Controllers\API\V1\ApiController@addExpenses');
        Route::post('getExpensesDetail', 'App\Http\Controllers\API\V1\ApiController@getExpensesDetail');
        Route::post('getAllExpenses', 'App\Http\Controllers\API\V1\ApiController@getAllExpenses');
        Route::get('getContact', 'App\Http\Controllers\API\V1\ApiController@getContact');
        Route::post('/getModels', 'App\Http\Controllers\API\V1\ApiController@getModels');
        Route::post('/getModelIdByName', 'App\Http\Controllers\API\V1\ApiController@getModelIdByName');
        Route::get('/getBrands', 'App\Http\Controllers\API\V1\ApiController@getBrands');
        Route::post('/getBrandIdByName', 'App\Http\Controllers\API\V1\ApiController@getBrandIdByName');
       
        Route::get('/getServiceProviders', 'App\Http\Controllers\API\V1\ApiController@getServiceProviders');
        Route::get('/getServiceTypes', 'App\Http\Controllers\API\V1\ApiController@getServiceTypes');
        Route::get('/getExpenses', 'App\Http\Controllers\API\V1\ApiController@getExpensesType');
        Route::get('/getFuelType', 'App\Http\Controllers\API\V1\ApiController@getFuelType');
        Route::get('/getFaqs', 'App\Http\Controllers\API\V1\ApiController@getFaqs');
        Route::get('/getTransmissionType', 'App\Http\Controllers\API\V1\ApiController@getTransmissionType');
        Route::get('/getCoverType', 'App\Http\Controllers\API\V1\ApiController@getCoverType');
        Route::get('/getInsuranceCompany', 'App\Http\Controllers\API\V1\ApiController@getInsuranceCompany');
        Route::post('/getEngineCapicity', 'App\Http\Controllers\API\V1\ApiController@getEngineCapicity');
        Route::post('/getEngineCapacityIdByName', 'App\Http\Controllers\API\V1\ApiController@getEngineCapacityIdByName');

        Route::get('getAllBanner', 'App\Http\Controllers\API\V1\ApiController@getAllBanner');

        Route::post('reportUser', 'App\Http\Controllers\API\V1\ApiController@reportUser');

        Route::get('getBlockedUsers', 'App\Http\Controllers\API\V1\ApiController@getBlockedUsers');

        Route::post('blockUnBlockUser', 'App\Http\Controllers\API\V1\ApiController@blockUnBlockUser');

        Route::get('getMoodQuestions', 'App\Http\Controllers\API\V1\ApiController@getMoodQuestions');
        Route::post('trackMood', 'App\Http\Controllers\API\V1\ApiController@trackMood');

        Route::get('/getProfile', 'App\Http\Controllers\API\V1\ApiController@getProfile');
        Route::get('/deleteUser', 'App\Http\Controllers\API\V1\ApiController@deleteUser');
        Route::get('/searchUsers', 'App\Http\Controllers\API\V1\ApiController@searchUsers');

        Route::get('getNotifications', 'App\Http\Controllers\API\V1\ApiController@getNotifications');
        Route::get('deleteNotification', 'App\Http\Controllers\API\V1\ApiController@deleteNotification');
        Route::get('deleteAllNotification', 'App\Http\Controllers\API\V1\ApiController@deleteAllNotification');







    });

