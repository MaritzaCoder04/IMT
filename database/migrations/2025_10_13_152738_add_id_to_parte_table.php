<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('parte', function (Blueprint $table) {
            // Agregar columna id como autoincremental
            $table->id()->first(); // Se coloca al principio
            
            // O si prefieres mantener ID_parte como primary key:
            // $table->unsignedSmallInteger('id')->after('ID_parte');
        });
    }

    public function down(): void
    {
        Schema::table('parte', function (Blueprint $table) {
            $table->dropColumn('id');
        });
    }
};