<?php

use App\Http\Controllers\Api\ExploradorController;
use Illuminate\Support\Facades\Route;

Route::post('/exploradores', [ExploradorController::class,'store'])->name('exploradores.store');

Route::put('/exploradores/{id}', [ExploradorController::class,'update'])->name('update.explorador');

Route::post('/exploradores/{id}/inventario', [ExploradorController::class,'adicionarItem'])->name('adicionarItem.explorador');

Route::post('/exploradores/trocar', [ExploradorController::class,'']);

Route::get('exploradores/{id}', [ExploradorController::class, '']);
