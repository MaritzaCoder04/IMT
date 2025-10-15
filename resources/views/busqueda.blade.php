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
                        <h2 class="section-title">Búsqueda Avanzada</h2>
                    </div>
                    
                    <div class="search-container">
                        <div class="search-row">
                            <div class="form-group">
                                <label class="form-label">Tipo de Búsqueda</label>
                                <select id="filtroTipo" class="form-select" onchange="toggleSearchFilters()">
                                    <option value="mes">Por Mes</option>
                                    <option value="organismo">Por Organismo</option>
                                    <option value="rango">Por Rango de Fechas</option>
                                </select>
                            </div>
                            <div class="form-group" id="filtroMesGroup">
                                <label class="form-label">Mes</label>
                                <select id="filtroMes" class="form-select">
                                    <option value="">Seleccionar mes...</option>
                                    <option value="01">Enero</option>
                                    <option value="02">Febrero</option>
                                    <option value="03">Marzo</option>
                                    <option value="04">Abril</option>
                                    <option value="05">Mayo</option>
                                    <option value="06">Junio</option>
                                    <option value="07">Julio</option>
                                    <option value="08">Agosto</option>
                                    <option value="09">Septiembre</option>
                                    <option value="10">Octubre</option>
                                    <option value="11">Noviembre</option>
                                    <option value="12">Diciembre</option>
                                </select>
                            </div>
                            <div class="form-group" id="filtroOrganismoGroup" style="display: none;">
                                <label class="form-label">Organismo</label>
                                <select id="filtroOrganismo" class="form-select"></select>
                            </div>
                            <div class="form-group" id="filtroFechaInicioGroup" style="display: none;">
                                <label class="form-label">Fecha Inicio</label>
                                <input type="date" id="fechaInicio" class="form-input">
                            </div>
                            <div class="form-group" id="filtroFechaFinGroup" style="display: none;">
                                <label class="form-label">Fecha Fin</label>
                                <input type="date" id="fechaFin" class="form-input">
                            </div>
                            <button class="submit-btn" onclick="buscarReuniones()">
                                Buscar
                            </button>
                        </div>
                    </div>
                    
                    <div id="resultadosBusqueda"></div>
                </section>
            </main>
        </div>
    </div>
</div>
    

    <script>
        let organismos = [];
let reuniones = [];

// Inicializar aplicación
function init() {
    cargarDatos();
    actualizarSelectOrganismos();
}

// Cargar datos (aquí conectarías con tu backend/API)
function cargarDatos() {
    // Cargar desde base de datos o API
    // Por ahora datos de ejemplo:
    /*
    organismos = [
        { id: 1, nombre: "Consejo Municipal", reunionesProgramadas: 12 },
        { id: 2, nombre: "Junta Directiva", reunionesProgramadas: 24 }
    ];
    reuniones = [
        { id: 1, organismoId: 1, fecha: "2024-01-15" },
        { id: 2, organismoId: 1, fecha: "2024-02-15" }
    ];
    */
}

// Actualizar select de organismos
function actualizarSelectOrganismos() {
    const select = document.getElementById('filtroOrganismo');
    if (!select) return;
    
    if (organismos.length === 0) {
        select.innerHTML = '<option value="">No hay organismos disponibles</option>';
    } else {
        select.innerHTML = '<option value="">Seleccionar organismo...</option>' + 
            organismos
                .sort((a, b) => a.nombre.localeCompare(b.nombre))
                .map(org => `<option value="${org.id}">${org.nombre}</option>`)
                .join('');
    }
}

// Alternar filtros de búsqueda
function toggleSearchFilters() {
    const tipo = document.getElementById('filtroTipo').value;
    const grupos = {
        mes: ['filtroMesGroup'],
        organismo: ['filtroOrganismoGroup'],
        rango: ['filtroFechaInicioGroup', 'filtroFechaFinGroup']
    };
    
    // Ocultar todos los grupos
    Object.values(grupos).flat().forEach(id => {
        const element = document.getElementById(id);
        if (element) element.style.display = 'none';
    });
    
    // Mostrar grupos relevantes
    if (grupos[tipo]) {
        grupos[tipo].forEach(id => {
            const element = document.getElementById(id);
            if (element) element.style.display = 'block';
        });
    }
}

