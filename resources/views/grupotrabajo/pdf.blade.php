<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte Grupos de Trabajo</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; color: #000; }
        h1 { text-align: center; font-size: 18px; margin: 0; }
        p { text-align: center; margin: 4px 0 12px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #000; padding: 8px; }
        th { background-color: #f8f9fa; }
    </style>
</head>
<body>
    <h1>Reporte de Grupos de Trabajo - Año {{ $anioSeleccionado ?? date('Y') }}</h1>
    @php
        $labels = [1=>'PRIMER BIMESTRE',2=>'SEGUNDO BIMESTRE',3=>'TERCER BIMESTRE',4=>'CUARTO BIMESTRE',5=>'QUINTO BIMESTRE',6=>'SEXTO BIMESTRE'];
        $bimestreActual = $bimestreActual ?? 1;
        $bLabel = $labels[$bimestreActual] ?? 'BIMESTRE';
    @endphp
    <p>{{ $bLabel }} • Generado: {{ date('d/m/Y H:i') }}</p>

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
                ['id' => 'apt',  'descripcion' => 'Elaboración de anteproyectos preliminares de normas y manuales', 'unidad' => 'Anteproyecto preliminar'],
                ['id' => 'aft',  'descripcion' => 'Elaboración de anteproyectos finales de normas y manuales',    'unidad' => 'Anteproyecto final'],
                ['id' => 'ppt',  'descripcion' => 'Elaboración de proyectos preliminares de normas y manuales',   'unidad' => 'Proyecto preliminar'],
                ['id' => 'np',   'descripcion' => 'Publicación de normas y manuales',                            'unidad' => 'Norma y/o manual'],
                ['id' => 'sub4', 'descripcion' => 'Subcomité No.4',                                              'unidad' => 'Reunión'],
                ['id' => 'gt1',  'descripcion' => 'Grupo de Trabajo 1',                                          'unidad' => 'Reunión'],
            ];
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

            <tr>
                <td style="border: 1px solid #000; text-align: center;">{{ $index + 1 }}</td>
                <td style="border: 1px solid #000;">{{ $grupo['descripcion'] }}</td>
                <td style="border: 1px solid #000; text-align: center;">{{ $grupo['unidad'] }}</td>
                <td style="border: 1px solid #000; text-align: center;">{{ $datos->meta_anual ?? '' }}</td>
                <td style="border: 1px solid #000; text-align: center;">{{ $realizadoBimestre }}</td>
                <td style="border: 1px solid #000; text-align: center;">{{ $porcBimestral }}%</td>
                <td style="border: 1px solid #000; text-align: center;">{{ $porcAnual }}%</td>
                <td style="border: 1px solid #000;">{{ $datos->observaciones ?? '' }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</body>
</html>