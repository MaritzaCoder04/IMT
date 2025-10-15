<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UpdateDocumentoPartesTitulosSeeder extends Seeder
{
    public function run(): void
    {
        // Mapeo de conversión (ajusta según tus datos reales)
        $parteMapping = [
            0 => null, // 0 = sin parte
            1 => 10001,
            2 => 10002,
            3 => 10004,
            // Agrega más según necesites
        ];

        $tituloMapping = [
            0 => null, // 0 = sin título
            1 => 5010101,
            2 => 5010103,
            // Agrega más según necesites
        ];

        // Actualizar partes
        foreach ($parteMapping as $old => $new) {
            DB::table('documento')
                ->where('parte', $old)
                ->update(['parte' => $new]);
        }

        // Actualizar títulos
        foreach ($tituloMapping as $old => $new) {
            DB::table('documento')
                ->where('titulo', $old)
                ->update(['titulo' => $new]);
        }
    }
}