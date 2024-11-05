<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\Api\PythonController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('/register', [RegisterController::class, 'register']);

Route::post('/login', [RegisterController::class, 'login']);

Route::post('/artisan/python/first-way', [PythonController::class, 'artisan']);
Route::post('/execute/python/second-way', [PythonController::class, 'execute']);
Route::post('/process/python/third-way', [PythonController::class, 'process']);

Route::middleware(['checkToken:api'])->group(function () {

    Route::resource('/products', ProductController::class);
    Route::post('/logout', [RegisterController::class, 'logout']);
    Route::get('/profile', [RegisterController::class, 'profile']);

});