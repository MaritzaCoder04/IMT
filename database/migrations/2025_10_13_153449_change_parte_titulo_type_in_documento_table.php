<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('documento', function (Blueprint $table) {
            // Cambiar de tinyint a int para soportar números grandes
            $table->integer('parte')->nullable()->change();
            $table->integer('titulo')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('documento', function (Blueprint $table) {
            $table->tinyInteger('parte')->nullable()->change();
            $table->tinyInteger('titulo')->nullable()->change();
        });
    }
};