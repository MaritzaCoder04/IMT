<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reuniones', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('grupo_trabajo_id');
            $table->date('fecha');
            $table->boolean('programada')->default(true); // true=programada, false=fuera de programación
            $table->text('motivo')->nullable();
            $table->timestamps();
            
            $table->foreign('grupo_trabajo_id')->references('id')->on('grupos_trabajo')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reuniones');
    }
};