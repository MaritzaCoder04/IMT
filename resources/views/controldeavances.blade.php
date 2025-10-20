@extends('home')
@section('contenido')

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<div class="all-form"> 
    <div class="form-container">
        <div class="container">
            <div class="layout">
                <main class="main-content">
                <!-- Sección Búsqueda -->
                    <section id="busqueda" class="section active">
                        <div class="section-header">
                            <h2 class="section-title"> Control De Avances</h2>
                        </div>
                        <form id="formBusqueda">
                            <div class="search-container">
                                <div class="search-row">
                                    <div class="form-row">
                                        <div class="form-group">
                                            <label for="cp">Cualquier Palabra</label>
                                            <input type="text" id="cp" name="cp" placeholder="Ingrese cualquier palabra">
                                        </div>   
                                        <div class="actions2">
                                            <button type="submit" class="btn btn-secondary">Buscar</button>
                                            <button type="button" class="btn btn-secondary2" onclick="limpiarFormulario()">Limpiar</button>
                                            <button type="button" class="btn btn-secondary" onclick="window.location='{{ route('formulario') }}'"> Agregar Nuevo Manual/Norma </button>
                                        </div>
                                    </div>
                                </div> 
                            </div>
                            <div id="resultadosBusqueda"></div>
                        </form>
                    </section>
                </main>
            </div>
        </div>
    </div>
</div>

&nbsp
&nbsp
        
<div class="docs-container">
    <div class="docs-table-wrapper2"> 
        <table class="docs-table">
            <thead>
                <tr>
                    <th rowspan="2">Etapas</th>
                        {{--<th rowspan="2">Estado</th>--}}
                        <th rowspan="2">Designación</th>
                        <th rowspan="2">Nombre</th>
                        <th rowspan="2">Nueva/Actualización</th>
                        <th colspan="5">Fecha Inicio</th>
                        <th colspan="5">Fecha Entrega</th>
                        <th colspan="5">Fecha Terminación</th>
                        <th rowspan="2">Avance</th>
                        <th rowspan="2">Modificar</th>
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
                    <td><button type="button" class="btn-action btn-etapas" onclick="window.location='{{ route('documentos.etapas', $documento->ID_doc) }}'"> 📅  </button> </td>
                        {{--<td>
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
                            --}}
                    <td>{{ $documento->info->designacion ?? '--' }}</td>
                    <td class="up">{{ $documento->nombre ?? '--' }}</td>
                    <td>{{ $documento->nueva ? 'Nueva' : 'Actualización' }}</td>
                    <td class="up">{{ $documento->etapas->{'1a'} ?? '-' }}</td>
                    <td class="up">{{ $documento->etapas->{'1b'} ?? '-' }}</td>
                    <td class="up">{{ $documento->etapas->{'1c'} ?? '-' }}</td>
                    <td class="up">{{ $documento->etapas->{'1d'} ?? '-' }}</td>
                    <td class="up">{{ $documento->etapas->{'1e'} ?? '-' }}</td>
                    <td>{{ $documento->etapas->{'2a'} ?? '-' }}</td>
                    <td>{{ $documento->etapas->{'2b'} ?? '-' }}</td>
                    <td>{{ $documento->etapas->{'2c'} ?? '-' }}</td>
                    <td>{{ $documento->etapas->{'2d'} ?? '-' }}</td>
                    <td>{{ $documento->etapas->{'2e'} ?? '-' }}</td>
                    <td class="up">{{ $documento->etapas->{'3a'} ?? '-' }}</td>
                    <td class="up">{{ $documento->etapas->{'3b'} ?? '-' }}</td>
                    <td class="up">{{ $documento->etapas->{'3c'} ?? '-' }}</td>
                    <td class="up">{{ $documento->etapas->{'3d'} ?? '-' }}</td>
                    <td class="up">{{ $documento->etapas->{'3e'} ?? '-' }}</td>
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
                            <td>
                                <button type="button" class="btn-action btn-edit" onclick="window.location='{{ route('documentos.edit', $documento->ID_doc) }}'">
                                    ✏️
                                </button>
                            </td>
                            <td>
                                <button type="button" class="btn-action btn-delete" onclick="window.location='{{ route('documentos.edit', $documento->ID_doc) }}'">
                                    🗑️ 
                                </button>
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
