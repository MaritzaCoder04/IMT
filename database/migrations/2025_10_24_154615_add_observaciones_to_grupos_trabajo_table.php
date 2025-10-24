// Migración: add_observaciones_to_grupos_trabajo_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('grupos_trabajo', function (Blueprint $table) {
            if (!Schema::hasColumn('grupos_trabajo', 'observaciones')) {
                $table->text('observaciones')->nullable()->after('meta_bimestre_6');
            }
        });
    }

    public function down()
    {
        Schema::table('grupos_trabajo', function (Blueprint $table) {
            $table->dropColumn('observaciones');
        });
    }
};