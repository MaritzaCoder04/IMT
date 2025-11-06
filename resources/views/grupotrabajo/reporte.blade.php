@extends('home')
@section('contenido')
<link rel="stylesheet" href="{{ asset('css/estilosModales.css') }}">
@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif

@php
    $anioSeleccionado = $anioSeleccionado ?? date('Y');
    $bimestreSeleccionado = $bimestreSeleccionado ?? 1;
@endphp

<style>
.tabs-container {
    display: flex;
    gap: 10px;
    margin-bottom: 20px;
    border-bottom: 2px solid #e0e0e0;
}

.tab-btn {
    padding: 12px 24px;
    background: transparent;
    border: none;
    cursor: pointer;
    font-weight: 500;
    color: #666;
    border-bottom: 3px solid transparent;
    transition: all 0.2s;
}

.tab-btn:hover {
    color: #333;
    background: #f5f5f5;
}

.tab-btn.active {
    color: #667eea;
    border-bottom-color: #667eea;
}

.tab-content {
    display: none;
}

.tab-content.active {
    display: block;
}

.bimestre-selector {
    display: flex;
    gap: 8px;
    margin-bottom: 20px;
    flex-wrap: wrap;
}

.bimestre-btn {
    padding: 10px 20px;
    border: 2px solid #e0e0e0;
    background: white;
    cursor: pointer;
    border-radius: 6px;
    transition: all 0.2s;
    font-weight: 500;
}

.bimestre-btn:hover {
    border-color: #206f87;
    background: #f5f5ff;
}

.bimestre-btn.active {
    background: #2889a7;
    color: white;
}

.bimestre-btn.completado {
    border-color: #4caf50;
    background: #e8f5e9;
}

.bimestre-btn.completado.active {
    background: #4caf50;
    color: white;
}

.reporte-card {
    background: white;
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    padding: 15px;
    margin-bottom: 15px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    transition: all 0.2s;
}

.reporte-card:hover {
    box-shadow: 0 3px 10px rgba(0,0,0,0.1);
    transform: translateY(-2px);
}

.reporte-info h4 {
    margin: 0 0 5px 0;
    color: #333;
}

.reporte-info p {
    margin: 0;
    color: #666;
    font-size: 0.9em;
}

.reporte-actions {
    display: flex;
    gap: 8px;
}

.estado-badge {
    display: inline-block;
    padding: 4px 12px;
    border-radius: 12px;
    font-size: 0.85em;
    font-weight: 600;
    margin-left: 10px;
}

.estado-guardado {
    background: #e8f5e9;
    color: #2e7d32;
}

.estado-borrador {
    background: #fff3e0;
    color: #f57c00;
}

.year-navigation {
    display: flex;
    align-items: center;
    gap: 15px;
    margin-bottom: 20px;
}

.year-btn {
    background: #2889a7;
    color: white;
    box-shadow: 0 6px 0 #206f87, 0 8px 10px rgba(0, 0, 0, 0.3);
    position: relative;
    top: 0;
    border: none;
    padding: 8px 16px;
    border-radius: 6px;
    cursor: pointer;
    font-size: 1.2em;
}

.year-btn:hover {
    top: 2px;
    box-shadow: 0 4px 0 #185466, 0 6px 8px rgba(0, 0, 0, 0.3);
}

.current-year {
    font-size: 1.5em;
    font-weight: 600;
    color: #333;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 15px;
    margin-bottom: 25px;
}

.stat-box {
    background: white;
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    padding: 15px;
    text-align: center;
}

.stat-box h3 {
    margin: 0;
    font-size: 2em;
    color: #667eea;
}

.stat-box p {
    margin: 5px 0 0 0;
    color: #666;
    font-size: 0.9em;
}


.form-group {
    margin-bottom: 15px;
}

.form-group label {
    display: block;
    margin-bottom: 5px;
    font-weight: 500;
}

.form-group input,
.form-group textarea {
    width: 100%;
    padding: 8px 12px;
    border: 1px solid #ddd;
    border-radius: 4px;
}

.modal-actions {
    display: flex;
    gap: 10px;
    margin-top: 20px;
}
</style>



