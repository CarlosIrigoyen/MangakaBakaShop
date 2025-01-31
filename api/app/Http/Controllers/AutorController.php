<?php

namespace App\Http\Controllers;

use App\Models\Autor;
use Illuminate\Http\Request;

class AutorController extends Controller{

    public function store(Request $request){
        // Validar los datos del formulario
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
        ]);

        // Crear el nuevo autor
        Autor::create([
            'nombre' => $validated['nombre'],
            'apellido' => $validated['apellido'],
        ]);

        // Redirigir a la lista de autores con mensaje de éxito
        return redirect()->route('autores.index')->with('success', 'Autor creado exitosamente.');
    }
    public function index(){
        // Obtener todos los autores de la base de datos
        $autores = Autor::all();

        // Retornar la vista con los autores
        return view('autores.index', compact('autores'));
    }
    public function edit($id){
       $autor = Autor::findOrFail($id);
       return response()->json($autor);
    }

    public function destroy($id)
    {
        // Encontrar el autor por su ID
        $autor = Autor::findOrFail($id);

        // Eliminar el autor
        $autor->delete();

        // Redirigir a la lista de autores con un mensaje de éxito
        return redirect()->route('autores.index')->with('success', 'Autor eliminado correctamente.');
    }
    public function update(Request $request, $id){
            // Obtener el autor por su ID
            $autor = Autor::findOrFail($id);

            // Validar los datos enviados
            $validated = $request->validate([
                'nombre' => 'required|string|max:255',
                'apellido' => 'required|string|max:255',
            ]);

            // Actualizar los campos del autor
            $autor->nombre = $validated['nombre'];
            $autor->apellido = $validated['apellido'];

            // Guardar los cambios
            $autor->save();

            // Redirigir con un mensaje de éxito
            return redirect()->route('autores.index')->with('success', 'Autor actualizado correctamente.');
        }


}


