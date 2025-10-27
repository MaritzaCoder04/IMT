@extends('home')

@section('contenido')
<div class="all-form">
    <div class="form-container">
        <div class="section-title">Registro Norma/Manual</div>
        <form action="{{ route('formulario.guardar') }}" method="POST">
            @csrf
            <div class="form-row">
                <div class="form-group">
                    <label for="tipoDocumento">Manual/Norma</label>
                    <select id="tipoDocumento" name="tipoDocumento" required>
                        <option value="">Selecciona un tipo de documento</option>
                        @isset($tipos)
                        @foreach($tipos as $t)
                            <option value="{{ $t->clave }}" @selected(old('tipoDocumento')==$t->clave)>{{ $t->desc }}</option>
                        @endforeach
                        @endisset
                    </select>
                    <div style="margin-top:6px;">
                        <a href="{{ route('tipos.index') }}" class="btn btn-secondary2">Editar tipos</a>
                    </div>
                </div>

                <div class="form-group">
                    <label for="sel-libro">Libro</label>
                    <select name="ID_libro" id="sel-libro" required>
                        <option value="">Selecciona un libro…</option>
                        @foreach($libros as $l)
                            <option value="{{ $l->ID_libro }}" @selected(old('ID_libro')==$l->ID_libro)>
                                {{ $l->desc }}
                            </option>
                        @endforeach
                    </select>
                    @error('ID_libro') <small class="text-danger">{{ $message }}</small> @enderror
                    <div style="margin-top:6px;">
                        <a href="{{ route('libros.index') }}" class="btn btn-secondary2">Editar libros</a>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="tema">Tema</label>
                    <select id="tema" name="tema" required>
                        <option value="">Selecciona un tema</option>
                        @isset($temas)
                        @foreach($temas as $t)
                            <option value="{{ $t->ID_tema }}" @selected(old('tema')==$t->ID_tema)>{{ $t->desc }}</option>
                        @endforeach
                        @endisset
                    </select>
                    <div style="margin-top:6px;">
                        <a href="{{ route('temas.index') }}" class="btn btn-secondary2">Editar temas</a>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="parte">Parte</label>
                <input type="text" id="parte" name="parte" placeholder="Ej: 10. Proyecto de Señalamiento" required>
            </div>

            <div class="form-group">
                <label for="titulo">Título</label>
                <input type="text" id="titulo" name="titulo" placeholder="Ej: 01. Proyecto de Señalamiento" required>
            </div>

            <div class="form-group">
                <label for="capitulo">Capítulo</label>
                <input type="text" id="capitulo" name="capitulo" placeholder="Ej: 003. Diseño de Señales Preventivas" required>
            </div>

            <div class="form-group">
                <label for="nombre">Nombre</label>
                <input type="text" id="nombre" name="nombre" placeholder="Ej: Diseño de Señales Preventivas" required>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="origen">Origen</label>
                    <select id="origen" name="origen" required>
                        <option value="">Selecciona el origen</option>
                        @isset($origenes)
                        @foreach($origenes as $o)
                            <option value="{{ $o->ID_origen }}" @selected(old('origen')==$o->ID_origen)>{{ $o->desc }}</option>
                        @endforeach
                        @endisset
                    </select>
                    <div style="margin-top:6px;">
                        <a href="{{ route('origenes.index') }}" class="btn btn-secondary2">Editar orígenes</a>
                    </div>
                </div>

                <div class="form-group">
                    <label for="fechaPublicacion">Fecha de Publicación</label>
                    <input type="text" id="fechaPublicacion" name="fechaPublicacion" placeholder="Ej: 2013" required>
                </div>

                {{--<div class="form-group">
                    <label for="designacion">Designación</label>
                    <input type="text" id="designacion"readonly>
                </div>--}}
            </div>

            <div class="actions">
                <button type="submit" class="btn btn-secondary">Registrar Documento</button>
                <button type="button" class="btn btn-secondary2" onclick="window.location='{{ route('controldeavances') }}'">Cancelar</button>
            </div>
        </form>
    </div>
</div>

@endsection
