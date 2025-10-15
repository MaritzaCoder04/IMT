@extends('home')

@section('contenido')


<div class="all-form">
    <div class="form-container">
        <form id="formularioLineasAccion" action="{{ route('formulario.guardar') }}" method="POST">
            @csrf
            
            <!-- Información Básica -->
            <div class="section-title">Información de la Línea de Acción</div>
            
            <div class="form-group">
                <label for="lineaAccion">Línea de Acción</label>
                <input type="text" id="lineaAccion" name="lineaAccion" 
                       placeholder="Ej: Infraestructura Vial" required>
            </div>

            <div class="form-group">
                <label for="unidadMedida">Unidad de Medida</label>
                <select id="unidadMedida" name="unidadMedida" required>
                    <option value="">Selecciona la unidad de medida</option>
                    <option value="pt">Producto Terminado</option>
                    <option value="ap">Anteproyecto Preliminar</option>
                    <option value="af">Anteproyecto Final</option>
                    <option value="pp">Proyecto Preliminar</option>
                    <option value="nm">Norma y/o Manual</option>
                    <option value="re">Reunión</option>
                    <option value="otro">Otro</option>
                </select>
            </div>

            <div class="form-group">
                <label for="metaAnual">Meta Anual Programada</label>
                <input type="number" id="metaAnual" name="metaAnual" 
                       placeholder="Ej: 12" min="0" step="0.01" required>
            </div>

            <!-- Informes Programados por Bimestre -->
            <div class="bimestres-section">
                <div class="section-title">Informes Programados por Bimestre</div>
                <div class="bimestres-grid">
                    <div class="bimestre-item">
                        <label>Enero - Febrero</label>
                        <input type="number" name="programado_b1" id="programado_b1" 
                               placeholder="0" min="0" step="0.01" value="0">
                    </div>
                    <div class="bimestre-item">
                        <label>Marzo - Abril</label>
                        <input type="number" name="programado_b2" id="programado_b2" 
                               placeholder="0" min="0" step="0.01" value="0">
                    </div>
                    <div class="bimestre-item">
                        <label>Mayo - Junio</label>
                        <input type="number" name="programado_b3" id="programado_b3" 
                               placeholder="0" min="0" step="0.01" value="0">
                    </div>
                    <div class="bimestre-item">
                        <label>Julio - Agosto</label>
                        <input type="number" name="programado_b4" id="programado_b4" 
                               placeholder="0" min="0" step="0.01" value="0">
                    </div>
                    <div class="bimestre-item">
                        <label>Septiembre - Octubre</label>
                        <input type="number" name="programado_b5" id="programado_b5" 
                               placeholder="0" min="0" step="0.01" value="0">
                    </div>
                    <div class="bimestre-item">
                        <label>Noviembre - Diciembre</label>
                        <input type="number" name="programado_b6" id="programado_b6" 
                               placeholder="0" min="0" step="0.01" value="0">
                    </div>
                </div>
            </div>

            <button type="submit" class="submit-btn">Registrar Línea de Acción</button>
        </form>
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


