<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::table('etapas', function (Blueprint $table) {
        $table->string('1a')->nullable();
        $table->string('1b')->nullable();
        $table->string('1c')->nullable();
        $table->string('2a')->nullable();
        $table->string('2b')->nullable();
        $table->string('2c')->nullable();
        $table->string('3a')->nullable();
        $table->string('3b')->nullable();
        $table->string('3c')->nullable();
        $table->string('4a')->nullable();
        $table->string('4b')->nullable();
        $table->string('4c')->nullable();
        $table->string('5a')->nullable();
        $table->string('5b')->nullable();
        $table->string('5c')->nullable();
    });
}

};
