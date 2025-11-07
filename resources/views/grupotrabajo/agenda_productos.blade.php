@extends('home')
@section('contenido')

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
    <div class="section-content">
        <h2 class="section-title">Agenda de Productos</h2>  
        

        <!-- Buscador -->
        <form method="GET" action="{{ route('grupotrabajo.agenda_productos') }}" class="search-box">
            <div class="input-clearable">
                <input type="text" name="busqueda" placeholder="Buscar grupo por nombre..." value="{{ request('busqueda') }}">
                <button type="button" class="clear-input" aria-label="Limpiar" onclick="const i=this.previousElementSibling;i.value='';i.dispatchEvent(new Event('input',{bubbles:true}));i.focus();">x</button>
            </div>
             @php
        $currentYear = (int)date('Y');
        $selectedYear = (int)request()->get('anio', $currentYear);
    @endphp
    <div class="year-switcher" style="display:flex; align-items:center; gap:8px;">
      <input type="number" id="page-year-input" value="{{ $selectedYear }}" min="1990" max="{{ $currentYear + 50 }}" placeholder="{{ $currentYear }}" style="padding:6px 10px; border-radius:6px; border:1px solid #cbd5e1; font-size:0.9rem; width:90px;" />
    </div>
            <button type="button" class="btn btn-ejemplo" onclick="abrirModalUrl('{{ route('grupotrabajo.create') }}')">
                NUEVO GRUPO DE TRABAJO
            </button>
            <button type="button" class="btn btn-icon" style="padding: 7px 12px; margin-left:8px;" onclick="abrirModalGrupos('{{ route('grupos.index') }}')" title="Tipos de Grupo" aria-label="Tipos de Grupo">
              <img src="{{ asset('img/pluss.png') }}" alt="Tipos de Grupo" style="width:30px;height:30px;" />
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
                        $reunionesPorBimestre = $stats['reuniones_por_bimestre'];
                        $terminadosPorBimestre = $stats['terminados_por_bimestre'] ?? null;
                    @endphp
                    <tr>
                        <td><strong>{{ $grupo->nombre }}</strong></td>
                        @for($i = 1; $i <= 6; $i++)
                            @php
                                $meta = $grupo->{'meta_bimestre_' . $i};
                                $realizadas = $reunionesPorBimestre[$i] ?? 0;
                                $terminadosCount = is_array($terminadosPorBimestre ?? null) ? ($terminadosPorBimestre[$i] ?? null) : null;
                                $usarTerminados = !is_null($terminadosCount);
                                $valorMostrar = $usarTerminados ? $terminadosCount : ($realizadas ?? 0);
                                $statusClassDisplay = $valorMostrar >= $meta ? 'status-ok' : ($valorMostrar > 0 ? 'status-warning' : 'status-danger');
                                $tituloMetric = $usarTerminados ? 'Terminados' : 'Realizadas';
                            @endphp
                            <td class="bimestre-cell">
                                <span class="reunion-count {{ $statusClassDisplay }}" title="{{ $tituloMetric }}: {{ $valorMostrar }} | Meta: {{ $meta }}">
                                    {{ $valorMostrar }}/{{ $meta }}
                                </span>
                            </td>
                        @endfor

                        <td class="bimestre-cell">
                            @php
                                // Programadas = meta anual (fallback a stats o conteo de reuniones programadas)
                                $totalProgramadas = (int) ($grupo->meta_anual ?? ($stats['total_programadas'] ?? ($grupo->reuniones->where('programada', true)->count())));
                                // Para productos (APT/AFT/PPT/NP), usar Terminados como "Atendidas"
                                $atendidasMostrar = isset($stats['terminados_total']) && !is_null($stats['terminados_total'])
                                    ? (int)$stats['terminados_total']
                                    : (int)$totalRealizadas;
                            @endphp
                            <strong>{{ $atendidasMostrar }} / {{ $totalProgramadas }}</strong>
                        </td>

                        <td>
                            <div class="table-actions">
                                <button type="button" class="btn-icon" 
                                        onclick="abrirModalUrl('{{ route('grupotrabajo.edit', $grupo->id) }}')"
                                        title="Editar grupo">
                                    <img src="{{ asset('img/pencil.png') }}" alt="Editar grupo">
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
                    @empty
                    <tr>
                        <td colspan="10" class="empty-state">
                            <div class="empty-state-icon">ðŸ“‹</div>
                            <p><strong>No hay grupos de trabajo registrados</strong></p>
                            <p style="color: #666; font-size: 0.9em;">Comienza agregando tu primer grupo de trabajo</p>
                        </td>
                    </tr>
                    @endforelse
                    @php
                        $sumAtendidas = 0;
                        $sumProgramadas = 0;
                        foreach ($grupos as $g) {
                            $statsG = $statsByGroup[$g->id] ?? [];
                            // Sumar Terminados si aplica, de lo contrario Realizadas
                            $sumAtendidas += isset($statsG['terminados_total']) && !is_null($statsG['terminados_total'])
                                ? (int)$statsG['terminados_total']
                                : (int)($statsG['total_realizadas'] ?? 0);
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

        <!-- Modal Overlay para Crear Grupo -->
        <div id="modal-overlay-gt" class="modal-overlay" onclick="cerrarModalGTOverlayClick(event)">
            <div class="modal-content" onclick="event.stopPropagation()">
                <div class="modal-header">
                    <h3>Grupo de Trabajo</h3>
                    <button type="button" class="close-modal-btn" onclick="cerrarModalGT()">x</button>
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
                    <button type="button" class="close-modal-btn" onclick="cerrarModalGrupos()">x</button>
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
                    <button type="button" class="close-modal-btn" onclick="cerrarModalAgenda()">x</button>
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
            let src = url + (url.includes('?') ? '&' : '?') + 'modal=1&scope=productos';
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
            const sep = url.includes('?') ? '&' : '?';
            iframe.src = url + sep + 'modal=1&scope=productos';
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
            // Indicar que la agenda proviene de Agenda de Productos para filtrar selects
            url += '&scope=productos';

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
<script>
document.addEventListener('DOMContentLoaded', function(){
  var input = document.getElementById('page-year-input');
  if (!input) return;
  input.addEventListener('change', function(){
    try { localStorage.setItem('anioGlobal', String(input.value)); } catch(e){}
    var url = new URL(window.location.href);
    var params = url.searchParams;
    var y = input.value || '';
    var busqueda = (document.querySelector('.search-box input[name="busqueda"]') || {}).value || '';
    if (y) params.set('anio', y); else params.delete('anio');
    if (busqueda) params.set('busqueda', busqueda); else params.delete('busqueda');
    url.search = params.toString();
    window.location.href = url.toString();
  });
});
</script>

<script>
document.addEventListener('DOMContentLoaded', function(){
  var input = document.getElementById('page-year-input');
  if (!input) return;
  input.addEventListener('change', function(){
    try { localStorage.setItem('anioGlobal', String(input.value)); } catch(e){}
    var url = new URL(window.location.href);
    var params = url.searchParams;
    var y = input.value || '';
    var busqueda = (document.querySelector('.search-box input[name="busqueda"]') || {}).value || '';
    if (y) params.set('anio', y); else params.delete('anio');
    if (busqueda) params.set('busqueda', busqueda); else params.delete('busqueda');
    url.search = params.toString();
    window.location.href = url.toString();
  });
});
</script>