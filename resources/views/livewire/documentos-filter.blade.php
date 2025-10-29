<div>
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
                        <input type="text" id="palabra" wire:model.live="palabra" placeholder="Ingrese cualquier palabra">
                    </div>
                    <div class="form-group">
                        <label for="palabraExacta">Palabra exacta</label>
                        <input type="text" id="palabraExacta" wire:model.live="palabraExacta" placeholder="Coincidencia exacta (ej: cal)">
                    </div>
                    <div class="form-group">
                        <label for="designacion">Designación</label>
                        <input type="text" id="designacion" wire:model.live="designacion" placeholder="Ingrese designación">
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
                        <input type="number" id="anio" wire:model.live="anio" placeholder="Ej: 2012" min="1990" max="2030">
                    </div>
                    <div class="actions">
                    @if($palabra || $palabraExacta || $designacion || $libro || $anio)
                        <button type="button" class="btn btn-secondary2" wire:click="limpiar">Limpiar</button>
                    @endif
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
                                <td>{{ $documento->info->desc_parte ?? ($documento->parte == 0 ? '-' : $documento->parte) }}</td>
                                <td>{{ $documento->info->desc_titulo ?? ($documento->titulo == 0 ? '-' : $documento->titulo) }}</td>
                                <td>{{ $documento->info->designacion ?? '-' }}</td>
                                <td>{{ $documento->nombre ?? '-' }}</td>
                                <td>{{ $documento->info->origen ?? '-' }}</td>
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
