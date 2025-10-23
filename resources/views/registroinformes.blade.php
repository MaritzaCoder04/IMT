@extends('home')

@section('contenido')


<div class="all-form">
    <div class="form-container">
        <form id="formularioLineasAccion" action="{{ route('formulario.guardar') }}" method="POST">
            @csrf
            <div class="form-group">
            <div class="section-title">Informes Realizados por Bimestre</div>
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
    </div>&nbsp

    <div class="form-container">
        <!-- Resultados Calculados -->
        <div class="resultados-section">
            <div class="section-title">Resultados</div>
            <div class="resultados-grid">
                <div class="resultado-item">
                    <label>Acumulado Real</label>
                    <div class="resultado-valor" id="acumuladoReal">0</div>
                </div>
                <div class="resultado-item">
                    <label>% Avance Bimestral</label>
                    <div class="resultado-valor" id="porcentajeBimestral">0%</div>
                </div>
                <div class="resultado-item">
                    <label>% Avance Anual</label>
                    <div class="resultado-valor" id="porcentajeAnual">0%</div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Función para calcular totales y porcentajes
    function calcularResultados() {
        // Obtener valores programados
        let totalProgramadoBimestral = 0;
        let totalRealizadoBimestral = 0;
        
        for (let i = 1; i <= 6; i++) {
            const programado = parseFloat(document.getElementById(`programado_b${i}`).value) || 0;
            const realizado = parseFloat(document.getElementById(`realizado_b${i}`).value) || 0;
            totalProgramadoBimestral += programado;
            totalRealizadoBimestral += realizado;
        }
        
        const metaAnual = parseFloat(document.getElementById('metaAnual').value) || 0;
        
        // Calcular acumulado real
        document.getElementById('acumuladoReal').textContent = totalRealizadoBimestral.toFixed(2);
        
        // Calcular porcentaje bimestral (realizado vs programado bimestral)
        const porcentajeBimestral = totalProgramadoBimestral > 0 
            ? (totalRealizadoBimestral / totalProgramadoBimestral * 100).toFixed(2)
            : 0;
        document.getElementById('porcentajeBimestral').textContent = porcentajeBimestral + '%';
        
        // Calcular porcentaje anual (realizado vs meta anual)
        const porcentajeAnual = metaAnual > 0 
            ? (totalRealizadoBimestral / metaAnual * 100).toFixed(2)
            : 0;
        document.getElementById('porcentajeAnual').textContent = porcentajeAnual + '%';
    }
    
    // Agregar event listeners a todos los inputs numéricos
    document.addEventListener('DOMContentLoaded', function() {
        const inputsNumericos = document.querySelectorAll('input[type="number"]');
        inputsNumericos.forEach(input => {
            input.addEventListener('input', calcularResultados);
        });
        
        // Calcular al cargar
        calcularResultados();
    });
    
    // Manejar el envío del formulario
    document.getElementById('formularioLineasAccion').addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Validar que la suma de programados no exceda la meta anual
        let totalProgramado = 0;
        for (let i = 1; i <= 6; i++) {
            totalProgramado += parseFloat(document.getElementById(`programado_b${i}`).value) || 0;
        }
        
        const metaAnual = parseFloat(document.getElementById('metaAnual').value) || 0;
        
        if (totalProgramado > metaAnual) {
            alert('Advertencia: La suma de los informes programados bimestralmente (' + totalProgramado + 
                  ') excede la meta anual (' + metaAnual + ')');
        }
        
        alert('La línea de acción se registró correctamente\n\n' +
              'Línea de Acción: ' + document.getElementById('lineaAccion').value + '\n' +
              'Acumulado Real: ' + document.getElementById('acumuladoReal').textContent + '\n' +
              'Avance Bimestral: ' + document.getElementById('porcentajeBimestral').textContent + '\n' +
              'Avance Anual: ' + document.getElementById('porcentajeAnual').textContent);
    });
</script>
@endsection