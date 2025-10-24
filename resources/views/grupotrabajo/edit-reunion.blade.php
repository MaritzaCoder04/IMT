@extends('home')

@section('contenido')

<div class="all-form">
    <div class="form-container">
        <div class="section-title">✏️ Editar Reunión</div>
        
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
            
            <div class="form-group">
                <label for="grupo_trabajo_id">Grupo de Trabajo</label>
                <select id="grupo_trabajo_id" name="grupo_trabajo_id" required>
                    <option value="">Selecciona un grupo...</option>
                    @foreach($grupos as $grupo)
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
                        <span>✅ Reunión Programada</span>
                    </label>
                    <label class="radio-option">
                        <input type="radio" name="programada" value="0" {{ !$reunion->programada ? 'checked' : '' }} onchange="toggleMotivo(this)">
                        <span>⚠️ Fuera de Programación</span>
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

            <div class="form-actions">
                <button type="button" class="btn btn-secondary" onclick="window.history.back()">
                    ❌ Cancelar
                </button>
                <button type="submit" class="btn btn-primary">
                    ✅ Actualizar Reunión
                </button>
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

@endsection