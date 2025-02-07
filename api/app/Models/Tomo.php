<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tomo extends Model
{


    public function tomos(){
         return $this->belongsToMany(Tomo::class, 'tomo_editorial', 'editorial_id', 'tomo_id');
    }

}
