<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('grupos_trabajo', function (Blueprint $table) {
            if (!Schema::hasColumn('grupos_trabajo', 'anio_meta')) {
                $table->integer('anio_meta')->nullable()->after('nombre');
            }
        });

        // Inicializar el año de meta para registros existentes al año actual si está en NULL
        try {
            DB::table('grupos_trabajo')->whereNull('anio_meta')->update(['anio_meta' => (int)date('Y')]);
        } catch (\Exception $e) {
            // Silencioso: si falla, se mantendrá NULL y se tratará como no visible
        }
    }

    public function down(): void
    {
        Schema::table('grupos_trabajo', function (Blueprint $table) {
            if (Schema::hasColumn('grupos_trabajo', 'anio_meta')) {
                $table->dropColumn('anio_meta');
            }
        });
    }
};