// Buscar reuniones
function buscarReuniones() {
    const tipo = document.getElementById('filtroTipo').value;
    const resultados = document.getElementById('resultadosBusqueda');
    let reunionesFiltradas = [];
    let criterio = '';
    
    if (tipo === 'mes') {
        const mes = document.getElementById('filtroMes').value;
        if (!mes) {
            showNotification('Selecciona un mes para buscar', 'error');
            return;
        }
        reunionesFiltradas = reuniones.filter(r => r.fecha.substring(5, 7) === mes);
        criterio = `Mes: ${document.getElementById('filtroMes').options[document.getElementById('filtroMes').selectedIndex].text}`;
    } else if (tipo === 'organismo') {
        const organismoId = parseInt(document.getElementById('filtroOrganismo').value);
        if (!organismoId) {
            showNotification('Selecciona un organismo para buscar', 'error');
            return;
        }
        reunionesFiltradas = reuniones.filter(r => r.organismoId === organismoId);
        const organismo = organismos.find(org => org.id === organismoId);
        criterio = `Organismo: ${organismo ? organismo.nombre : 'Desconocido'}`;
    } else if (tipo === 'rango') {
        const fechaInicio = document.getElementById('fechaInicio').value;
        const fechaFin = document.getElementById('fechaFin').value;
        
        if (!fechaInicio || !fechaFin) {
            showNotification('Selecciona un rango de fechas completo', 'error');
            return;
        }
        
        if (fechaInicio > fechaFin) {
            showNotification('La fecha de inicio debe ser anterior a la fecha de fin', 'error');
            return;
        }
        
        reunionesFiltradas = reuniones.filter(r => r.fecha >= fechaInicio && r.fecha <= fechaFin);
        criterio = `Rango: ${new Date(fechaInicio).toLocaleDateString('es-ES')} - ${new Date(fechaFin).toLocaleDateString('es-ES')}`;
    }
    
    if (reunionesFiltradas.length === 0) {
        resultados.innerHTML = `
            <div class="empty-state">
                <br><br>
                <div>No se encontraron reuniones</div>
                <div style="margin-top: 12px; font-size: 0.85rem; opacity: 0.8;">
                    ${criterio}
                </div>
            </div>
        `;
        return;
    }
    
    // Agrupar por organismo
    const reunionesPorOrganismo = reunionesFiltradas.reduce((acc, reunion) => {
        const organismo = organismos.find(org => org.id === reunion.organismoId);
        const nombreOrganismo = organismo ? organismo.nombre : 'Organismo eliminado';
        
        if (!acc[nombreOrganismo]) {
            acc[nombreOrganismo] = [];
        }
        acc[nombreOrganismo].push(reunion);
        return acc;
    }, {});
    
    resultados.innerHTML = `
        <div style="margin-bottom: 20px; padding: 15px; background: rgba(141, 110, 99, 0.1); border-radius: 12px; border-left: 4px solid #8D6E63;">
            <strong>Resultados de búsqueda</strong><br>
            <div class="item-meta">${criterio} | ${reunionesFiltradas.length} reuniones encontradas</div>
        </div>
        <div class="results-container">
            <div class="card-list">
                ${Object.keys(reunionesPorOrganismo)
                    .sort()
                    .map(nombreOrganismo => {
                        const reunionesOrganismo = reunionesPorOrganismo[nombreOrganismo]
                            .sort((a, b) => new Date(b.fecha) - new Date(a.fecha));
                        
                        return `
                            <div style="margin-bottom: 25px;">
                                <h4 style="color: #5D4037; margin-bottom: 15px; padding-bottom: 8px; border-bottom: 1px solid rgba(139, 110, 99, 0.2);">
                                    ${nombreOrganismo} (${reunionesOrganismo.length} reuniones)
                                </h4>
                                ${reunionesOrganismo.map(reunion => {
                                    const fecha = new Date(reunion.fecha);
                                    const fechaFormateada = fecha.toLocaleDateString('es-ES', {
                                        day: 'numeric',
                                        month: 'long',
                                        year: 'numeric'
                                    });
                                    
                                    return `
                                        <div class="list-item" style="margin-left: 20px; margin-bottom: 8px;">
                                            <div class="item-content">
                                                <div class="item-meta">
                                                    ${fechaFormateada}
                                                </div>
                                            </div>
                                        </div>
                                    `;
                                }).join('')}
                            </div>
                        `;
                    }).join('')}
            </div>
        </div>
    `;
}

// Notificaciones
function showNotification(message, type = 'success') {
    const notification = document.createElement('div');
    notification.className = 'notification show';
    notification.textContent = message;
    
    if (type === 'error') {
        notification.style.background = 'linear-gradient(135deg, #f44336, #d32f2f)';
    }
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.classList.remove('show');
        setTimeout(() => document.body.removeChild(notification), 300);
    }, 3000);
}

// Inicializar al cargar
document.addEventListener('DOMContentLoaded', init);
    </script>
@endsection