<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('documento', function (Blueprint $table) {
            if (!Schema::hasColumn('documento', 'anio_simple')) {
                $table->unsignedTinyInteger('anio_simple')->nullable()->after('anio');
            }
        });

        // Backfill anio_simple from anio (last two digits)
        try {
            DB::table('documento')
                ->whereNotNull('anio')
                ->update(['anio_simple' => DB::raw('MOD(anio, 100)')]);
        } catch (\Exception $e) {
            // If backfill fails, leave values as NULL; application will set them on next save
        }
    }

    public function down(): void
    {
        Schema::table('documento', function (Blueprint $table) {
            if (Schema::hasColumn('documento', 'anio_simple')) {
                $table->dropColumn('anio_simple');
            }
        });
    }
};