<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AutorController;

Route::get('/autores', [AutorController::class, 'index'])->name('autores.index');
Route::get('/', function () {
    return view('welcome');
});

// Ruta de recursos para autores
Route::resource('autores', AutorController::class);

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});
