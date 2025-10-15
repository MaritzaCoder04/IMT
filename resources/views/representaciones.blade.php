@extends('home')

@section('contenido')
<div class="all-form">
    <div class="form-container">
        <div class="container">
            <div class="layout">
                <main class="main-content">
                    <!-- Sección Organismos -->
                    <section id="organismos" class="section active">
                        <div class="section-header">
                            <h2 class="section-title">Gestión de Organismos</h2>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">Nombre del Organismo</label>
                                <input type="text" id="nombreOrganismo" class="form-input" placeholder="Ingresa el nombre del organismo" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Reuniones Anuales</label>
                                <input type="number" id="reunionesProgramadas" class="form-input" min="0" placeholder="Número de reuniones programadas">
                            </div>
                        </div>
                        
                        <button class="submit-btn" onclick="agregarOrganismo()">
                            Agregar Organismo
                        </button>
                        
                        <div style="margin-top: 30px;">
                            <h3 style="color: #4a4c4f; margin-bottom: 20px; font-weight: 400;">Organismos Registrados</h3>
                            <div id="listaOrganismos" class="card-list"></div>
                        </div>
                    </section>
                </main>
            </div>
        </div>
    </div>
</div>

<script>
let organismos = [];
let reuniones = [];

// Inicializar aplicación
function init() {
    cargarDatos();
    actualizarListaOrganismos();
}

// Cargar datos (aquí conectarías con tu backend/API)
function cargarDatos() {
    // Cargar desde base de datos o API
    // Por ahora datos de ejemplo opcionales:
    /*
    organismos = [
        { id: 1, nombre: "Consejo Municipal", reunionesProgramadas: 12, fechaCreacion: new Date().toISOString() },
        { id: 2, nombre: "Junta Directiva", reunionesProgramadas: 24, fechaCreacion: new Date().toISOString() }
    ];
    reuniones = [
        { id: 1, organismoId: 1, fecha: "2024-01-15" },
        { id: 2, organismoId: 1, fecha: "2024-02-15" }
    ];
    */
}

// Agregar organismo
function agregarOrganismo() {
    const nombre = document.getElementById('nombreOrganismo').value.trim();
    const programadas = parseInt(document.getElementById('reunionesProgramadas').value) || 0;
    
    if (!nombre) {
        return;
    }
    
    if (programadas < 0) {
        alert('El número de reuniones debe ser mayor o igual a 0', 'error');
        return;
    }
    
    const organismo = {
        id: Date.now(),
        nombre: nombre,
        reunionesProgramadas: programadas,
        fechaCreacion: new Date().toISOString()
    };
    
    organismos.push(organismo);
    guardarDatos();
    limpiarFormularioOrganismo();
    actualizarListaOrganismos();
    alert(`Organismo "${nombre}" agregado exitosamente`);
}

// Limpiar formulario
function limpiarFormularioOrganismo() {
    document.getElementById('nombreOrganismo').value = '';
    document.getElementById('reunionesProgramadas').value = '';
}

// Editar organismo
function editarOrganismo(id, campo) {
    const organismo = organismos.find(org => org.id === id);
    if (!organismo) return;
    
    let nuevoValor;
    if (campo === 'nombre') {
        nuevoValor = prompt('Nuevo nombre:', organismo.nombre);
        if (nuevoValor && nuevoValor.trim()) {
            organismo.nombre = nuevoValor.trim();
            alert('Nombre actualizado');
        } else if (nuevoValor === '') {
            alert('El nombre no puede estar vacío', 'error');
            return;
        }
    } else if (campo === 'reuniones') {
        nuevoValor = prompt('Reuniones programadas:', organismo.reunionesProgramadas);
        if (nuevoValor !== null) {
            const numero = parseInt(nuevoValor);
            if (isNaN(numero) || numero < 0) {
                alert('Ingresa un número válido mayor o igual a 0', 'error');
                return;
            }
            organismo.reunionesProgramadas = numero;
            alert('Reuniones programadas actualizadas');
        }
    }
    
    if (nuevoValor !== null && nuevoValor !== '') {
        guardarDatos();
        actualizarListaOrganismos();
    }
}

// Eliminar organismo
function eliminarOrganismo(id) {
    const organismo = organismos.find(org => org.id === id);
    if (!organismo) return;
    
    if (confirm(`¿Eliminar "${organismo.nombre}" y todas sus reuniones?`)) {
        organismos = organismos.filter(org => org.id !== id);
        reuniones = reuniones.filter(reunion => reunion.organismoId !== id);
        guardarDatos();
        actualizarListaOrganismos();
        alert('Organismo eliminado');
    }
}

// Actualizar lista de organismos
function actualizarListaOrganismos() {
    const lista = document.getElementById('listaOrganismos');
    
    if (organismos.length === 0) {
        lista.innerHTML = `
            <div class="empty-state">
                <div>No hay organismos registrados</div>
            </div>
        `;
        return;
    }
    
    lista.innerHTML = organismos
        .sort((a, b) => a.nombre.localeCompare(b.nombre))
        .map(organismo => {
            const reunionesRealizadas = reuniones.filter(r => r.organismoId === organismo.id).length;
            const progreso = organismo.reunionesProgramadas > 0 
                ? (reunionesRealizadas / organismo.reunionesProgramadas) * 100 
                : 0;
            
            return `
                <div class="list-item">
                    <div class="item-content">
                        <h4>${escapeHtml(organismo.nombre)}</h4>
                        <div class="item-meta">
                            ${reunionesRealizadas}/${organismo.reunionesProgramadas} reuniones
                            <span class="meeting-badge">${progreso.toFixed(1)}% completado</span>
                        </div>
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: ${Math.min(progreso, 100)}%"></div>
                        </div>
                    </div>
                    <div class="item-actions">
                        <button class="btn btn-secondary btn-small" onclick="editarOrganismo(${organismo.id}, 'nombre')" title="Editar nombre">
                            ✏️
                        </button>
                        <button class="btn btn-secondary btn-small" onclick="editarOrganismo(${organismo.id}, 'reuniones')" title="Editar reuniones programadas">
                            📊
                        </button>
                        <button class="btn btn-danger btn-small" onclick="eliminarOrganismo(${organismo.id})" title="Eliminar">
                            🗑️
                        </button>
                    </div>
                </div>
            `;
        }).join('');
}

// Escapar HTML para prevenir XSS
function escapeHtml(text) {
    const map = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
    };
    return text.replace(/[&<>"']/g, m => map[m]);
}

// Guardar datos
function guardarDatos() {
    const datos = {
        organismos: organismos,
        reuniones: reuniones,
        ultimaActualizacion: new Date().toISOString()
    };
    // Aquí guardarías en tu base de datos
    console.log('Datos guardados:', datos);
}


// Inicializar al cargar
document.addEventListener('DOMContentLoaded', init);
</script>
@endsection