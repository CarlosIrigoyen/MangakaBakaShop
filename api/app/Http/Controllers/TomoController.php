<?php

namespace App\Http\Controllers;

use App\Models\Tomo;
use App\Models\Manga;
use App\Models\Editorial;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Str;

class TomoController extends Controller
{
    public function index(Request $request)
{
    // Obtener parámetros de filtrado
    $filterType = $request->get('filter_type'); // 'idioma', 'autor', 'manga' o 'editorial'
    $search     = $request->get('search');

    // Construir la consulta principal
    $query = Tomo::with('manga', 'editorial', 'manga.autor');

    if ($filterType) {
        if ($filterType == 'idioma') {
            $idioma = $request->get('idioma');
            if ($idioma) {
                $query->where('idioma', $idioma);
            }
        } elseif ($filterType == 'autor') {
            // Se espera que el select de autor envíe el id del autor
            $autor = $request->get('autor');
            if ($autor) {
                $query->whereHas('manga.autor', function ($q) use ($autor) {
                    $q->where('id', $autor);
                });
            }
        } elseif ($filterType == 'manga') {
            $mangaId = $request->get('manga_id');
            if ($mangaId) {
                $query->where('manga_id', $mangaId);
            }
        } elseif ($filterType == 'editorial') {
            $editorialId = $request->get('editorial_id');
            if ($editorialId) {
                $query->where('editorial_id', $editorialId);
            }
        }
    }

    if ($search) {
        $query->where(function ($q) use ($search) {
            $q->where('numero_tomo', 'like', "%$search%")
              ->orWhereHas('manga', function ($q2) use ($search) {
                  $q2->where('titulo', 'like', "%$search%");
              })
              ->orWhereHas('editorial', function ($q3) use ($search) {
                  $q3->where('nombre', 'like', "%$search%");
              });
        });
    }

    // Ordenar por el número de tomo (ascendente)
    $query->orderBy('numero_tomo', 'asc');

    // Paginar 6 tomos por página y conservar los parámetros en la URL
    $tomos = $query->paginate(6)->appends($request->query());

    // Consultar todas las opciones para los selects (para filtros y para el modal de creación)
    $mangas = Manga::all();
    $editoriales = Editorial::all();

    // Calcular, para cada manga, el próximo número de tomo y la fecha mínima (último tomo + 1 mes)
    $nextTomos = [];
    foreach ($mangas as $manga) {
        $lastTomo = Tomo::where('manga_id', $manga->id)
                        ->orderBy('numero_tomo', 'desc')
                        ->first();
        if ($lastTomo) {
            $nextTomos[$manga->id] = [
                'numero' => $lastTomo->numero_tomo + 1,
                'fecha'  => Carbon::parse($lastTomo->fecha_publicacion)
                                  ->addMonth()
                                  ->format('Y-m-d')
            ];
        } else {
            $nextTomos[$manga->id] = [
                'numero' => 1,
                'fecha'  => null
            ];
        }
    }

    // Obtener todos los tomos con stock menor a 10 (sin depender de los filtros)
    $lowStockTomos = Tomo::where('stock', '<', 10)->with('manga')->get();
    $hasLowStock   = $lowStockTomos->isNotEmpty();

    return view('tomos.index', compact('tomos', 'mangas', 'editoriales', 'nextTomos', 'lowStockTomos', 'hasLowStock'));
}


    public function store(Request $request)
    {
        $manga_id = $request->input('manga_id');
        $lastTomo = Tomo::where('manga_id', $manga_id)
                        ->orderBy('numero_tomo', 'desc')
                        ->first();

        if ($lastTomo) {
            $nextNumero = $lastTomo->numero_tomo + 1;
            // La fecha mínima para tomos posteriores es el último tomo + 1 mes
            $minFecha = Carbon::parse($lastTomo->fecha_publicacion)
                             ->addMonth()
                             ->format('Y-m-d');
        } else {
            $nextNumero = 1;
            $minFecha = null;
        }

        // Reglas de validación
        $rules = [
            'manga_id'         => 'required|exists:mangas,id',
            'editorial_id'     => 'required|exists:editoriales,id',
            'formato'          => 'required|in:Tankōbon,Aizōban,Kanzenban,Bunkoban,Wideban',
            'idioma'           => 'required|in:Español,Inglés,Japonés',
            'precio'           => 'required|numeric',
            'fecha_publicacion'=> $lastTomo ? "required|date|after_or_equal:$minFecha|before:" . date('Y-m-d') : "required|date|before:" . date('Y-m-d'),
            'portada'          => 'required|image|max:2048',
            'stock'            => 'sometimes|numeric|min:0'
        ];

        $validated = $request->validate($rules);

        // Asignar automáticamente el número de tomo calculado
        $validated['numero_tomo'] = $nextNumero;

        // Procesar la imagen de la portada
        if ($request->hasFile('portada')) {
            $file = $request->file('portada');
            $manga = Manga::find($validated['manga_id']);
            $mangaTitle = Str::slug($manga->titulo);
            $filename = "portada{$nextNumero}." . $file->getClientOriginalExtension();
            $destinationPath = public_path("tomo_portada/{$mangaTitle}");
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }
            $file->move($destinationPath, $filename);
            $validated['portada'] = "tomo_portada/{$mangaTitle}/" . $filename;
        }

        // Si no se especifica stock, se asigna 0 por defecto
        if (!isset($validated['stock'])) {
            $validated['stock'] = 0;
        }

        Tomo::create($validated);

