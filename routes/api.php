<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DestinationController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\BookmarkController;
use App\Http\Controllers\SendEmailController;


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

    Route::post('auth/login', [UserController::class,'login']);
    Route::post('auth/logout', [UserController::class,'logout']);
    Route::post('auth/register', [UserController::class,'register']);
    Route::post('auth/getprofile', [UserController::class,'getprofile']);
    Route::post('auth/editprofile', [UserController::class,'editprofile']);
    Route::post('auth/destination', [DestinationController::class,'add_destination']);
    Route::post('auth/addreview', [ReviewController::class,'addreview']);
    Route::post('auth/addbookmark', [BookmarkController::class,'addbookmark']);
    Route::get('send-email', [SendEmailController::class, 'index']);
