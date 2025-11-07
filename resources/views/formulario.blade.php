@extends('home')

@section('contenido')
<link rel="stylesheet" href="{{ asset('css/estilosModales.css') }}">
<div class="all-form">
        <form action="{{ route('formulario.guardar') }}" method="POST">
            @csrf
            <input type="hidden" name="modal" value="{{ request('modal') ? 1 : '' }}">
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
                        <button type="button" class="btn btn-ejemplo" style="padding: 7px 12px;" onclick="abrirModalTipos('{{ route('tipos.index') }}')" title="Editar tipos" aria-label="Editar tipos">
                          <img src="{{ asset('img/pencil.png') }}" alt="Editar tipos" style="width:16px;height:16px;" />
                        </button>
                    </div>
                </div>

                <div class="form-group">
                    <label for="sel-libro">Libro</label>
                    <select name="ID_libro" id="sel-libro" required>
                        <option value="">Selecciona un libroâ€¦</option>
                        @foreach($libros as $l)
                            <option value="{{ $l->ID_libro }}" @selected(old('ID_libro')==$l->ID_libro)>
                                {{ $l->desc }}
                            </option>
                        @endforeach
                    </select>
                    @error('ID_libro') <small class="text-danger">{{ $message }}</small> @enderror
                    <div style="margin-top:6px;">
                        <button type="button" class="btn btn-ejemplo" style="padding: 7px 12px;" onclick="abrirModalLibros('{{ route('libros.index') }}')" title="Editar libros" aria-label="Editar libros">
                          <img src="{{ asset('img/pencil.png') }}" alt="Editar libros" style="width:16px;height:16px;" />
                        </button>
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
                        <button type="button" class="btn btn-ejemplo" style="padding: 7px 12px;" onclick="abrirModalTemas('{{ route('temas.index') }}')" title="Editar temas" aria-label="Editar temas">
                          <img src="{{ asset('img/pencil.png') }}" alt="Editar temas" style="width:16px;height:16px;" />
                        </button>
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
                        <button type="button" class="btn btn-ejemplo" style="padding: 7px 12px;" onclick="abrirModalOrigenes('{{ route('origenes.index') }}')" title="Editar orígenes" aria-label="Editar orígenes">
                          <img src="{{ asset('img/pencil.png') }}" alt="Editar orígenes" style="width:16px;height:16px;" />
                        </button>
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
            </div>
        </form>
    </div>
</div>

<!-- Modal para editar/agregar tipos -->
<div id="modal-overlay-tipos" class="modal-overlay" onclick="cerrarModalTiposClick(event)">
  <div class="modal-content" onclick="event.stopPropagation()">
    <div class="modal-header">
      <h3>Tipos de documento</h3>
      <button type="button" class="close-modal-btn" onclick="cerrarModalTipos()">x</button>
    </div>
    <div class="modal-body">
      <iframe id="modal-iframe-tipos" src="about:blank" title="Tipos"></iframe>
    </div>
  </div>
  </div>

<!-- Modal para editar/agregar libros -->
<div id="modal-overlay-libros" class="modal-overlay" onclick="cerrarModalLibrosClick(event)">
  <div class="modal-content" onclick="event.stopPropagation()">
    <div class="modal-header">
      <h3>Libros</h3>
      <button type="button" class="close-modal-btn" onclick="cerrarModalLibros()">x</button>
    </div>
    <div class="modal-body">
      <iframe id="modal-iframe-libros" src="about:blank" title="Libros"></iframe>
    </div>
  </div>
</div>

<!-- Modal para editar/agregar temas -->
<div id="modal-overlay-temas" class="modal-overlay" onclick="cerrarModalTemasClick(event)">
  <div class="modal-content" onclick="event.stopPropagation()">
    <div class="modal-header">
      <h3>Temas</h3>
      <button type="button" class="close-modal-btn" onclick="cerrarModalTemas()">x</button>
    </div>
    <div class="modal-body">
      <iframe id="modal-iframe-temas" src="about:blank" title="Temas"></iframe>
    </div>
  </div>
