<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('etapas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ID_doc'); // Relación con documento
            
            // Etapa 1 - APT
            $table->date('1a')->nullable(); // Fecha Inicio
            $table->date('2a')->nullable(); // Fecha Entrega
            $table->date('3a')->nullable(); // Fecha Terminación
            
            // Etapa 2 - AFT
            $table->date('1b')->nullable();
            $table->date('2b')->nullable();
            $table->date('3b')->nullable();
            
            // Etapa 3 - PPT
            $table->date('1c')->nullable();
            $table->date('2c')->nullable();
            $table->date('3c')->nullable();
            
            // Etapa 4 - NA
            $table->date('1d')->nullable();
            $table->date('2d')->nullable();
            $table->date('3d')->nullable();
            
            // Etapa 5 - NP
            $table->date('1e')->nullable();
            $table->date('2e')->nullable();
            $table->date('3e')->nullable();
            
            $table->timestamps();
            
            // Llave foránea
            $table->foreign('ID_doc')->references('ID_doc')->on('documento')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('etapas');
    }
};