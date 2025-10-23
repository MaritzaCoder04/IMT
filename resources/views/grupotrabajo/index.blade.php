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
.header-section {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    flex-wrap: wrap;
    gap: 15px;
}

.actions {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

.btn-agenda {
    background: #667eea;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.btn-agenda:hover {
    opacity: 0.9;
}

.stats-cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 15px;
    margin-bottom: 25px;
}

.stat-card {
    background: white;
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    padding: 15px;
    display: flex;
    align-items: center;
    gap: 12px;
}

.stat-icon {
    font-size: 2em;
    width: 50px;
    height: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
}

.stat-info h4 {
    margin: 0;
    font-size: 0.85em;
    color: #666;
    font-weight: 500;
}

.stat-info p {
    margin: 5px 0 0 0;
    font-size: 1.5em;
    font-weight: 600;
    color: #333;
}

.progress-bar {
    width: 100%;
    height: 8px;
    background: #f0f0f0;
    border-radius: 4px;
    overflow: hidden;
    position: relative;
}

.progress-fill {
    height: 100%;
    border-radius: 4px;
    transition: width 0.3s;
}

.progress-text {
    font-size: 0.8em;
    color: #666;
    margin-top: 3px;
}

.bimestre-cell {
    text-align: center;
    font-weight: 600;
}

.reunion-count {
    display: inline-block;
    padding: 2px 8px;
    border-radius: 12px;
    font-size: 0.85em;
    font-weight: 600;
}

.status-ok {
    background: #e8f5e9;
    color: #2e7d32;
}

.status-warning {
    background: #fff3e0;
    color: #f57c00;
}

.status-danger {
    background: #ffebee;
    color: #c62828;
}

.table-actions {
    display: flex;
    gap: 5px;
    justify-content: center;
}

.btn-icon {
    padding: 5px 10px;
    font-size: 0.9em;
    border: none;
    background: transparent;
    cursor: pointer;
    border-radius: 4px;
    transition: all 0.2s;
}

.btn-icon:hover {
    background: #f0f0f0;
    transform: scale(1.1);
}

.search-box {
    display: flex;
    gap: 10px;
    margin-bottom: 20px;
}

.search-box input {
    flex: 1;
    padding: 8px 12px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 0.9em;
}

.tooltip-info {
    position: relative;
    cursor: help;
    color: #666;
    font-size: 0.85em;
    margin-left: 5px;
}

.empty-state {
    text-align: center;
    padding: 40px 20px;
}

.empty-state-icon {
    font-size: 3em;
    margin-bottom: 10px;
    opacity: 0.3;
}

@media (max-width: 768px) {
    .header-section {
        flex-direction: column;
        align-items: stretch;
    }
    
    .actions {
        flex-direction: column;
    }
}
</style>

