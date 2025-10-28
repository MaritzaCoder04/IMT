<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('grupo_trabajo_reportes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('grupo_trabajo_id');
            $table->integer('anio');
            $table->tinyInteger('bimestre');

            $table->integer('meta_bimestral')->nullable();
            $table->integer('realizado_bimestre')->default(0);
            $table->integer('total_acumulado')->default(0);
            $table->integer('porc_bimestral')->default(0);
            $table->integer('porc_anual')->default(0);

            $table->text('observaciones')->nullable();
            $table->text('notas')->nullable();

            $table->timestamps();

            $table->foreign('grupo_trabajo_id')
                ->references('id')
                ->on('grupos_trabajo')
                ->onDelete('cascade');

            $table->unique(['grupo_trabajo_id', 'anio', 'bimestre'], 'gt_rep_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('grupo_trabajo_reportes');
    }
};