        // Recuperar la URL a redirigir (con filtros, si se envió)
        $redirectTo = $request->input('redirect_to', route('tomos.index'));
        return redirect($redirectTo)->with('success', 'Tomo creado exitosamente.');
    }

    public function destroy($id, Request $request)
    {
        $tomo = Tomo::findOrFail($id);

        // (Opcional) Eliminar el archivo de la portada si existe
        if (file_exists(public_path($tomo->portada))) {
            unlink(public_path($tomo->portada));
        }

        $tomo->delete();

        // Recuperar la URL de redirección con filtros y paginación (enviada desde el formulario)
        $redirectTo = $request->input('redirect_to', route('tomos.index'));

        // Extraer los parámetros de consulta de la URL
        $urlComponents = parse_url($redirectTo);
        $queryParams = [];
        if (isset($urlComponents['query'])) {
            parse_str($urlComponents['query'], $queryParams);
        }

        // Reconstruir la consulta similar al método index para obtener la paginación actualizada
        $query = Tomo::with('manga', 'editorial', 'manga.autor');

        if (isset($queryParams['filter_type'])) {
            $filterType = $queryParams['filter_type'];
            if ($filterType == 'idioma' && !empty($queryParams['idioma'])) {
                $query->where('idioma', $queryParams['idioma']);
            } elseif ($filterType == 'autor' && !empty($queryParams['autor'])) {
                $autor = $queryParams['autor'];
                $query->whereHas('manga.autor', function ($q) use ($autor) {
                    $q->where('id', $autor);
                });
            } elseif ($filterType == 'manga' && !empty($queryParams['manga_id'])) {
                $query->where('manga_id', $queryParams['manga_id']);
            } elseif ($filterType == 'editorial' && !empty($queryParams['editorial_id'])) {
                $query->where('editorial_id', $queryParams['editorial_id']);
            }
        }

        if (!empty($queryParams['search'])) {
            $search = $queryParams['search'];
            $query->where(function ($q) use ($search) {
                $q->where('numero_tomo', 'like', "%$search%")
                  ->orWhereHas('manga', function ($q2) use ($search) {
                      $q2->where('titulo', 'like', "%$search%");
                  })
                  ->orWhereHas('editorial', function ($q3) use ($search) {
                      $q3->where('nombre', 'like', "%$search%");
                  });
            });
        }

        // Paginar (suponiendo 6 items por página)
        $tomos = $query->paginate(6)->appends($queryParams);

        // Obtener la página actual (si se envió) y la última página
        $currentPage = isset($queryParams['page']) ? (int)$queryParams['page'] : 1;
        $lastPage = $tomos->lastPage();

        // Si la página actual es mayor a la última página disponible, ajustar el parámetro 'page'
        if ($currentPage > $lastPage) {
            $queryParams['page'] = $lastPage;
            $redirectTo = route('tomos.index', $queryParams);
        }

        return redirect($redirectTo)->with('success', 'Tomo eliminado exitosamente.');
    }

    public function edit($id)
    {
        $tomo = Tomo::with('manga', 'editorial')->findOrFail($id);
        $mangas = Manga::all();
        $editoriales = Editorial::all();

        return response()->json([
            'tomo'       => $tomo,
            'mangas'     => $mangas,
            'editoriales'=> $editoriales,
        ]);
    }

    public function update(Request $request, $id)
    {
        $tomo = Tomo::findOrFail($id);

        $rules = [
            'manga_id'         => 'required|exists:mangas,id',
            'editorial_id'     => 'required|exists:editoriales,id',
            'formato'          => 'required|in:Tankōbon,Aizōban,Kanzenban,Bunkoban,Wideban',
            'idioma'           => 'required|in:Español,Inglés,Japonés',
            'precio'           => 'required|numeric',
            'fecha_publicacion'=> 'required|date|before:' . date('Y-m-d'),
            'stock'            => 'sometimes|numeric|min:0',
        ];

        if ($request->hasFile('portada')) {
            $rules['portada'] = 'image|max:2048';
        }

        $validated = $request->validate($rules);

        if ($request->hasFile('portada')) {
            // Eliminar la portada anterior si existe
            if (file_exists(public_path($tomo->portada))) {
                unlink(public_path($tomo->portada));
            }

            $file = $request->file('portada');
            $manga = Manga::find($validated['manga_id']);
            $mangaTitle = Str::slug($manga->titulo);
            $filename = "portada{$tomo->numero_tomo}." . $file->getClientOriginalExtension();
            $destinationPath = public_path("tomo_portada/{$mangaTitle}");

            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }

            $file->move($destinationPath, $filename);
            $validated['portada'] = "tomo_portada/{$mangaTitle}/" . $filename;
        }

        $tomo->update($validated);

        // Utilizar el campo 'redirect_to' para conservar filtros y paginación
        $redirectTo = $request->input('redirect_to', route('tomos.index'));
        return redirect($redirectTo)->with('success', 'Tomo actualizado correctamente.');
    }
    public function updateMultipleStock(Request $request)
{
    // Validar que se envíe un array de tomos con el stock y que cada stock sea un entero mínimo 1.
    $request->validate([
        'tomos' => 'required|array',
        'tomos.*.id' => 'required|exists:tomos,id',
        'tomos.*.stock' => 'required|integer|min:1',
    ]);

    // Recorrer cada entrada y actualizar el stock correspondiente.
    foreach ($request->tomos as $tomoData) {
        $tomo = Tomo::findOrFail($tomoData['id']);
        $tomo->update([
            'stock' => $tomoData['stock'],
        ]);
    }

    // Redireccionar al listado con mensaje de éxito.
    return redirect()->route('tomos.index')->with('success', 'Stocks actualizados correctamente.');
}



}
