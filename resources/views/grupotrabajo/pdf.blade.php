<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte Grupos de Trabajo</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        h1 { text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .totals { font-weight: bold; }
    </style>
</head>
<body>
    <h1>Reporte de Grupos de Trabajo - {{ date('Y') }}</h1>
    <p>Fecha de generación: {{ date('d/m/Y H:i') }}</p>

    <table>
        <thead>
            <tr>
                <th rowspan="2">No.</th>
                <th rowspan="2">Descripción</th>
                <th rowspan="2">Unidad de medida</th>
                <th rowspan="2">Meta</th>
                <th rowspan="2">Avance bimestral Productos terminados</th>
                <th colspan="2">Primer Bimestre PORCENTAJE</th>
                <th rowspan="2">Total Anual</th>
                <th rowspan="2">Observaciones</th>
            </tr>
            <tr>
                <th>Realizado vs. Programado en el periodo</th>
                <th>Realizado acumulado vs. Prog. Anual</th>
            </tr>
        </thead>
        <tbody>
            @foreach($grupos as $grupo)
            <tr>
                <td></td>
                        <td>{{ $grupo->nombre }}</td>
                        <td></td>
                        <td>{{ $grupo->meta_anual }}</td>
                        <td></td>
                        <td>{{ $grupo->realizados[1] ?? 0 }}</td>
                        <td>{{ $grupo->realizados[2] ?? 0 }}</td>
                <td class="totals">{{ $grupo->total_realizado }}</td>
                <td>{{ $grupo->observaciones ?? '--' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>