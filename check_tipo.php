<?php

require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "Datos de la tabla tipo:\n";
$tipos = DB::table('tipo')->get();

foreach ($tipos as $tipo) {
    echo "ID: {$tipo->ID_tipo}, Clave: {$tipo->clave}, Descripción: {$tipo->desc}\n";
}

echo "\nDatos de algunos documentos con tipo:\n";
$documentos = DB::table('documento')->select('ID_doc', 'nombre', 'tipo')->limit(10)->get();

foreach ($documentos as $doc) {
    echo "Doc ID: {$doc->ID_doc}, Tipo: {$doc->tipo}, Nombre: {$doc->nombre}\n";
}