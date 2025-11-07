@extends('home')

@section('contenido')
<div class="container" style="max-width: 1200px; margin: 0 auto; padding: 2rem 1rem;">
    

    <!-- Formulario Agregar -->
    <div style="background: white; border: 1px solid #e0e7ff; border-radius: 8px; padding: 1.5rem; margin-bottom: 1.5rem;">
        <h4 style="color: #1e40af; font-weight: 500; font-size: 1rem; margin-bottom: 1rem;">
            Agregar nuevo tipo
        </h4>
        <form method="POST" action="{{ route('tipos.store') }}" style="display: flex; gap: 0.75rem; flex-wrap: wrap; align-items: center;">
            @csrf
            <input type="hidden" name="modal" value="{{ request('modal') ? 1 : '' }}">
            <input type="text" 
                   name="desc" 
                   placeholder="Descripción" 
                   required 
                   style="flex: 1; min-width: 250px; padding: 0.5rem 0.75rem; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 0.875rem; outline: none; transition: border-color 0.2s;"
                   onfocus="this.style.borderColor='#3b82f6'" 
                   onblur="this.style.borderColor='#cbd5e1'">
            
            <input type="text" 
                   name="clave" 
                   placeholder="Clave (ej: m, n)" 
                   required 
                   style="flex: 0.5; min-width: 150px; padding: 0.5rem 0.75rem; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 0.875rem; outline: none; transition: border-color 0.2s;"
                   onfocus="this.style.borderColor='#3b82f6'" 
                   onblur="this.style.borderColor='#cbd5e1'">
            
            <button type="submit" 
                    style="background: #3b82f6; color: white; padding: 0.5rem 1.25rem; border: none; border-radius: 6px; font-size: 0.875rem; font-weight: 500; cursor: pointer; transition: background 0.2s;"
                    onmouseover="this.style.background='#2563eb'" 
                    onmouseout="this.style.background='#3b82f6'">
                Guardar
            </button>
        </form>
    </div>

    <!-- Tabla -->
    <div style="background: white; border: 1px solid #e0e7ff; border-radius: 8px; overflow: hidden;">
        <div style="padding: 1.5rem 1.5rem 1rem;">
            <h4 style="color: #1e40af; font-weight: 500; font-size: 1rem; margin: 0;">
                Tipos existentes
            </h4>
        </div>
        
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #f8fafc; border-top: 1px solid #e2e8f0; border-bottom: 1px solid #e2e8f0;">
                        <th style="padding: 0.75rem 1.5rem; text-align: left; font-size: 0.875rem; font-weight: 500; color: #64748b;">Descripción</th>
                        <th style="padding: 0.75rem 1.5rem; text-align: left; font-size: 0.875rem; font-weight: 500; color: #64748b;">Clave</th>
                        <th style="padding: 0.75rem 1.5rem; text-align: left; font-size: 0.875rem; font-weight: 500; color: #64748b; width: 180px;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($tipos as $t)
                    <tr style="border-bottom: 1px solid #f1f5f9;">
                        <td style="padding: 1rem 1.5rem;">
                            <form method="POST" action="{{ route('tipos.update', ['tipo' => $t->ID_tipo]) }}" style="display: contents;">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="modal" value="{{ request('modal') ? 1 : '' }}">
                                <input type="text" 
                                       name="desc" 
                                       value="{{ $t->desc }}" 
                                       required 
                                       style="width: 100%; padding: 0.375rem 0.5rem; border: 1px solid #e2e8f0; border-radius: 4px; font-size: 0.875rem; outline: none; transition: border-color 0.2s;"
                                       onfocus="this.style.borderColor='#3b82f6'" 
                                       onblur="this.style.borderColor='#e2e8f0'">
                        </td>
                        <td style="padding: 1rem 1.5rem;">
                                <input type="text" 
                                       name="clave" 
                                       value="{{ $t->clave }}" 
                                       required 
                                       style="width: 100%; padding: 0.375rem 0.5rem; border: 1px solid #e2e8f0; border-radius: 4px; font-size: 0.875rem; outline: none; transition: border-color 0.2s;"
                                       onfocus="this.style.borderColor='#3b82f6'" 
                                       onblur="this.style.borderColor='#e2e8f0'">
                        </td>
                        <td style="padding: 1rem 1.5rem;">
                            <div style="display: flex; gap: 0.5rem;">
                                <button type="submit" 
                                        style="background: #3b82f6; color: white; padding: 0.375rem 0.875rem; border: none; border-radius: 4px; font-size: 0.8125rem; font-weight: 500; cursor: pointer; transition: background 0.2s;"
                                        onmouseover="this.style.background='#2563eb'" 
                                        onmouseout="this.style.background='#3b82f6'">Guardar</button>
                            </div>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" style="padding: 1rem 1.5rem; color: #64748b;">No hay tipos configurados.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
