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
    </style>
    <!-- Sección Búsqueda -->
    <section id="busqueda" class="section active">
        <div class="section-header">
            <h2 class="section-title">Todos Los Manuales/Normas</h2>
        </div>

        <div class="search-container">
            <div class="search-row">
                <div class="form-row">
                    <div class="form-group">
                        <label for="palabra">Cualquier Palabra</label>
                        <div class="input-clearable">
                            <input type="text" id="palabra" wire:model.live="palabra" placeholder="Ingrese cualquier palabra">
                            <button type="button" class="clear-input" aria-label="Limpiar" onclick="const i=this.previousElementSibling;i.value='';i.dispatchEvent(new Event('input',{bubbles:true}));">x</button>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="palabraExacta">Palabra Exacta</label>
                        <div class="input-clearable">
                            <input type="text" id="palabraExacta" wire:model.live="palabraExacta" placeholder="Coincidencia exacta (ej: cal)">
                            <button type="button" class="clear-input" aria-label="Limpiar" onclick="const i=this.previousElementSibling;i.value='';i.dispatchEvent(new Event('input',{bubbles:true}));">x</button>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="designacion">Designación</label>
                        <div class="input-clearable">
                            <input type="text" id="designacion" wire:model.live="designacion" placeholder="Ingrese designación">
                            <button type="button" class="clear-input" aria-label="Limpiar" onclick="const i=this.previousElementSibling;i.value='';i.dispatchEvent(new Event('input',{bubbles:true}));">x</button>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="libro">Libro</label>
                        <select id="libro" wire:model.live="libro">
                            <option value="">Selecciona un libro</option>
                            @foreach($libros as $libroItem)
                                <option value="{{ $libroItem->ID_libro }}">{{ $libroItem->desc }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="anio">Año</label>
                        <div class="input-clearable">
                            <input type="number" id="anio" wire:model.live="anio" placeholder="Ej: 2012" min="1990" max="2030">
                            <button type="button" class="clear-input" aria-label="Limpiar" onclick="const i=this.previousElementSibling;i.value='';i.dispatchEvent(new Event('input',{bubbles:true}));">x</button>
                        </div>
                    </div>
                    <div class="actions">
                    <!--<button wire:click="descargarSQL" class="btn btn-success">Descargar SQL</button>-->
                    </div>
                </div>
            </div>
            
        </div>
        
        <div id="resultadosBusqueda"></div>
    </section>

    <!-- Tabla de Documentos -->
                <div class="docs-table-wrapper">
                    <table class="docs-table">
                        <thead>
                            <tr>
                                <th>Norma/Manual</th>
                                <th>Libro</th>
                                <th>Tema</th>
                                <th>Parte</th>
                                <th>Título</th>
                                <th>Designación</th>
                                <th>Nombre</th>
                                <th>Origen</th>
                                <th>Nueva</th>
                                <th>Actualización</th>
                            </tr>
                        </thead>
                        <tbody>
                        @forelse($documentosProcesados as $documento)
                            <tr>
                                <td>{{ $documento->tipoRelacion->desc ?? ($documento->tipo == 1 ? 'Manual' : 'Norma') }}</td>
                                <td>{{ $documento->libroRelacion->clave ?? ($documento->libro ?? '-') }}</td>
                                <td>{{ $documento->temaRelacion->clave ?? ($documento->tema == 0 ? '-' : $documento->tema) }}</td>
                                <td>{{ optional($documento->info)->desc_parte ?: ($documento->parteRelacion->desc ?? ($documento->parte == 0 ? '-' : $documento->parte)) }}</td>
                                <td>{{ optional($documento->info)->desc_titulo ?: ($documento->tituloRelacion->desc ?? ($documento->titulo == 0 ? '-' : $documento->titulo)) }}</td>
                                <td>{{ optional($documento->info)->designacion ?? '-' }}</td>
                                <td>{{ $documento->nombre ?? '-' }}</td>
                                <td>{{ $documento->origenRelacion->desc ?? (optional($documento->info)->origen ?? '-') }}</td>
                                <td>{{ $documento->fecha_nueva }}</td>
                                <td>{{ $documento->fechas_actualizacion ?? '-' }}</td>
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
</div>

