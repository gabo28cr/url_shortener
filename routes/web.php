<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UrlController;

Route::get('/urls', [UrlController::class, 'index']);
Route::post('/urls', [UrlController::class, 'store']);
Route::get('/urls/{code}', [UrlController::class, 'show']);
Route::delete('/urls/{id}', [UrlController::class, 'destroy']);
Route::get('/r/{code}', [UrlController::class, 'redirect']);

