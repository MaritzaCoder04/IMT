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

<style>
.calendario-container {
    max-width: 100%;
    overflow-x: auto;
    padding: 15px;
}

.calendario-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
    flex-wrap: wrap;
    gap: 15px;
}

.section-title {
    margin: 0;
}

.grupo-card {
    background: white;
    border: 1px solid #e0e0e0;
    border-radius: 6px;
    margin-bottom: 15px;
    overflow: hidden;
}

.grupo-header {
    background: #04638b;
    padding: 12px 15px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    color: white;
}

.grupo-info h3 {
    margin: 0 0 4px 0;
    font-size: 1.05em;
    font-weight: 600;
}

.grupo-stats {
    font-size: 0.85em;
    opacity: 0.95;
}

.meses-grid {
    display: grid;
    grid-template-columns: repeat(6, 1fr);
    gap: 0;
    background: #f5f5f5;
}

.bimestre-columna {
    background: white;
    border-right: 1px solid #e0e0e0;
    min-height: 100px;
}

.bimestre-columna:last-child {
    border-right: none;
}

.bimestre-header {
    background: #4a90e2;
    color: white;
    padding: 8px 10px;
    text-align: center;
    font-weight: 600;
    font-size: 0.88em;
    border-bottom: 1px solid #e0e0e0;
}

