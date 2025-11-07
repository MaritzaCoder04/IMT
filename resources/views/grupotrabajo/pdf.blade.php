<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte de Avance del Programa de la Elaboración de Normas</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; color: #000; margin: 0; }
        h1 { text-align: center; font-size: 18px; margin: 0; }
        p { text-align: center; margin: 4px 0 12px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #000; padding: 8px; }
        th { background-color: #f8f9fa; }
    </style>
</head>
<body>
    @php
        $labels = [1=>'PRIMER BIMESTRE',2=>'SEGUNDO BIMESTRE',3=>'TERCER BIMESTRE',4=>'CUARTO BIMESTRE',5=>'QUINTO BIMESTRE',6=>'SEXTO BIMESTRE'];
        $bimestreActual = $bimestreActual ?? 1;
        $bLabel = $labels[$bimestreActual] ?? 'BIMESTRE';

        $logoCandidates = [
            public_path('img/Logo_IMT.png'),
            public_path('Logos IMT/Logo_IMT.png'),
        ];
        $logoPath = '';
        foreach ($logoCandidates as $candidate) {
            if (@file_exists($candidate)) { $logoPath = $candidate; break; }
        }
        $logoData = (extension_loaded('gd') && $logoPath)
            ? ('data:image/png;base64,'.base64_encode(file_get_contents($logoPath)))
            : '';
    @endphp

    <div style="display: flex; align-items: center; margin: 0 0 12px 0;">
        @if($logoData)
            <img src="{{ $logoData }}" alt="Logo IMT" style="width: 60px; height: auto; margin: 0 10px 0 0;" />
        @endif
        <div style="flex: 1; text-align: center;">
            <div style="font-weight: bold; font-size: 14px;">DIRECCIÓN GENERAL</div>
            <div style="font-size: 12px;">Sistema de Gestión de la Calidad</div>
            <div style="font-weight: bold; font-size: 12px;">REPORTE DE AVANCE DEL PROGRAMA DE LA ELABORACIÓN DE NORMAS</div>
        </div>
    </div>

    <table style="width: 100%; border-collapse: collapse; font-size: 12px;">
        <thead>
            <tr style="background-color: #f8f9fa;">
                <th rowspan="3" style="border: 1px solid #000; padding: 8px; text-align: center; vertical-align: middle; width: 5%;">No.</th>
                <th rowspan="3" style="border: 1px solid #000; padding: 8px; text-align: center; vertical-align: middle; width: 35%;">Descripción</th>
                <th rowspan="3" style="border: 1px solid #000; padding: 8px; text-align: center; vertical-align: middle; width: 10%;">Unidad de medida</th>
                <th rowspan="3" style="border: 1px solid #000; padding: 8px; text-align: center; vertical-align: middle; width: 8%;">Meta</th>
                <th rowspan="3" style="border: 1px solid #000; padding: 8px; text-align: center; vertical-align: middle; width: 10%;">Avance bimestral <br><br>Productos terminados<br><br>(sólo en el caso de normas y manuales)</th>
                <th colspan="2" style="border: 1px solid #000; padding: 8px; text-align: center;">{{ $bLabel }}</th>
                <th rowspan="3" style="border: 1px solid #000; padding: 8px; text-align: center; vertical-align: middle; width: 15%;">Observaciones</th>
            </tr>
            <tr style="background-color: #f8f9fa;">
                <th colspan="2" style="border: 1px solid #000; padding: 8px; text-align: center;">PORCENTAJE</th>
            </tr>
            <tr style="background-color: #f8f9fa;">
                <th style="border: 1px solid #000; padding: 8px; text-align: center; font-size: 10px;">Realizado vs. Programado en el período</th>
                <th style="border: 1px solid #000; padding: 8px; text-align: center; font-size: 10px;">Realizado acumulado vs. Prog. Anual</th>
            </tr>
        </thead>
        <tbody>
        @php
            $grupos = [
                ['id' => '',  'descripcion' => 'Con base en el entorno tecnológico mundial, el IMT emitirá normas y manuales para ampliar y actualizar la Normativa Técnica de la SICT para la infraestructura del transporte, en lo referente a proyecto, construcción, conservación y características de materiales, así como métodos de muestreo y pruebas de materiales, con una meta de 18 anteproyectos preliminares, 17 anteproyectos finales, 14 proyectos preliminares y 14 normas y manuales por publicar.', 'unidad' => 'Producto Terminado'],
                ['id' => 'apt',  'descripcion' => 'Elaboración de anteproyectos preliminares de normas y manuales', 'unidad' => 'Anteproyecto preliminar'],
                ['id' => 'aft',  'descripcion' => 'Elaboración de anteproyectos finales de normas y manuales',    'unidad' => 'Anteproyecto final'],
                ['id' => 'ppt',  'descripcion' => 'Elaboración de proyectos preliminares de normas y manuales',   'unidad' => 'Proyecto preliminar'],
                ['id' => 'np',   'descripcion' => 'Publicación de normas y manuales',                            'unidad' => 'Norma y/o manual'],
                ['id' => '',   'descripcion' => 'Se continuará coordinando el Subcomité Número 4 de Señalamiento y Dispositivos de Seguridad Vial, del Comité Consultivo Nacional de Normalización de Transporte Terrestre y su Grupo de Trabajo 1, mismo que concluirá la NOM-037-SCT2-2025, Barreras de protección en carreteras y vías urbanas y publicará la NOM-033-SCT2-2024, Diseño de plazas de cobro en carreteras. Criterios de seguridad vial.', 'unidad' => ''],
                ['id' => 'sub4', 'descripcion' => 'Coordinación de las reuniones de trabajo del Subcomité No. 4', 'unidad' => 'Reunión'],
                ['id' => 'gt1',  'descripcion' => 'Coordinación de las reuniones del grupo de trabajo',           'unidad' => 'Reunión'],
                ['id' => '',   'descripcion' => 'Se continuará participando en los comités consultivos nacionales de normalización de Transporte Terrestre, de Transporte Aéreo, de Seguridad al Usuario, de la Secretaría de Economía, de Ordenamiento Territorial y Desarrollo Urbano; en los grupos de trabajo del Organismo Nacional de Normalización y Certificación de la Construcción y Edificación, S. C. (ONNCCE), y en el Comité Técnico 4.6 de la Asociación Mundial de la Carretera.', 'unidad' => 'Reunión'],
            ];
            // Calcular rowspan para Observaciones del bloque (de 6.1.4 hasta g))
            $rowspanBloque = 0; $enBloque = false;
            foreach ($grupos as $gcalc) {
                $isProducto = (empty($gcalc['id']) && (($gcalc['unidad'] ?? '') === 'Producto Terminado'));
                if ($isProducto) { $enBloque = true; }
                if ($enBloque) { $rowspanBloque++; }
                if (($gcalc['id'] ?? '') === 'gt1') { break; }
            }
            // Observación consolidada del bloque: usar la de gt1
            $obsBloque = (collect($todosLosGrupos ?? [])->where('id','gt1')->first()->observaciones ?? '');
        @endphp

        @foreach ($grupos as $index => $grupo)
            @php
                $datos = collect($todosLosGrupos ?? [])->where('id', $grupo['id'])->first();

                $metaBimestral = 0;
                $realizadoBimestre = 0;
                $totalAcumulado = 0;
                $porcBimestral = 0;
                $porcAnual = 0;

                if ($datos) {
                    $metaBimestral = $datos->{'meta_bimestre_' . $bimestreActual} ?? 0;
                    $realizadoBimestre = $datos->realizados[$bimestreActual] ?? 0;
                    for ($i = 1; $i <= $bimestreActual; $i++) {
                        $totalAcumulado += $datos->realizados[$i] ?? 0;
                    }
                    $porcBimestral = $metaBimestral > 0 ? round(($realizadoBimestre / $metaBimestral) * 100) : 0;
                    $porcAnual = ($datos->meta_anual ?? 0) > 0 ? round(($totalAcumulado / $datos->meta_anual) * 100) : 0;
                }
            @endphp

            @php 
                $soloDescripcion = empty($grupo['id']); 
                $esDescripcionProductoTerminado = $soloDescripcion && (($grupo['unidad'] ?? '') === 'Producto Terminado');
                $metaTotalProductoTerminado = 0;
                $descripcionDinamica = null;
                $etiquetaLetra = '';
                switch ($grupo['id'] ?? '') {
                    case 'apt': $etiquetaLetra = 'a)'; break;
                    case 'aft': $etiquetaLetra = 'b)'; break;
                    case 'ppt': $etiquetaLetra = 'c)'; break;
                    case 'np':  $etiquetaLetra = 'd)'; break;
                    case 'sub4':$etiquetaLetra = 'f)'; break;
                    case 'gt1': $etiquetaLetra = 'g)'; break;
                }
                // Fila especial final (g.3) con agregados
                $esFilaG3 = $soloDescripcion && (($grupo['unidad'] ?? '') === 'Reunión');
                $metaOtrosAnual = 0;
                $metaOtrosBimestral = 0;
                $realizadoOtrosBimestre = 0;
                $totalOtrosAcumulado = 0;
                $porcBimestralOtros = 0;
                $porcAnualOtros = 0;

                if ($esDescripcionProductoTerminado) {
                    $idsSumar = ['apt','aft','ppt','np'];
                    $metaPorId = [];
                    foreach ($idsSumar as $sid) {
                        $d = collect($todosLosGrupos ?? [])->where('id', $sid)->first();
                        $meta = $d ? ($d->meta_anual ?? 0) : 0;
                        $metaPorId[$sid] = $meta;
                        $metaTotalProductoTerminado += $meta;
                    }
                    $aptMeta = $metaPorId['apt'] ?? 0;
                    $aftMeta = $metaPorId['aft'] ?? 0;
                    $pptMeta = $metaPorId['ppt'] ?? 0;
                    $npMeta  = $metaPorId['np']  ?? 0;
                    $descripcionDinamica = "Con base en el entorno tecnológico mundial, el IMT emitirá normas y manuales para ampliar y actualizar la Normativa Técnica de la SICT para la infraestructura del transporte, en lo referente a proyecto, construcción, conservación y características de materiales, así como métodos de muestreo y pruebas de materiales, con una meta de {$aptMeta} anteproyectos preliminares, {$aftMeta} anteproyectos finales, {$pptMeta} proyectos preliminares y {$npMeta} normas y manuales por publicar.";
                }
                if ($esFilaG3) {
                    $excluir = ['apt','aft','ppt','np','sub4','gt1'];
                    foreach (($todosLosGrupos ?? []) as $g) {
                        if (in_array($g->id ?? null, $excluir)) continue;
                        $metaOtrosAnual += ($g->meta_anual ?? 0);
                        $metaOtrosBimestral += ($g->{'meta_bimestre_' . $bimestreActual} ?? 0);
                        $realizadoOtrosBimestre += ($g->realizados[$bimestreActual] ?? 0);
                        for ($i = 1; $i <= $bimestreActual; $i++) {
                            $totalOtrosAcumulado += ($g->realizados[$i] ?? 0);
                        }
                    }
                    $porcBimestralOtros = $metaOtrosBimestral > 0 ? round(($realizadoOtrosBimestre / $metaOtrosBimestral) * 100) : 0;
                    $porcAnualOtros = $metaOtrosAnual > 0 ? round(($totalOtrosAcumulado / $metaOtrosAnual) * 100) : 0;
                }
            @endphp
            <tr>
                <td style="border: 1px solid #000; text-align: center;">{{ $esDescripcionProductoTerminado ? '6.1.4' : ($esFilaG3 ? 'g.3' : ($soloDescripcion ? '' : $etiquetaLetra)) }}</td>
                <td style="border: 1px solid #000; text-align: justify;">{{ $esDescripcionProductoTerminado ? $descripcionDinamica : $grupo['descripcion'] }}</td>
                <td style="border: 1px solid #000; text-align: center;">{{ $esDescripcionProductoTerminado ? 'Producto Terminado' : ($esFilaG3 ? 'Reunión' : ($soloDescripcion ? '' : $grupo['unidad'])) }}</td>
                <td style="border: 1px solid #000; text-align: center;">{{ $esDescripcionProductoTerminado ? $metaTotalProductoTerminado : ($esFilaG3 ? $metaOtrosAnual : ($soloDescripcion ? '' : ($datos->meta_anual ?? ''))) }}</td>
                <td style="border: 1px solid #000; text-align: center;">{{ $esDescripcionProductoTerminado ? '' : ($esFilaG3 ? $realizadoOtrosBimestre : ($soloDescripcion ? '' : $realizadoBimestre)) }}</td>
                <td style="border: 1px solid #000; text-align: center;">{{ $esDescripcionProductoTerminado ? '' : ($esFilaG3 ? ($porcBimestralOtros . '%') : ($soloDescripcion ? '' : ($porcBimestral . '%'))) }}</td>
                <td style="border: 1px solid #000; text-align: center;">{{ $esDescripcionProductoTerminado ? '' : ($esFilaG3 ? ($porcAnualOtros . '%') : ($soloDescripcion ? '' : ($porcAnual . '%'))) }}</td>
                <td style="border: 1px solid #000;">
                    @if($esFilaG3)
                        {{ $notasReporte ?? '' }}
                    @elseif($esDescripcionProductoTerminado)
                        {{-- Fila 6.1.4 (Producto Terminado): sin observaciones consolidadas --}}
                    @elseif(!$soloDescripcion)
                        {{ $datos->observaciones ?? '' }}
                    @else
                        {{-- Filas descriptivas sin id: celda vacía --}}
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</body>
</html>
