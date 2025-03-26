<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\TomoController;
use App\Models\Autor;
use App\Models\Manga;
use App\Models\Editorial;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/register', [ClienteController::class, 'store']);
Route::post('/login', [ClienteController::class, 'login']);

Route::get('public/tomos', [TomoController::class, 'indexPublic']);

// Ruta para obtener filtros dinámicos desde la base de datos
Route::get('filters', function () {
    $authors = Autor::select('id', 'nombre')->get();
    $mangas = Manga::select('id', 'titulo')->get();
    $editorials = Editorial::select('id', 'nombre')->get();

    // Si los idiomas están almacenados en la base de datos, podrías obtenerlos desde ahí.
    // Si son valores fijos, los dejas como están.
    $languages = ['Español', 'Inglés', 'Japonés'];

    return response()->json([
        'authors'     => $authors,
        'languages'   => $languages,
        'mangas'      => $mangas,
        'editorials'  => $editorials,
    ]);
});
