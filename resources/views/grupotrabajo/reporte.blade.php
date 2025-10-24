@extends('home')

@section('contenido')

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<div class="all-form">
    <div class="form-container">
        <div class="section-title">Reporte de Grupos de Trabajo - {{ date('Y') }}</div>
        
        <div class="actions">
            <button type="button" class="btn btn-secondary">
                GUardar
            </button>
            <button type="button" class="btn btn-secondary" onclick="window.location='{{ route('grupotrabajo.pdf') }}'">
                📥 Descargar PDF
            </button>
        </div>

        <div class="docs-table-wrapper">
            <table class="docs-table">
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
                    @forelse($grupos as $grupo)
                    <tr>
                        <td></td>
                        <td>{{ $grupo->nombre }}</td>
                        <td></td>
                        <td>{{ $grupo->meta_anual }}</td>
                        <td></td>
                        <td>{{ $grupo->realizados[1] ?? 0 }}</td>
                        <td>{{ $grupo->realizados[2] ?? 0 }}</td>
                        <td><strong>{{ $grupo->total_realizado }}</strong></td>
                        <td>
                            <form action="{{ route('grupotrabajo.actualizarObservaciones', $grupo->id) }}" method="POST" style="display: inline;">
                                @csrf
                                <textarea name="observaciones" rows="2" style="width: 100%;">{{ $grupo->observaciones }}</textarea>
                                <button type="submit" class="btn btn-secondary" style="margin-top: 5px; font-size: 12px;">Guardar</button>
                            </form>
                        </td>
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
</div>

@endsection

{{--
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
                        @forelse($grupos as $index => $grupo)
                        @php
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
                            
                            $porcBimestral = $metaBimestral > 0 ? round(($realizadoBimestre / $metaBimestral) * 100) : 0;
                            $porcAnual = $grupo->meta_anual > 0 ? round(($totalAcumulado / $grupo->meta_anual) * 100) : 0;
                        @endphp
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td><strong>{{ $grupo->nombre }}</strong></td>
                            <td>Reuniones</td>
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

        <div id="tab-inventario" class="tab-content">
            <div class="stats-grid">
                <div class="stat-box">
                    <h3>{{ count($reportesPorAnio ?? []) }}</h3>
                    <p>Años con Reportes</p>
                </div>
                <div class="stat-box">
                    <h3>{{ array_sum(array_map('count', $reportesPorAnio ?? [])) }}</h3>
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
        form.action = '{{ route("grupotrabajo.eliminarReporte", "") }}/' + reporteId;
        
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
</script>

@endsection
--}}