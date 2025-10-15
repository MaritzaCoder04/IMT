@extends('home') 

@section('contenido')


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