<div class="all-form">
    <div class="form-container">
        <div class="section-header">
            <h2 class="section-title"> Reporte Bimestral</h2>
        </div>

        <!--
        <div class="tabs-container">
            <button class="tab-btn active" onclick="cambiarTab('crear')">
                ➕ Crear/Editar Reporte
            </button>
            <button class="tab-btn" onclick="cambiarTab('inventario')">
                📚 Inventario de Reportes
            </button>
        </div>
        -->
 
        <div id="tab-crear" class="tab-content active">
            <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px;">
            </div>

            <div class="bimestre-selector">
                @php
                    $bimestres = [
                        1 => 'Bimestre 1 (Ene-Feb)',
                        2 => 'Bimestre 2 (Mar-Abr)',
                        3 => 'Bimestre 3 (May-Jun)',
                        4 => 'Bimestre 4 (Jul-Ago)',
                        5 => 'Bimestre 5 (Sep-Oct)',
                        6 => 'Bimestre 6 (Nov-Dic)',
                    ];
                    $bimestreActual = $bimestreSeleccionado ?? 1;
                @endphp

                <div class="reporte-toolbar" style="display:grid; grid-template-columns: 1fr auto; align-items:center; gap:10px; width:100%;">
                    <div class="bimestres-left" style="display:flex; align-items:center; gap:8px; flex-wrap:wrap;">
                        @foreach($bimestres as $num => $nombre)
                            @php
                                $reporteExiste = isset($reportesGuardados[$anioSeleccionado][$num]);
                            @endphp
                            <button class="bimestre-btn {{ $num == $bimestreActual ? 'active' : '' }} {{ $reporteExiste ? 'completado' : '' }}" 
                                    onclick="cambiarBimestre({{ $num }})"
                                    title="{{ $reporteExiste ? 'Reporte guardado' : 'Sin guardar' }}">
                                {{ $nombre }}
                                @if($reporteExiste)
                                    ✓
                                @endif
                            </button>
                        @endforeach
                    </div>
                    <div class="descargar-right" style="display:flex; align-items:center;">
                        <button type="button" class="btn btn-ejemplo" onclick="window.location='{{ route('grupotrabajo.pdf', ['anio' => $anioSeleccionado ?? date('Y'), 'bimestre' => $bimestreSeleccionado ?? 1]) }}'">
                            Descargar PDF
                        </button>
                    </div>
                </div>
            </div>
            

            <div class="docs-table-wrapper">
                <table class="docs-table">
                    <thead>
                        <tr>
                            <th rowspan="2">No.</th>
                            <th rowspan="2">Grupo de Trabajo</th>
                            <th rowspan="2">Meta Anual</th>
                            <th rowspan="2">Meta Bimestral</th>
                            <th rowspan="2">Realizado en Bimestre</th>
                            <th colspan="2">PORCENTAJE</th>
                        </tr>
                        <tr>
                            <th>Realizado vs. Meta Bimestral</th>
                            <th>Acumulado vs. Meta Anual</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($todosLosGrupos as $index => $grupo)
                        @php
                            // Usar únicamente los datos precalculados del controlador
                            $metaBimestral = $grupo->{'meta_bimestre_' . $bimestreActual};
                            $realizadoBimestre = $grupo->realizados[$bimestreActual] ?? 0;
                            $totalAcumulado = 0;
                            for ($i = 1; $i <= $bimestreActual; $i++) {
                                $totalAcumulado += $grupo->realizados[$i] ?? 0;
                            }

                            $unidadMedida = (isset($grupo->es_fijo) && $grupo->es_fijo) ? 'Documentos terminados' : 'Reuniones';
                            
                            $porcBimestral = $metaBimestral > 0 ? round(($realizadoBimestre / $metaBimestral) * 100) : 0;
                            $porcAnual = $grupo->meta_anual > 0 ? round(($totalAcumulado / $grupo->meta_anual) * 100) : 0;
                        @endphp
                        <tr style="{{ isset($grupo->es_fijo) && $grupo->es_fijo ? 'background-color: #f8f9fa; border-left: 4px solid #007bff;' : '' }}">
                            <td>{{ $index + 1 }}</td>
                            <td>
                                {{ $grupo->nombre }}
                            </td>
                            <td>{{ $grupo->meta_anual }}</td>
                            <td>{{ $metaBimestral }}</td>
                            <td>{{ $realizadoBimestre }}</td>
                            <td>{{ $porcBimestral }}%</td>
                            <td>{{ $porcAnual }}%</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="empty-state">
                                <p>No hay grupos de trabajo registrados</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- REPORTE DE AVANCE DEL PROGRAMA DE LA ELABORACION DE NORMAS -->
        <div style="margin-top: 40px;">
            <div style="display: flex; align-items: center; justify-content: center; margin-bottom: 10px;">
                <h3 style="text-align: center; font-weight: bold; margin: 0;">REPORTE DE AVANCE DEL PROGRAMA DE LA ELABORACIÓN DE NORMAS</h3>
            </div>
            
            <table class="table docs-table" style="width: 100%; border-collapse: collapse; font-size: 12px;">
                <thead>
                    <tr style="background-color: #f8f9fa;">
                        <th rowspan="3" style="border: 1px solid #000; padding: 8px; text-align: center; vertical-align: middle; width: 5%;">No.</th>
                        <th rowspan="3" style="border: 1px solid #000; padding: 8px; text-align: center; vertical-align: middle; width: 35%;">Descripción</th>
                        <th rowspan="3" style="border: 1px solid #000; padding: 8px; text-align: center; vertical-align: middle; width: 10%;">Unidad de medida</th>
                        <th rowspan="3" style="border: 1px solid #000; padding: 8px; text-align: center; vertical-align: middle; width: 8%;">Meta</th>
                        <th rowspan="3" style="border: 1px solid #000; padding: 8px; text-align: center; vertical-align: middle; width: 10%;">Avance bimestral <br><br>Productos terminados<br><br>(sólo en el caso de normas y manuales)</th>
                        <th colspan="2" style="border: 1px solid #000; padding: 8px; text-align: center;">PRIMER BIMESTRE</th>
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
        // Tabla normativa: solo los cuatro rubros de productos (sin coordinación)
        $grupos = [
            ['id' => '',  'descripcion' => 'Con base en el entorno tecnológico mundial, el IMT emitirá normas y manuales para ampliar y actualizar la Normativa Técnica de la SICT para la infraestructura del transporte, en lo referente a proyecto, construcción, conservación y características de materiales, así como métodos de muestreo y pruebas de materiales, con una meta de 18 anteproyectos preliminares, 17 anteproyectos finales, 14 proyectos preliminares y 14 normas y manuales por publicar.', 'unidad' => 'Producto Terminado'],
            ['id' => 'apt',  'descripcion' => 'Elaboración de anteproyectos preliminares de normas y manuales', 'unidad' => 'Anteproyecto preliminar'],
            ['id' => 'aft',  'descripcion' => 'Elaboración de anteproyectos finales de normas y manuales',    'unidad' => 'Anteproyecto final'],
            ['id' => 'ppt',  'descripcion' => 'Elaboración de proyectos preliminares de normas y manuales',   'unidad' => 'Proyecto preliminar'],
            ['id' => 'np',   'descripcion' => 'Publicación de normas y manuales', 'unidad' => 'Norma y/o manual'],
            ['id' => '',   'descripcion' => 'Se continuará coordinando el Subcomité Número 4 de Señalamiento y Dispositivos de Seguridad Vial, del Comité Consultivo Nacional de Normalización de Transporte Terrestre y su Grupo de Trabajo 1, mismo que concluirá la NOM-037-SCT2-2025, Barreras de protección en carreteras y vías urbanas y publicará la NOM-033-SCT2-2024, Diseño de plazas de cobro en carreteras. Criterios de seguridad vial.', 'unidad' => ''],
            ['id' => 'sub4', 'descripcion' => 'Coordinación de las reuniones de trabajo del Subcomité No. 4', 'unidad' => 'Reunión'],
            ['id' => 'gt1',  'descripcion' => 'Coordinación de las reuniones del grupo de trabajo',            'unidad' => 'Reunión'],
            ['id' => '',   'descripcion' => 'Se continuará participando en los comités consultivos nacionales de normalización de Transporte Terrestre, de Transporte Aéreo, de Seguridad al Usuario, de la Secretaría de Economía, de Ordenamiento Territorial y Desarrollo Urbano; en los grupos de trabajo del Organismo Nacional de Normalización y Certificación de la Construcción y Edificación, S. C. (ONNCCE), y en el Comité Técnico 4.6 de la Asociación Mundial de la Carretera.', 'unidad' => 'Reunión'],
                                        
        ];

        // Calcular rowspan del bloque de observaciones (desde 6.1.4 hasta g))
        $rowspanBloque = 0;
        $enBloque = false;
        foreach ($grupos as $gcalc) {
            $isProducto = (empty($gcalc['id']) && (($gcalc['unidad'] ?? '') === 'Producto Terminado'));
            if ($isProducto) { $enBloque = true; }
            if ($enBloque) { $rowspanBloque++; }
            if (($gcalc['id'] ?? '') === 'gt1') { break; }
        }
        // Observación del bloque: usar la del grupo gt1
        $gt1DatosBloque = collect($todosLosGrupos ?? [])->where('id', 'gt1')->first();
        $obsBloque = $gt1DatosBloque->observaciones ?? '';
    @endphp

    @foreach ($grupos as $index => $grupo)
        @php
            $datos = collect($todosLosGrupos)->where('id', $grupo['id'])->first();

            $metaBimestral = 0;
            $realizadoBimestre = 0;
            $totalAcumulado = 0;
            $porcBimestral = 0;
            $porcAnual = 0;

            if ($datos) {
                $metaBimestral = $datos->{'meta_bimestre_' . $bimestreActual} ?? 0;
                $realizadoBimestre = $datos->realizados[$bimestreActual] ?? 0;

                // Acumulado hasta el bimestre actual
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
            // Fila especial final (g.3): descripción con unidad 'Reunión' y agregados
            $esFilaG3 = $soloDescripcion && (($grupo['unidad'] ?? '') === 'Reunión');
            $metaOtrosAnual = 0;
            $metaOtrosBimestral = 0;
            $realizadoOtrosBimestre = 0;
            $totalOtrosAcumulado = 0;
            $porcBimestralOtros = 0;
            $porcAnualOtros = 0;

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
        @endphp
        <tr>
            <td style="border: 1px solid #000; text-align: center;">{{ $esDescripcionProductoTerminado ? '6.1.4' : ($esFilaG3 ? 'g.3' : ($soloDescripcion ? '' : $etiquetaLetra)) }}</td>
            <td style="border: 1px solid #000; text-align: justify;">{{ $esDescripcionProductoTerminado ? $descripcionDinamica : $grupo['descripcion'] }}</td>
            <td style="border: 1px solid #000; text-align: center;">{{ $esDescripcionProductoTerminado ? 'Producto Terminado' : ($esFilaG3 ? 'Reunión' : ($soloDescripcion ? '' : $grupo['unidad'])) }}</td>
            <td style="border: 1px solid #000; text-align: center;">{{ $esDescripcionProductoTerminado ? $metaTotalProductoTerminado : ($esFilaG3 ? $metaOtrosAnual : ($soloDescripcion ? '' : ($datos->meta_anual ?? ''))) }}</td>
            <td style="border: 1px solid #000; text-align: center;">{{ $esDescripcionProductoTerminado ? '' : ($esFilaG3 ? $realizadoOtrosBimestre : ($soloDescripcion ? '' : $realizadoBimestre)) }}</td>
            <td style="border: 1px solid #000; text-align: center;">{{ $esDescripcionProductoTerminado ? '' : ($esFilaG3 ? ($porcBimestralOtros . '%') : ($soloDescripcion ? '' : ($porcBimestral . '%'))) }}</td>
            <td style="border: 1px solid #000; text-align: center;">{{ $esDescripcionProductoTerminado ? '' : ($esFilaG3 ? ($porcAnualOtros . '%') : ($soloDescripcion ? '' : ($porcAnual . '%'))) }}</td>
            @php
                $mostrarTextarea = !$soloDescripcion; // sólo filas con id (a, b, c, d, f, g) y la fila g.3
            @endphp
            <td style="border: 1px solid #000;">
                @if($esFilaG3)
                    <textarea
                        id="obs-g3"
                        class="observaciones-autosave"
                        style="width: 100%; min-height: 60px;"></textarea>
                @elseif($esDescripcionProductoTerminado)
                    {{-- Fila 6.1.4 (Producto Terminado): sin observaciones consolidadas, celda vacía --}}
                @elseif($mostrarTextarea)
                    <textarea
                        id="obs-{{ $grupo['id'] }}"
                        class="observaciones-autosave"
                        style="width: 100%; min-height: 60px;">{{ $datos->observaciones ?? '' }}</textarea>
                @else
                    {{-- Filas descriptivas sin id: celda vacía --}}
                @endif
            </td>
        </tr>
    @endforeach
</tbody>



            </table>
        </div>
<!--
        <div id="tab-inventario" class="tab-content">
            <div class="stats-grid">
                <div class="stat-box">
                    <h3>{{ count($reportesPorAnio ?? []) }}</h3>
                    <p>Años con Reportes</p>
                </div>
                <div class="stat-box">
                    <h3>{{ array_sum(array_map('count', ($reportesPorAnio ?? collect())->toArray())) }}</h3>
                    <p>Total de Reportes</p>
                </div>
                <div class="stat-box">
                    <h3>{{ $ultimoReporte->anio ?? '--' }}</h3>
                    <p>Último Año Registrado</p>
                </div>
            </div>

            @forelse($reportesPorAnio ?? [] as $anio => $reportes)
            <div style="margin-bottom: 30px;">
                <h3 style="color: #667eea; margin-bottom: 15px; display: flex; align-items: center; gap: 10px;">
                    📅 Año {{ $anio }}
                    <span style="font-size: 0.7em; font-weight: normal; color: #666;">
                        ({{ count($reportes) }} de 6 bimestres completados)
                    </span>
                </h3>

                <div style="display: grid; gap: 10px;">
                    @foreach($reportes as $reporte)
                    <div class="reporte-card">
                        <div class="reporte-info">
                            <h4>
                                {{ $bimestres[$reporte->bimestre] ?? 'Bimestre ' . $reporte->bimestre }}
                                <span class="estado-badge estado-guardado">✓ Guardado</span>
                            </h4>
                            <p>
                                📅 Fecha de creación: {{ $reporte->created_at->format('d/m/Y H:i') }}
                                @if($reporte->updated_at != $reporte->created_at)
                                    | Última edición: {{ $reporte->updated_at->format('d/m/Y H:i') }}
                                @endif
                            </p>
                        </div>
                        <div class="reporte-actions">
                            <button class="btn btn-secondary" 
                                    onclick="window.location='{{ route('grupotrabajo.reporte') }}?anio={{ $reporte->anio }}&bimestre={{ $reporte->bimestre }}'">
                                👁️ Ver
                            </button>
                            <button class="btn btn-secondary" 
                                    onclick="window.location='{{ route('grupotrabajo.pdf', ['anio' => $reporte->anio, 'bimestre' => $reporte->bimestre]) }}'">
                                📥 PDF
                            </button>
                            <button class="btn btn-secondary2" 
                                    onclick="confirmarEliminarReporte({{ $reporte->anio }}, {{ $reporte->bimestre }})"
                                    style="background: #f44336;">
                                🗑️
                            </button>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @empty
            <div style="text-align: center; padding: 60px 20px;">
                <div style="font-size: 4em; margin-bottom: 15px; opacity: 0.3;">📋</div>
                <h3>No hay reportes guardados</h3>
                <p style="color: #666;">Comienza creando tu primer reporte en la pestaña "Crear/Editar Reporte"</p>
                <button class="btn btn-secondary" onclick="cambiarTab('crear')">
                    ➕ Crear Primer Reporte
                </button>
            </div> 
            @endforelse
        </div>

    </div>
</div>

<div id="modal-guardar" class="modal-overlay" onclick="cerrarModal(event)">
    <div class="modal-content" onclick="event.stopPropagation()">
        <div class="modal-header">
            <h3>💾 Guardar Reporte</h3>
        </div>
        <div class="modal-body">
            <form action="{{ route('grupotrabajo.guardarReporte') }}" method="POST">
                @csrf
                <input type="hidden" name="anio" id="form-anio" value="{{ $anioSeleccionado ?? date('Y') }}">
                <input type="hidden" name="bimestre" id="form-bimestre" value="{{ $bimestreSeleccionado ?? 1 }}">
                <input type="hidden" name="datos_grupos" id="form-datos">

                <div class="form-group">
                    <label>📅 Año</label>
                    <input type="text" value="{{ $anioSeleccionado ?? date('Y') }}" readonly style="background: #f5f5f5;">
                </div>

                <div class="form-group">
                    <label>📊 Bimestre</label>
                    <input type="text" value="{{ $bimestres[$bimestreSeleccionado ?? 1] ?? 'Bimestre 1' }}" readonly style="background: #f5f5f5;">
                </div>

                <div class="form-group">
                    <label>📝 Notas adicionales (opcional)</label>
                    <textarea name="notas" rows="3" placeholder="Agregar notas generales sobre este reporte..."></textarea>
                </div>

                <div style="background: #e8f5e9; padding: 12px; border-radius: 4px; margin: 15px 0; border-left: 3px solid #4caf50;">
                    <strong>ℹ️ Nota:</strong> Se guardarán los datos actuales de todos los grupos y sus observaciones.
                </div>

                <div class="modal-actions">
                    <button type="submit" class="btn btn-secondary" style="background: #4caf50;">
                        💾 Guardar Reporte
                    </button>
                    <button type="button" class="btn btn-secondary2" onclick="cerrarModalBtn()">
                        ❌ Cancelar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>-->

<script>
let anioActual = {{ $anioSeleccionado ?? date('Y') }};
let bimestreActual = {{ $bimestreSeleccionado ?? 1 }};

// Claves de localStorage para persistir selección
const LS_KEY_BIMESTRE = 'reporte:last_bimestre';
const LS_KEY_ANIO = 'reporte:last_anio';

// Al cargar, si faltan parámetros en la URL, usar la última selección guardada
document.addEventListener('DOMContentLoaded', function() {
    try {
        const url = new URL(window.location.href);
        const hasB = url.searchParams.has('bimestre');
        const hasY = url.searchParams.has('anio');
        const storedB = localStorage.getItem(LS_KEY_BIMESTRE);
        const storedY = localStorage.getItem(LS_KEY_ANIO);

        // Si la URL ya trae selección, guardarla para persistir al volver sin parámetros
        if (hasB && hasY) {
            try {
                localStorage.setItem(LS_KEY_BIMESTRE, String(url.searchParams.get('bimestre')));
                localStorage.setItem(LS_KEY_ANIO, String(url.searchParams.get('anio')));
            } catch (e) { /* ignorar */ }
            return; // no redirigir, respetar la selección explícita
        }

        if ((!hasB || !hasY) && storedB) {
            const anioToUse = storedY || String(new Date().getFullYear());
            url.searchParams.set('bimestre', storedB);
            url.searchParams.set('anio', anioToUse);
            window.location.replace(url.toString());
            return;
        }
    } catch (e) { /* ignorar */ }
});

function cambiarTab(tab) {
    document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
    document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));
    
    event.target.classList.add('active');
    document.getElementById('tab-' + tab).classList.add('active');
}

