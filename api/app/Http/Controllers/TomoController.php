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

        // Construir la consulta de los tomos usando la relación 'editorial' (en singular)
        $query = Tomo::with('manga', 'editorial');

        if ($mangaId) {
            $query->where('manga_id', $mangaId);
        }

        if ($editorialId) {
            // Para la relación belongsTo, basta con filtrar por la columna 'editorial_id'
            $query->where('editorial_id', $editorialId);
        }

        if ($idioma) {
            $query->where('idioma', $idioma);
        }

        if ($search) {
            $query->where('numero_tomo', 'like', "%$search%")
                  ->orWhereHas('manga', function ($query) use ($search) {
                      $query->where('titulo', 'like', "%$search%");
                  });
        }

        // Obtener los tomos con paginación
        $tomos = $query->paginate(9);

        // Pasar las variables a la vista
        return view('tomos.index', compact('tomos', 'mangas', 'editoriales'));
    }
    public function store(Request $request)
    {
        // Validar los datos del formulario
        $validated = $request->validate([
            'manga_id'         => 'required|exists:mangas,id',
            'editorial_id'     => 'required|exists:editoriales,id',
            'numero_tomo'      => 'required|integer',
            'formato'          => 'required|in:Tankōbon,Aizōban,Kanzenban,Bunkoban,Wideban',
            'idioma'           => 'required|in:Español,Inglés,Japonés',
            'precio'           => 'required|numeric',
            'fecha_publicacion'=> 'required|date',
            'portada'          => 'required|image|max:2048', // máximo 2MB, por ejemplo
        ]);

        // Procesar la imagen de la portada
        if ($request->hasFile('portada')) {
            $file = $request->file('portada');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('images/tomos'), $filename);
            $validated['portada'] = 'images/tomos/' . $filename;
        }

        // Crear el tomo
        Tomo::create($validated);

        // Redireccionar con mensaje de éxito
        return redirect()->route('tomos.index')->with('success', 'Tomo creado exitosamente.');
    }
}
