<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\GrupoTrabajo;
use App\Models\Reunion;

// Crear grupos de trabajo de prueba
$subcomite = GrupoTrabajo::create([
    'nombre' => 'Subcomité No.4',
    'meta_anual' => 12,
    'meta_bimestre_1' => 2,
    'meta_bimestre_2' => 2,
    'meta_bimestre_3' => 2,
    'meta_bimestre_4' => 2,
    'meta_bimestre_5' => 2,
    'meta_bimestre_6' => 2
]);

$grupoTrabajo = GrupoTrabajo::create([
    'nombre' => 'Grupo de Trabajo Principal',
    'meta_anual' => 6,
    'meta_bimestre_1' => 1,
    'meta_bimestre_2' => 1,
    'meta_bimestre_3' => 1,
    'meta_bimestre_4' => 1,
    'meta_bimestre_5' => 1,
    'meta_bimestre_6' => 1
]);

// Crear reuniones de prueba
Reunion::create([
    'grupo_trabajo_id' => $subcomite->id,
    'fecha' => '2024-01-15',
    'programada' => true
]);

Reunion::create([
    'grupo_trabajo_id' => $subcomite->id,
    'fecha' => '2024-02-20',
    'programada' => true
]);

Reunion::create([
    'grupo_trabajo_id' => $subcomite->id,
    'fecha' => '2024-03-10',
    'programada' => true
]);

Reunion::create([
    'grupo_trabajo_id' => $grupoTrabajo->id,
    'fecha' => '2024-01-25',
    'programada' => true
]);

Reunion::create([
    'grupo_trabajo_id' => $grupoTrabajo->id,
    'fecha' => '2024-04-15',
    'programada' => true
]);

echo "Datos de prueba creados exitosamente:\n";
echo "- Subcomité ID: " . $subcomite->id . "\n";
echo "- Grupo de Trabajo ID: " . $grupoTrabajo->id . "\n";
echo "- Total reuniones creadas: " . Reunion::count() . "\n";