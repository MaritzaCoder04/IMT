@extends('home')

@section('contenido')

<div class="all-form">
        
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
        
        <form action="{{ route('grupotrabajo.updateReunion', $reunion->id) }}" method="POST">
            @csrf
            @method('PUT')
            @if(request('modal'))
                <input type="hidden" name="modal" value="1">
            @endif
            @php
                // Cuando el editor se abre desde Agenda de Productos, limitar opciones del select
                $gruposMostrar = $grupos;
                if (request()->get('scope') === 'productos') {
                    $soloNombres = [
                        'Anteproyecto Preliminar',
                        'Anteproyecto Final',
                        'Proyecto Preliminar',
                        'Publicación de Manuales/Normas',
                    ];
                    try {
                        $gruposMostrar = collect($grupos)->filter(function($g) use ($soloNombres){
                            return in_array($g->nombre, $soloNombres);
                        });
                    } catch (\Throwable $e) {
                        $gruposMostrar = $grupos;
                    }
                }
            @endphp
            @php
                // Cuando el editor se abre desde Agenda de Representaciones, limitar opciones a Subcomité No.4 y GT1
                if (request()->get('scope') === 'representaciones') {
                    $soloNombresRep = [
                        'Subcomité No.4',
                        'Grupo de Trabajo 1',
                    ];
                    try {
                        $gruposMostrar = collect($grupos)->filter(function($g) use ($soloNombresRep){
                            return in_array($g->nombre, $soloNombresRep);
                        });
                    } catch (\Throwable $e) {
                        // Si algo falla, mantener lista original
                    }
                }
            @endphp
            
            <div class="form-group">
                <label for="grupo_trabajo_id">Grupo de Trabajo</label>
                <select id="grupo_trabajo_id" name="grupo_trabajo_id" required>
                    <option value="">Selecciona un grupo...</option>
                    @foreach($gruposMostrar as $grupo)
                        <option value="{{ $grupo->id }}" {{ $reunion->grupo_trabajo_id == $grupo->id ? 'selected' : '' }}>
                            {{ $grupo->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="fecha">Fecha de la Reunión</label>
                <input type="date" id="fecha" name="fecha" value="{{ $reunion->fecha->format('Y-m-d') }}" required>
            </div>

            <div class="form-group">
                <label>Tipo de Reunión</label>
                <div class="radio-group">
                    <label class="radio-option">
                        <input type="radio" name="programada" value="1" {{ $reunion->programada ? 'checked' : '' }} onchange="toggleMotivo(this)">
                        Reunión Programada
                    </label>
                    <label class="radio-option">
                        <input type="radio" name="programada" value="0" {{ !$reunion->programada ? 'checked' : '' }} onchange="toggleMotivo(this)">
                        Fuera de Programación
                    </label>
                </div>
            </div>

            <div class="form-group" id="motivo-programada" style="{{ $reunion->programada ? 'display: block;' : 'display: none;' }}">
                <label for="motivo_programada">¿Por qué fue agregada esta reunión?</label>
                <textarea id="motivo_programada" name="motivo" rows="3" placeholder="Describe el motivo de la reunión programada...">{{ $reunion->programada ? $reunion->motivo : '' }}</textarea>
            </div>

            <div class="form-group" id="motivo-noprogramada" style="{{ !$reunion->programada ? 'display: block;' : 'display: none;' }}">
                <label for="motivo_noprogramada">¿Por qué fue fuera de programación?</label>
                <textarea id="motivo_noprogramada" name="motivo" rows="3" placeholder="Explica por qué esta reunión fue fuera de la programación original..." {{ !$reunion->programada ? 'required' : '' }}>{{ !$reunion->programada ? $reunion->motivo : '' }}</textarea>
            </div>

            <div class="form-actions" style="display:flex; justify-content:flex-end; gap:0.625rem; margin-top:1rem;">
                <button type="submit" style="background: #3b82f6; color: white; padding: 0.625rem 1.5rem; border: none; border-radius: 6px; font-size: 0.875rem; font-weight: 500; cursor: pointer; transition: background 0.2s;" onmouseover="this.style.background='#2563eb'" onmouseout="this.style.background='#3b82f6'">Actualizar Reunión</button>
            </div>
        </form>
    </div>
</div>

<script>
function toggleMotivo(radio) {
    const motivoProgramada = document.getElementById('motivo-programada');
    const motivoNoProgramada = document.getElementById('motivo-noprogramada');
    const textareaNoProgramada = document.getElementById('motivo_noprogramada');
    const textareaProgramada = document.getElementById('motivo_programada');
    
    if (radio.value === '1') {
        motivoProgramada.style.display = 'block';
        motivoNoProgramada.style.display = 'none';
        textareaNoProgramada.removeAttribute('required');
        textareaProgramada.name = 'motivo';
        textareaNoProgramada.name = '';
    } else {
        motivoProgramada.style.display = 'none';
        motivoNoProgramada.style.display = 'block';
        textareaNoProgramada.setAttribute('required', 'required');
        textareaNoProgramada.name = 'motivo';
        textareaProgramada.name = '';
    }
}
</script>

<!-- Autocierre cuando se carga dentro de iframe con saved=1 -->
<script>
(function(){
  try {
    const params = new URLSearchParams(window.location.search);
    const isIframe = window.top !== window.self;
    if (isIframe && params.get('modal') === '1' && params.get('saved') === '1') {
      window.parent && window.parent.postMessage({ type: 'modal-close', reload: true }, '*');
    }
  } catch (e) {}
})();
</script>

@endsection