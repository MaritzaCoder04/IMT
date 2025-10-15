<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DbcontrollerVistas;

Route::get('/', [DbcontrollerVistas::class, 'Inicio'])->name('home');


// Rutas de vistas
Route::get('Inicio',[DbcontrollerVistas::class, 'Inicio'])->name('Inicio');
Route::get('/exportar-sql', [DbcontrollerVistas::class, 'exportarSQL'])->name('exportar.sql');

