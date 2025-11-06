@extends('home')

@section('contenido')
<div class="main-container">
    <div class="section-content">
        <h2 class="section-title">Programación</h2>

        <!-- Buscador -->
        <form method="GET" action="{{ route('grupotrabajo.fijos') }}" class="search-box">
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
                                $totalProgramadas = $stats['total_programadas'] ?? ($grupo->reuniones->where('programada', true)->count());
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
                            $sumProgramadas += (int)($statsG['total_programadas'] ?? ($g->reuniones->where('programada', true)->count()));
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
        window.addEventListener('message', function(ev){
          const d = ev && ev.data ? ev.data : {};
          if(d.type === 'modal-close' || d.type === 'close-modal'){
            cerrarModalGT();
            cerrarModalGrupos();
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