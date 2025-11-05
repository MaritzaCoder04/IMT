<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

return new class extends Migration {
    public function up(): void
    {
        // Copia datos existentes de etapas (3a, 3b, 3c, 3e) a etapas_eventos
        $etapas = ['3a', '3b', '3c', '3e'];

        $rows = DB::table('etapas')
            ->select(array_merge(['ID_doc'], $etapas))
            ->get();

        foreach ($rows as $row) {
            foreach ($etapas as $etapa) {
                $valor = $row->{$etapa} ?? null;
                if (!$valor) {
                    continue;
                }

                // Normaliza fecha a YYYY-MM-DD si está en otro formato
                $fecha = self::parseFecha($valor);
                if (!$fecha) {
                    continue; // ignora valores no parseables
                }

                // Evita duplicados por la unique key
                $exists = DB::table('etapas_eventos')
                    ->where('ID_doc', $row->ID_doc)
                    ->where('etapa', $etapa)
                    ->where('fecha', $fecha)
                    ->exists();

                if (!$exists) {
                    DB::table('etapas_eventos')->insert([
                        'ID_doc' => (int) $row->ID_doc,
                        'etapa' => $etapa,
                        'fecha' => $fecha,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    }

    public function down(): void
    {
        DB::table('etapas_eventos')->whereIn('etapa', ['3a', '3b', '3c', '3e'])->delete();
    }

    private static function parseFecha(string $valor): ?string
    {
        $valor = trim($valor);

        // Casos típicos: 'YYYY-MM-DD', 'YYYY/MM/DD', 'DD/MM/YYYY'
        $patterns = [
            '/^(\\d{4})-(\\d{2})-(\\d{2})$/',
            '/^(\\d{4})\\/(\\d{2})\\/(\\d{2})$/',
            '/^(\\d{2})\\/(\\d{2})\\/(\\d{4})$/',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $valor, $m)) {
                // Normaliza a YYYY-MM-DD
                if (Str::contains($pattern, '^(\\d{4})')) {
                    // YYYY-MM-DD o YYYY/MM/DD
                    $y = $m[1]; $mo = $m[2]; $d = $m[3];
                } else {
                    // DD/MM/YYYY
                    $d = $m[1]; $mo = $m[2]; $y = $m[3];
                }

                // Valida y retorna
                $date = sprintf('%04d-%02d-%02d', (int)$y, (int)$mo, (int)$d);
                return $date;
            }
        }

        // Intenta parseo flexible si viene con texto
        try {
            $dt = new DateTime($valor);
            return $dt->format('Y-m-d');
        } catch (Throwable $e) {
            Log::warning('Fecha de etapa no válida para backfill', ['valor' => $valor]);
            return null;
        }
    }
};