<div>
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
                                            <input type="text" id="cp" wire:model.live="busqueda" placeholder="Ingrese cualquier palabra">
                                        </div>   
                                        <div class="actions2">
                                            <button type="button" class="btn btn-secondary2" wire:click="limpiar">Limpiar</button>
                                        </div>
                                    </div>
                                </div> 
                            </div>
                        </section>
                    </main>
                </div>
            </div>
        </div>
    </div>

    &nbsp
    &nbsp

    <div class="docs-container">
        <div class="all-form"> 
            <div class="docs-table-wrapper">
            <table class="docs-table">
                <thead>
                    <tr>
                        <th rowspan="2">Origen</th>
                        <th rowspan="2">Designación</th>
                        <th rowspan="2">Nombre</th>
                        <th rowspan="2">Nueva/Actualización</th>
                        <th rowspan="2">Última Fecha</th>
                        <th colspan="5">Fecha Terminación</th>
                    </tr>
                    <tr>
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
                        <td>{{ $documento->origen ?? '--' }}</td>
                        <td>{{ $documento->designacion ?? '--' }}</td>
                        <td>{{ $documento->nombre ?? '--' }}</td>
                        <td>{{ ucfirst($documento->tipo_ultima_fecha ?? '--') }}</td>
                        <td>{{ $documento->ultima_fecha ?? '--' }}</td>

                        {{-- Fechas de Terminación --}}
                        <td>{{ $documento->etapas->{'3a'} ?? '--' }}</td>
                        <td>{{ $documento->etapas->{'3b'} ?? '--' }}</td>
                        <td>{{ $documento->etapas->{'3c'} ?? '--' }}</td>
                        <td>{{ $documento->etapas->{'3d'} ?? '--' }}</td>
                        <td>{{ $documento->etapas->{'3e'} ?? '--' }}</td>
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
        </div>
    </div>
</div>