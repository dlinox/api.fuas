<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/health-fua', [App\Http\Controllers\FUAController::class, 'healthFUA']);