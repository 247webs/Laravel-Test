<?php

use Illuminate\Support\Facades\Route;
use App\Helpers\Constants;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Auth::routes();

Route::get('/', [App\Http\Controllers\ProductController::class, 'index'])->name('product');

Route::group(['middleware' => 'auth'], function () {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::get('/product/{id}', [App\Http\Controllers\ProductController::class, 'purchaseView'])->name('product.purchase');
    Route::post('/product/charge', [App\Http\Controllers\ProductController::class, 'purchase'])->name('product.charge');
    Route::get('/users', [App\Http\Controllers\UserController::class, 'index'])
        ->middleware('role:'.Constants::ROLE_ADMIN)
        ->name('users.list');
});
