@extends('home')

@section('contenido')
<div class="all-form">
    <div class="form-container">
        <div class="section-title">Editar Documento</div>
        
        <form action="{{ route('documentos.update', $documento->ID_doc) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="form-group">
                <label for="tipoDocumento">Manual/Norma</label>
                <select id="tipoDocumento" name="tipoDocumento" required>
                    <option value="">Selecciona un tipo de documento</option>
                    <option value="1" {{ $documento->tipo == 1 ? 'selected' : '' }}>Manual</option>
                    <option value="2" {{ $documento->tipo == 2 ? 'selected' : '' }}>Norma</option>
                </select>
            </div>

            <div class="form-group">
                <label for="nombre">Nombre</label>
                <input type="text" id="nombre" name="nombre" value="{{ $documento->nombre }}" required>
            </div>

            <div class="form-group">
                <label for="libro">Libro</label>
                <input type="number" id="libro" name="libro" value="{{ $documento->libro }}" required>
            </div>

            <div class="form-group">
                <label for="tema">Tema</label>
                <input type="number" id="tema" name="tema" value="{{ $documento->tema }}" required>
            </div>

            <div class="form-group">
                <label for="parte">Parte</label>
                <input type="number" id="parte" name="parte" value="{{ $documento->parte }}" required>
            </div>

            <div class="form-group">
                <label for="titulo">Titulo</label>
                <input type="number" id="titulo" name="titulo" value="{{ $documento->titulo }}" required>
            </div>

            <div class="form-group">
                <label for="capitulo">Capitulo</label>
                <input type="number" id="capitulo" name="capitulo" value="{{ $documento->capitulo }}" required>
            </div>

            <div class="form-group">
                <label for="origen">Origen</label>
                <input type="number" id="origen" name="origen" value="{{ $documento->origen }}" required>
            </div>

            <div class="form-group">
                <label for="fechaPublicacion">Año de Publicación</label>
                <input type="text" id="fechaPublicacion" name="fechaPublicacion" value="{{ $documento->anio }}" required>
            </div>

            <div class="actions">
                <button type="submit" class="btn btn-secondary">Guardar Cambios</button>
                <button type="button" class="btn btn-secondary2" onclick="window.location='{{ route('controldeavances') }}'">Cancelar</button>
            </div>
        </form>
    </div>
</div>
@endsection