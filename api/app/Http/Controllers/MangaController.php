<?php

namespace App\Http\Controllers;

use App\Models\Manga;
use App\Models\Autor;
use App\Models\Dibujante;
use App\Models\Genero;
use Illuminate\Http\Request;

class MangaController extends Controller
{
    public function index()
    {
        // Obtener todos los mangas junto con los autores, dibujantes y géneros
        $mangas = Manga::with(['autor', 'dibujante', 'generos'])->get();
        $autores = Autor::all();
        $dibujantes = Dibujante::all();
        $generos = Genero::all();

        return view('mangas.index', compact('mangas', 'autores', 'dibujantes', 'generos'));
    }

    public function store(Request $request)
    {
        // Validar los datos del formulario
        $request->validate([
            'titulo' => 'required|string|max:255',
            'autor_id' => 'required|exists:autores,id',
            'dibujante_id' => 'required|exists:dibujantes,id',
            'generos' => 'required|array',
            'generos.*' => 'exists:generos,id',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'nullable|date|after_or_equal:fecha_inicio', // Asegurarse de que la fecha de fin no sea anterior a la de inicio
        ]);

        // Crear el nuevo manga
        $manga = Manga::create([
            'titulo' => $request->titulo,
            'autor_id' => $request->autor_id,
            'dibujante_id' => $request->dibujante_id,
            'fecha_inicio' => $request->fecha_inicio,
            'fecha_fin' => $request->fecha_fin,
        ]);

        // Asociar los géneros seleccionados
        $manga->generos()->attach($request->generos);

        return redirect()->route('mangas.index')->with('success', 'Manga creado exitosamente.');
    }

    public function edit($id)
    {
        // Obtener el manga a editar junto con sus relaciones
        $manga = Manga::with(['autor', 'dibujante', 'generos'])->findOrFail($id);
        $autores = Autor::all();
        $dibujantes = Dibujante::all();
        $generos = Genero::all();

        return view('mangas.edit', compact('manga', 'autores', 'dibujantes', 'generos'));
    }

    public function update(Request $request, $id)
    {
        // Validar los datos del formulario
        $request->validate([
            'titulo' => 'required|string|max:255',
            'autor_id' => 'required|exists:autores,id',
            'dibujante_id' => 'required|exists:dibujantes,id',
            'generos' => 'required|array',
            'generos.*' => 'exists:generos,id',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'nullable|date|after_or_equal:fecha_inicio', // Asegurarse de que la fecha de fin no sea anterior a la de inicio
        ]);

        // Obtener el manga a actualizar
        $manga = Manga::findOrFail($id);

        // Actualizar los datos del manga
        $manga->update([
            'titulo' => $request->titulo,
            'autor_id' => $request->autor_id,
            'dibujante_id' => $request->dibujante_id,
            'fecha_inicio' => $request->fecha_inicio,
            'fecha_fin' => $request->fecha_fin,
        ]);

        // Actualizar los géneros asociados
        $manga->generos()->sync($request->generos);

        return redirect()->route('mangas.index')->with('success', 'Manga actualizado exitosamente.');
    }

    public function destroy($id)
    {
        // Eliminar el manga y sus relaciones
        $manga = Manga::findOrFail($id);
        $manga->generos()->detach(); // Desasociar los géneros
        $manga->delete();

        return redirect()->route('mangas.index')->with('success', 'Manga eliminado exitosamente.');
    }
}
