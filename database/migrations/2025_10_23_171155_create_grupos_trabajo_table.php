<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('grupos_trabajo', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->integer('meta_anual');
            $table->integer('meta_bimestre_1')->default(0);
            $table->integer('meta_bimestre_2')->default(0);
            $table->integer('meta_bimestre_3')->default(0);
            $table->integer('meta_bimestre_4')->default(0);
            $table->integer('meta_bimestre_5')->default(0);
            $table->integer('meta_bimestre_6')->default(0);
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('grupos_trabajo');
    }
};