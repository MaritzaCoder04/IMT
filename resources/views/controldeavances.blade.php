
@extends('home')

@section('contenido')

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
{{--
<div class="all-form"> 
    <div class="form-container">
    <div class="container">
        <div class="layout">
            <main class="main-content">
                <!-- Sección Búsqueda -->
                <section id="busqueda" class="section active">
                    <div class="section-header">
                        <h2 class="section-title">Filtros</h2>
                    </div>
                    <form id="formBusqueda">
                    <div class="search-container">
                        <div class="search-row">
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="cp">Cualquier Palabra</label>
                                    <input type="text" id="cp" name="cp" placeholder="Ingrese cualquier palabra">
                                </div>
                                <div class="form-group">
                                    <label for="designacion">Designación</label>
                                    <input type="text" id="designacion"readonly>
                                </div>
                                <div class="form-group">
                                    <label for="libro">Libro</label>
                                    <select id="libro" name="libro">
                                        <option value="">Selecciona un libro</option>
                                        <option value="pry">Opción 1</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="parte">Parte</label>
                                    <select id="parte" name="parte">
                                        <option value="">Selecciona una parte</option>
                                        <option value="pr">Opción 1</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="time">Año</label>
                                    <input type="text" id="time" name="time" placeholder="Ej: 2010">
                                </div>
                            </div>           
                            <div class="actions">
                                <button type="submit" class="btn btn-secondary">Buscar</button>
                                <button type="button" class="btn btn-secondary2" onclick="limpiarFormulario()">Limpiar</button>
                            </div>
                        </div>
                    </div>
                    
                    <div id="resultadosBusqueda"></div>
                </section>
            </form>
            </main>
        </div>
    </div>
</div>

&nbsp
&nbsp--}}

        <div class="actions">
            <button type="button" class="btn btn-secondary" onclick="window.location='{{ route('formulario') }}'">
                Agregar Nuevo Manual/Norma
            </button>
        </div>

        &nbsp
        &nbsp
        
        <div class="docs-container">
            <div class="docs-table-wrapper"> 
                <table class="docs-table">
                    <thead>
                        <tr>
                            <th rowspan="2">Etapas</th>
                            <th rowspan="2">Modificar</th>
                            <th rowspan="2">Estado</th>
                            <th rowspan="2">Designación</th>
                            <th rowspan="2">Nombre</th>
                            <th rowspan="2">Nueva/Actualización</th>
                            <th colspan="5">Fecha Inicio</th>
                            <th colspan="5">Fecha Entrega</th>
                            <th colspan="5">Fecha Terminación</th>
                            <th rowspan="2">Avance</th>
                            <th rowspan="2">Eliminar</th>
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
                            <td>
                                <button type="button" class="btn-action btn-etapas" onclick="window.location='{{ route('documentos.etapas', $documento->ID_doc) }}'"> 
                                    📅 
                                </button>
                            </td>
                            <td>
                                <button type="button" class="btn-action btn-edit" onclick="window.location='{{ route('documentos.edit', $documento->ID_doc) }}'">
                                    ✏️
                                </button>
                            </td>
                            <td>
                                <form action="{{ route('documentos.archive', $documento->ID_doc) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('PATCH')
                                    @if($documento->vigente == 1)
                                        <button type="submit" class="btn-action btn-archive" onclick="return confirm('¿Estás seguro de archivar este documento?')">
                                            📦 
                                        </button>
                                    @else
                                        <button type="submit" class="btn-action btn-unarchive" onclick="return confirm('¿Estás seguro de desarchivar este documento?')">
                                            📂 
                                        </button>
                                    @endif
                                </form>
                            </td>
                            <td>{{ $documento->designacion ?? '--' }}</td>
                            <td>{{ $documento->nombre ?? '--' }}</td>
                            <td>{{ $documento->nueva ? 'Nueva' : 'Actualización' }}</td>
                            
                            {{-- Fechas de Inicio --}}
                            <td>{{ $documento->etapas->{'1a'} ?? '--' }}</td>
                            <td>{{ $documento->etapas->{'1b'} ?? '--' }}</td>
                            <td>{{ $documento->etapas->{'1c'} ?? '--' }}</td>
                            <td>{{ $documento->etapas->{'1d'} ?? '--' }}</td>
                            <td>{{ $documento->etapas->{'1e'} ?? '--' }}</td>
                            
                            {{-- Fechas de Entrega --}}
                            <td>{{ $documento->etapas->{'2a'} ?? '--' }}</td>
                            <td>{{ $documento->etapas->{'2b'} ?? '--' }}</td>
                            <td>{{ $documento->etapas->{'2c'} ?? '--' }}</td>
                            <td>{{ $documento->etapas->{'2d'} ?? '--' }}</td>
                            <td>{{ $documento->etapas->{'2e'} ?? '--' }}</td>
                            
                            {{-- Fechas de Terminación --}}
                            <td>{{ $documento->etapas->{'3a'} ?? '--' }}</td>
                            <td>{{ $documento->etapas->{'3b'} ?? '--' }}</td>
                            <td>{{ $documento->etapas->{'3c'} ?? '--' }}</td>
                            <td>{{ $documento->etapas->{'3d'} ?? '--' }}</td>
                            <td>{{ $documento->etapas->{'3e'} ?? '--' }}</td>
                            
                            <td>
                                @php
                                    $etapas = $documento->etapas;
                                    $totalFechas = 15;
                                    $fechasCompletadas = 0;
                                    
                                    if ($etapas) {
                                        $campos = ['1a', '2a', '3a', '1b', '2b', '3b', '1c', '2c', '3c', '1d', '2d', '3d', '1e', '2e', '3e'];
                                        foreach ($campos as $campo) {
                                            if (!empty($etapas->$campo)) {
                                                $fechasCompletadas++;
                                            }
                                        }
                                    }
                                    
                                    $porcentaje = $totalFechas > 0 ? round(($fechasCompletadas / $totalFechas) * 100) : 0;
                                @endphp
                                
                                <div class="progress-mini" title="{{ $fechasCompletadas }}/{{ $totalFechas }} fechas">
                                    <div class="progress-bar-mini" style="width: {{ $porcentaje }}%"></div>
                                </div>
                                <small>{{ $porcentaje }}%</small>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="22" class="empty-state">
                                <p>No hay documentos registrados</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
</div>

@endsection
