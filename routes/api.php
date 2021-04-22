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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

//List books
Route::get('/books', [App\Http\Controllers\BooksController::class, 'index']);

//List single book
Route::get('/books/{isbn}', [App\Http\Controllers\BooksController::class, 'show']);

//Create book
Route::post('/books/create/{isbn}', [App\Http\Controllers\BooksController::class, 'store']);

//Delete book
Route::post('/books/delete/{isbn}', [App\Http\Controllers\BooksController::class, 'destroy']);
