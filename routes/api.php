<?php

use App\Http\Controllers\UrlController;
use Illuminate\Support\Facades\Route;

Route::get('/urls', [UrlController::class, 'index']);
Route::post('/urls', [UrlController::class, 'store']);
Route::get('/urls/{code}', [UrlController::class, 'show']);
Route::delete('/urls/{id}', [UrlController::class, 'destroy']);