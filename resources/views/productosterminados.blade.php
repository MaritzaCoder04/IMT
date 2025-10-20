@extends('home') 
@section('contenido')

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
    <div class="all-form"> 

        <div class="docs-table-wrapper">
        <table class="docs-table">
            <thead>
                <tr>
                    <th rowspan="2">Origen</th>
                    <th rowspan="2">Designación</th>
                    <th rowspan="2">Nombre</th>
                    <th rowspan="2">Nueva/Actualización</th>
                    <th rowspan="2">Año Publicación</th>
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
                    <td>{{ $documento->nue_act ?? '--' }}</td>
                    <td>{{ $documento->anio_pub?? '--' }}</td>

                    {{-- Fechas de Terminación --}}
                    <td>{{ $documento->etapas->{'3a'} ?? '--' }}</td>
                    <td>{{ $documento->etapas->{'3b'} ?? '--' }}</td>
                    <td>{{ $documento->etapas->{'3c'} ?? '--' }}</td>
                    <td>{{ $documento->etapas->{'3d'} ?? '--' }}</td>
                    <td>{{ $documento->etapas->{'3e'} ?? '--' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="11" class="empty-state">
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
@endsection