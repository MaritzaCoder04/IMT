<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('grupos') && !Schema::hasColumn('grupos', 'unidad_medida')) {
            Schema::table('grupos', function (Blueprint $table) {
                $table->string('unidad_medida')->default('Reunión');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('grupos') && Schema::hasColumn('grupos', 'unidad_medida')) {
            Schema::table('grupos', function (Blueprint $table) {
                $table->dropColumn('unidad_medida');
            });
        }
    }
};