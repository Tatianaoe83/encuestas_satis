<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\EnvioController;
use App\Http\Controllers\ResultadosController;

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

// Rutas para el módulo de clientes
Route::resource('clientes', ClienteController::class)
    ->middleware(['auth']);

// Rutas para el módulo de envíos
Route::resource('envios', EnvioController::class)
    ->middleware(['auth']);

// Rutas adicionales para envíos
Route::post('envios/{envio}/marcar-enviado', [EnvioController::class, 'marcarEnviado'])
    ->name('envios.marcar-enviado')
    ->middleware(['auth']);

Route::post('envios/{envio}/marcar-respondido', [EnvioController::class, 'marcarRespondido'])
    ->name('envios.marcar-respondido')
    ->middleware(['auth']);

// Ruta para visualización de resultados
Route::get('resultados', [ResultadosController::class, 'index'])
    ->name('resultados.index')
    ->middleware(['auth']);

Route::get('resultados/exportar', [ResultadosController::class, 'exportar'])
    ->name('resultados.exportar')
    ->middleware(['auth']);

Route::get('resultados/detalle', [ResultadosController::class, 'detalle'])
    ->name('resultados.detalle')
    ->middleware(['auth']);

require __DIR__.'/auth.php';
