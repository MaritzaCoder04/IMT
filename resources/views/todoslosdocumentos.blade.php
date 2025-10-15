
@extends('home')
@section('contenido') 
<div class="all-form"> 
    <div class="form-container">
    <div class="container">
        <div class="layout">
            <main class="main-content">
                <!-- Sección Búsqueda -->
                <section id="busqueda" class="section active">
                    <div class="section-header">
                        <h2 class="section-title">Filtros</h2>
                    </div>
                    <form id="formBusqueda">
                    <div class="search-container">
                        <div class="search-row">
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="cp">Cualquier Palabra</label>
                                    <input type="text" id="cp" name="cp" placeholder="Ingrese cualquier palabra">
                                </div>
                                <div class="form-group">
                                    <label for="designacion">Designación</label>
                                    <input type="text" id="designacion"readonly>
                                </div>
                                <div class="form-group">
                                    <label for="libro">Libro</label>
                                    <select id="libro" name="libro">
                                        <option value="">Selecciona un libro</option>
                                        <option value="pry">Opción 1</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="parte">Parte</label>
                                    <select id="parte" name="parte">
                                        <option value="">Selecciona una parte</option>
                                        <option value="pr">Opción 1</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="time">Año</label>
                                    <input type="text" id="time" name="time" placeholder="Ej: 2010">
                                </div>
                            </div>
                            {{--
                            <div class="form-row">
                                    <label>
                                        <input type="checkbox" id="uv" name="uv">
                                        Ultima Versión
                                      </label> 
                            </div>--}}              
                            <div class="actions">
                                <button type="submit" class="btn btn-secondary">Buscar</button>
                                <button type="button" class="btn btn-secondary2" onclick="window.location='{{ route('todoslosdocumentos') }}'">Limpiar</button>
                            </div>
                    </div>
                    
                    <div id="resultadosBusqueda"></div>
                </section>
            </form>
            </main>
        </div>
    </div>
</div>

&nbsp
{{--<a href="{{ url('/exportar-procesados-sql') }}" class="btn-export">
    🧠 Exportar Procesados a SQL
</a>
--}}

&nbsp




<div class="all-form"> 
<div class="form-container">
<div class="docs-container">
    
    <div class="docs-table-wrapper">
        <table class="docs-table">
            <thead>
                <tr>
                    <th>Norma/Manual</th>
                    <th>Libro</th>
                    <th>Tema</th>
                    <th>Parte</th>
                    <th>Título</th>
                    <th>Capítulo</th>
                    <th>Designación</th>
                    <th>Nombre</th>
                    <th>Origen</th>
                    <th>Nueva</th>
                    <th>Actualización</th>
                </tr>
            </thead>
            <tbody>
                @forelse($documentosProcesados as $documento)
<tr>
    <td>{{ $documento->tipo == 1 ? 'Manual' : 'Norma' }}</td>
    <td>{{ $documento->libroRelacion->desc ?? $documento->libro }}</td>
    <td>{{ $documento->temaRelacion->desc ?? $documento->tema }}</td>
    <td>{{ $documento->parte }}</td>
    <td>{{ $documento->titulo }}</td>
    <td>{{ $documento->capitulo }}</td>
    <td>{{ $documento->designacion ?? '--' }}</td>
    <td>{{ $documento->nombre ?? '--' }}</td>
    <td>{{ $documento->origen ?? '--' }}</td>
    <td>{{ $documento->fecha_nueva ?? '--' }}</td>
    <td>{{ $documento->fechas_actualizacion ?? '--' }}</td>
</tr>
@empty
<tr>
    <td colspan="11" class="empty-state">
        <p>No hay documentos registrados</p>
    </td>
</tr>
@endforelse
            </tbody>
        </table>
    </div>
</div>
</div>
</div>



<script>
    document.addEventListener("DOMContentLoaded", () => {
        const form = document.getElementById("formBusqueda");
        const tabla = document.querySelector(".docs-table tbody");
    
        form.addEventListener("submit", async function (e) {
            e.preventDefault();
    
            const params = new URLSearchParams(new FormData(form));
            tabla.innerHTML = `<tr><td colspan="11">Buscando...</td></tr>`;
    
            try {
                const response = await fetch(`/buscar-documentos?${params.toString()}`);
                const data = await response.json();
    
                if (data.length === 0) {
                    tabla.innerHTML = `<tr><td colspan="11">No se encontraron resultados</td></tr>`;
                    return;
                }
    
                let filasHTML = "";
                data.forEach(doc => {
                    filasHTML += `
                        <tr>
                            <td>${doc.tipo == 1 ? 'Manual' : 'Norma'}</td>
                            <td>${doc.libro ?? '--'}</td>
                            <td>${doc.tema ?? '--'}</td>
                            <td>${doc.parte ?? '--'}</td>
                            <td>${doc.titulo ?? '--'}</td>
                            <td>${doc.capitulo ?? '--'}</td>
                            <td>${doc.designacion ?? '--'}</td>
                            <td>${doc.nombre ?? '--'}</td>
                            <td>${doc.origen ?? '--'}</td>
                            <td>${doc.fecha_nueva ?? '--'}</td>
                            <td>${doc.fechas_actualizacion ?? '--'}</td>
                        </tr>
                    `;
                });
    
                tabla.innerHTML = filasHTML;
    
            } catch (error) {
                tabla.innerHTML = `<tr><td colspan="11">Error al realizar la búsqueda</td></tr>`;
                console.error(error);
            }
        });
    });
    </script>
    
    
    
@endsection