function cambiarAnio(direccion) {
    anioActual += direccion;
    // Guardar selección en localStorage
    try {
        localStorage.setItem(LS_KEY_ANIO, String(anioActual));
        localStorage.setItem(LS_KEY_BIMESTRE, String(bimestreActual));
    } catch (e) { /* ignorar */ }
    window.location = '{{ route("grupotrabajo.reporte") }}?anio=' + anioActual + '&bimestre=' + bimestreActual;
}

function cambiarBimestre(bimestre) {
    bimestreActual = bimestre;
    // Guardar selección en localStorage
    try {
        localStorage.setItem(LS_KEY_BIMESTRE, String(bimestre));
        localStorage.setItem(LS_KEY_ANIO, String(anioActual));
    } catch (e) { /* ignorar */ }
    window.location = '{{ route("grupotrabajo.reporte") }}?anio=' + anioActual + '&bimestre=' + bimestre;
}

function abrirModalGuardar() {
    // Recopilar observaciones de todos los grupos
    const datosGrupos = [];
    document.querySelectorAll('[id^="obs-"]').forEach(textarea => {
        const grupoId = textarea.id.replace('obs-', '');
        datosGrupos.push({
            grupo_id: grupoId,
            observaciones: textarea.value
        });
    });
    
    document.getElementById('form-datos').value = JSON.stringify(datosGrupos);
    // Sincronizar comentario de g.3 con el campo 'notas' del reporte
    const obsG3 = document.getElementById('obs-g3');
    const notasField = document.querySelector('textarea[name="notas"]');
    if (obsG3 && notasField) {
        notasField.value = obsG3.value;
    }
    document.getElementById('modal-guardar').classList.add('active');
}

