@extends('home')

@section('contenido')

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<div class="all-form">
    <div class="form-container">
        <div class="section-title">Reporte de Grupos de Trabajo - {{ date('Y') }}</div>
        
        <div class="actions">
            <button type="button" class="btn btn-secondary">
                GUardar
            </button>
            <button type="button" class="btn btn-secondary" onclick="window.location='{{ route('grupotrabajo.pdf') }}'">
                📥 Descargar PDF
            </button>
        </div>

        <div class="docs-table-wrapper">
            <table class="docs-table">
                <thead>
                    <tr>
                        <th rowspan="2">No.</th>
                        <th rowspan="2">Descripción</th>
                        <th rowspan="2">Unidad de medida</th>
                        <th rowspan="2">Meta</th>
                        <th rowspan="2">Avance bimestral Productos terminados</th>
                        <th colspan="2">Primer Bimestre PORCENTAJE</th>
                        <th rowspan="2">Total Anual</th>
                        <th rowspan="2">Observaciones</th>
                    </tr>
                    <tr>
                        <th>Realizado vs. Programado en el periodo</th>
                        <th>Realizado acumulado vs. Prog. Anual</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($grupos as $grupo)
                    <tr>
                        <td></td>
                        <td>{{ $grupo->nombre }}</td>
                        <td></td>
                        <td>{{ $grupo->meta_anual }}</td>
                        <td></td>
                        <td>{{ $grupo->realizados[1] ?? 0 }}</td>
                        <td>{{ $grupo->realizados[2] ?? 0 }}</td>
                        <td><strong>{{ $grupo->total_realizado }}</strong></td>
                        <td>
                            <form action="{{ route('grupotrabajo.actualizarObservaciones', $grupo->id) }}" method="POST" style="display: inline;">
                                @csrf
                                <textarea name="observaciones" rows="2" style="width: 100%;">{{ $grupo->observaciones }}</textarea>
                                <button type="submit" class="btn btn-secondary" style="margin-top: 5px; font-size: 12px;">Guardar</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="empty-state">
                            <p>No hay grupos de trabajo registrados</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection