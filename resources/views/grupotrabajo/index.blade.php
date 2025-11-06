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

<style>


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

/* Iconos dentro de botones de acción */
.btn-icon img {
    width: 25px;
    height: 25px;
    display: inline-block;
    vertical-align: middle;
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

/* Input con botón de limpiar (X) */
.search-box .input-clearable { position: relative; flex: 1; }
.search-box .input-clearable input { padding-right: 2rem; width: 100%; }
.search-box .clear-input {
    position: absolute;
    right: 0.5rem;
    top: 50%;
    transform: translateY(-50%);
    border: none;
    background: transparent;
    font-size: 1.1rem;
    line-height: 1;
    cursor: pointer;
    color: #666;
}
.search-box .clear-input:hover { color: #000; }
.search-box .input-clearable input:placeholder-shown + .clear-input { display: none; }

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
            <div class="section-header">
                <h2 class="section-title"> Programación</h2>
            </div>
        </div>

        @php
            $totalGrupos = $grupos->count();
            $totalMetaAnual = $grupos->sum('meta_anual');
            $totalReuniones = $grupos->sum(function($g) { return $g->reuniones->count(); });
            $progresoGeneral = $totalMetaAnual > 0 ? round(($totalReuniones / $totalMetaAnual) * 100) : 0;
        @endphp

        <!-- Tarjetas de estadísticas -->
         <!--
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
        -->

        <!-- Buscador -->
        <form method="GET" action="{{ route('grupotrabajo.index') }}" class="search-box">
            <div class="input-clearable">
                <input type="text" name="busqueda" placeholder="Buscar grupo por nombre..." value="{{ request('busqueda') }}">
                <button type="button" class="clear-input" aria-label="Limpiar" onclick="const i=this.previousElementSibling;i.value='';i.dispatchEvent(new Event('input',{bubbles:true}));i.focus();">×</button>
            </div>
            <button type="button" class="btn btn-ejemplo" onclick="abrirModalUrl('{{ route('grupotrabajo.create') }}')">
                Agregar Grupo
            </button>
            <button type="button" class="btn btn-ejemplo" style="padding: 7px 12px; margin-left:8px;" onclick="abrirModalGrupos('{{ route('grupos.index') }}')" title="Tipos de Grupo" aria-label="Tipos de Grupo">
              <img src="{{ asset('img/pencil.png') }}" alt="Tipos de Grupo" style="width:16px;height:16px;" />
            </button>
        </form>

        <div class="docs-table-wrapper3">
            <table class="docs-table">
                <thead>
                    <tr>
                        <th>Nombre del Grupo</th>
                        <th>Ene-Feb</th>
                        <th>Mar-Abr</th>
                        <th>May-Jun</th>
                        <th>Jul-Ago</th>
                        <th>Sep-Oct</th>
                        <th>Nov-Dic</th>
                        <th>Atendidas / Programadas</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($grupos as $grupo)
                    @php
                        $stats = $statsByGroup[$grupo->id] ?? [
                            'total_realizadas' => 0,
                            'progreso' => 0,
                            'reuniones_por_bimestre' => [1=>0,2=>0,3=>0,4=>0,5=>0,6=>0],
                            'terminados_por_bimestre' => null,
                            'terminados_total' => null,
                        ];
                        $totalRealizadas = $stats['total_realizadas'];
                        $progreso = $stats['progreso'];
                        $reunionesPorBimestre = $stats['reuniones_por_bimestre'];
                        $terminadosPorBimestre = $stats['terminados_por_bimestre'] ?? null;
                    @endphp
                    <tr>
                        <td><strong>{{ $grupo->nombre }}</strong></td>
                        
                        @for($i = 1; $i <= 6; $i++)
                            @php
                                $meta = $grupo->{'meta_bimestre_' . $i};
                                $realizadas = $reunionesPorBimestre[$i] ?? 0;
                                $statusClass = $realizadas >= $meta ? 'status-ok' : ($realizadas > 0 ? 'status-warning' : 'status-danger');
                            @endphp
                            <td class="bimestre-cell">
                                @php
                                    // Si hay datos de terminados para este grupo, mostrar terminados/meta;
                                    // de lo contrario, mostrar realizadas/meta.
                                    $terminadosCount = is_array($terminadosPorBimestre ?? null) ? ($terminadosPorBimestre[$i] ?? null) : null;
                                    $usarTerminados = !is_null($terminadosCount);
                                    $valorMostrar = $usarTerminados ? $terminadosCount : ($realizadas ?? 0);
                                    $statusClassDisplay = $valorMostrar >= $meta ? 'status-ok' : ($valorMostrar > 0 ? 'status-warning' : 'status-danger');
                                    $tituloMetric = $usarTerminados ? 'Terminados' : 'Realizadas';
                                @endphp
                                <span class="reunion-count {{ $statusClassDisplay }}" title="{{ $tituloMetric }}: {{ $valorMostrar }} | Meta: {{ $meta }}">
                                    {{ $valorMostrar }}/{{ $meta }}
                                </span>
                            </td>
                        @endfor
                        
                        <td class="bimestre-cell">
                            @php
                                // Mostrar "Atendidas / Programadas": Programadas = meta anual (fallback a stats o conteo)
                                $totalProgramadas = (int) ($grupo->meta_anual ?? ($stats['total_programadas'] ?? ($grupo->reuniones->where('programada', true)->count())));
                            @endphp
                            <strong>{{ $totalRealizadas }} / {{ $totalProgramadas }}</strong>
                        </td>
                        
                        <td>
                            <div class="table-actions">
                                <button type="button" class="btn-icon" 
                                        onclick="abrirModalUrl('{{ route('grupotrabajo.edit', $grupo->id) }}')"
                                        title="Editar grupo">
                                    <img src="{{ asset('img/pencil.png') }}" alt="Editar grupo">
                                </button>
                                <button type="button" class="btn-icon" 
                                        data-nombre="{{ $grupo->nombre }}"
                                        onclick="abrirAgendaDeGrupo(this)"
                                        title="Agenda del grupo">
                                    <img src="{{ asset('img/agend.png') }}" alt="Agenda del grupo">
                                </button>
                                <form action="{{ route('grupotrabajo.destroy', $grupo->id) }}" 
                                      method="POST" 
                                      style="display: inline;"
                                      onsubmit="return confirm('¿Estás seguro de eliminar este grupo? Se eliminarán también todas sus reuniones.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-icon" title="Eliminar grupo">
                                        <img src="{{ asset('img/delete.png') }}" alt="Eliminar grupo">
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @php
                        // Acumular totales globales de la tabla
                        // Nota: se suman dentro del bucle con variables persistentes
                    @endphp
                    @empty
                    <tr>
                        <td colspan="10" class="empty-state">
                            <div class="empty-state-icon">📋</div>
                            <p><strong>No hay grupos de trabajo registrados</strong></p>
                            <p style="color: #666; font-size: 0.9em;">Comienza agregando tu primer grupo de trabajo</p>
                        </td>
                    </tr>
                    @endforelse
                    @php
                        // Fila de totales: suma de Atendidas y Programadas
                        $sumAtendidas = 0;
                        $sumProgramadas = 0;
                        foreach ($grupos as $g) {
                            $statsG = $statsByGroup[$g->id] ?? [];
                            $sumAtendidas += (int)($statsG['total_realizadas'] ?? 0);
                            $sumProgramadas += (int)($g->meta_anual ?? ($statsG['total_programadas'] ?? ($g->reuniones->where('programada', true)->count())));
                        }
                    @endphp
                    <tr>
                        <td><strong>Totales</strong></td>
                        <td colspan="6"></td>
                        <td class="bimestre-cell"><strong>{{ $sumAtendidas }} / {{ $sumProgramadas }}</strong></td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <script>
        document.addEventListener('DOMContentLoaded', function() {
          const input = document.querySelector('.search-box input[name="busqueda"]');
          const tbody = document.querySelector('.docs-table tbody');
          if (!input || !tbody) return;

          const rows = Array.from(tbody.querySelectorAll('tr'));
          let noRow = document.getElementById('no-results-row');
          if (!noRow) {
            noRow = document.createElement('tr');
            noRow.id = 'no-results-row';
            const td = document.createElement('td');
            td.colSpan = 10;
            td.className = 'empty-state';
            td.textContent = 'No se encontraron grupos';
            noRow.appendChild(td);
            noRow.style.display = 'none';
            tbody.appendChild(noRow);
          }

          const filter = () => {
            const q = input.value.trim().toLowerCase();
            let anyVisible = false;
            rows.forEach(row => {
              if (row.id === 'no-results-row') return;
              const nameCell = row.querySelector('td:first-child');
              const text = nameCell ? nameCell.textContent.toLowerCase() : '';
              const show = !q || text.includes(q);
              row.style.display = show ? '' : 'none';
              if (show) anyVisible = true;
            });
            noRow.style.display = (!anyVisible && q) ? '' : 'none';
          };

          input.addEventListener('input', filter);
          filter();

          const clearBtn = document.querySelector('.search-box .clear-input');
          if (clearBtn) {
            clearBtn.addEventListener('click', function() {
              input.value = '';
              input.dispatchEvent(new Event('input', { bubbles: true }));
              input.focus();
            });
          }
        });
        </script>
        
        <!-- Modal Overlay para Crear Grupo -->
        <div id="modal-overlay-gt" class="modal-overlay" onclick="cerrarModalGTOverlayClick(event)">
            <div class="modal-content" onclick="event.stopPropagation()">
                <div class="modal-header">
                    <h3>Grupo de Trabajo</h3>
                    <button type="button" class="close-modal-btn" onclick="cerrarModalGT()">×</button>
                </div>
                <div class="modal-body">
                    <iframe id="modal-iframe-gt" src="about:blank" title="Crear grupo"></iframe>
                </div>
            </div>
        </div>

        <!-- Modal Overlay para Tipos de Grupo de Trabajo -->
        <div id="modal-overlay-grupos" class="modal-overlay" onclick="cerrarModalGruposOverlayClick(event)">
            <div class="modal-content" onclick="event.stopPropagation()">
                <div class="modal-header">
                    <h3>Tipos de Grupo de Trabajo</h3>
                    <button type="button" class="close-modal-btn" onclick="cerrarModalGrupos()">×</button>
                </div>
                <div class="modal-body">
                    <iframe id="modal-iframe-grupos" src="about:blank" title="Tipos de grupo"></iframe>
                </div>
            </div>
        </div>

        <!-- Modal Overlay para Agenda del Grupo -->
        <div id="modal-overlay-agenda" class="modal-overlay" onclick="cerrarModalAgendaOverlayClick(event)">
            <div class="modal-content" onclick="event.stopPropagation()">
                <div class="modal-header">
                    <h3 id="agenda-modal-title">Agenda del grupo</h3>
                    <button type="button" class="close-modal-btn" onclick="cerrarModalAgenda()">×</button>
                </div>
                <div class="modal-body">
                    <iframe id="modal-iframe-agenda" src="about:blank" title="Agenda del grupo"></iframe>
                </div>
            </div>
        </div>
        
        <script>
        function abrirModalUrl(url){
          try{
            const iframe = document.getElementById('modal-iframe-gt');
            const params = new URLSearchParams(window.location.search);
            let anio = params.get('anio');
            if(!anio){
              try { anio = localStorage.getItem('anioGlobal'); } catch(e) {}
            }
            let src = url + (url.includes('?') ? '&' : '?') + 'modal=1';
            if (anio) { src += '&anio=' + encodeURIComponent(anio); }
            iframe.src = src;
            document.getElementById('modal-overlay-gt').classList.add('active');
          }catch(e){}
        }
        function cerrarModalGT(){
          try{
            const overlay = document.getElementById('modal-overlay-gt');
            const iframe = document.getElementById('modal-iframe-gt');
            overlay.classList.remove('active');
            iframe.src = 'about:blank';
          }catch(e){}
        }
        function cerrarModalGTOverlayClick(event){ if(event && event.target && event.target.id==='modal-overlay-gt'){ cerrarModalGT(); } }
        function abrirModalGrupos(url){
          try{
            const iframe = document.getElementById('modal-iframe-grupos');
            iframe.src = url + (url.includes('?') ? '&' : '?') + 'modal=1';
            document.getElementById('modal-overlay-grupos').classList.add('active');
          }catch(e){}
        }
        function cerrarModalGrupos(){
          try{
            const overlay = document.getElementById('modal-overlay-grupos');
            const iframe = document.getElementById('modal-iframe-grupos');
            overlay.classList.remove('active');
            iframe.src = 'about:blank';
          }catch(e){}
        }
        function cerrarModalGruposOverlayClick(event){ if(event && event.target && event.target.id==='modal-overlay-grupos'){ cerrarModalGrupos(); } }
        function abrirAgendaDeGrupo(el){
          try{
            const nombre = (el && el.dataset && el.dataset.nombre) ? el.dataset.nombre : '';
            const base = "{{ route('grupotrabajo.agenda') }}";
            const params = new URLSearchParams(window.location.search);
            let anio = params.get('anio');
            if(!anio){
              try { anio = localStorage.getItem('anioGlobal'); } catch(e) {}
            }
            let url = base + '?modal=1';
            if (anio) { url += '&anio=' + encodeURIComponent(anio); }
            if (nombre) { url += '&busqueda=' + encodeURIComponent(nombre); }

            const iframe = document.getElementById('modal-iframe-agenda');
            const titleEl = document.getElementById('agenda-modal-title');
            if (titleEl && nombre) { titleEl.textContent = 'Agenda: ' + nombre; }
            iframe.src = url;
            document.getElementById('modal-overlay-agenda').classList.add('active');
          }catch(e){}
        }
        function cerrarModalAgenda(){
          try{
            const overlay = document.getElementById('modal-overlay-agenda');
            const iframe = document.getElementById('modal-iframe-agenda');
            overlay.classList.remove('active');
            iframe.src = 'about:blank';
          }catch(e){}
        }
        function cerrarModalAgendaOverlayClick(event){ if(event && event.target && event.target.id==='modal-overlay-agenda'){ cerrarModalAgenda(); } }
        window.addEventListener('message', function(ev){
          const d = ev && ev.data ? ev.data : {};
          if(d.type === 'modal-close' || d.type === 'close-modal'){
            cerrarModalGT();
            cerrarModalGrupos();
            cerrarModalAgenda();
            if (d.reload || d.saved) { window.location.reload(); }
          }
        });
        // Autocierre cuando index se carga dentro de iframe con saved=1
        (function(){
          try {
            const params = new URLSearchParams(window.location.search);
            const isIframe = window.top !== window.self;
            if (isIframe && params.get('modal') === '1' && params.get('saved') === '1') {
              window.parent && window.parent.postMessage({ type: 'modal-close', reload: true }, '*');
            }
          } catch (e) {}
        })();
        </script>
    </div>
</div>

@endsection