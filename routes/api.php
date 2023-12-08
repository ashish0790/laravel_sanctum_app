<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EmployeeController;
// use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\ResetPasswordController;

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


Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/register', [AuthController::class, 'register'])->name('register');


Route::get('/password/reset/{token}', [ResetPasswordController::class,'resetPassword'])->name('password.reset');
Route::post('/password/reset/', [ResetPasswordController::class,'resetPost'])->name('reset.post');
Route::post('/forgotPassword', [ResetPasswordController::class,'forgotPassword'])->name('forgot');


Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/curUserDetail',[AuthController::class,'curUserDetail'])->name('userDetails');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::resource('/employees', EmployeeController::class);
    Route::post('/loginpasswordChange',[ResetPasswordController::class,'loginpasswordChange'])->name('loginpasswordChange');
});

