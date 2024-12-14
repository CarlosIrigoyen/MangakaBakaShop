<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Autor extends Model
{
    use HasFactory;

    // Asegúrate de que el nombre de la tabla sea correcto, si es diferente
    protected $table = 'autores'; 

    // Definir los campos que se pueden asignar de forma masiva
    protected $fillable = ['nombre', 'apellido'];

    // Aquí puedes definir las relaciones con otros modelos si es necesario
}
