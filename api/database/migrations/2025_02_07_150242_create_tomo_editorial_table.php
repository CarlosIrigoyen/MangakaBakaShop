<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTomoEditorialTable extends Migration
{
    public function up()
    {
        Schema::create('tomo_editorial', function (Blueprint $table) {
            $table->id();
            // Definir las claves foráneas
            $table->foreignId('tomo_id')->constrained()->onDelete('cascade');
            $table->foreignId('editoriales_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tomo_editorial');
    }
}
