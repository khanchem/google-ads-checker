<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AmazonScraperController; 
use App\Http\Controllers\GoogleAdsController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
});

//Amazon Rapid api Scrapper 
Route::post('/url-checker', [AmazonScraperController::class, 'checkAdSenseTags'])->name('search-product');


Route::get('/job', [GoogleAdsController::class, 'checkAdSenseTags']);


