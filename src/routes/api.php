<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\TableController;
use App\Http\Controllers\OpinionController;
use App\Http\Controllers\StripeController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// IMAGENES
Route::middleware("cors")->middleware('auth:sanctum')->post('/image/upload', [ImageController::class, 'upload']);
Route::middleware("cors")->middleware('auth:sanctum')->post('/image/delete', [ImageController::class, 'delete']);

// USERS
Route::middleware("cors")->middleware('auth:sanctum')->resource('user', UserController::class);

//Auth
Route::middleware("cors")->post('/register', [AuthController::class, 'register']);
Route::middleware("cors")->post('/login', [AuthController::class, 'login']);
Route::middleware("cors")->post('/logout', [AuthController::class, 'logout']);
Route::middleware("cors")->middleware('auth:sanctum')->get('/auth/user', [AuthController::class, 'getUserAuth']);


// MENU
Route::middleware("cors")->resource('menu', MenuController::class);

// RESERVATIONS
Route::middleware("cors")->resource('reservation', ReservationController::class);
Route::middleware("cors")->middleware('auth:sanctum')->post('/reservation/activate/{reservation}', [ReservationController::class, 'updateKey']);

// Orders
Route::middleware("cors")->resource('order', OrderController::class);


// Tables
Route::middleware("cors")->middleware('auth:sanctum')->resource('table', TableController::class);

// Opinions
Route::middleware("cors")->resource('opinion', OpinionController::class);

// Stripe
Route::middleware("cors")->post('/checkout', [StripeController::class, 'checkout']);
