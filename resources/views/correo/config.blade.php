<div class="container" style="padding: 16px;">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <h3>Configurar notificaciones por correo</h3>
    <p>Ingrese el correo destinatario y un mensaje personalizado para las alertas automáticas.</p>

    <form method="POST" action="{{ route('correo.guardar', ['modal' => request()->query('modal')]) }}">
        @csrf
        <div class="form-group" style="margin-bottom:12px;">
            <label for="email">Correo destinatario</label>
            <input type="email" id="email" name="email" value="{{ old('email', $email) }}" required style="width:100%; padding:8px;">
            @error('email') <div class="alert alert-danger">{{ $message }}</div> @enderror
        </div>
        <div class="form-group" style="margin-bottom:12px;">
            <label for="mensaje">Mensaje personalizado (opcional)</label>
            <textarea id="mensaje" name="mensaje" rows="4" style="width:100%; padding:8px;">{{ old('mensaje', $mensaje) }}</textarea>
            @error('mensaje') <div class="alert alert-danger">{{ $message }}</div> @enderror
        </div>
        <div style="display:flex; gap:8px;">
            <button type="submit" class="btn btn-ejemplo">Guardar</button>
            <button type="button" class="btn btn-secundario" onclick="window.parent.postMessage({type:'modal-close'}, '*')">Cancelar</button>
        </div>
    </form>
</div>