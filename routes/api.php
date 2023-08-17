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

Route::post('/auth/login', [UserController::class, 'login']);
Route::post('/auth/register', [UserController::class, 'register']);

Route::middleware('jwt.verify')->group(function () {
    Route::post('auth/logout', [UserController::class, 'logout']);
    Route::get('auth/getprofile', [UserController::class, 'getprofile']);
    Route::post('auth/editprofile', [UserController::class, 'editprofile']);
    Route::post('auth/destination', [DestinationController::class, 'add_destination']);
    Route::get('auth/children_destinations', [DestinationController::class, 'get_children_destinations']);
    Route::get('auth/nature_destinations', [DestinationController::class, 'get_nature_destinations']);
    Route::get('auth/all_destinations', [DestinationController::class, 'get_all_destinations']);
    Route::get('auth/get_bookmarks', [UserController::class, 'get_bookmarks']);
    Route::get('auth/img_slider', [DestinationController::class, 'get_img_slider']);
    Route::post('auth/addreview', [ReviewController::class, 'addreview']);
    Route::post('auth/addbookmark', [BookmarkController::class, 'addbookmark']);
    Route::get('send-email', [SendEmailController::class, 'index']);
});
