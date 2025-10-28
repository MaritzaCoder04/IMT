<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use App\Models\GrupoTrabajo;

class AddMissingGrupoTrabajosSeeder extends Seeder
{
    public function run(): void
    {
        if (!Schema::hasTable('grupos_trabajo')) {
            $this->command?->warn('Tabla "grupos_trabajo" no existe; se omite el seeder.');
            return;
        }

        $defaults = [
            'meta_anual' => 0,
            'meta_bimestre_1' => 0,
            'meta_bimestre_2' => 0,
            'meta_bimestre_3' => 0,
            'meta_bimestre_4' => 0,
            'meta_bimestre_5' => 0,
            'meta_bimestre_6' => 0,
            'observaciones' => null,
        ];

        $names = [
            'Subcomité No.4',
            'Grupo de Trabajo 1',
        ];

        foreach ($names as $name) {
            $record = GrupoTrabajo::firstOrCreate(['nombre' => $name], $defaults);
            $this->command?->info("Asegurado: {$record->nombre}");
        }
    }
}