.bimestre-contenido {
    padding: 8px 6px;
    min-height: 85px;
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.reunion-badge {
    background: #e8f5e9;
    border-left: 3px solid #4caf50;
    border-radius: 3px;
    padding: 5px 8px;
    font-size: 0.8em;
    cursor: pointer;
    transition: all 0.2s;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.reunion-badge:hover {
    transform: translateX(2px);
    box-shadow: 0 2px 5px rgba(0,0,0,0.15);
}

.reunion-badge.fuera-programacion {
    background: #fff3e0;
    border-left-color: #ff9800;
}

.reunion-fecha {
    font-weight: 600;
}

.reunion-icon {
    font-size: 0.9em;
}

.empty-bimestre {
    color: #bbb;
    text-align: center;
    padding: 15px 0;
    font-size: 0.85em;
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
    max-width: 550px;
    width: 90%;
    max-height: 85vh;
    overflow-y: auto;
    box-shadow: 0 5px 20px rgba(0,0,0,0.3);
}

.modal-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 15px 20px;
    border-radius: 8px 8px 0 0;
}

.modal-header h3 {
    margin: 0;
    font-size: 1.1em;
}

.modal-body {
    padding: 20px;
}

.form-busqueda {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}

.form-busqueda input {
    padding: 8px 12px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 0.9em;
}

.form-busqueda input[type="text"] {
    flex: 1;
    min-width: 200px;
}

.form-busqueda input[type="number"] {
    width: 90px;
}

.form-busqueda button {
    padding: 8px 18px;
    white-space: nowrap;
}

.btn-agregar {
    background: #4caf50;
    color: white;
    border: none;
    padding: 7px 14px;
    border-radius: 4px;
    cursor: pointer;
    font-size: 0.88em;
    transition: background 0.2s;
}

.btn-agregar:hover {
    background: #45a049;
}

.leyenda {
    display: flex;
    gap: 15px;
    font-size: 0.82em;
    flex-wrap: wrap;
}

.leyenda-item {
    display: flex;
    align-items: center;
    gap: 6px;
}

.leyenda-color {
    width: 18px;
    height: 18px;
    border-radius: 2px;
}

.form-group {
    margin-bottom: 15px;
}

.form-group label {
    display: block;
    margin-bottom: 5px;
    font-weight: 500;
    font-size: 0.9em;
    color: #333;
}

.form-group input,
.form-group select,
.form-group textarea {
    width: 100%;
    padding: 9px 12px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 0.9em;
}

.form-group textarea {
    resize: vertical;
    font-family: inherit;
}

.radio-group {
    display: flex;
    gap: 20px;
    margin-top: 8px;
}

.radio-option {
    display: flex;
    align-items: center;
    gap: 8px;
    cursor: pointer;
}

.radio-option input[type="radio"] {
    width: auto;
    cursor: pointer;
}

.motivo-section {
    background: #fff9e6;
    border: 1px solid #ffe082;
    border-radius: 4px;
    padding: 12px;
    margin-top: 10px;
}

.modal-actions {
    display: flex;
    gap: 10px;
    margin-top: 20px;
    padding-top: 15px;
    border-top: 1px solid #eee;
}

.btn {
    padding: 9px 20px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 0.9em;
    transition: all 0.2s;
}

.btn-primary {
    background: #4a90e2;
    color: white;
}

.btn-primary:hover {
    background: #357abd;
}

.btn-secondary {
    background: #6c757d;
    color: white;
}

.btn-secondary:hover {
    background: #5a6268;
}

.info-tooltip {
    position: relative;
    display: inline-block;
    margin-left: 5px;
    cursor: help;
    color: #666;
    font-size: 0.85em;
}

@media (max-width: 768px) {
    .meses-grid {
        grid-template-columns: repeat(3, 1fr);
    }
    
    .calendario-header {
        flex-direction: column;
        align-items: stretch;
    }
}
</style>

<div class="calendario-container">
    <div class="calendario-header">
        <div>
            <h2 class="section-title">📅 Agenda de Reuniones {{ $anio }}</h2>
        </div>
        
        <div style="display: flex; gap: 10px; flex-wrap: wrap; align-items: center;">
            <form method="GET" action="{{ route('grupotrabajo.agenda') }}" class="form-busqueda">
                <input type="text" name="busqueda" placeholder="Buscar grupo..." value="{{ $busqueda }}">
                <input type="number" name="anio" placeholder="Año" value="{{ $anio }}">
                <button type="submit" class="btn btn-secondary">🔍 Buscar</button>
                @if($busqueda)
                    <button type="button" class="btn-secondary" onclick="window.location='{{ route('grupotrabajo.agenda', ['anio' => $anio]) }}'" style="background: #6c757d;">
                        ✖️ Limpiar
                    </button>
                @endif
            </form>
        </div>
    </div>

    @if($grupos->isEmpty())
    <div style="text-align: center; padding: 60px 20px; background: white; border-radius: 8px; border: 1px solid #e0e0e0;">
        <div style="font-size: 4em; margin-bottom: 15px; opacity: 0.3;">📭</div>
        <h3 style="margin: 0 0 10px 0; color: #333;">No se encontraron grupos</h3>
        <p style="color: #666; margin-bottom: 20px;">
            @if($busqueda)
                No hay grupos que coincidan con "{{ $busqueda }}"
            @else
                No hay grupos de trabajo registrados para el año {{ $anio }}
            @endif
        </p>
        <div style="display: flex; gap: 10px; justify-content: center; flex-wrap: wrap;">
            @if($busqueda)
                <button type="button" class="btn btn-secondary" onclick="window.location='{{ route('grupotrabajo.agenda', ['anio' => $anio]) }}'">
                    Ver Todos los Grupos
                </button>
            @endif
        </div>
    </div>
    @endif

    @foreach($grupos as $grupo)
    <div class="grupo-card">
        <div class="grupo-header">
            <div class="grupo-info">
                <h3>{{ $grupo->nombre }}</h3>
                <div class="grupo-stats">
                    🎯 Meta: {{ $grupo->meta_anual }} | ✅ Realizadas: {{ $grupo->reuniones->count() }} | 
                    📊 {{ $grupo->meta_anual > 0 ? round(($grupo->reuniones->count() / $grupo->meta_anual) * 100) : 0 }}%
                    @if($grupo->reuniones->count() >= $grupo->meta_anual)
                        <span style="margin-left: 10px;">🎉 ¡Meta cumplida!</span>
                    @elseif($grupo->reuniones->count() > 0)
                        <span style="margin-left: 10px;">⚡ {{ $grupo->meta_anual - $grupo->reuniones->count() }} pendientes</span>
                    @else
                        <span style="margin-left: 10px;">⏳ Sin reuniones aún</span>
                    @endif
                </div>
            </div>
            <div style="display: flex; gap: 8px; align-items: center;">
                <button type="button" class="btn-agregar" onclick="abrirModal({{ $grupo->id }})">
                    ➕ Nueva Reunión
                </button>
                <button type="button" class="btn-icon" onclick="window.location='{{ route('grupotrabajo.edit', $grupo->id) }}'" 
                        style="background: rgba(255,255,255,0.2); color: white; padding: 7px 12px;" title="Editar grupo">
                    ⚙️
                </button>
            </div>
        </div>

        <div class="meses-grid">
            @php
                $bimestres = [
                    ['Ene-Feb', [1, 2]],
                    ['Mar-Abr', [3, 4]],
                    ['May-Jun', [5, 6]],
                    ['Jul-Ago', [7, 8]],
                    ['Sep-Oct', [9, 10]],
                    ['Nov-Dic', [11, 12]]
                ];
                $reunionesPorMes = $grupo->reuniones->groupBy(function($reunion) {
                    return $reunion->fecha->format('n');
                });
            @endphp

            @foreach($bimestres as $index => $bimestre)
            @php
                $metaBimestre = $grupo->{'meta_bimestre_' . ($index + 1)};
            @endphp
            <div class="bimestre-columna">
                <div class="bimestre-header">
                    {{ $bimestre[0] }}
                    <small style="display: block; font-size: 0.75em; opacity: 0.9; margin-top: 2px;">Meta: {{ $metaBimestre }}</small>
                </div>
                <div class="bimestre-contenido">
                    @php 
                        $reunionesBimestre = collect();
                        foreach($bimestre[1] as $mes) {
                            $reunionesBimestre = $reunionesBimestre->merge($reunionesPorMes->get($mes, collect()));
                        }
                        $reunionesBimestre = $reunionesBimestre->sortBy('fecha');
                        $cumpleMeta = $reunionesBimestre->count() >= $metaBimestre;
                    @endphp
                    
                    @if($reunionesBimestre->count() > 0)
                        @if($cumpleMeta)
                            <div style="text-align: center; padding: 3px; background: #e8f5e9; border-radius: 3px; font-size: 0.75em; margin-bottom: 5px; color: #2e7d32; font-weight: 600;">
                                ✓ {{ $reunionesBimestre->count() }}/{{ $metaBimestre }}
                            </div>
                        @else
                            <div style="text-align: center; padding: 3px; background: #fff3e0; border-radius: 3px; font-size: 0.75em; margin-bottom: 5px; color: #f57c00; font-weight: 600;">
                                ⚡ {{ $reunionesBimestre->count() }}/{{ $metaBimestre }}
                            </div>
                        @endif
                    @endif
                    
                    @forelse($reunionesBimestre as $reunion)
                        <div class="reunion-badge {{ !$reunion->programada ? 'fuera-programacion' : '' }}" 
                             onclick="verDetalleReunion({{ json_encode([
                                 'fecha' => $reunion->fecha->format('d/m/Y'),
                                 'programada' => $reunion->programada,
                                 'motivo' => $reunion->motivo,
                                 'grupo' => $grupo->nombre
                             ]) }})"
                             title="Click para ver detalles">
                            <span class="reunion-fecha">{{ $reunion->fecha->format('d/M') }}</span>
                            <span class="reunion-icon">{{ $reunion->programada ? '✓' : '⚠' }}</span>
                        </div>
                    @empty
                        <div class="empty-bimestre">
                            @if($metaBimestre > 0)
                                Sin reuniones<br>
                                <small style="color: #f57c00;">Falta: {{ $metaBimestre }}</small>
                            @else
                                --
                            @endif
                        </div>
                    @endforelse
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Modal para agregar reunión -->
    <div id="modal-{{ $grupo->id }}" class="modal-overlay" onclick="cerrarModal(event, {{ $grupo->id }})">
        <div class="modal-content" onclick="event.stopPropagation()">
            <div class="modal-header">
                <h3>➕ Agregar Reunión - {{ $grupo->nombre }}</h3>
            </div>
            <div class="modal-body">
                <form action="{{ route('grupotrabajo.guardarReunion') }}" method="POST">
                    @csrf
                    <input type="hidden" name="grupo_trabajo_id" value="{{ $grupo->id }}">
                    
                    <div class="form-group">
                        <label>📅 Fecha de Reunión *</label>
                        <input type="date" name="fecha" required>
                    </div>

                    <div class="form-group">
                        <label>
                            ¿Esta reunión fue programada o fuera de programación? *
                            <span class="info-tooltip" title="Indica si la reunión estaba en la agenda original">ℹ️</span>
                        </label>
                        <div class="radio-group">
                            <label class="radio-option">
                                <input type="radio" name="programada" value="1" required onchange="toggleMotivo(this, {{ $grupo->id }})">
                                ✅ Sí, fue programada
                            </label>
                            <label class="radio-option">
                                <input type="radio" name="programada" value="0" required onchange="toggleMotivo(this, {{ $grupo->id }})">
                                ⚠️ No, fuera de programación
                            </label>
                        </div>
                    </div>

                    <div id="motivo-programada-{{ $grupo->id }}" style="display: none;">
                        <div class="form-group">
                            <label>✅ ¿Por qué fue agregada esta reunión?</label>
                            <textarea name="motivo_programada" rows="3" placeholder="Ej: Reunión mensual planificada, seguimiento de proyecto X, etc."></textarea>
                            <small style="color: #666; display: block; margin-top: 5px;">Explica brevemente el propósito de esta reunión programada</small>
                        </div>
                    </div>

                    <div id="motivo-noprogramada-{{ $grupo->id }}" style="display: none;">
                        <div class="motivo-section">
                            <div class="form-group" style="margin-bottom: 0;">
                                <label>⚠️ ¿Por qué fue fuera de programación? *</label>
                                <textarea name="motivo_noprogramada" rows="4" placeholder="Ej: Urgencia por incidente crítico, solicitud del cliente, cambio de última hora, etc." required></textarea>
                                <small style="color: #666; display: block; margin-top: 5px;">Es importante documentar por qué esta reunión no estaba planificada</small>
                            </div>
                        </div>
                    </div>

                    <div class="modal-actions">
                        <button type="submit" class="btn btn-primary">💾 Guardar Reunión</button>
                        <button type="button" class="btn btn-secondary" onclick="cerrarModalBtn({{ $grupo->id }})">❌ Cancelar</button>
                    </div>
                </form>
                
                <!-- Resumen de reuniones del grupo -->
                @if($grupo->reuniones->count() > 0)
                <div style="margin-top: 20px; padding-top: 20px; border-top: 1px solid #eee;">
                    <h4 style="margin: 0 0 10px 0; font-size: 0.95em; color: #666;">📊 Resumen de Reuniones</h4>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; font-size: 0.85em;">
                        <div style="background: #e8f5e9; padding: 8px; border-radius: 4px;">
                            <strong style="color: #2e7d32;">✅ Programadas:</strong> {{ $grupo->reuniones->where('programada', true)->count() }}
                        </div>
                        <div style="background: #fff3e0; padding: 8px; border-radius: 4px;">
                            <strong style="color: #f57c00;">⚠️ Fuera de prog.:</strong> {{ $grupo->reuniones->where('programada', false)->count() }}
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
    @endforeach
