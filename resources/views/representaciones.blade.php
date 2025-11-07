@extends('home')

@section('contenido')
<style>
    .reunion-tracker {
        padding: 2rem;
        background: #f8fafc;
        min-height: 100vh;
    }
    
    .reunion-header {
        margin-bottom: 2rem;
    }
    
    .reunion-title {
        font-size: 2rem;
        font-weight: 300;
        color: #1e3a8a;
        margin-bottom: 1.5rem;
    }
    
    .tabs-container {
        display: flex;
        gap: 0.25rem;
        margin-bottom: 0.25rem;
        align-items: center;
        background: white;
        border-bottom: 1px solid #cbd5e1;
    }
    
    .tab-item {
        position: relative;
    }
    
    .tab-button {
        padding: 0.75rem 1.5rem;
        font-weight: 500;
        border: none;
        background: transparent;
        cursor: pointer;
        transition: all 0.2s;
        color: #64748b;
        border-top: 1px solid transparent;
        border-left: 1px solid transparent;
        border-right: 1px solid transparent;
    }
    
    .tab-button.active {
        color: #1d4ed8;
        background: #eff6ff;
        border-top: 2px solid #2563eb;
        border-left: 1px solid #cbd5e1;
        border-right: 1px solid #cbd5e1;
    }
    
    .tab-button:hover:not(.active) {
        background: #f1f5f9;
    }
    
    .delete-year {
        position: absolute;
        top: -4px;
        right: -4px;
        background: #ef4444;
        color: white;
        border: none;
        border-radius: 50%;
        width: 18px;
        height: 18px;
        font-size: 10px;
        cursor: pointer;
        opacity: 0;
        transition: opacity 0.2s;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .tab-item:hover .delete-year {
        opacity: 1;
    }
    
    .add-year-form {
        display: none;
        align-items: center;
        gap: 0.5rem;
        padding: 0 0.5rem;
    }
    
    .add-year-input {
        width: 80px;
        padding: 0.25rem 0.5rem;
        border: 1px solid #93c5fd;
        border-radius: 0.25rem;
        font-size: 0.875rem;
    }
    
    .add-year-input:focus {
        outline: none;
        ring: 1px solid #2563eb;
    }
    
    .btn-small {
        padding: 0.25rem 0.75rem;
        font-size: 0.875rem;
        border: none;
        border-radius: 0.25rem;
        cursor: pointer;
        transition: background 0.2s;
    }
    
    .btn-primary {
        background: #2563eb;
        color: white;
    }
    
    .btn-primary:hover {
        background: #1d4ed8;
    }
    
    .btn-secondary {
        background: #cbd5e1;
        color: #334155;
    }
    
    .btn-secondary:hover {
        background: #94a3b8;
    }
    
    .btn-add-year {
        padding: 0.75rem 1rem;
        color: #2563eb;
        background: transparent;
        border: none;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 0.25rem;
        font-size: 0.875rem;
        transition: background 0.2s;
    }
    
    .btn-add-year:hover {
        background: #eff6ff;
    }
    
    .table-container {
        background: white;
        border: 1px solid #cbd5e1;
        overflow: hidden;
    }
    
    .table-scroll {
        overflow-x: auto;
    }
    
    .reunion-table {
        width: 100%;
        border-collapse: collapse;
    }
    
    .reunion-table th,
    .reunion-table td {
        border: 1px solid #cbd5e1;
        padding: 0.5rem 0.75rem;
        text-align: center;
    }
    
    .reunion-table thead th {
        background: #f1f5f9;
        font-weight: 600;
        color: #334155;
        font-size: 0.875rem;
    }
    
    .reunion-table thead tr:first-child th {
        background: #e2e8f0;
    }
    
    .empresa-cell {
        text-align: left;
        font-weight: 500;
        color: #1e293b;
        background: white;
        position: sticky;
        left: 0;
        z-index: 10;
    }
    
    .semana-header {
        background: #f8fafc;
        font-size: 0.75rem;
        color: #64748b;
        font-weight: 500;
        min-width: 50px;
    }
    
    .dia-cell {
        background: white;
        cursor: pointer;
        transition: background 0.2s;
        min-height: 24px;
        font-size: 0.875rem;
        font-weight: 500;
        color: #334155;
    }
    
    .dia-cell:hover {
        background: #f8fafc;
    }
    
    .dia-cell.empty {
        color: #cbd5e1;
    }
    
    .dia-input {
        width: 100%;
        text-align: center;
        font-size: 0.875rem;
        padding: 0.125rem 0.25rem;
        border: 1px solid #60a5fa;
        border-radius: 0.25rem;
    }
    
    .dia-input:focus {
        outline: none;
        ring: 1px solid #2563eb;
    }
    
    .totales-cell {
        background: #eff6ff;
        font-weight: 700;
        color: #1d4ed8;
    }
    
    .programadas-input {
        width: 100%;
        text-align: center;
        font-weight: 700;
        color: #1e3a8a;
        background: transparent;
        border: none;
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
    }
    
    .programadas-input:focus {
        outline: none;
        ring: 1px solid #2563eb;
        border-radius: 0.25rem;
    }
</style>

<div class="reunion-tracker">
    <div class="reunion-header">
        <h1 class="reunion-title">Registro de Reuniones</h1>
        <div class="actions" style="display:flex; gap:8px; align-items:center;">
    @php
        $currentYear = (int)date('Y');
        $selectedYear = (int)request()->get('anio', $currentYear);
    @endphp
    <div class="year-switcher" style="display:flex; align-items:center; gap:8px;">
      <input type="number" id="page-year-input" value="{{ $selectedYear }}" min="1990" max="{{ $currentYear + 50 }}" placeholder="{{ $currentYear }}" style="padding:6px 10px; border-radius:6px; border:1px solid #cbd5e1; font-size:0.9rem; width:90px;" />
    </div>
            <button type="button" class="btn btn-ejemplo" style="padding: 7px 12px;" onclick="abrirModalGrupos('{{ route('grupos.index') }}')" title="Tipos de Grupo" aria-label="Tipos de Grupo">Tipos de Grupo</button>
        </div>
    </div>
    
    <!-- Pestañas de años -->
    <div class="tabs-container">
        <div class="tab-item">
            <button class="tab-button active" onclick="cambiarAnio(2023)">2023</button>
            <button class="delete-year" onclick="eliminarAnio(2023)">x</button>
        </div>
        <div class="tab-item">
            <button class="tab-button" onclick="cambiarAnio(2024)">2024</button>
            <button class="delete-year" onclick="eliminarAnio(2024)">x</button>
        </div>
        <div class="tab-item">
            <button class="tab-button" onclick="cambiarAnio(2025)">2025</button>
            <button class="delete-year" onclick="eliminarAnio(2025)">x</button>
        </div>
        <div class="tab-item">
            <button class="tab-button" onclick="cambiarAnio(2026)">2026</button>
            <button class="delete-year" onclick="eliminarAnio(2026)">x</button>
        </div>
        
        <div class="add-year-form" id="addYearForm">
            <input type="number" class="add-year-input" id="newYearInput" placeholder="2027">
            <button class="btn-small btn-primary" onclick="agregarAnio()">OK</button>
            <button class="btn-small btn-secondary" onclick="toggleAddYear()">Cancelar</button>
        </div>
        
        <button class="btn-add-year" id="btnAddYear" onclick="toggleAddYear()">
            <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
        </button>
    </div>

    <!-- Tabla -->
    <div class="table-container">
        <div class="table-scroll">
            <table class="reunion-table">
                <thead>
                    <tr>
                        <th rowspan="2" class="empresa-cell">Empresa</th>
                        <th colspan="4">Ene</th>
                        <th colspan="4">Feb</th>
                        <th colspan="4">Mar</th>
                        <th colspan="4">Abr</th>
                        <th colspan="4">May</th>
                        <th colspan="4">Jun</th>
                        <th colspan="4">Jul</th>
                        <th colspan="4">Ago</th>
                        <th colspan="4">Sep</th>
                        <th colspan="4">Oct</th>
                        <th colspan="4">Nov</th>
                        <th colspan="4">Dic</th>
                        <th rowspan="2" class="totales-cell" style="min-width: 100px;">Realizadas</th>
                        <th rowspan="2" class="totales-cell" style="min-width: 110px;">Programadas</th>
                    </tr>
                    <tr>
                        <!-- 12 meses x 4 semanas = 48 columnas -->
                        <th class="semana-header">S1</th>
                        <th class="semana-header">S2</th>
                        <th class="semana-header">S3</th>
                        <th class="semana-header">S4</th>
                        
                        <th class="semana-header">S1</th>
                        <th class="semana-header">S2</th>
                        <th class="semana-header">S3</th>
                        <th class="semana-header">S4</th>
                        
                        <th class="semana-header">S1</th>
                        <th class="semana-header">S2</th>
                        <th class="semana-header">S3</th>
                        <th class="semana-header">S4</th>
                        
                        <th class="semana-header">S1</th>
                        <th class="semana-header">S2</th>
                        <th class="semana-header">S3</th>
                        <th class="semana-header">S4</th>
                        
                        <th class="semana-header">S1</th>
                        <th class="semana-header">S2</th>
                        <th class="semana-header">S3</th>
                        <th class="semana-header">S4</th>
                        
                        <th class="semana-header">S1</th>
                        <th class="semana-header">S2</th>
                        <th class="semana-header">S3</th>
                        <th class="semana-header">S4</th>
                        
                        <th class="semana-header">S1</th>
                        <th class="semana-header">S2</th>
                        <th class="semana-header">S3</th>
                        <th class="semana-header">S4</th>
                        
                        <th class="semana-header">S1</th>
                        <th class="semana-header">S2</th>
                        <th class="semana-header">S3</th>
                        <th class="semana-header">S4</th>
                        
                        <th class="semana-header">S1</th>
                        <th class="semana-header">S2</th>
                        <th class="semana-header">S3</th>
                        <th class="semana-header">S4</th>
                        
                        <th class="semana-header">S1</th>
                        <th class="semana-header">S2</th>
                        <th class="semana-header">S3</th>
                        <th class="semana-header">S4</th>
                        
                        <th class="semana-header">S1</th>
                        <th class="semana-header">S2</th>
                        <th class="semana-header">S3</th>
                        <th class="semana-header">S4</th>
                        
                        <th class="semana-header">S1</th>
                        <th class="semana-header">S2</th>
                        <th class="semana-header">S3</th>
                        <th class="semana-header">S4</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Empresa A -->
                    <tr>
                        <td class="empresa-cell">Empresa A</td>
                        <!-- Enero -->
                        <td class="dia-cell" onclick="editarDia(this)">5</td>
                        <td class="dia-cell" onclick="editarDia(this)">12</td>
                        <td class="dia-cell empty" onclick="editarDia(this)">-</td>
                        <td class="dia-cell empty" onclick="editarDia(this)">-</td>
                        <!-- Febrero -->
                        <td class="dia-cell" onclick="editarDia(this)">2</td>
                        <td class="dia-cell empty" onclick="editarDia(this)">-</td>
                        <td class="dia-cell empty" onclick="editarDia(this)">-</td>
                        <td class="dia-cell empty" onclick="editarDia(this)">-</td>
                        <!-- Marzo -->
                        <td class="dia-cell" onclick="editarDia(this)">8</td>
                        <td class="dia-cell" onclick="editarDia(this)">15</td>
                        <td class="dia-cell" onclick="editarDia(this)">22</td>
                        <td class="dia-cell empty" onclick="editarDia(this)">-</td>
                        <!-- Abril -->
                        <td class="dia-cell" onclick="editarDia(this)">3</td>
                        <td class="dia-cell empty" onclick="editarDia(this)">-</td>
                        <td class="dia-cell empty" onclick="editarDia(this)">-</td>
                        <td class="dia-cell empty" onclick="editarDia(this)">-</td>
                        <!-- Mayo a Diciembre (vacíos) -->
                        <td class="dia-cell empty" onclick="editarDia(this)">-</td>
                        <td class="dia-cell empty" onclick="editarDia(this)">-</td>
                        <td class="dia-cell empty" onclick="editarDia(this)">-</td>
                        <td class="dia-cell empty" onclick="editarDia(this)">-</td>
                        
                        <td class="dia-cell empty" onclick="editarDia(this)">-</td>
                        <td class="dia-cell empty" onclick="editarDia(this)">-</td>
                        <td class="dia-cell empty" onclick="editarDia(this)">-</td>
                        <td class="dia-cell empty" onclick="editarDia(this)">-</td>
                        
                        <td class="dia-cell empty" onclick="editarDia(this)">-</td>
                        <td class="dia-cell empty" onclick="editarDia(this)">-</td>
                        <td class="dia-cell empty" onclick="editarDia(this)">-</td>
                        <td class="dia-cell empty" onclick="editarDia(this)">-</td>
                        
                        <td class="dia-cell empty" onclick="editarDia(this)">-</td>
                        <td class="dia-cell empty" onclick="editarDia(this)">-</td>
                        <td class="dia-cell empty" onclick="editarDia(this)">-</td>
                        <td class="dia-cell empty" onclick="editarDia(this)">-</td>
                        
                        <td class="dia-cell empty" onclick="editarDia(this)">-</td>
                        <td class="dia-cell empty" onclick="editarDia(this)">-</td>
                        <td class="dia-cell empty" onclick="editarDia(this)">-</td>
                        <td class="dia-cell empty" onclick="editarDia(this)">-</td>
                        
                        <td class="dia-cell empty" onclick="editarDia(this)">-</td>
                        <td class="dia-cell empty" onclick="editarDia(this)">-</td>
                        <td class="dia-cell empty" onclick="editarDia(this)">-</td>
                        <td class="dia-cell empty" onclick="editarDia(this)">-</td>
                        
                        <td class="dia-cell empty" onclick="editarDia(this)">-</td>
                        <td class="dia-cell empty" onclick="editarDia(this)">-</td>
                        <td class="dia-cell empty" onclick="editarDia(this)">-</td>
                        <td class="dia-cell empty" onclick="editarDia(this)">-</td>
                        
                        <td class="dia-cell empty" onclick="editarDia(this)">-</td>
                        <td class="dia-cell empty" onclick="editarDia(this)">-</td>
                        <td class="dia-cell empty" onclick="editarDia(this)">-</td>
                        <td class="dia-cell empty" onclick="editarDia(this)">-</td>
                        
                        <td class="totales-cell">8</td>
                        <td class="totales-cell">
                            <input type="number" class="programadas-input" value="24" min="0">
                        </td>
                    </tr>
                    
                    <!-- Empresa B -->
                    <tr>
                        <td class="empresa-cell">Empresa B</td>
                        <!-- Enero -->
                        <td class="dia-cell" onclick="editarDia(this)">6</td>
                        <td class="dia-cell" onclick="editarDia(this)">13</td>
                        <td class="dia-cell" onclick="editarDia(this)">20</td>
                        <td class="dia-cell empty" onclick="editarDia(this)">-</td>
                        <!-- Febrero -->
                        <td class="dia-cell" onclick="editarDia(this)">3</td>
                        <td class="dia-cell" onclick="editarDia(this)">17</td>
                        <td class="dia-cell empty" onclick="editarDia(this)">-</td>
                        <td class="dia-cell empty" onclick="editarDia(this)">-</td>
                        <!-- Marzo -->
                        <td class="dia-cell" onclick="editarDia(this)">9</td>
                        <td class="dia-cell" onclick="editarDia(this)">16</td>
                        <td class="dia-cell" onclick="editarDia(this)">23</td>
                        <td class="dia-cell" onclick="editarDia(this)">30</td>
                        <!-- Abril -->
                        <td class="dia-cell" onclick="editarDia(this)">4</td>
                        <td class="dia-cell" onclick="editarDia(this)">11</td>
                        <td class="dia-cell" onclick="editarDia(this)">18</td>
                        <td class="dia-cell empty" onclick="editarDia(this)">-</td>
                        <!-- Mayo a Diciembre (vacíos) -->
                        <td class="dia-cell empty" onclick="editarDia(this)">-</td>
                        <td class="dia-cell empty" onclick="editarDia(this)">-</td>
                        <td class="dia-cell empty" onclick="editarDia(this)">-</td>
                        <td class="dia-cell empty" onclick="editarDia(this)">-</td>
                        
                        <td class="dia-cell empty" onclick="editarDia(this)">-</td>
                        <td class="dia-cell empty" onclick="editarDia(this)">-</td>
                        <td class="dia-cell empty" onclick="editarDia(this)">-</td>
                        <td class="dia-cell empty" onclick="editarDia(this)">-</td>
                        
                        <td class="dia-cell empty" onclick="editarDia(this)">-</td>
                        <td class="dia-cell empty" onclick="editarDia(this)">-</td>
                        <td class="dia-cell empty" onclick="editarDia(this)">-</td>
                        <td class="dia-cell empty" onclick="editarDia(this)">-</td>
                        
                        <td class="dia-cell empty" onclick="editarDia(this)">-</td>
                        <td class="dia-cell empty" onclick="editarDia(this)">-</td>
                        <td class="dia-cell empty" onclick="editarDia(this)">-</td>
                        <td class="dia-cell empty" onclick="editarDia(this)">-</td>
                        
                        <td class="dia-cell empty" onclick="editarDia(this)">-</td>
                        <td class="dia-cell empty" onclick="editarDia(this)">-</td>
                        <td class="dia-cell empty" onclick="editarDia(this)">-</td>
                        <td class="dia-cell empty" onclick="editarDia(this)">-</td>
                        
                        <td class="dia-cell empty" onclick="editarDia(this)">-</td>
                        <td class="dia-cell empty" onclick="editarDia(this)">-</td>
                        <td class="dia-cell empty" onclick="editarDia(this)">-</td>
                        <td class="dia-cell empty" onclick="editarDia(this)">-</td>
                        
                        <td class="dia-cell empty" onclick="editarDia(this)">-</td>
                        <td class="dia-cell empty" onclick="editarDia(this)">-</td>
                        <td class="dia-cell empty" onclick="editarDia(this)">-</td>
                        <td class="dia-cell empty" onclick="editarDia(this)">-</td>
                        
                        <td class="dia-cell empty" onclick="editarDia(this)">-</td>
                        <td class="dia-cell empty" onclick="editarDia(this)">-</td>
                        <td class="dia-cell empty" onclick="editarDia(this)">-</td>
                        <td class="dia-cell empty" onclick="editarDia(this)">-</td>
                        
                        <td class="totales-cell">13</td>
                        <td class="totales-cell">
                            <input type="number" class="programadas-input" value="36" min="0">
                        </td>
                    </tr>
                    
                    <!-- Empresa C -->
                    <tr>
                        <td class="empresa-cell">Empresa C</td>
                        <!-- Enero -->
                        <td class="dia-cell" onclick="editarDia(this)">7</td>
                        <td class="dia-cell empty" onclick="editarDia(this)">-</td>
                        <td class="dia-cell empty" onclick="editarDia(this)">-</td>
                        <td class="dia-cell empty" onclick="editarDia(this)">-</td>
                        <!-- Febrero -->
                        <td class="dia-cell" onclick="editarDia(this)">5</td>
                        <td class="dia-cell empty" onclick="editarDia(this)">-</td>
                        <td class="dia-cell empty" onclick="editarDia(this)">-</td>
                        <td class="dia-cell empty" onclick="editarDia(this)">-</td>
                        <!-- Marzo -->
                        <td class="dia-cell" onclick="editarDia(this)">10</td>
                        <td class="dia-cell" onclick="editarDia(this)">24</td>
                        <td class="dia-cell empty" onclick="editarDia(this)">-</td>
                        <td class="dia-cell empty" onclick="editarDia(this)">-</td>
                        <!-- Abril -->
                        <td class="dia-cell" onclick="editarDia(this)">6</td>
                        <td class="dia-cell empty" onclick="editarDia(this)">-</td>
                        <td class="dia-cell empty" onclick="editarDia(this)">-</td>
                        <td class="dia-cell empty" onclick="editarDia(this)">-</td>
                        <!-- Mayo a Diciembre (vacíos) -->
                        <td class="dia-cell empty" onclick="editarDia(this)">-</td>
                        <td class="dia-cell empty" onclick="editarDia(this)">-</td>
                        <td class="dia-cell empty" onclick="editarDia(this)">-</td>
                        <td class="dia-cell empty" onclick="editarDia(this)">-</td>
                        
                        <td class="dia-cell empty" onclick="editarDia(this)">-</td>
                        <td class="dia-cell empty" onclick="editarDia(this)">-</td>
                        <td class="dia-cell empty" onclick="editarDia(this)">-</td>
                        <td class="dia-cell empty" onclick="editarDia(this)">-</td>
                        
                        <td class="dia-cell empty" onclick="editarDia(this)">-</td>
                        <td class="dia-cell empty" onclick="editarDia(this)">-</td>
                        <td class="dia-cell empty" onclick="editarDia(this)">-</td>
                        <td class="dia-cell empty" onclick="editarDia(this)">-</td>
                        
                        <td class="dia-cell empty" onclick="editarDia(this)">-</td>
                        <td class="dia-cell empty" onclick="editarDia(this)">-</td>
                        <td class="dia-cell empty" onclick="editarDia(this)">-</td>
                        <td class="dia-cell empty" onclick="editarDia(this)">-</td>
                        
                        <td class="dia-cell empty" onclick="editarDia(this)">-</td>
                        <td class="dia-cell empty" onclick="editarDia(this)">-</td>
                        <td class="dia-cell empty" onclick="editarDia(this)">-</td>
                        <td class="dia-cell empty" onclick="editarDia(this)">-</td>
                        
                        <td class="dia-cell empty" onclick="editarDia(this)">-</td>
                        <td class="dia-cell empty" onclick="editarDia(this)">-</td>
                        <td class="dia-cell empty" onclick="editarDia(this)">-</td>
                        <td class="dia-cell empty" onclick="editarDia(this)">-</td>
                        
                        <td class="dia-cell empty" onclick="editarDia(this)">-</td>
                        <td class="dia-cell empty" onclick="editarDia(this)">-</td>
                        <td class="dia-cell empty" onclick="editarDia(this)">-</td>
                        <td class="dia-cell empty" onclick="editarDia(this)">-</td>
                        
                        <td class="dia-cell empty" onclick="editarDia(this)">-</td>
                        <td class="dia-cell empty" onclick="editarDia(this)">-</td>
                        <td class="dia-cell empty" onclick="editarDia(this)">-</td>
                        <td class="dia-cell empty" onclick="editarDia(this)">-</td>
                        
                        <td class="totales-cell">5</td>
                        <td class="totales-cell">
                            <input type="number" class="programadas-input" value="12" min="0">
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Tipos de Grupo -->
<div id="modal-overlay-grupos" class="modal-overlay" onclick="cerrarModalGruposOverlayClick(event)" style="display:none;">
  <div class="modal-content" role="dialog" aria-modal="true" aria-labelledby="modal-title-grupos">
    <div class="modal-header" style="display:flex; justify-content:space-between; align-items:center;">
      <h3 id="modal-title-grupos" style="margin:0;">Tipos de Grupo de Trabajo</h3>
      <button type="button" class="btn btn-secondary" onclick="cerrarModalGrupos()">Cerrar</button>
    </div>
    <div class="modal-body" style="height:70vh;">
      <iframe id="modal-iframe-grupos" src="about:blank" title="Tipos de grupo" style="width:100%; height:100%; border:none;"></iframe>
    </div>
  </div>
  <style>
    .modal-overlay { position: fixed; inset: 0; background: rgba(0,0,0,0.45); display: none; align-items: center; justify-content: center; z-index: 9999; }
    .modal-overlay.active { display: flex; }
    .modal-content { background: #fff; border-radius: 8px; width: min(1000px, 95vw); box-shadow: 0 10px 25px rgba(0,0,0,0.15); }
    .modal-header { padding: 10px 16px; border-bottom: 1px solid #e5e7eb; }
    .modal-body { padding: 10px; }
  </style>
</div>

<script>
    function abrirModalGrupos(url){
      try{
        const iframe = document.getElementById('modal-iframe-grupos');
        const sep = url.includes('?') ? '&' : '?';
        iframe.src = url + sep + 'modal=1&scope=representaciones';
        document.getElementById('modal-overlay-grupos').classList.add('active');
      }catch(e){}
    }
    function cerrarModalGrupos(){
      try{
        const overlay = document.getElementById('modal-overlay-grupos');
        const iframe = document.getElementById('modal-iframe-grupos');
        overlay.classList.remove('active');
        iframe.src = 'about:blank';
      }catch(e){}
    }
    function cerrarModalGruposOverlayClick(event){ if(event && event.target && event.target.id==='modal-overlay-grupos'){ cerrarModalGrupos(); } }
    function toggleAddYear() {
        const form = document.getElementById('addYearForm');
        const btn = document.getElementById('btnAddYear');
        
        if (form.style.display === 'none' || form.style.display === '') {
            form.style.display = 'flex';
            btn.style.display = 'none';
            document.getElementById('newYearInput').focus();
        } else {
            form.style.display = 'none';
            btn.style.display = 'flex';
        }
    }
    
    function cambiarAnio(anio) {
        // Remover clase active de todos los botones
        document.querySelectorAll('.tab-button').forEach(btn => {
            btn.classList.remove('active');
        });
        
        // Agregar clase active al botón clickeado
        event.target.classList.add('active');
        
        // Aquí iría la lógica para cargar los datos del año seleccionado
        console.log('Cambiando a año:', anio);
    }
    
    function agregarAnio() {
        const input = document.getElementById('newYearInput');
        const anio = parseInt(input.value);
        
        if (anio && anio > 1900 && anio < 2100) {
            // Aquí iría la lógica para agregar el año
            console.log('Agregando año:', anio);
            toggleAddYear();
            input.value = '';
        } else {
            alert('Por favor ingresa un año válido');
        }
    }
    
    function eliminarAnio(anio) {
        if (confirm(`¿Estás seguro de eliminar el año ${anio}?`)) {
            // Aquí iría la lógica para eliminar el año
            console.log('Eliminando año:', anio);
        }
    }
    
    function editarDia(celda) {
        const valorActual = celda.textContent.trim();
        const valor = valorActual === '-' ? '' : valorActual;
        
        const input = document.createElement('input');
        input.type = 'text';
        input.className = 'dia-input';
        input.value = valor;
        input.placeholder = 'Día';
        
        celda.innerHTML = '';
        celda.appendChild(input);
        input.focus();
        
        const guardar = () => {
            const nuevoValor = input.value.trim();
            const textoMostrar = nuevoValor || '-';
            
            celda.innerHTML = textoMostrar;
            
            if (nuevoValor) {
                celda.classList.remove('empty');
            } else {
                celda.classList.add('empty');
            }
            
            // Aquí iría la lógica para guardar en base de datos
            console.log('Guardando día:', nuevoValor);
        };
        
        input.addEventListener('blur', guardar);
        input.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                guardar();
            }
        });
    }
</script>

@endsection
<script>
document.addEventListener('DOMContentLoaded', function(){
  var input = document.getElementById('page-year-input');
  if (!input) return;
  input.addEventListener('change', function(){
    try { localStorage.setItem('anioGlobal', String(input.value)); } catch(e){}
    var url = new URL(window.location.href);
    var params = url.searchParams;
    var y = input.value || '';
    if (y) params.set('anio', y); else params.delete('anio');
    url.search = params.toString();
    window.location.href = url.toString();
  });
});
</script>