<div class="all-form">
    <div class="form-container">
        <div class="header-section">
            <div class="section-title">👥 Grupos de Trabajo</div>
            <div class="actions">
                
                <button type="button" class="btn btn-secondary" onclick="window.location='{{ route('grupotrabajo.create') }}'">
                    ➕ Agregar Grupo
                </button>
            </div>
        </div>

        @php
            $totalGrupos = $grupos->count();
            $totalMetaAnual = $grupos->sum('meta_anual');
            $totalReuniones = $grupos->sum(function($g) { return $g->reuniones->count(); });
            $progresoGeneral = $totalMetaAnual > 0 ? round(($totalReuniones / $totalMetaAnual) * 100) : 0;
        @endphp

        <!-- Tarjetas de estadísticas -->
        <div class="stats-cards">
            <div class="stat-card">
                <div class="stat-icon" style="background: #e3f2fd;">
                    👥
                </div>
                <div class="stat-info">
                    <h4>Total Grupos</h4>
                    <p>{{ $totalGrupos }}</p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon" style="background: #f3e5f5;">
                    🎯
                </div>
                <div class="stat-info">
                    <h4>Meta Anual Total</h4>
                    <p>{{ $totalMetaAnual }}</p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon" style="background: #e8f5e9;">
                    ✅
                </div>
                <div class="stat-info">
                    <h4>Reuniones Realizadas</h4>
                    <p>{{ $totalReuniones }}</p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon" style="background: #fff3e0;">
                    📊
                </div>
                <div class="stat-info">
                    <h4>Progreso General</h4>
                    <p>{{ $progresoGeneral }}%</p>
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: {{ $progresoGeneral }}%; background: {{ $progresoGeneral >= 75 ? '#4caf50' : ($progresoGeneral >= 50 ? '#ff9800' : '#f44336') }};"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Buscador -->
        <form method="GET" action="{{ route('grupotrabajo.index') }}" class="search-box">
            <input type="text" name="busqueda" placeholder="🔍 Buscar grupo por nombre..." value="{{ request('busqueda') }}">
            <button type="submit" class="btn btn-secondary">Buscar</button>
            @if(request('busqueda'))
                <button type="button" class="btn btn-secondary2" onclick="window.location='{{ route('grupotrabajo.index') }}'">Limpiar</button>
            @endif
        </form>

        <div class="docs-table-wrapper">
            <table class="docs-table">
                <thead>
                    <tr>
                        <th>Nombre del Grupo</th>
                        <th>Meta Anual</th>
                        <th>Bim 1<br><small style="font-weight: 400;">(Ene-Feb)</small></th>
                        <th>Bim 2<br><small style="font-weight: 400;">(Mar-Abr)</small></th>
                        <th>Bim 3<br><small style="font-weight: 400;">(May-Jun)</small></th>
                        <th>Bim 4<br><small style="font-weight: 400;">(Jul-Ago)</small></th>
                        <th>Bim 5<br><small style="font-weight: 400;">(Sep-Oct)</small></th>
                        <th>Bim 6<br><small style="font-weight: 400;">(Nov-Dic)</small></th>
                        <th>Total / Progreso</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($grupos as $grupo)
                    @php
                        $totalRealizadas = $grupo->reuniones->count();
                        $progreso = $grupo->meta_anual > 0 ? round(($totalRealizadas / $grupo->meta_anual) * 100) : 0;
                        
                        // Calcular reuniones por bimestre
                        $reunionesPorBimestre = [
                            1 => $grupo->reuniones->filter(fn($r) => in_array($r->fecha->month, [1,2]))->count(),
                            2 => $grupo->reuniones->filter(fn($r) => in_array($r->fecha->month, [3,4]))->count(),
                            3 => $grupo->reuniones->filter(fn($r) => in_array($r->fecha->month, [5,6]))->count(),
                            4 => $grupo->reuniones->filter(fn($r) => in_array($r->fecha->month, [7,8]))->count(),
                            5 => $grupo->reuniones->filter(fn($r) => in_array($r->fecha->month, [9,10]))->count(),
                            6 => $grupo->reuniones->filter(fn($r) => in_array($r->fecha->month, [11,12]))->count(),
                        ];
                    @endphp
                    <tr>
                        <td><strong>{{ $grupo->nombre }}</strong></td>
                        <td class="bimestre-cell">{{ $grupo->meta_anual }}</td>
                        
                        @for($i = 1; $i <= 6; $i++)
                            @php
                                $meta = $grupo->{'meta_bimestre_' . $i};
                                $realizadas = $reunionesPorBimestre[$i];
                                $statusClass = $realizadas >= $meta ? 'status-ok' : ($realizadas > 0 ? 'status-warning' : 'status-danger');
                            @endphp
                            <td class="bimestre-cell">
                                <span class="reunion-count {{ $statusClass }}" title="Meta: {{ $meta }} | Realizadas: {{ $realizadas }}">
                                    {{ $realizadas }}/{{ $meta }}
                                </span>
                            </td>
                        @endfor
                        
                        <td class="bimestre-cell">
                            <strong>{{ $totalRealizadas }}/{{ $grupo->meta_anual }}</strong>
                            <div class="progress-bar" style="margin-top: 5px;">
                                <div class="progress-fill" style="width: {{ $progreso }}%; background: {{ $progreso >= 75 ? '#4caf50' : ($progreso >= 50 ? '#ff9800' : '#f44336') }};"></div>
                            </div>
                            <div class="progress-text">{{ $progreso }}%</div>
                        </td>
                        
                        <td>
                            <div class="table-actions">
                                <button type="button" class="btn-icon" 
                                        onclick="window.location='{{ route('grupotrabajo.agenda') }}?busqueda={{ urlencode($grupo->nombre) }}'"
                                        title="Ver agenda del grupo">
                                    📅
                                </button>
                                <button type="button" class="btn-icon" 
                                        onclick="window.location='{{ route('grupotrabajo.edit', $grupo->id) }}'"
                                        title="Editar grupo">
                                    ✏️
                                </button>
                                <form action="{{ route('grupotrabajo.destroy', $grupo->id) }}" 
                                      method="POST" 
                                      style="display: inline;"
                                      onsubmit="return confirm('¿Estás seguro de eliminar este grupo? Se eliminarán también todas sus reuniones.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-icon" title="Eliminar grupo">
                                        🗑️
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="empty-state">
                            <div class="empty-state-icon">📋</div>
                            <p><strong>No hay grupos de trabajo registrados</strong></p>
                            <p style="color: #666; font-size: 0.9em;">Comienza agregando tu primer grupo de trabajo</p>
                            <button type="button" class="btn btn-secondary" style="margin-top: 15px;" onclick="window.location='{{ route('grupotrabajo.create') }}'">
                                ➕ Agregar Primer Grupo
                            </button>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection