<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::post('events/searchByName', '\App\Http\Controllers\Api\Event@searchByName');
Route::post('events/check', '\App\Http\Controllers\Api\Event@check');
Route::post('cart/send_images', '\App\Http\Controllers\Api\Cart@sendImages')->name("api.cart.send_images");
Route::post('contact/send', '\App\Http\Controllers\Api\Contact@sendMessage')->name("api.contact.send");
Route::apiResource('cart', \App\Http\Controllers\Api\Cart::class);
Route::post('user/check', '\App\Http\Controllers\Api\User@store')->name("api.user.check");

Route::middleware(['auth:sanctum'])->group(function () {
    Route::apiResource('events', \App\Http\Controllers\Api\Event::class);
    Route::apiResource('countries', \App\Http\Controllers\Api\Country::class);
    Route::apiResource('cities', \App\Http\Controllers\Api\City::class);
    Route::apiResource('bib_number', \App\Http\Controllers\Api\BibNumber::class);
    Route::apiResource('configuration', \App\Http\Controllers\Api\Configurations::class);
    Route::apiResource('sponsor_configuration', \App\Http\Controllers\Api\SponsorConfiguration::class);
    Route::post('upload_manager', '\App\Http\Controllers\Api\UploadManager@store')->name("api.upload_manager");
    Route::post('upload_manager/check_image', '\App\Http\Controllers\Api\UploadManager@checkImage')->name("api.upload_manager.check_image");
//    Route::post('upload_manager_images', '\App\Http\Controllers\Api\UploadManager@store')->name("api.upload_manager");
    Route::post('image_status', '\App\Http\Controllers\Api\ImageAnalysisStatus@index')->name("api.image_status");
    Route::post('analysis', '\App\Http\Controllers\Api\ImageAnalysis@startAnalysis')->name("api.image_analysis.start");
    Route::post('without_analysis', '\App\Http\Controllers\Api\ImageAnalysis@startWithoutAnalysis')->name("api.image_analysis.without_analysis");
    Route::post('clear_finished', '\App\Http\Controllers\Api\ImageAnalysis@analysisFinished')->name("api.image_analysis.analysis_finished");
});

\URL::forceScheme('https');
