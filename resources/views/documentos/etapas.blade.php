
@extends('home')
@section('contenido')
<div class="all-form">
        <div class="doc-summary" style="margin: 8px 0 12px 0; display: flex; gap: 24px; align-items: center;">
            <div><strong>Documento:</strong> {{ $documento->nombre ?? '--' }}</div>
            <div><strong>Designación:</strong> {{ $documento->info->designacion ?? '--' }}</div>
        </div>
        
        
        <form id="fechasForm" action="{{ route('fechas.guardar', $documento->ID_doc) }}" method="POST">
            @csrf
            <input type="hidden" name="modal" value="{{ request('modal') ? 1 : '' }}">
        
            @php
                $completadas = \App\Models\EtapaEvento::where('ID_doc', $documento->ID_doc)->pluck('etapa')->toArray();
            @endphp
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
                        <td class="check-inline {{ in_array('1a', $completadas) ? 'completed-cell' : '' }}">
                            <input type="date" class="date-input" name="etapa1_periodo1" value="{{ $etapas->{"1a"} ?? '' }}">
                            <label class="check-label" title="Marcar inicio completado">
                                <input type="checkbox" name="complete_1a" {{ in_array('1a', $completadas) ? 'checked' : '' }}> ✔️
                            </label>
                        </td>
                        <td class="check-inline {{ in_array('2a', $completadas) ? 'completed-cell' : '' }}">
                            <input type="date" class="date-input" name="etapa1_periodo2" value="{{ $etapas->{"2a"} ?? '' }}">
                            <label class="check-label" title="Marcar entrega completada">
                                <input type="checkbox" name="complete_2a" {{ in_array('2a', $completadas) ? 'checked' : '' }}> ✔️
                            </label>
                        </td>
                        <td class="check-inline {{ in_array('3a', $completadas) ? 'completed-cell' : '' }}">
                            <input type="date" class="date-input" name="etapa1_periodo3" value="{{ $etapas->{"3a"} ?? '' }}">
                            <label class="check-label" title="Marcar terminación completada">
                                <input type="checkbox" name="complete_3a" {{ in_array('3a', $completadas) ? 'checked' : '' }}> ✔️
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <td class="etapa-label">
                            <span class="status-dot" id="dot-2"></span>
                            AFT
                        </td>
                        <td class="check-inline {{ in_array('1b', $completadas) ? 'completed-cell' : '' }}">
                            <input type="date" class="date-input" name="etapa2_periodo1" value="{{ $etapas->{"1b"} ?? '' }}">
                            <label class="check-label" title="Marcar inicio completado">
                                <input type="checkbox" name="complete_1b" {{ in_array('1b', $completadas) ? 'checked' : '' }}> ✔️
                            </label>
                        </td>
                        <td class="check-inline {{ in_array('2b', $completadas) ? 'completed-cell' : '' }}">
                            <input type="date" class="date-input" name="etapa2_periodo2" value="{{ $etapas->{"2b"} ?? '' }}">
                            <label class="check-label" title="Marcar entrega completada">
                                <input type="checkbox" name="complete_2b" {{ in_array('2b', $completadas) ? 'checked' : '' }}> ✔️
                            </label>
                        </td>
                        <td class="check-inline {{ in_array('3b', $completadas) ? 'completed-cell' : '' }}">
                            <input type="date" class="date-input" name="etapa2_periodo3" value="{{ $etapas->{"3b"} ?? '' }}">
                            <label class="check-label" title="Marcar terminación completada">
                                <input type="checkbox" name="complete_3b" {{ in_array('3b', $completadas) ? 'checked' : '' }}> ✔️
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <td class="etapa-label">
                            <span class="status-dot" id="dot-3"></span>
                            PPT
                        </td>
                        <td class="check-inline {{ in_array('1c', $completadas) ? 'completed-cell' : '' }}">
                            <input type="date" class="date-input" name="etapa3_periodo1" value="{{ $etapas->{"1c"} ?? '' }}">
                            <label class="check-label" title="Marcar inicio completado">
                                <input type="checkbox" name="complete_1c" {{ in_array('1c', $completadas) ? 'checked' : '' }}> ✔️
                            </label>
                        </td>
                        <td class="check-inline {{ in_array('2c', $completadas) ? 'completed-cell' : '' }}">
                            <input type="date" class="date-input" name="etapa3_periodo2" value="{{ $etapas->{"2c"} ?? '' }}">
                            <label class="check-label" title="Marcar entrega completada">
                                <input type="checkbox" name="complete_2c" {{ in_array('2c', $completadas) ? 'checked' : '' }}> ✔️
                            </label>
                        </td>
                        <td class="check-inline {{ in_array('3c', $completadas) ? 'completed-cell' : '' }}">
                            <input type="date" class="date-input" name="etapa3_periodo3" value="{{ $etapas->{"3c"} ?? '' }}">
                            <label class="check-label" title="Marcar terminación completada">
                                <input type="checkbox" name="complete_3c" {{ in_array('3c', $completadas) ? 'checked' : '' }}> ✔️
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <td class="etapa-label">
                            <span class="status-dot" id="dot-4"></span>
                            NA
                        </td>
                        <td class="check-inline {{ in_array('1d', $completadas) ? 'completed-cell' : '' }}">
                            <input type="date" class="date-input" name="etapa4_periodo1" value="{{ $etapas->{"1d"} ?? '' }}">
                            <label class="check-label" title="Marcar inicio completado">
                                <input type="checkbox" name="complete_1d" {{ in_array('1d', $completadas) ? 'checked' : '' }}> ✔️
                            </label>
                        </td>
                        <td class="check-inline {{ in_array('2d', $completadas) ? 'completed-cell' : '' }}">
                            <input type="date" class="date-input" name="etapa4_periodo2" value="{{ $etapas->{"2d"} ?? '' }}">
                            <label class="check-label" title="Marcar entrega completada">
                                <input type="checkbox" name="complete_2d" {{ in_array('2d', $completadas) ? 'checked' : '' }}> ✔️
                            </label>
                        </td>
                        <td class="check-inline {{ in_array('3d', $completadas) ? 'completed-cell' : '' }}">
                            <input type="date" class="date-input" name="etapa4_periodo3" value="{{ $etapas->{"3d"} ?? '' }}">
                            <label class="check-label" title="Marcar terminación completada">
                                <input type="checkbox" name="complete_3d" {{ in_array('3d', $completadas) ? 'checked' : '' }}> ✔️
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <td class="etapa-label">
                            <span class="status-dot" id="dot-5"></span>
                            NP
                        </td>
                        <td class="check-inline {{ in_array('1e', $completadas) ? 'completed-cell' : '' }}">
                            <input type="date" class="date-input" name="etapa5_periodo1" value="{{ $etapas->{"1e"} ?? '' }}">
                            <label class="check-label" title="Marcar inicio completado">
                                <input type="checkbox" name="complete_1e" {{ in_array('1e', $completadas) ? 'checked' : '' }}> ✔️
                            </label>
                        </td>
                        <td class="check-inline {{ in_array('2e', $completadas) ? 'completed-cell' : '' }}">
                            <input type="date" class="date-input" name="etapa5_periodo2" value="{{ $etapas->{"2e"} ?? '' }}">
                            <label class="check-label" title="Marcar entrega completada">
                                <input type="checkbox" name="complete_2e" {{ in_array('2e', $completadas) ? 'checked' : '' }}> ✔️
                            </label>
                        </td>
                        <td class="check-inline {{ in_array('3e', $completadas) ? 'completed-cell' : '' }}">
                            <input type="date" class="date-input" name="etapa5_periodo3" value="{{ $etapas->{"3e"} ?? '' }}">
                            <label class="check-label" title="Marcar terminación completada">
                                <input type="checkbox" name="complete_3e" {{ in_array('3e', $completadas) ? 'checked' : '' }}> ✔️
                            </label>
                        </td>
                    </tr>
                </tbody>
            </table>

            <div class="actions">
                <button type="submit" class="btn btn-secondary">Guardar</button>
                <button type="button" class="btn btn-secondary2" onclick="limpiarFormulario()">Limpiar</button>
            </div>

        </form>
    </div>
  </div>


    <script>
        let fechasData = {};

        function actualizarEstadisticas() {
            const checkEtapas = ['2a','2b','2c','2d','2e','3a','3b','3c','3d','3e'];
            let completadas = 0;

            // Contar solo fechas con check activo y con valor dentro de entrega/terminación (2x y 3x)
            checkEtapas.forEach(c => {
                const checkbox = document.querySelector(`input[name="complete_${c}"]`);
                const dateInput = (c[0] === '2') ? document.querySelector(`input[name="etapa${c[1]}_periodo2"]`) : document.querySelector(`input[name="etapa${c[1]}_periodo3"]`);
                const td = checkbox ? checkbox.closest('td') : null;
                const ok = checkbox && checkbox.checked && dateInput && !!dateInput.value;
                if (td) { td.classList.toggle('completed-cell', !!ok); }
                if (ok) { completadas++; }
            });

            const progreso = Math.round((completadas / 10) * 100);

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

            // Sombrear inicio (1x) cuando hay check y fecha, sin afectar progreso
            ['1a','1b','1c','1d','1e'].forEach(c => {
                const checkbox = document.querySelector(`input[name="complete_${c}"]`);
                const dateInput = document.querySelector(`input[name="etapa${c[1]}_periodo1"]`);
                const td = checkbox ? checkbox.closest('td') : null;
                const ok = checkbox && checkbox.checked && dateInput && !!dateInput.value;
                if (td) { td.classList.toggle('completed-cell', !!ok); }
            });
        }

        // Event listeners para inputs
        document.querySelectorAll('.date-input').forEach(input => {
            input.addEventListener('change', function() {
                fechasData[this.name] = this.value;
                actualizarEstadisticas();
            });
        });
        document.querySelectorAll('input[type="checkbox"][name^="complete_"]').forEach(chk => {
            chk.addEventListener('change', actualizarEstadisticas);
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