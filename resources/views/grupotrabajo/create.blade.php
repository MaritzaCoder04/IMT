@extends('home')

@section('contenido')

<div class="all-form">
    <div class="form-container">
        <div class="section-title">Nuevo Grupo de Trabajo</div>
        
        <form action="{{ route('grupotrabajo.store') }}" method="POST">
            @csrf
            
            <div class="form-group">
                <label for="nombre">Nombre del Grupo</label>
                <select id="nombre" name="nombre" required>
                    <option value="">Selecciona un grupo...</option>
                    @foreach($grupos as $grupo)
                        <option value="{{ $grupo->nombre }}">{{ $grupo->nombre }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="meta_anual">Meta Anual</label>
                <input type="number" id="meta_anual" name="meta_anual" min="0" required>
            </div>

            <div class="section-title">Metas Bimestrales</div>

            <div class="form-row">
                <div class="form-group">
                    <label for="meta_bimestre_1">Bimestre 1 (Ene-Feb)</label>
                    <input type="number" id="meta_bimestre_1" name="meta_bimestre_1" min="0" required>
                </div>

                <div class="form-group">
                    <label for="meta_bimestre_2">Bimestre 2 (Mar-Abr)</label>
                    <input type="number" id="meta_bimestre_2" name="meta_bimestre_2" min="0" required>
                </div>

                <div class="form-group">
                    <label for="meta_bimestre_3">Bimestre 3 (May-Jun)</label>
                    <input type="number" id="meta_bimestre_3" name="meta_bimestre_3" min="0" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="meta_bimestre_4">Bimestre 4 (Jul-Ago)</label>
                    <input type="number" id="meta_bimestre_4" name="meta_bimestre_4" min="0" required>
                </div>

                <div class="form-group">
                    <label for="meta_bimestre_5">Bimestre 5 (Sep-Oct)</label>
                    <input type="number" id="meta_bimestre_5" name="meta_bimestre_5" min="0" required>
                </div>

                <div class="form-group">
                    <label for="meta_bimestre_6">Bimestre 6 (Nov-Dic)</label>
                    <input type="number" id="meta_bimestre_6" name="meta_bimestre_6" min="0" required>
                </div>
            </div>

            <div class="actions">
                <button type="submit" class="btn btn-secondary">Guardar</button>
                <button type="button" class="btn btn-secondary2" onclick="window.location='{{ route('grupotrabajo.index') }}'">Cancelar</button>
            </div>
        </form>
    </div>
</div>

@endsection