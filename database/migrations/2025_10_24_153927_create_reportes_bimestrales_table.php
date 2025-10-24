<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('reportes_bimestrales', function (Blueprint $table) {
            $table->id();
            $table->integer('anio');
            $table->integer('bimestre'); // 1-6
            $table->json('datos_grupos'); // Guardará los datos de cada grupo
            $table->text('notas')->nullable();
            $table->timestamps();
            
            // Índice único para evitar duplicados
            $table->unique(['anio', 'bimestre']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('reportes_bimestrales');
    }
};