</div>

<!-- Modal para editar/agregar orígenes -->
<div id="modal-overlay-origenes" class="modal-overlay" onclick="cerrarModalOrigenesClick(event)">
  <div class="modal-content" onclick="event.stopPropagation()">
    <div class="modal-header">
      <h3>Orígenes</h3>
      <button type="button" class="close-modal-btn" onclick="cerrarModalOrigenes()">x</button>
    </div>
    <div class="modal-body">
      <iframe id="modal-iframe-origenes" src="about:blank" title="Orígenes"></iframe>
    </div>
  </div>
</div>

<script>
function abrirModalTipos(url){
  try{
    const iframe = document.getElementById('modal-iframe-tipos');
    iframe.src = url + (url.includes('?') ? '&' : '?') + 'modal=1';
    document.getElementById('modal-overlay-tipos').classList.add('active');
  }catch(e){}
}
function cerrarModalTipos(){
  try{
    const overlay = document.getElementById('modal-overlay-tipos');
    const iframe = document.getElementById('modal-iframe-tipos');
    overlay.classList.remove('active');
    iframe.src = 'about:blank';
  }catch(e){}
}
function cerrarModalTiposClick(event){ if(event && event.target && event.target.id==='modal-overlay-tipos'){ cerrarModalTipos(); } }
window.addEventListener('message', function(ev){
  const d = ev && ev.data ? ev.data : {};
  if(d.type === 'modal-close' || d.type === 'close-modal'){
    cerrarModalTipos();
    cerrarModalLibros();
    cerrarModalTemas();
    cerrarModalOrigenes();
    if (d.reload || d.saved) { window.location.reload(); }
  }
});

function abrirModalLibros(url){
  try{
    const iframe = document.getElementById('modal-iframe-libros');
    iframe.src = url + (url.includes('?') ? '&' : '?') + 'modal=1';
    document.getElementById('modal-overlay-libros').classList.add('active');
  }catch(e){}
}
function cerrarModalLibros(){
  try{
    const overlay = document.getElementById('modal-overlay-libros');
    const iframe = document.getElementById('modal-iframe-libros');
    overlay.classList.remove('active');
    iframe.src = 'about:blank';
  }catch(e){}
}
function cerrarModalLibrosClick(event){ if(event && event.target && event.target.id==='modal-overlay-libros'){ cerrarModalLibros(); } }

function abrirModalTemas(url){
  try{
    const iframe = document.getElementById('modal-iframe-temas');
    iframe.src = url + (url.includes('?') ? '&' : '?') + 'modal=1';
    document.getElementById('modal-overlay-temas').classList.add('active');
  }catch(e){}
}
function cerrarModalTemas(){
  try{
    const overlay = document.getElementById('modal-overlay-temas');
    const iframe = document.getElementById('modal-iframe-temas');
    overlay.classList.remove('active');
    iframe.src = 'about:blank';
  }catch(e){}
}
function cerrarModalTemasClick(event){ if(event && event.target && event.target.id==='modal-overlay-temas'){ cerrarModalTemas(); } }

function abrirModalOrigenes(url){
  try{
    const iframe = document.getElementById('modal-iframe-origenes');
    iframe.src = url + (url.includes('?') ? '&' : '?') + 'modal=1';
    document.getElementById('modal-overlay-origenes').classList.add('active');
  }catch(e){}
}
function cerrarModalOrigenes(){
  try{
    const overlay = document.getElementById('modal-overlay-origenes');
    const iframe = document.getElementById('modal-iframe-origenes');
    overlay.classList.remove('active');
    iframe.src = 'about:blank';
  }catch(e){}
}
function cerrarModalOrigenesClick(event){ if(event && event.target && event.target.id==='modal-overlay-origenes'){ cerrarModalOrigenes(); } }
</script>

@endsection

