
@extends('home')

@section('contenido')
<div class="all-form">
    <div class="form-container">
        <div class="section-title2">Gestión de Fechas</div>
        <div class="progress-bar">
            <div class="progress-fill" id="progressFill"></div>
        </div>
        
        <div class="progress-info">
            <span1 id="completadas">0</span1> de <span1 id="total">15</span1> fechas completadas
        </div>

        
        <form id="fechasForm" action="{{ route('fechas.guardar', $documento->ID_doc) }}" method="POST">
            @csrf
        
            <table>
                <thead>
                    <tr>
                        <th></th>
                        <th>Fecha Inicio</th>
                        <th>Fecha Entrega</th>
                        <th>Fecha Terminación</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="etapa-label">
                            <span class="status-dot" id="dot-1"></span>
                            APT
                        </td>
                        <td><input type="date" class="date-input" name="etapa1_periodo1" value="{{ $etapas->{"1a"} ?? '' }}"></td>
                        <td><input type="date" class="date-input" name="etapa1_periodo2" value="{{ $etapas->{"2a"} ?? '' }}"></td>
                        <td><input type="date" class="date-input" name="etapa1_periodo3" value="{{ $etapas->{"3a"} ?? '' }}"></td>
                    </tr>
                    <tr>
                        <td class="etapa-label">
                            <span class="status-dot" id="dot-2"></span>
                            AFT
                        </td>
                        <td><input type="date" class="date-input" name="etapa2_periodo1" value="{{ $etapas->{"1b"} ?? '' }}"></td>
                        <td><input type="date" class="date-input" name="etapa2_periodo2" value="{{ $etapas->{"2b"} ?? '' }}"></td>
                        <td><input type="date" class="date-input" name="etapa2_periodo3" value="{{ $etapas->{"3b"} ?? '' }}"></td>
                    </tr>
                    <tr>
                        <td class="etapa-label">
                            <span class="status-dot" id="dot-3"></span>
                            PPT
                        </td>
                        <td><input type="date" class="date-input" name="etapa3_periodo1" value="{{ $etapas->{"1c"} ?? '' }}"></td>
                        <td><input type="date" class="date-input" name="etapa3_periodo2" value="{{ $etapas->{"2c"} ?? '' }}"></td>
                        <td><input type="date" class="date-input" name="etapa3_periodo3" value="{{ $etapas->{"3c"} ?? '' }}"></td>
                    </tr>
                    <tr>
                        <td class="etapa-label">
                            <span class="status-dot" id="dot-4"></span>
                            NA
                        </td>
                        <td><input type="date" class="date-input" name="etapa4_periodo1" value="{{ $etapas->{"1d"} ?? '' }}"></td>
                        <td><input type="date" class="date-input" name="etapa4_periodo2" value="{{ $etapas->{"2d"} ?? '' }}"></td>
                        <td><input type="date" class="date-input" name="etapa4_periodo3" value="{{ $etapas->{"3d"} ?? '' }}"></td>
                    </tr>
                    <tr>
                        <td class="etapa-label">
                            <span class="status-dot" id="dot-5"></span>
                            NP
                        </td>
                        <td><input type="date" class="date-input" name="etapa5_periodo1" value="{{ $etapas->{"1e"} ?? '' }}"></td>
                        <td><input type="date" class="date-input" name="etapa5_periodo2" value="{{ $etapas->{"2e"} ?? '' }}"></td>
                        <td><input type="date" class="date-input" name="etapa5_periodo3" value="{{ $etapas->{"3e"} ?? '' }}"></td>
                    </tr>
                </tbody>
            </table>

            <div class="actions">
                <button type="submit" class="btn btn-secondary">Guardar</button>
                <button type="button" class="btn btn-secondary2" onclick="limpiarFormulario()">Limpiar</button>
                <button type="button" class="btn btn-secondary2" onclick="window.location='{{ route('controldeavances') }}'">Cancelar</button>
            </div>

        </form>
    </div>
  </div>


    <script>
        let fechasData = {};

        function actualizarEstadisticas() {
            const inputs = document.querySelectorAll('.date-input');
            let completadas = 0;
            
            inputs.forEach(input => {
                if (input.value) {
                    input.classList.add('filled');
                    completadas++;
                } else {
                    input.classList.remove('filled');
                }
            });

            const progreso = Math.round((completadas / 15) * 100);
            
            document.getElementById('completadas').textContent = completadas;
            document.getElementById('progressFill').style.width = progreso + '%';

            // Actualizar puntos de estado por etapa
            for (let i = 1; i <= 5; i++) {
                const etapaInputs = document.querySelectorAll(`[name^="etapa${i}_"]`);
                const etapaCompleta = Array.from(etapaInputs).every(input => input.value);
                const dot = document.getElementById(`dot-${i}`);
                
                if (etapaCompleta) {
                    dot.classList.add('complete');
                } else {
                    dot.classList.remove('complete');
                }
            }
        }

        // Event listeners para inputs
        document.querySelectorAll('.date-input').forEach(input => {
            input.addEventListener('change', function() {
                fechasData[this.name] = this.value;
                actualizarEstadisticas();
            });
        });

        // Limpiar formulario
        function limpiarFormulario() {
            if (confirm('¿Limpiar todas las fechas?')) {
                document.querySelectorAll('.date-input').forEach(input => {
                    input.value = '';
                });
                fechasData = {};
                actualizarEstadisticas();
            }
        }

        // Envío del formulario
        document.getElementById('fechasForm').addEventListener('submit', function(e) {
            /*e.preventDefault();
            
            const formData = new FormData(this);
            const datos = {};
            
            for (let [key, value] of formData.entries()) {
                if (value) {
                    datos[key] = value;
                }
            }
            
            alert(`Fechas guardadas: ${Object.keys(datos).length}/15`);*/
        });

        // Inicializar
        actualizarEstadisticas();
    </script>
@endsection