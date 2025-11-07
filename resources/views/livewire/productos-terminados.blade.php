<div>
    <style>
        /* Input con botón de limpiar (X) */
        .input-clearable { position: relative; display: inline-block; width: 100%; }
        .input-clearable input { padding-right: 2rem; width: 100%; }
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
        .input-clearable input:placeholder-shown + .clear-input { display: none; }

        /* Badges de estado de entrega */
        .badge { display: inline-block; padding: 0.2rem 0.5rem; border-radius: 0.25rem; font-size: 0.85rem; border: 1px solid transparent; }
        .badge-success { background: #e6f4ea; color: #207844; border-color: #bfe3c8; }
        .badge-danger { background: #fdecea; color: #b6231b; border-color: #f5c2c0; }
        /* Modal básico */
        .modal-overlay { position: fixed; inset: 0; background: rgba(0,0,0,.35); display: none; align-items: center; justify-content: center; z-index: 9999; }
        .modal-overlay.show { display: flex; }
        .modal-box { background: #fff; padding: 1rem 1.25rem; border-radius: 6px; max-width: 640px; width: 92%; box-shadow: 0 10px 30px rgba(0,0,0,.2); }
        .modal-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: .5rem; }
        .modal-title { font-size: 1.1rem; font-weight: 600; }
        .modal-close { background: transparent; border: none; font-size: 1.4rem; cursor: pointer; line-height: 1; }
        .modal-body { font-size: .95rem; line-height: 1.4; white-space: pre-wrap; }
    </style>
    <div class="all-form"> 
        <div class="form-container">
            <div class="container">
                <div class="layout">
                    <main class="main-content">
                    <!-- Sección Búsqueda -->
                        <section id="busqueda" class="section active">
                            <div class="section-header">
                                <h2 class="section-title"> Productos Terminados</h2>
                            </div>
                            <div class="search-container">
                                <div class="search-row">
                                    <div class="form-row">
                                        <div class="form-group">
                                            <label for="cp">Cualquier Palabra</label>
                                            <div class="input-clearable">
                                                <input type="text" id="cp" wire:model.live="busqueda" placeholder="Ingrese cualquier palabra">
                                                <button type="button" class="clear-input" aria-label="Limpiar" onclick="const i=this.previousElementSibling;i.value='';i.dispatchEvent(new Event('input',{bubbles:true}));">x</button>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="pe">Palabra Exacta</label>
                                            <div class="input-clearable">
                                                <input type="text" id="pe" wire:model.live="palabraExacta" placeholder="Coincidencia exacta (ej: cal)">
                                                <button type="button" class="clear-input" aria-label="Limpiar" onclick="const i=this.previousElementSibling;i.value='';i.dispatchEvent(new Event('input',{bubbles:true}));">x</button>
                                            </div>
                                        </div>
                                        
                                    </div>
                                </div> 
                            </div>
                        </section>
                    </main>
                </div>
            </div>

            &nbsp

            <div class="docs-table-wrapper">
            <table class="docs-table">
                <thead>
                    <tr>
                        <th rowspan="2">Origen</th>
                        <th rowspan="2">Designación</th>
                        <th rowspan="2">Nombre</th>
                        <th rowspan="2">Nueva/Actualización</th>
                        <th rowspan="2">Última Fecha</th>
                        <th rowspan="2">Entrega</th>
                        <th colspan="6">Fecha Terminación</th>
                    </tr>
                    <tr>
                        <th>Año</th>
                        <th>APT</th>
                        <th>AFT</th>
                        <th>PPT</th>
                        <th>NA</th>
                        <th>NP</th>
                    </tr>
                </thead>
                
                <tbody>
                    @forelse($documentosProcesados as $documento)
                    <tr>
                        <td>{{ $documento->origenRelacion->desc ?? $documento->info->origen ?? '--' }}</td>
                        <td>{{ $documento->info->designacion ?? '--' }}</td>
                        <td>{{ $documento->nombre ?? '--' }}</td>
                        <td>{{ ucfirst($documento->tipo_ultima_fecha ?? '--') }}</td>
                        @php
                            $etapas = $documento->etapas ?? null;
                            $anoTerm = '-';
                            if ($etapas) {
                                $camposTerm = ['3a','3b','3c','3d','3e'];
                                $yearsCount = [];
                                foreach ($camposTerm as $campo) {
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
                                    $anoTerm = max($candidates);
                                }
                            }
                        @endphp
                        
                        <td>{{ $documento->ultima_fecha ?? '--' }}</td>
                        @php
                            $entregaStatus = $documento->entrega_programacion ?? null;
                            $entregaDetalle = $documento->entrega_detalle ?? null;
                        @endphp
                        <td>
                            @if($entregaStatus === 'dentro')
                                <button type="button" class="badge badge-success" title="Ver detalle" wire:click="abrirModalEntrega({{ $documento->ID_doc }})">Dentro de programación</button>
                            @elseif($entregaStatus === 'fuera')
                                <button type="button" class="badge badge-danger" title="Ver detalle" wire:click="abrirModalEntrega({{ $documento->ID_doc }})">Fuera de programación</button>
                            @else
                                <span class="badge" title="Estado no disponible">--</span>
                            @endif
                        </td>
                        <td>{{ $anoTerm }}</td>

                        {{-- Fechas de Terminación --}}
                        <td>{{ !empty($documento->etapas->{'3a'}) ? date('d/m', strtotime($documento->etapas->{'3a'})) : '--' }}</td>
                        <td>{{ !empty($documento->etapas->{'3b'}) ? date('d/m', strtotime($documento->etapas->{'3b'})) : '--' }}</td>
                        <td>{{ !empty($documento->etapas->{'3c'}) ? date('d/m', strtotime($documento->etapas->{'3c'})) : '--' }}</td>
                        <td>{{ !empty($documento->etapas->{'3d'}) ? date('d/m', strtotime($documento->etapas->{'3d'})) : '--' }}</td>
                        <td>{{ !empty($documento->etapas->{'3e'}) ? date('d/m', strtotime($documento->etapas->{'3e'})) : '--' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="empty-state">
                            <p>No hay documentos registrados</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Modal de detalle de entrega --}}
        <div class="modal-overlay {{ $modalEntregaOpen ? 'show' : '' }}" wire:click.self="cerrarModalEntrega">
            <div class="modal-box" role="dialog" aria-modal="true" aria-labelledby="modalEntregaTitulo">
                <div class="modal-header">
                    <div id="modalEntregaTitulo" class="modal-title">{{ $modalEntregaTitulo }}</div>
                    <button class="modal-close" aria-label="Cerrar" wire:click="cerrarModalEntrega">×</button>
                </div>
                <div class="modal-body">{{ $modalEntregaContenido }}</div>
            </div>
        </div>

            </div>
        </div>
    </div>
</div>