</div>

<!-- Modal para ver detalles de reunión -->
<div id="modal-detalle" class="modal-overlay" onclick="cerrarModalDetalle(event)">
    <div class="modal-content" onclick="event.stopPropagation()" style="max-width: 500px;">
        <div class="modal-header">
            <h3>📋 Detalles de la Reunión</h3>
        </div>
        <div class="modal-body">
            <div class="form-group">
                <label>Grupo de Trabajo</label>
                <div id="detalle-grupo" style="padding: 10px; background: #f5f5f5; border-radius: 4px; font-weight: 600;"></div>
            </div>
            <div class="form-group">
                <label>Fecha</label>
                <div id="detalle-fecha" style="padding: 10px; background: #f5f5f5; border-radius: 4px;"></div>
            </div>
            <div class="form-group">
                <label>Estado</label>
                <div id="detalle-estado" style="padding: 10px; border-radius: 4px;"></div>
            </div>
            <div class="form-group" id="detalle-motivo-container">
                <label id="detalle-motivo-label">Motivo</label>
                <div id="detalle-motivo" style="padding: 12px; background: #f9f9f9; border-left: 3px solid #ddd; border-radius: 4px; white-space: pre-wrap;"></div>
            </div>
            <div class="modal-actions">
                <button type="button" class="btn btn-secondary" onclick="cerrarModalDetalleBtn()">Cerrar</button>
                <button type="button" class="btn btn-primary" onclick="editarReunion()" style="background: #ff9800;">
                    ✏️ Editar
                </button>
                <button type="button" class="btn" onclick="eliminarReunion()" style="background: #f44336; color: white;">
                    🗑️ Eliminar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Info flotante de ayuda 
