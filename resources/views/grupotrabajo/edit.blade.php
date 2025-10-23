@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Editar Grupo de Trabajo</h2>

    <form action="{{ route('grupotrabajo.update', $grupo->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre del grupo</label>
            <input type="text" id="nombre" name="nombre" class="form-control" value="{{ $grupo->nombre }}">
        </div>

        <div class="mb-3">
            <label for="meta_anual" class="form-label">Meta anual</label>
            <input type="number" id="meta_anual" name="meta_anual" class="form-control" value="{{ $grupo->meta_anual }}">
        </div>

        <button type="submit" class="btn btn-success">Guardar cambios</button>
    </form>
</div>
@endsection
