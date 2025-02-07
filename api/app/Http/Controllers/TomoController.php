<?php

namespace App\Http\Controllers;

use App\Models\Tomo;
use App\Models\Manga;
use App\Models\Editorial;
use Illuminate\Http\Request;

class TomoController extends Controller
{
    public function index(Request $request)
    {
        // Obtener los filtros
        $mangaId = $request->get('manga_id');
        $editorialId = $request->get('editorial_id');
        $idioma = $request->get('idioma');
        $search = $request->get('search');

        // Consultar los mangas y editoriales para el filtro
        $mangas = Manga::all();
        $editoriales = Editorial::all();

        // Construir la consulta de los tomos
        $query = Tomo::with('manga', 'editoriales');

        if ($mangaId) {
            $query->where('manga_id', $mangaId);
        }

        if ($editorialId) {
            $query->whereHas('editoriales', function ($query) use ($editorialId) {
                $query->where('editorial_id', $editorialId);
            });
        }

        if ($idioma) {
            $query->where('idioma', $idioma);
        }

        if ($search) {
            $query->where('numero_tomo', 'like', "%$search%")
                  ->orWhereHas('manga', function($query) use ($search) {
                      $query->where('titulo', 'like', "%$search%");
                  });
        }

        // Obtener los tomos con paginación
        $tomos = $query->paginate(9);

        // Pasar las variables a la vista
        return view('tomos.index', compact('tomos', 'mangas', 'editoriales'));
    }
}
