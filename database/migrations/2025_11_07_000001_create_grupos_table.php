<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('grupos')) {
            Schema::create('grupos', function (Blueprint $table) {
                $table->id();
                $table->string('nombre');
                $table->text('descripcion')->nullable();
                $table->boolean('activo')->default(true);
                $table->timestamps();
            });

            // Datos iniciales útiles para la aplicación de grupos de trabajo
            DB::table('grupos')->insert([
                ['nombre' => 'Anteproyecto Preliminar', 'descripcion' => 'Grupo de trabajo para anteproyectos preliminares', 'activo' => true, 'created_at' => now(), 'updated_at' => now()],
                ['nombre' => 'Anteproyecto Final', 'descripcion' => 'Grupo de trabajo para anteproyectos finales', 'activo' => true, 'created_at' => now(), 'updated_at' => now()],
                ['nombre' => 'Proyecto Preliminar', 'descripcion' => 'Grupo de trabajo para proyectos preliminares', 'activo' => true, 'created_at' => now(), 'updated_at' => now()],
                ['nombre' => 'Publicación de Manuales/Normas', 'descripcion' => 'Grupo de trabajo para publicación de manuales y normas', 'activo' => true, 'created_at' => now(), 'updated_at' => now()],
                ['nombre' => 'Subcomité No.4', 'descripcion' => 'Subcomité número 4', 'activo' => true, 'created_at' => now(), 'updated_at' => now()],
                ['nombre' => 'Grupo de Trabajo 1', 'descripcion' => 'Grupo de trabajo número 1', 'activo' => true, 'created_at' => now(), 'updated_at' => now()],
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grupos');
    }
};