<div style="position: fixed; bottom: 20px; right: 20px; background: white; border: 2px solid #667eea; border-radius: 50%; width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; cursor: pointer; box-shadow: 0 3px 10px rgba(0,0,0,0.2); z-index: 999;" onclick="toggleAyuda()" title="Ayuda">
    <span style="font-size: 1.5em;">💡</span>
</div>
<div id="panel-ayuda" style="display: none; position: fixed; bottom: 80px; right: 20px; background: white; border: 1px solid #e0e0e0; border-radius: 8px; padding: 20px; max-width: 350px; box-shadow: 0 5px 20px rgba(0,0,0,0.2); z-index: 998;">
    <h4 style="margin: 0 0 15px 0; color: #667eea;">💡 Guía Rápida</h4>
    <ul style="margin: 0; padding-left: 20px; font-size: 0.9em; line-height: 1.6;">
        <li><strong>Click en una reunión</strong> para ver sus detalles completos</li>
        <li><strong>Colores:</strong> Verde = programada, Naranja = fuera de programación</li>
        <li><strong>Meta por bimestre:</strong> Se muestra en el encabezado de cada columna</li>
        <li><strong>Indicador de progreso:</strong> Aparece cuando hay reuniones en el bimestre</li>
        <li><strong>Botón ⚙️:</strong> Edita el grupo y sus metas</li>
        <li><strong>Estado del grupo:</strong> Muestra si la meta anual se cumplió o cuántas faltan</li>
    </ul>
    <button onclick="toggleAyuda()" style="margin-top: 15px; padding: 8px 15px; background: #667eea; color: white; border: none; border-radius: 4px; cursor: pointer; width: 100%;">
        Cerrar
    </button>
