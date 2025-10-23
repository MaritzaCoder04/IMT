@extends('home')
@section('contenido')

<div class="all-form">
    <div class="form-container">
        <form id="formularioLineasAccion" action="{{ route('formulario.guardar') }}" method="POST">
            @csrf
            <div class="form-group">
            <div class="section-title">Informes Realizados por Bimestre</div>
            <button type="submit" class="btn-primary">Agregar</button>
                    <label for="unidadMedida">Linea de Acción</label>
                    <select id="unidadMedida" name="unidadMedida" required>
                        <option value="">Selecciona una linea de acción</option>
                        <option value="l1">LA 1</option>
                        <option value="l2">LA 2</option>
                    </select>
            </div>

            <!-- Informes Realizados por Bimestre -->
            <div class="form-group">
                <label for="unidadMedida">Informes Realizados</label>
                <div class="bimestres-section">
                <div class="bimestres-grid">
                    <div class="bimestre-item">
                        <label>Enero - Febrero</label>
                        <input type="number" name="realizado_b1" id="realizado_b1" 
                               placeholder="0" min="0" step="0.01" value="0">
                    </div>
                    <div class="bimestre-item">
                        <label>Marzo - Abril</label>
                        <input type="number" name="realizado_b2" id="realizado_b2" 
                               placeholder="0" min="0" step="0.01" value="0">
                    </div>
                    <div class="bimestre-item">
                        <label>Mayo - Junio</label>
                        <input type="number" name="realizado_b3" id="realizado_b3" 
                               placeholder="0" min="0" step="0.01" value="0">
                    </div>
                    <div class="bimestre-item">
                        <label>Julio - Agosto</label>
                        <input type="number" name="realizado_b4" id="realizado_b4" 
                               placeholder="0" min="0" step="0.01" value="0">
                    </div>
                    <div class="bimestre-item">
                        <label>Septiembre - Octubre</label>
                        <input type="number" name="realizado_b5" id="realizado_b5" 
                               placeholder="0" min="0" step="0.01" value="0">
                    </div>
                    <div class="bimestre-item">
                        <label>Noviembre - Diciembre</label>
                        <input type="number" name="realizado_b6" id="realizado_b6" 
                               placeholder="0" min="0" step="0.01" value="0">
                    </div>
                </div>
                </div>
            </div>

            <!-- Observaciones -->
            <div class="form-group">
                <label for="observaciones">Observaciones</label>
                <textarea id="observaciones" name="observaciones" 
                          placeholder="Ingrese observaciones o comentarios sobre el seguimiento..."></textarea>
            </div>

            <button type="submit" class="submit-btn">Guardar Cambios</button>
        </form>
    </div>
</div>

@endsection