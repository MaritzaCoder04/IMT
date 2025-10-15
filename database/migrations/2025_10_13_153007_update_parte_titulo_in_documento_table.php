<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('documento', function (Blueprint $table) {
            // Cambiar el tipo de dato para que acepte números más grandes
            $table->integer('parte')->change(); // Era tinyint(4)
            $table->integer('titulo')->change(); // Era tinyint(4)
        });
    }

    public function down(): void
    {
        Schema::table('documento', function (Blueprint $table) {
            $table->tinyInteger('parte')->change();
            $table->tinyInteger('titulo')->change();
        });
    }
};