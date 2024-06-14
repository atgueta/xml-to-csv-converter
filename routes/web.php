<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

use App\Http\Controllers\FileConversionController;

Route::get('/', [FileConversionController::class, 'index']);
Route::post('/upload', [FileConversionController::class, 'upload']);
