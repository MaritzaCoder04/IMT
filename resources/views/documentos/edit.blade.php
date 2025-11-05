@extends('home')
@section('contenido')
<div class="all-form">
        
        <form action="{{ route('documentos.update', $documento->ID_doc) }}" method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" name="modal" value="{{ request('modal') ? 1 : '' }}">
            
            <div style="display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 1.25rem; ">
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
</div>


            <div style="display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 1.25rem;">

            <div class="form-group">
                <label for="libro">Libro</label>
                <select id="libro" name="libro" required>
                    <option value="">Selecciona un libro</option>
                    @foreach($libros as $l)
                        <option value="{{ $l->ID_libro }}" {{ (string)$documento->libro === (string)$l->ID_libro ? 'selected' : '' }}>
                            {{ $l->desc }} {{ $l->clave ? '(' . $l->clave . ')' : '' }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="tema">Tema</label>
                <select id="tema" name="tema" required>
                    <option value="">Selecciona un tema</option>
                    @foreach($temas as $t)
                        <option value="{{ $t->ID_tema }}" {{ (string)$documento->tema === (string)$t->ID_tema ? 'selected' : '' }}>
                            {{ $t->desc }} {{ $t->clave ? '(' . $t->clave . ')' : '' }}
                        </option>
                    @endforeach
                </select>
            </div>
</div>

            <div style="display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 1.25rem; ">


            <div class="form-group">
                <label for="parteDesc">Parte</label>
                <input type="text" id="parteDesc" value="{{ optional($documento->info)->desc_parte ?? ($documento->parteRelacion->desc ?? '') }}" readonly>
                <input type="hidden" name="parte" value="{{ $documento->parte }}">
            </div>

            <div class="form-group">
                <label for="tituloDesc">Titulo</label>
                <input type="text" id="tituloDesc" value="{{ optional($documento->info)->desc_titulo ?? ($documento->tituloRelacion->desc ?? '') }}" readonly>
                <input type="hidden" name="titulo" value="{{ $documento->titulo }}">
            </div>
</div>

            <div style="display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 1.25rem;">


            <div class="form-group">
                <label for="capitulo">Capitulo</label>
                <input type="number" id="capitulo" name="capitulo" value="{{ $documento->capitulo }}" required>
            </div>

            <div class="form-group">
                <label for="origen">Origen</label>
                <select id="origen" name="origen" required>
                    <option value="">Selecciona un origen</option>
                    @foreach($origenes as $o)
                        <option value="{{ $o->ID_origen }}" {{ (string)$documento->origen === (string)$o->ID_origen ? 'selected' : '' }}>
                            {{ $o->desc }}
                        </option>
                    @endforeach
                </select>
            </div>
</div>

            <div style="display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 1.25rem;">


            <div class="form-group">
                <label for="fechaPublicacion">Año de Publicación</label>
                <input type="text" id="fechaPublicacion" name="fechaPublicacion" value="{{ $documento->anio }}" required>
            </div>
</div>

            <div class="actions">
                <button type="submit" class="btn btn-secondary">Guardar Cambios</button>
            </div>
        </form>
    </div>
</div>
@endsection