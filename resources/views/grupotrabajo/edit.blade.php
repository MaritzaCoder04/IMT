@extends('home')

@section('contenido')
<div class="container">
    <h2>Editar Grupo de Trabajo</h2>

    <form action="{{ route('grupotrabajo.update', $grupo->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre del grupo</label>
            <select id="nombre" name="nombre" class="form-control" required>
                <option value="">Selecciona un grupo...</option>
                @foreach($grupos as $grupoOption)
                    <option value="{{ $grupoOption->nombre }}" {{ $grupo->nombre == $grupoOption->nombre ? 'selected' : '' }}>
                        {{ $grupoOption->nombre }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="meta_anual" class="form-label">Meta anual</label>
            <input type="number" id="meta_anual" name="meta_anual" class="form-control" value="{{ $grupo->meta_anual }}">
        </div>

        <button type="submit" class="btn btn-success">Guardar cambios</button>
    </form>
</div>
@endsection
