<?php

use App\Http\Controllers\PetaniController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('dashboard');
});

Route::resource('petani', PetaniController::class);