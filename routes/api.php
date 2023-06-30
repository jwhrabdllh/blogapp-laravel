<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\LikeController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// auth
Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']); 
Route::get('logout', [AuthController::class, 'logout']);
Route::get('me', [AuthController::class, 'me'])->middleware('jwtAuth');
Route::post('add_photo_screen', [AuthController::class, 'addPhotoScreen'])->middleware('jwtAuth');
Route::post('user_profile', [AuthController::class, 'userProfile'])->middleware('jwtAuth');

// post
Route::group(['middleware' => ['jwtAuth']], function () {
    Route::get('post/get_posts', [PostController::class, 'posts']);
    Route::post('post/create', [PostController::class, 'create']);
    Route::put('post/update', [PostController::class, 'update']);
    Route::delete('post/delete/{id}', [PostController::class, 'delete']);
    Route::get('post/my_post', [PostController::class, 'myPosts']);
});

// comment
Route::group(['middleware' => ['jwtAuth']], function () {
    Route::post('post/comment', [CommentController::class, 'comments']);
    Route::post('comment/create', [CommentController::class, 'create']);
    Route::delete('comment/delete/{id}', [CommentController::class, 'delete']);
});

// like
Route::post('post/like',[LikeController::class, 'like'])->middleware('jwtAuth');
