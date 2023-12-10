<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::get('/test', function () {
  return response('API Test');
});

Route::post('register', [UserController::class, 'register']);
Route::post('login', [UserController::class, 'login']);

Route::group(['middleware' => ['auth:api']], function () {
  Route::get('profile', [UserController::class, 'profile']);
  Route::post('logout', [UserController::class, 'logout']);

  // BOOKS ROUTES
  Route::post('books', [BookController::class, 'index']);
  Route::post('books-create', [BookController::class, 'store']);
  Route::patch('books-update/{id}', [BookController::class, 'update']);
  Route::get('books-show/{id}', [BookController::class, 'show']);
  Route::delete('books-remove/{id}', [BookController::class, 'destroy']);
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
  return $request->user();
});
