<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use App\Models\Documento;
use App\Models\DocumentoInfo;
use App\Models\Libro;
use App\Models\Tema;
use App\Models\Origen;
use App\Models\Parte;
use App\Models\Titulo;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('documento:backfill-info', function () {
    $this->info('Iniciando backfill de documentoinfo...');
    $count = 0;
    $errores = 0;

    Documento::chunk(200, function ($docs) use (&$count, &$errores) {
        foreach ($docs as $documento) {
            try {
                $temaPad = str_pad((string)($documento->tema ?? 0), 2, '0', STR_PAD_LEFT);
                $partePad = str_pad((string)($documento->parte ?? 0), 2, '0', STR_PAD_LEFT);
                $tituloPad = str_pad((string)($documento->titulo ?? 0), 2, '0', STR_PAD_LEFT);
                $capituloPad = str_pad((string)($documento->capitulo ?? 0), 3, '0', STR_PAD_LEFT);

                $tipoClaveDb = \App\Models\Tipo::where('ID_tipo', $documento->tipo)->value('clave');
                $libroClaveDb = Libro::where('ID_libro', $documento->libro)->value('clave');
                $temaClaveDb = Tema::where('ID_tema', $documento->tema)->value('clave');

                // Obtener descripciones por ID directo (documento.parte/titulo ya contienen ID compuesto)
                $descParte = null;
                $descTitulo = null;
                if (!empty($documento->parte) && $documento->parte != 0) {
                    $descParte = Parte::where('ID_parte', $documento->parte)->value('desc');
                }
                if (!empty($documento->titulo) && $documento->titulo != 0) {
                    $descTitulo = Titulo::where('ID_titulo', $documento->titulo)->value('desc');
                }

                $componentes = [
                    $tipoClaveDb,
                    $libroClaveDb,
                    $temaClaveDb,
                    $documento->parte,
                    $tituloPad,
                    $capituloPad
                ];
                $componentesFiltrados = array_filter($componentes, function($v) {
                    return !is_null($v) && $v !== '' && $v !== 0 && $v !== '0' && $v !== '00' && $v !== '000';
                });
                $designacionGenerada = implode('-', $componentesFiltrados);

                // No se guarda 'origen' en documentoinfo en este proyecto

                // Asegurar anio_simple
                $anioSimple = $documento->anio_simple ?: substr((string)($documento->anio ?? ''), -2);

                DocumentoInfo::updateOrCreate(
                    ['ID_doc' => $documento->ID_doc],
                    [
                        'ID_doc' => $documento->ID_doc,
                        'nombre' => $documento->nombre,
                        'tipo' => (string)$documento->tipo,
                        'libro' => (string)$documento->libro,
                        'tema' => (string)$documento->tema,
                        'parte' => (string)$documento->parte,
                        // Evitar NULL en columnas con restricción NOT NULL
                        'desc_parte' => $descParte ?? '',
                        'titulo' => (string)$documento->titulo,
                        'desc_titulo' => $descTitulo ?? '',
                        'capitulo' => (string)$documento->capitulo,
                        'designacion' => $designacionGenerada ?: null,
                        // No guardamos anio_simple/anio en documentoinfo en este proyecto
                    ]
                );
                $count++;
            } catch (\Throwable $e) {
                $errores++;
                if ($errores <= 5) {
                    $this->error('Error en ID_doc ' . ($documento->ID_doc ?? 'desconocido') . ': ' . $e->getMessage());
                }
            }
        }
    });

    $this->info("Backfill completado. Registros procesados: {$count}. Errores: {$errores}.");
})->purpose('Genera desc_parte, desc_titulo y designación en documentoinfo para todos los documentos');
