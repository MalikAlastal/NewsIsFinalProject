<?php

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
// Admin Model Routes
Route::post('/addAdmin' , [\App\Http\Controllers\AdminController::class , 'store']);
Route::post('/addComment' , [\App\Http\Controllers\NewsCommentController::class , 'store']);
Route::post('/login' , [\App\Http\Controllers\AdminController::class , 'login']);
Route::get('/getAllNews' , [\App\Http\Controllers\NewsController::class , 'getAllNews']);
Route::get('/filterNews' , [\App\Http\Controllers\NewsController::class , 'filterNews']);
Route::get('/getNewsById/{id}' , [\App\Http\Controllers\NewsController::class , 'show']);
Route::post('/updateComment/{id}' , [\App\Http\Controllers\NewsCommentController::class , 'update']);
Route::get('/deleteComment/{id}' , [\App\Http\Controllers\NewsCommentController::class , 'destroy']);


Route::group(['middleware' =>'auth:api'] , function(){
    Route::get('/getAdmins' , [\App\Http\Controllers\AdminController::class , 'index']);
    Route::post('/addCategory' , [\App\Http\Controllers\NewsCategoryController::class , 'store']);
    Route::post('/addNews' , [\App\Http\Controllers\NewsController::class , 'store']);
    Route::post('/updateNews/{id}' , [\App\Http\Controllers\NewsController::class , 'update']);
    Route::get('/deleteNews/{id}' , [\App\Http\Controllers\NewsController::class , 'destroy']);
});