</div>
-->

<script>
function abrirModal(grupoId) {
    document.getElementById('modal-' + grupoId).classList.add('active');
}

function cerrarModal(event, grupoId) {
    if (event.target.classList.contains('modal-overlay')) {
        document.getElementById('modal-' + grupoId).classList.remove('active');
    }
}

function cerrarModalBtn(grupoId) {
    document.getElementById('modal-' + grupoId).classList.remove('active');
}

function toggleMotivo(radio, grupoId) {
    const motivoProgramada = document.getElementById('motivo-programada-' + grupoId);
    const motivoNoProgramada = document.getElementById('motivo-noprogramada-' + grupoId);
    const textareaNoProgramada = motivoNoProgramada.querySelector('textarea');
    
    if (radio.value === '1') {
        motivoProgramada.style.display = 'block';
        motivoNoProgramada.style.display = 'none';
        textareaNoProgramada.removeAttribute('required');
    } else {
        motivoProgramada.style.display = 'none';
        motivoNoProgramada.style.display = 'block';
        textareaNoProgramada.setAttribute('required', 'required');
    }
}

function verDetalleReunion(datos) {
    const modal = document.getElementById('modal-detalle');
    const estadoDiv = document.getElementById('detalle-estado');
    const motivoContainer = document.getElementById('detalle-motivo-container');
    const motivoLabel = document.getElementById('detalle-motivo-label');
    
    document.getElementById('detalle-grupo').textContent = datos.grupo;
    document.getElementById('detalle-fecha').textContent = datos.fecha;
    
    if (datos.programada) {
        estadoDiv.innerHTML = '<span style="color: #4caf50; font-weight: 600;">✅ Reunión Programada</span>';
        estadoDiv.style.background = '#e8f5e9';
        estadoDiv.style.borderLeft = '3px solid #4caf50';
        motivoLabel.textContent = '¿Por qué fue agregada?';
    } else {
        estadoDiv.innerHTML = '<span style="color: #ff9800; font-weight: 600;">⚠️ Fuera de Programación</span>';
        estadoDiv.style.background = '#fff3e0';
        estadoDiv.style.borderLeft = '3px solid #ff9800';
        motivoLabel.textContent = '¿Por qué fue fuera de programación?';
    }
    
    if (datos.motivo) {
        motivoContainer.style.display = 'block';
        document.getElementById('detalle-motivo').textContent = datos.motivo;
    } else {
        motivoContainer.style.display = 'none';
    }
    
    modal.classList.add('active');
}

function cerrarModalDetalle(event) {
    if (event.target.classList.contains('modal-overlay')) {
        document.getElementById('modal-detalle').classList.remove('active');
    }
}

function cerrarModalDetalleBtn() {
    document.getElementById('modal-detalle').classList.remove('active');
}

function toggleAyuda() {
    const panel = document.getElementById('panel-ayuda');
    panel.style.display = panel.style.display === 'none' ? 'block' : 'none';
}

function editarReunion() {
    alert('Función de editar reunión - Implementar según tu ruta de edición');
    // window.location = '{{ route("reunion.edit", "ID") }}';
}

function eliminarReunion() {
    if (confirm('¿Estás seguro de eliminar esta reunión?')) {
        alert('Función de eliminar reunión - Implementar según tu ruta');
        // Aquí iría el código para eliminar
    }
}

// Cerrar ayuda al hacer click fuera
document.addEventListener('click', function(event) {
    const ayudaPanel = document.getElementById('panel-ayuda');
    const ayudaBtn = event.target.closest('[onclick*="toggleAyuda"]');
    
    if (!ayudaBtn && !ayudaPanel.contains(event.target) && ayudaPanel.style.display === 'block') {
        ayudaPanel.style.display = 'none';
    }
});
</script>

@endsection