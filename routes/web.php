<?php

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\UserController;
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

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('admin')->group(function() {
    Route::get('/login', [AdminAuthController::class, 'loginForm'])->middleware('guest')->name('admin.login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('admin.postlogin');

    // akses admin
    Route::get('/logout', [AdminAuthController::class, 'logout'])->middleware('admin')->name('admin.logout');
    Route::get('/user', [UserController::class, 'index'])->middleware('admin')->name('admin.index');
    Route::get('/edit/{id}', [UserController::class, 'edit'])->middleware('admin')->name('admin.edit');
    Route::put('/update/{id}', [UserController::class, 'update'])->middleware('admin')->name('admin.update');
    Route::get('/delete/{id}', [UserController::class, 'destroy'])->middleware('admin')->name('admin.delete');
});