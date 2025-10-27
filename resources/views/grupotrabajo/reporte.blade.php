@extends('home')
@section('contenido')
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
    border-color: #667eea;
    background: #f5f5ff;
}

.bimestre-btn.active {
    border-color: #667eea;
    background: #667eea;
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
    background: #667eea;
    color: white;
    border: none;
    padding: 8px 16px;
    border-radius: 6px;
    cursor: pointer;
    font-size: 1.2em;
}

.year-btn:hover {
    background: #5568d3;
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

.modal-overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0,0,0,0.6);
    z-index: 1000;
    align-items: center;
    justify-content: center;
}

.modal-overlay.active {
    display: flex;
}

.modal-content {
    background: white;
    border-radius: 8px;
    max-width: 500px;
    width: 90%;
    max-height: 85vh;
    overflow-y: auto;
    box-shadow: 0 5px 20px rgba(0,0,0,0.3);
}

.modal-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 20px;
    border-radius: 8px 8px 0 0;
}

.modal-body {
    padding: 20px;
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
        <div class="section-title">📊 Reportes de Grupos de Trabajo</div>

        <div class="tabs-container">
            <button class="tab-btn active" onclick="cambiarTab('crear')">
                ➕ Crear/Editar Reporte
            </button>
            <button class="tab-btn" onclick="cambiarTab('inventario')">
                📚 Inventario de Reportes
            </button>
        </div>

        <div id="tab-crear" class="tab-content active">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 15px;">
                <div class="year-navigation">
                    <button class="year-btn" onclick="cambiarAnio(-1)">◀</button>
                    <span class="current-year" id="anio-actual">{{ $anioSeleccionado ?? date('Y') }}</span>
                    <button class="year-btn" onclick="cambiarAnio(1)">▶</button>
                </div>

                <div style="display: flex; gap: 10px;">
                    <button type="button" class="btn btn-secondary" onclick="abrirModalGuardar()">
                        💾 Guardar Reporte
                    </button>
                    <button type="button" class="btn btn-secondary" onclick="window.location='{{ route('grupotrabajo.pdf', ['anio' => $anioSeleccionado ?? date('Y'), 'bimestre' => $bimestreSeleccionado ?? 1]) }}'">
                        📥 Descargar PDF
                    </button>
                </div>
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

            <div class="docs-table-wrapper">
                <table class="docs-table">
                    <thead>
                        <tr>
                            <th rowspan="2">No.</th>
                            <th rowspan="2">Grupo de Trabajo</th>
                            <th rowspan="2">Unidad de medida</th>
                            <th rowspan="2">Meta Anual</th>
                            <th rowspan="2">Meta Bimestral</th>
                            <th rowspan="2">Realizado en Bimestre</th>
                            <th colspan="2">PORCENTAJE</th>
                            <th rowspan="2">Total Acumulado</th>
                            <th rowspan="2">Observaciones</th>
                        </tr>
                        <tr>
                            <th>Realizado vs. Meta Bimestral</th>
                            <th>Acumulado vs. Meta Anual</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($todosLosGrupos as $index => $grupo)
                        @php
                            if (isset($grupo->es_fijo) && $grupo->es_fijo) {
                                // Para grupos fijos, usar los datos calculados desde etapas
                                $metaBimestral = $grupo->{'meta_bimestre_' . $bimestreActual};
                                $realizadoBimestre = $grupo->realizados[$bimestreActual] ?? 0;
                                $totalAcumulado = 0;
                                
                                // Calcular acumulado hasta el bimestre actual
                                for ($i = 1; $i <= $bimestreActual; $i++) {
                                    $totalAcumulado += $grupo->realizados[$i] ?? 0;
                                }
                                
                                $unidadMedida = 'Documentos terminados';
                            } else {
                                // Para grupos regulares, usar la lógica original
                                $metaBimestral = $grupo->{'meta_bimestre_' . $bimestreActual};
                                $realizadoBimestre = $grupo->reuniones->filter(function($r) use ($bimestreActual) {
                                    $mes = $r->fecha->month;
                                    $inicio = ($bimestreActual - 1) * 2 + 1;
                                    $fin = $bimestreActual * 2;
                                    return $mes >= $inicio && $mes <= $fin;
                                })->count();
                                
                                $totalAcumulado = $grupo->reuniones->filter(function($r) use ($bimestreActual) {
                                    $mes = $r->fecha->month;
                                    $fin = $bimestreActual * 2;
                                    return $mes <= $fin;
                                })->count();
                                
                                $unidadMedida = 'Reuniones';
                            }
                            
                            $porcBimestral = $metaBimestral > 0 ? round(($realizadoBimestre / $metaBimestral) * 100) : 0;
                            $porcAnual = $grupo->meta_anual > 0 ? round(($totalAcumulado / $grupo->meta_anual) * 100) : 0;
                        @endphp
                        <tr style="{{ isset($grupo->es_fijo) && $grupo->es_fijo ? 'background-color: #f8f9fa; border-left: 4px solid #007bff;' : '' }}">
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <strong>{{ $grupo->nombre }}</strong>
                                @if(isset($grupo->es_fijo) && $grupo->es_fijo)
                                    <span style="font-size: 0.8em; color: #007bff; font-weight: normal;">(Grupo Fijo)</span>
                                @endif
                            </td>
                            <td>{{ $unidadMedida }}</td>
                            <td>{{ $grupo->meta_anual }}</td>
                            <td>{{ $metaBimestral }}</td>
                            <td><strong>{{ $realizadoBimestre }}</strong></td>
                            <td>
                                <span style="color: {{ $porcBimestral >= 100 ? '#4caf50' : ($porcBimestral >= 70 ? '#ff9800' : '#f44336') }}; font-weight: 600;">
                                    {{ $porcBimestral }}%
                                </span>
                            </td>
                            <td>
                                <span style="color: {{ $porcAnual >= 100 ? '#4caf50' : ($porcAnual >= 70 ? '#ff9800' : '#f44336') }}; font-weight: 600;">
                                    {{ $porcAnual }}%
                                </span>
                            </td>
                            <td><strong>{{ $totalAcumulado }}</strong></td>
                            <td>
                                <textarea 
                                    id="obs-{{ $grupo->id }}" 
                                    rows="2" 
                                    style="width: 100%;" 
                                    placeholder="Agregar observaciones...">{{ $grupo->observaciones ?? '' }}</textarea>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="empty-state">
                                <p>No hay grupos de trabajo registrados</p>
                                <button class="btn btn-secondary" onclick="window.location='{{ route('grupotrabajo.create') }}'">
                                    ➕ Crear Primer Grupo
                                </button>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Sección 6.1.4 - Normativa Técnica de la SICT -->
        <div style="margin-top: 40px;">
            <div style="display: flex; align-items: center; justify-content: center; margin-bottom: 10px;">
                <h3 style="text-align: center; font-weight: bold; margin: 0;">REPORTE DE AVANCE DEL PROGRAMA DE LA ELABORACIÓN DE NORMAS</h3>
            </div>
            
            <table class="table table-bordered" style="width: 100%; border-collapse: collapse; font-size: 12px;">
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
                        // Obtener datos de grupos fijos para la sección 6.1.4
                        $gruposFijosData = collect($gruposFijos ?? []);
                        $aptGrupo = $gruposFijosData->where('id', 'apt')->first();
                        $aftGrupo = $gruposFijosData->where('id', 'aft')->first();
                        $pptGrupo = $gruposFijosData->where('id', 'ppt')->first();
                        $npGrupo = $gruposFijosData->where('id', 'np')->first();
                        
                        // Calcular totales de metas
                        $totalMetas = ($aptGrupo->metaAnual ?? 0) + ($aftGrupo->metaAnual ?? 0) + ($pptGrupo->metaAnual ?? 0) + ($npGrupo->metaAnual ?? 0);
                    @endphp
                    
                    <!-- Fila principal con descripción general -->
                    <tr>
                        <td style="border: 1px solid #000; padding: 8px; text-align: center; vertical-align: middle; font-weight: bold;">6.1.4</td>
                        <td style="border: 1px solid #000; padding: 8px; text-align: justify; font-size: 11px;">
                            Con base en el entorno tecnológico mundial, el IMT emitirá normas y manuales para ampliar y actualizar la Normativa Técnica de la SICT para la infraestructura del transporte, en lo referente a proyecto, construcción, conservación y características de materiales, así como métodos de muestreo y pruebas de materiales, con una meta de <strong>{{ $aptGrupo->metaAnual ?? 0 }}</strong> anteproyectos preliminares, <strong>{{ $aftGrupo->metaAnual ?? 0 }}</strong> anteproyectos finales, <strong>{{ $pptGrupo->metaAnual ?? 0 }}</strong> proyectos preliminares y <strong>{{ $npGrupo->metaAnual ?? 0 }}</strong> normas y manuales por publicar.
                        </td>
                        <td style="border: 1px solid #000; padding: 8px; text-align: center; vertical-align: middle;">Producto Terminado</td>
                        <td style="border: 1px solid #000; padding: 8px; text-align: center; vertical-align: middle; font-weight: bold;">{{ $totalMetas }}</td>
                        <td style="border: 1px solid #000; padding: 8px; text-align: center; vertical-align: middle;">
                        </td>
                        <td style="border: 1px solid #000; padding: 8px; text-align: center; vertical-align: middle;">
                        </td>
                        <td style="border: 1px solid #000; padding: 8px; text-align: center; vertical-align: middle;">
                        </td>
                        <td rowspan="8" style="border: 1px solid #000; padding: 8px; vertical-align: top;">
                            <textarea class="observaciones-autosave" data-field="observaciones_generales" style="width: 100%; min-height: 120px; border: 1px solid #ddd; padding: 5px; font-size: 11px; resize: vertical;" placeholder="Sin observaciones generales">{{ $observaciones_generales ?? '' }}</textarea>
                        </td>
                    </tr>
                    
                    <!-- a) Elaboración de anteproyectos preliminares -->
                    <tr>
                        <td style="border: 1px solid #000; padding: 8px; text-align: center; vertical-align: middle; font-weight: bold;">a)</td>
                        <td style="border: 1px solid #000; padding: 8px;"> Elaboración de anteproyectos preliminares de normas y manuales</td>
                        <td style="border: 1px solid #000; padding: 8px; text-align: center;">Anteproyecto preliminar</td>
                        <td style="border: 1px solid #000; padding: 8px; text-align: center;">{{ $aptGrupo->metaAnual ?? 0 }}</td>
                        <td style="border: 1px solid #000; padding: 8px; text-align: center;">{{ $aptGrupo->realizadoBimestre[0] ?? 0 }}</td>
                        <td style="border: 1px solid #000; padding: 8px; text-align: center;">
                            @php
                                $metaBim = $aptGrupo->metaBimestral[0] ?? 0;
                                $realBim = $aptGrupo->realizadoBimestre[0] ?? 0;
                                $porcPeriodo = $metaBim > 0 ? round(($realBim / $metaBim) * 100, 1) : 0;
                            @endphp
                            {{ $porcPeriodo }}%
                        </td>
                        <td style="border: 1px solid #000; padding: 8px; text-align: center;">
                            @php
                                $metaAnual = $aptGrupo->metaAnual ?? 0;
                                $totalAcum = $aptGrupo->totalAcumulado ?? 0;
                                $porcAnual = $metaAnual > 0 ? round(($totalAcum / $metaAnual) * 100, 1) : 0;
                            @endphp
                            {{ $porcAnual }}%
                        </td>
                    </tr>
                    
                    <!-- b) Elaboración de anteproyectos finales -->
                    <tr>
                        <td style="border: 1px solid #000; padding: 8px; text-align: center; vertical-align: middle; font-weight: bold;">b)</td>
                        <td style="border: 1px solid #000; padding: 8px;"> Elaboración de anteproyectos finales de normas y manuales</td>
                        <td style="border: 1px solid #000; padding: 8px; text-align: center;">Anteproyecto final</td>
                        <td style="border: 1px solid #000; padding: 8px; text-align: center;">{{ $aftGrupo->metaAnual ?? 0 }}</td>
                        <td style="border: 1px solid #000; padding: 8px; text-align: center;">{{ $aftGrupo->realizadoBimestre[0] ?? 0 }}</td>
                        <td style="border: 1px solid #000; padding: 8px; text-align: center;">
                            @php
                                $metaBim = $aftGrupo->metaBimestral[0] ?? 0;
                                $realBim = $aftGrupo->realizadoBimestre[0] ?? 0;
                                $porcPeriodo = $metaBim > 0 ? round(($realBim / $metaBim) * 100, 1) : 0;
                            @endphp
                            {{ $porcPeriodo }}%
                        </td>
                        <td style="border: 1px solid #000; padding: 8px; text-align: center;">
                            @php
                                $metaAnual = $aftGrupo->metaAnual ?? 0;
                                $totalAcum = $aftGrupo->totalAcumulado ?? 0;
                                $porcAnual = $metaAnual > 0 ? round(($totalAcum / $metaAnual) * 100, 1) : 0;
                            @endphp
                            {{ $porcAnual }}%
                        </td>
                    </tr>
                    
                    <!-- c) Elaboración de proyectos preliminares -->
                    <tr>
                        <td style="border: 1px solid #000; padding: 8px; text-align: center; vertical-align: middle; font-weight: bold;">c)</td>
                        <td style="border: 1px solid #000; padding: 8px;">Elaboración de proyectos preliminares de normas y manuales</td>
                        <td style="border: 1px solid #000; padding: 8px; text-align: center;">Proyecto preliminar</td>
                        <td style="border: 1px solid #000; padding: 8px; text-align: center;">{{ $pptGrupo->metaAnual ?? 0 }}</td>
                        <td style="border: 1px solid #000; padding: 8px; text-align: center;">{{ $pptGrupo->realizadoBimestre[0] ?? 0 }}</td>
                        <td style="border: 1px solid #000; padding: 8px; text-align: center;">
                            @php
                                $metaBim = $pptGrupo->metaBimestral[0] ?? 0;
                                $realBim = $pptGrupo->realizadoBimestre[0] ?? 0;
                                $porcPeriodo = $metaBim > 0 ? round(($realBim / $metaBim) * 100, 1) : 0;
                            @endphp
                            {{ $porcPeriodo }}%
                        </td>
                        <td style="border: 1px solid #000; padding: 8px; text-align: center;">
                            @php
                                $metaAnual = $pptGrupo->metaAnual ?? 0;
                                $totalAcum = $pptGrupo->totalAcumulado ?? 0;
                                $porcAnual = $metaAnual > 0 ? round(($totalAcum / $metaAnual) * 100, 1) : 0;
                            @endphp
                            {{ $porcAnual }}%
                        </td>
                    </tr>
                    
                    <!-- d) Publicación de normas y manuales -->
                    <tr>
                        <td style="border: 1px solid #000; padding: 8px; text-align: center; vertical-align: middle; font-weight: bold;">d)</td>
                        <td style="border: 1px solid #000; padding: 8px;">Publicación de normas y manuales</td>
                        <td style="border: 1px solid #000; padding: 8px; text-align: center;">Norma y/o manual</td>
                        <td style="border: 1px solid #000; padding: 8px; text-align: center;">{{ $npGrupo->metaAnual ?? 0 }}</td>
                        <td style="border: 1px solid #000; padding: 8px; text-align: center;">{{ $npGrupo->realizadoBimestre[0] ?? 0 }}</td>
                        <td style="border: 1px solid #000; padding: 8px; text-align: center;">
                            @php
                                $metaBim = $npGrupo->metaBimestral[0] ?? 0;
                                $realBim = $npGrupo->realizadoBimestre[0] ?? 0;
                                $porcPeriodo = $metaBim > 0 ? round(($realBim / $metaBim) * 100, 1) : 0;
                            @endphp
                            {{ $porcPeriodo }}%
                        </td>
                        <td style="border: 1px solid #000; padding: 8px; text-align: center;">
                            @php
                                $metaAnual = $npGrupo->metaAnual ?? 0;
                                $totalAcum = $npGrupo->totalAcumulado ?? 0;
                                $porcAnual = $metaAnual > 0 ? round(($totalAcum / $metaAnual) * 100, 1) : 0;
                            @endphp
                            {{ $porcAnual }}%
                        </td>
                    </tr>
                    <!--puro contexto -->
                    <tr>
                        <td style="border: 1px solid #000; padding: 8px; text-align: center; vertical-align: middle; font-weight: bold;"></td>
                        <td style="border: 1px solid #000; padding: 8px; text-align: justify; font-size: 11px;">
                            Se continuará coordinando el Subcomité Número 4 de Señalamiento y Dispositivos de Seguridad Vial, del Comité Consultivo Nacional de Normalización de Transporte Terrestre y su Grupo de Trabajo 1, mismo que concluirá la NOM-037-SCT2-2025, Barreras de protección en carreteras y vías urbanas y publicará la NOM-033-SCT2-2024, Diseño de plazas de cobro en carreteras. Criterios de seguridad vial.
                        </td>
                        <td style="border: 1px solid #000; padding: 8px; text-align: center; vertical-align: middle;"></td>
                        <td style="border: 1px solid #000; padding: 8px; text-align: center; vertical-align: middle; font-weight: bold;"></td>
                        <td style="border: 1px solid #000; padding: 8px; text-align: center; vertical-align: middle;">
                        </td>
                        <td style="border: 1px solid #000; padding: 8px; text-align: center; vertical-align: middle;">
                        </td>
                        <td style="border: 1px solid #000; padding: 8px; text-align: center; vertical-align: middle;">
                        </td>
                    </tr>
                    <!-- f) Coordinación de reuniones del subcomité No.4 -->
                    <tr>
                        <td style="border: 1px solid #000; padding: 8px; width: 5%; text-align: center; vertical-align: middle;"><strong>f)</strong></td>
                        <td style="border: 1px solid #000; padding: 8px; width: 35%;">
                            Coordinación de las reuniones de trabajo del subcomité No.4
                        </td>
                        <td style="border: 1px solid #000; padding: 8px; width: 10%; text-align: center;">Reunión</td>
                        <td style="border: 1px solid #000; padding: 8px; width: 8%; text-align: center;">
                            @php
                                $grupoSubcomite = collect($todosLosGrupos ?? [])->firstWhere('nombre', 'like', '%subcomité%4%') 
                                    ?? collect($todosLosGrupos ?? [])->firstWhere('nombre', 'like', '%Subcomité%4%')
                                    ?? collect($todosLosGrupos ?? [])->firstWhere('nombre', 'Subcomité No.4');
                                $metaSubcomite = $grupoSubcomite->meta_anual ?? 4;
                            @endphp
                            {{ $metaSubcomite }}
                        </td>
                        <td style="border: 1px solid #000; padding: 8px; width: 10%; text-align: center;">
                            {{ isset($reunionesSubcomite) ? $reunionesSubcomite->where('bimestre', 1)->count() : 0 }}
                        </td>
                        <td style="border: 1px solid #000; padding: 8px; width: 10%; text-align: center;">
                            @php
                                $reunionesSubBim = isset($reunionesSubcomite) ? $reunionesSubcomite->where('bimestre', 1)->count() : 0;
                                $metaSubBim = 1; // Meta bimestral para subcomité
                                $porcSubPeriodo = $metaSubBim > 0 ? round(($reunionesSubBim / $metaSubBim) * 100, 1) : 0;
                            @endphp
                            {{ $porcSubPeriodo }}%
                        </td>
                        <td style="border: 1px solid #000; padding: 8px; width: 10%; text-align: center;">
                            @php
                                $reunionesSubTotal = isset($reunionesSubcomite) ? $reunionesSubcomite->count() : 0;
                                $metaSubAnual = 4;
                                $porcSubAnual = $metaSubAnual > 0 ? round(($reunionesSubTotal / $metaSubAnual) * 100, 1) : 0;
                            @endphp
                            {{ $porcSubAnual }}%
                        </td>
                    </tr>
                    
                    <!-- g) Coordinación de reuniones del grupo de trabajo -->
                    <tr>
                        <td style="border: 1px solid #000; padding: 8px; text-align: center; vertical-align: middle;"><strong>g)</strong></td>
                        <td style="border: 1px solid #000; padding: 8px;">
                            Coordinación de las reuniones del grupo de trabajo
                        </td>
                        <td style="border: 1px solid #000; padding: 8px; text-align: center;">Reunión</td>
                        <td style="border: 1px solid #000; padding: 8px; text-align: center;">
                            @php
                                $grupoTrabajo = collect($todosLosGrupos ?? [])->firstWhere('nombre', 'like', '%grupo%trabajo%') 
                                    ?? collect($todosLosGrupos ?? [])->firstWhere('nombre', 'like', '%Grupo%Trabajo%');
                                $metaGrupoTrabajo = $grupoTrabajo->meta_anual ?? 13;
                            @endphp
                            {{ $metaGrupoTrabajo }}
                        </td>
                        <td style="border: 1px solid #000; padding: 8px; text-align: center;">
                            {{ isset($reunionesGrupoTrabajo) ? $reunionesGrupoTrabajo->where('bimestre', 1)->count() : 0 }}
                        </td>
                        <td style="border: 1px solid #000; padding: 8px; text-align: center;">
                            @php
                                $reunionesGrupoBim = isset($reunionesGrupoTrabajo) ? $reunionesGrupoTrabajo->where('bimestre', 1)->count() : 0;
                                $metaGrupoBim = 2; // Meta bimestral para grupos de trabajo
                                $porcGrupoPeriodo = $metaGrupoBim > 0 ? round(($reunionesGrupoBim / $metaGrupoBim) * 100, 1) : 0;
                            @endphp
                            {{ $porcGrupoPeriodo }}%
                        </td>
                        <td style="border: 1px solid #000; padding: 8px; text-align: center;">
                            @php
                                $reunionesGrupoTotal = isset($reunionesGrupoTrabajo) ? $reunionesGrupoTrabajo->count() : 0;
                                $metaGrupoAnual = $metaGrupoTrabajo; // Usar la meta dinámica del grupo de trabajo
                                $porcGrupoAnual = $metaGrupoAnual > 0 ? round(($reunionesGrupoTotal / $metaGrupoAnual) * 100, 1) : 0;
                            @endphp
                            {{ $porcGrupoAnual }}%
                        </td>
                    </tr>
                    <!-- g.3) Participación en comités consultivos -->
                    <tr>
                        <td style="border: 1px solid #000; padding: 8px; width: 5%; text-align: center; vertical-align: middle;"><strong>g.3</strong></td>
                        <td style="border: 1px solid #000; padding: 8px; width: 35%;">
                        Se continuará participando en los comités consultivos nacionales de normalización de Transporte Terrestre, de Transporte Aéreo, de seguridad al Usuario, de la Secretaría de Economía, de Ordenamiento Territorial y Desarrollo Urbano; en los grupos de trabajo del Organismo Nacional de Normalización y Certificación de la Construcción y Edificación, S.C. (ONNCCE), y en el Comité Técnico 4.6 de la Asociación Mundial de la Carretera.
                        </td>
                        <td style="border: 1px solid #000; padding: 8px; width: 10%; text-align: center;">Reunión</td>
                        <td style="border: 1px solid #000; padding: 8px; width: 8%; text-align: center;">57</td>
                        <td style="border: 1px solid #000; padding: 8px; width: 10%; text-align: center;">9</td>
                        <td style="border: 1px solid #000; padding: 8px; width: 10%; text-align: center;">112.5%</td>
                        <td style="border: 1px solid #000; padding: 8px; width: 10%; text-align: center;">15.8%</td>
                        <td style="border: 1px solid #000; padding: 8px; width: 15%;">
                            <textarea class="observaciones-autosave" data-field="observaciones_g3" style="width: 100%; min-height: 60px; border: 1px solid #ddd; padding: 5px; font-size: 11px; resize: vertical;" placeholder="Sin observaciones">{{ $observaciones_g3 ?? '' }}</textarea>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

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
                                    onclick="window.location='{{ route('grupotrabajo.reportes', ['anio' => $reporte->anio, 'bimestre' => $reporte->bimestre]) }}'">
                                👁️ Ver
                            </button>
                            <button class="btn btn-secondary" 
                                    onclick="window.location='{{ route('grupotrabajo.pdf', ['anio' => $reporte->anio, 'bimestre' => $reporte->bimestre]) }}'">
                                📥 PDF
                            </button>
                            <button class="btn btn-secondary2" 
                                    onclick="confirmarEliminarReporte({{ $reporte->id }})"
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
</div>

<script>
let anioActual = {{ $anioSeleccionado ?? date('Y') }};
let bimestreActual = {{ $bimestreSeleccionado ?? 1 }};

function cambiarTab(tab) {
    document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
    document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));
    
    event.target.classList.add('active');
    document.getElementById('tab-' + tab).classList.add('active');
}

function cambiarAnio(direccion) {
    anioActual += direccion;
    window.location = '{{ route("grupotrabajo.reportes") }}?anio=' + anioActual + '&bimestre=' + bimestreActual;
}

function cambiarBimestre(bimestre) {
    bimestreActual = bimestre;
    window.location = '{{ route("grupotrabajo.reportes") }}?anio=' + anioActual + '&bimestre=' + bimestre;
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

function confirmarEliminarReporte(reporteId) {
    if (confirm('¿Estás seguro de eliminar este reporte? Esta acción no se puede deshacer.')) {
        // Crear formulario y enviar
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ url("grupos-trabajo/reportes") }}/' + reporteId;
        
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
