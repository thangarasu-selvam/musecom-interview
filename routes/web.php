<?php

use Illuminate\Support\Facades\Route;

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

Route::get('user/register', [\App\Http\Controllers\RegisterController::class, 'create']);
Route::post('user/register', [\App\Http\Controllers\RegisterController::class, 'store']);

Route::get('/admin/login', [\App\Http\Controllers\AdminController::class, 'login']);
Route::post('/admin/login', [\App\Http\Controllers\AdminController::class, 'check_login']);
Route::get('/admin/user/list', [\App\Http\Controllers\UserController::class, 'user_list']);
