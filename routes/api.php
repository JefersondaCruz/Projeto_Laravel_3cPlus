<?php

use App\Http\Controllers\Api\ExploradorController;
use Illuminate\Support\Facades\Route;

Route::post('/exploradores', [ExploradorController::class,'store'])->name('exploradores.store');
