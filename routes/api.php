<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CommentController;

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

Route::post('/login', [AuthController::class, 'authenticate']);
Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/logout', [AuthController::class, 'logout']);
    // post
    Route::get('/profiel', [UserController::class, 'index']);
    Route::post('/post',[PostController::class,'store']);
    Route::delete('/post/{id}',[PostController::class,'delete'])->middleware('cekPost');
    Route::put('/post/{id}',[PostController::class,'update'])->middleware('cekPost');

    // komentar
    Route::get('/post/{id}/comment',[CommentController::class,'show']);
    Route::post('/comment',[CommentController::class,'store']);
    Route::put('/comment/{id}',[CommentController::class,'update'])->middleware('cekComment');
    Route::delete('/comment/{id}',[CommentController::class,'delete'])->middleware('cekComment');
});

Route::get('/posts', [PostController::class, 'index']);
Route::get('/post/{id}', [PostController::class, 'show']);

