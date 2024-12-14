<?php

namespace App\Http\Controllers;

use App\Models\Autor;
use Illuminate\Http\Request;

class AutorController extends Controller
{
    public function index()
    {
        // Obtener todos los autores de la base de datos
        $autores = Autor::all();

        // Retornar la vista con los autores
        return view('autores.index', compact('autores'));
    }
}
