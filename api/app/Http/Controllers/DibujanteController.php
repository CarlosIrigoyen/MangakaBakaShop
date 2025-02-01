<?php

namespace App\Http\Controllers;
use App\Models\Dibujante;
use Illuminate\Http\Request;

class DibujanteController extends Controller
{
    // Almacenar un nuevo dibujante
    public function store(Request $request)
    {
        // Validar los datos del formulario
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
        ]);

        // Crear el nuevo dibujante
        Dibujante::create([
            'nombre' => $validated['nombre'],
            'apellido' => $validated['apellido'],
        ]);

        // Redirigir a la lista de dibujantes con mensaje de éxito
        return redirect()->route('dibujantes.index')->with('success', 'Dibujante creado exitosamente.');
    }

    // Mostrar la lista de dibujantes
    public function index()
    {
        // Obtener todos los dibujantes de la base de datos
        $dibujantes = Dibujante::all();

        // Retornar la vista con los dibujantes
        return view('dibujantes.index', compact('dibujantes'));
    }

    // Editar un dibujante (obtener datos para el formulario)
    public function edit($id)
    {
        // Buscar el dibujante por su ID
        $dibujante = Dibujante::findOrFail($id);

        // Retornar los datos del dibujante como JSON
        return response()->json($dibujante);
    }

    // Eliminar un dibujante
    public function destroy($id)
    {
        // Encontrar el dibujante por su ID
        $dibujante = Dibujante::findOrFail($id);

        // Eliminar el dibujante
        $dibujante->delete();

        // Redirigir a la lista de dibujantes con un mensaje de éxito
        return redirect()->route('dibujantes.index')->with('success', 'Dibujante eliminado correctamente.');
    }

    // Actualizar los datos de un dibujante
    public function update(Request $request, $id)
    {
        // Obtener el dibujante por su ID
        $dibujante = Dibujante::findOrFail($id);

        // Validar los datos enviados
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
        ]);

        // Actualizar los campos del dibujante
        $dibujante->nombre = $validated['nombre'];
        $dibujante->apellido = $validated['apellido'];

        // Guardar los cambios
        $dibujante->save();

        // Redirigir con un mensaje de éxito
        return redirect()->route('dibujantes.index')->with('success', 'Dibujante actualizado correctamente.');
    }
}
