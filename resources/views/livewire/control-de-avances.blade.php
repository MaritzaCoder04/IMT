<div>
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
        /* Botones de acción estilo grupotrabajo index */
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
        .btn-icon img {
            width: 25px;
            height: 25px;
            display: inline-block;
            vertical-align: middle;
        }
        .table-actions {
            display: flex;
            gap: 5px;
            justify-content: center;
        }
        /* Alineación horizontal de inputs y botones en la búsqueda */
        #busqueda .form-row {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            align-items: flex-end;
        }
        #busqueda .form-group {
            min-width: 260px;
        }
        #busqueda .actions2 {
            display: flex;
            gap: 10px;
            align-items: flex-end;
        }
        #busqueda .search-container {
            margin-bottom: 16px; /* separa la búsqueda de la tabla */
        }
        /* Input con botón de limpiar (X) */
        .input-clearable { position: relative; }
        .input-clearable input { padding-right: 2rem; }
        .clear-input {
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
        .clear-input:hover { color: #000; }
        /* Ocultar la X cuando el campo está vacío usando placeholder-shown */
        .input-clearable input:placeholder-shown + .clear-input { display: none; }

        /* Estilos para la barra de progreso */
        .progress-mini {
            position: relative;
            width: 100%;
            height: 9px;
            background: #f0f0f0;
            overflow: hidden;
            margin-top: 15px;
        }
        
        .progress-bar-mini {
            height: 100%;
            transition: width 0.3s;
        }
        
        .progress-text {
            font-size: 0.8em;
            color: #666;
            text-align: center;
        }
    </style>

    <div class="all-form"> 
        <div class="form-container">
            <div class="container">
                <div class="layout">
                    <main class="main-content">
                        <!-- Sección Búsqueda -->
                        <section id="busqueda" class="section active">
                            <div class="section-header">
                                <h2 class="section-title">Control De Avances</h2>
                            </div>
                            <div class="search-container">
                                <div class="search-row">
                                    <div class="form-row">
                                        <div class="form-group">
                                            <label for="busqueda">Cualquier Palabra</label>
                                            <div class="input-clearable">
                                                <input type="text" wire:model.live="busqueda" id="busqueda" placeholder="Ingrese cualquier palabra">
                                                <button type="button" class="clear-input" aria-label="Limpiar" onclick="const i=this.previousElementSibling;i.value='';i.dispatchEvent(new Event('input',{bubbles:true}));">×</button>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="palabraExacta">Palabra exacta</label>
                                            <div class="input-clearable">
                                                <input type="text" wire:model.live="palabraExacta" id="palabraExacta" placeholder="Coincidencia exacta (ej: cal)">
                                                <button type="button" class="clear-input" aria-label="Limpiar" onclick="const i=this.previousElementSibling;i.value='';i.dispatchEvent(new Event('input',{bubbles:true}));">×</button>
                                            </div>
                                        </div>
                                            <button type="button" class="btn btn-ejemplo" onclick="abrirModalUrl('{{ route('formulario') }}')"> Agregar Nuevo Manual/Norma </button>
                                        
                                    </div>
                                </div> 
                            </div>
                        </section>
                    </main>
            </div>
        </div>
        <div class="docs-table-wrapper2"> 
            <table class="docs-table">
                <thead>
                    <tr>
                        <th rowspan="2">Designación</th>
                        <th rowspan="2">Nombre</th>
                        <th rowspan="2">Nueva/Actualización</th>
                        <!--<th rowspan="2">Año</th>-->
                        <th colspan="5">Fecha Inicio</th>
                        <th colspan="5">Fecha Entrega</th>
                        <th colspan="5">Fecha Terminación</th>
                        <th rowspan="2">Avance</th>
                        <th rowspan="2">Acciones</th>
                    </tr>
                    <tr>
                        <th>APT</th>
                        <th>AFT</th>
                        <th>PPT</th>
                        <th>NA</th>
                        <th>NP</th>
                        <th>APT</th>
                        <th>AFT</th>
                        <th>PPT</th>
                        <th>NA</th>
                        <th>NP</th>
                        <th>APT</th>
                        <th>AFT</th>
                        <th>PPT</th>
                        <th>NA</th>
                        <th>NP</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($documentos as $documento)
                    <tr class="{{ $documento->vigente == 0 ? 'archivado' : '' }}">
                        <td>{{ $documento->info->designacion ?? '--' }}</td>
                        <td class="up">{{ $documento->nombre ?? '--' }}</td>
                        <td>{{ $documento->nueva ? 'Nueva' : 'Actualización' }}</td>
                        @php
                            $etapas = $documento->etapas ?? null;
                            $ano = '-';
                            if ($etapas) {
                                $campos = ['1a','1b','1c','1d','1e','2a','2b','2c','2d','2e','3a','3b','3c','3d','3e'];
                                $yearsCount = [];
                                foreach ($campos as $campo) {
                                    $val = $etapas->{$campo} ?? null;
                                    if (!empty($val)) {
                                        $y = date('Y', strtotime($val));
                                        $yearsCount[$y] = isset($yearsCount[$y]) ? $yearsCount[$y] + 1 : 1;
                                    }
                                }
                                if (!empty($yearsCount)) {
                                    $maxCount = max($yearsCount);
                                    $candidates = [];
                                    foreach ($yearsCount as $year => $count) {
                                        if ($count === $maxCount) { $candidates[] = $year; }
                                    }
                                    $ano = max($candidates);
                                }
                            }
                        @endphp
                        <!--<td>{{ $ano }}</td>-->
                        <td class="up">{{ !empty($documento->etapas->{'1a'}) ? date('d/m', strtotime($documento->etapas->{'1a'})) : '-' }}</td>
                        <td class="up">{{ !empty($documento->etapas->{'1b'}) ? date('d/m', strtotime($documento->etapas->{'1b'})) : '-' }}</td>
                        <td class="up">{{ !empty($documento->etapas->{'1c'}) ? date('d/m', strtotime($documento->etapas->{'1c'})) : '-' }}</td>
                        <td class="up">{{ !empty($documento->etapas->{'1d'}) ? date('d/m', strtotime($documento->etapas->{'1d'})) : '-' }}</td>
                        <td class="up">{{ !empty($documento->etapas->{'1e'}) ? date('d/m', strtotime($documento->etapas->{'1e'})) : '-' }}</td>
                        <td>{{ !empty($documento->etapas->{'2a'}) ? date('d/m', strtotime($documento->etapas->{'2a'})) : '-' }}</td>
                        <td>{{ !empty($documento->etapas->{'2b'}) ? date('d/m', strtotime($documento->etapas->{'2b'})) : '-' }}</td>
                        <td>{{ !empty($documento->etapas->{'2c'}) ? date('d/m', strtotime($documento->etapas->{'2c'})) : '-' }}</td>
                        <td>{{ !empty($documento->etapas->{'2d'}) ? date('d/m', strtotime($documento->etapas->{'2d'})) : '-' }}</td>
                        <td>{{ !empty($documento->etapas->{'2e'}) ? date('d/m', strtotime($documento->etapas->{'2e'})) : '-' }}</td>
                        <td class="up">{{ !empty($documento->etapas->{'3a'}) ? date('d/m', strtotime($documento->etapas->{'3a'})) : '-' }}</td>
                        <td class="up">{{ !empty($documento->etapas->{'3b'}) ? date('d/m', strtotime($documento->etapas->{'3b'})) : '-' }}</td>
                        <td class="up">{{ !empty($documento->etapas->{'3c'}) ? date('d/m', strtotime($documento->etapas->{'3c'})) : '-' }}</td>
                        <td class="up">{{ !empty($documento->etapas->{'3d'}) ? date('d/m', strtotime($documento->etapas->{'3d'})) : '-' }}</td>
                        <td class="up">{{ !empty($documento->etapas->{'3e'}) ? date('d/m', strtotime($documento->etapas->{'3e'})) : '-' }}</td>
                        <td>
                            @php
                            $etapas = $documento->etapas;
                            $totalFechas = 10; // Solo fechas de entrega (2a-2e) y terminación (3a-3e)
                            $fechasCompletadas = 0;
                                        
                                        if ($etapas) {
                                            // Solo considerar fechas de entrega (2a-2e) y terminación (3a-3e)
                                            $campos = ['2a', '2b', '2c', '2d', '2e', '3a', '3b', '3c', '3d', '3e'];
                                            foreach ($campos as $campo) {
                                                if (!empty($etapas->$campo)) {
                                                    $fechasCompletadas++;
                                                }
                                            }
                                        }
                                        
                                        $porcentaje = $totalFechas > 0 ? round(($fechasCompletadas / $totalFechas) * 100) : 0;
                                    @endphp
                                    
                                    <div class="progress-mini" title="{{ $fechasCompletadas }}/{{ $totalFechas }} fechas">
                                        <div class="progress-bar-mini" style="width: {{ $porcentaje }}%; background: {{ $porcentaje >= 75 ? '#4caf50' : ($porcentaje >= 50 ? '#ff9800' : '#f44336') }};"></div>
                                    </div>
                                    <div class="progress-text">{{ $porcentaje }}%</div>
                                </td>
                                <td>
                                    <div class="table-actions">
                    <button type="button" class="btn-icon" title="Ver etapas" onclick="abrirModalUrl('{{ route('documentos.etapas', $documento->ID_doc) }}')">
                        <img src="{{ asset('img/notebook.png') }}" alt="Ver etapas">
                    </button>
                    <button type="button" class="btn-icon" title="Editar documento" onclick="abrirModalUrl('{{ route('documentos.edit', $documento->ID_doc) }}')">
                        <img src="{{ asset('img/pencil.png') }}" alt="Editar documento">
                    </button>
                    <button type="button" class="btn-icon" title="Eliminar documento" wire:click="eliminar({{ $documento->ID_doc }})" onclick="return confirm('¿Estás seguro de que deseas eliminar este documento?')">
                        <img src="{{ asset('img/delete.png') }}" alt="Eliminar documento">
                    </button>
                                    </div>
                                </td>
                                
                            </tr>
                            @empty
                            <tr>
                <td colspan="21" class="empty-state">
                    <p>No hay documentos registrados</p>
                </td>
            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

    <div id="modalOverlay" class="modal-overlay" aria-hidden="true" onclick="if(event && event.target && event.target.id==='modalOverlay'){ cerrarModal(); }">
        <div class="modal-content" role="dialog" aria-modal="true" onclick="event.stopPropagation()">
            <div class="modal-header">
                <h3>Acción</h3>
                <button class="close-modal-btn" onclick="cerrarModal()" aria-label="Cerrar">×</button>
            </div>
            <div class="modal-body">
                <iframe id="modalIframe" src="about:blank"></iframe>
            </div>
        </div>
    </div>

    <script>
        function abrirModalUrl(url) {
            const overlay = document.getElementById('modalOverlay');
            const iframe = document.getElementById('modalIframe');
            const sep = url.includes('?') ? '&' : '?';
            iframe.src = url + sep + 'modal=1';
            overlay.classList.add('active');
            document.body.style.overflow = 'hidden';
        }
        function cerrarModal() {
            const overlay = document.getElementById('modalOverlay');
            const iframe = document.getElementById('modalIframe');
            overlay.classList.remove('active');
            iframe.src = 'about:blank';
            document.body.style.overflow = '';
        }
        window.addEventListener('message', function(e){
            try {
                const data = e.data || {};
                if (data && data.type === 'modal-close') {
                    cerrarModal();
                    window.location.reload();
                }
            } catch(err) {}
        });
        window.addEventListener('keydown', function(e){ if(e.key==='Escape'){ cerrarModal(); } });
    </script>

</div>