function cerrarModal(event) {
    if (event.target.classList.contains('modal-overlay')) {
        document.getElementById('modal-guardar').classList.remove('active');
    }
}

function cerrarModalBtn() {
    document.getElementById('modal-guardar').classList.remove('active');
}

function confirmarEliminarReporte(anio, bimestre) {
    if (confirm('¿Estás seguro de eliminar este reporte? Esta acción no se puede deshacer.')) {
        // Crear formulario y enviar
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ url("grupos-trabajo/reportes") }}/' + anio + '/' + bimestre;
        
        const csrf = document.createElement('input');
        csrf.type = 'hidden';
        csrf.name = '_token';
        csrf.value = '{{ csrf_token() }}';
        
        const method = document.createElement('input');
        method.type = 'hidden';
        method.name = '_method';
        method.value = 'DELETE';
        
        form.appendChild(csrf);
        form.appendChild(method);
        document.body.appendChild(form);
        form.submit();
    }
}

// Funcionalidad de autosave para observaciones
document.addEventListener('DOMContentLoaded', function() {
    const observacionesTextareas = document.querySelectorAll('.observaciones-autosave');
    let saveTimeout;
    
    observacionesTextareas.forEach(function(textarea) {
        // Agregar indicador visual de guardado
        const indicator = document.createElement('div');
        indicator.className = 'save-indicator';
        indicator.style.cssText = 'position: absolute; right: 5px; top: 5px; font-size: 10px; color: #28a745; display: none;';
        indicator.textContent = '✓ Guardado';
        
        // Hacer el contenedor padre relativo para posicionar el indicador
        textarea.parentElement.style.position = 'relative';
        textarea.parentElement.appendChild(indicator);
        
        textarea.addEventListener('input', function() {
            // Mostrar indicador de "guardando..."
            indicator.textContent = '⏳ Guardando...';
            indicator.style.color = '#ffc107';
            indicator.style.display = 'block';
            
            // Limpiar timeout anterior
            clearTimeout(saveTimeout);
            
            // Guardar después de 1 segundo de inactividad
            saveTimeout = setTimeout(function() {
                // Simular guardado (aquí se haría la petición AJAX real)
                console.log('Guardando observación:', textarea.dataset.field, '=', textarea.value);
                
                // Mostrar confirmación de guardado
                indicator.textContent = '✓ Guardado';
                indicator.style.color = '#28a745';
                
                // Ocultar indicador después de 2 segundos
                setTimeout(function() {
                    indicator.style.display = 'none';
                }, 2000);
            }, 1000);
        });
        
        // Guardar también al perder el foco
        textarea.addEventListener('blur', function() {
            clearTimeout(saveTimeout);
            if (textarea.value !== textarea.defaultValue) {
                console.log('Guardando observación al perder foco:', textarea.dataset.field, '=', textarea.value);
                indicator.textContent = '✓ Guardado';
                indicator.style.color = '#28a745';
                indicator.style.display = 'block';
                setTimeout(function() {
                    indicator.style.display = 'none';
                }, 2000);
            }
        });
    });
});
</script>

@endsection

<script>
    function descargarPDFTalCual() {
        try {
            const obsBloqueEl = document.getElementById('obs-gt1');
            const obsG3El = document.getElementById('obs-g3');
            const obsBloque = obsBloqueEl ? obsBloqueEl.value : '';
            const obsG3 = obsG3El ? obsG3El.value : '';
            const anio = @json($anioSeleccionado ?? '');
            const bimestre = @json($bimestreActual ?? '');
            const base = '{{ route('grupotrabajo.pdf') }}';
            const url = `${base}?anio=${encodeURIComponent(anio)}&bimestre=${encodeURIComponent(bimestre)}&observaciones_bloque=${encodeURIComponent(obsBloque)}&notas=${encodeURIComponent(obsG3)}`;
            window.location.href = url;
        } catch (e) {
            alert('No se pudo iniciar la descarga del PDF: ' + (e && e.message ? e.message : e));
        }
    }
</script>
