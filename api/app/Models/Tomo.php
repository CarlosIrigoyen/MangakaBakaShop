<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tomo extends Model
{
    // Definir la tabla explícitamente (opcional si Laravel ya la detecta correctamente)
    protected $table = 'tomos';

    protected $fillable = [
        'manga_id',
        'editorial_id',
        'numero_tomo',
        'formato',
        'idioma',
        'precio',
        'fecha_publicacion',
        'portada'
    ];

    public $timestamps = false;

    // Relación con Manga (asumiendo que un tomo pertenece a un manga)
    public function manga()
    {
        return $this->belongsTo(Manga::class, 'manga_id');
    }

    // Relación: un Tomo pertenece a una Editorial.
    public function editorial()
    {
        return $this->belongsTo(Editorial::class, 'editorial_id');
    }
}
