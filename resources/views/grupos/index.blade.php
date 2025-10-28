@extends('home')

@section('contenido')
<div class="container" style="max-width: 1200px; margin: 0 auto; padding: 2rem 1rem;">
    <!-- Header -->
    <div style="margin-bottom: 2rem;">
        <h2 style="color: #1e40af; font-weight: 600; font-size: 1.75rem; margin-bottom: 0.5rem;">
            Tipos de Grupo de Trabajo
        </h2>
        <a href="{{ route('grupotrabajo.index') }}" 
           style="display: inline-flex; align-items: center; color: #64748b; text-decoration: none; font-size: 0.875rem; transition: color 0.2s;"
           onmouseover="this.style.color='#1e40af'" 
           onmouseout="this.style.color='#64748b'">
            ← Volver a Grupos de trabajo
        </a>
    </div>

    <!-- Formulario Agregar -->
    <div style="background: white; border: 1px solid #e0e7ff; border-radius: 8px; padding: 1.5rem; margin-bottom: 1.5rem;">
        <h4 style="color: #1e40af; font-weight: 500; font-size: 1rem; margin-bottom: 1rem;">
            Agregar nuevo tipo
        </h4>
        <form method="POST" action="{{ route('grupos.store') }}" style="display: flex; gap: 0.75rem; flex-wrap: wrap; align-items: center;">
            @csrf
            <input type="text" 
                   name="nombre" 
                   placeholder="Nombre" 
                   list="nombres-grupos"
                   required 
                   style="flex: 1; min-width: 200px; padding: 0.5rem 0.75rem; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 0.875rem; outline: none; transition: border-color 0.2s;"
                   onfocus="this.style.borderColor='#3b82f6'" 
                   onblur="this.style.borderColor='#cbd5e1'">
            
            <input type="text" 
                   name="descripcion" 
                   placeholder="Descripción" 
                   style="flex: 1; min-width: 200px; padding: 0.5rem 0.75rem; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 0.875rem; outline: none; transition: border-color 0.2s;"
                   onfocus="this.style.borderColor='#3b82f6'" 
                   onblur="this.style.borderColor='#cbd5e1'">
            
            <label style="display: flex; align-items: center; gap: 0.5rem; color: #475569; font-size: 0.875rem; cursor: pointer;">
                <input type="checkbox" 
                       name="activo" 
                       value="1" 
                       style="width: 16px; height: 16px; accent-color: #3b82f6; cursor: pointer;">
                Activo
            </label>
            
            <button type="submit" 
                    style="background: #3b82f6; color: white; padding: 0.5rem 1.25rem; border: none; border-radius: 6px; font-size: 0.875rem; font-weight: 500; cursor: pointer; transition: background 0.2s;"
                    onmouseover="this.style.background='#2563eb'" 
                    onmouseout="this.style.background='#3b82f6'">
                Guardar
            </button>
        </form>

        <!-- Sugerencias de nombres combinados -->
        <datalist id="nombres-grupos">
            @foreach($nombresGrupos as $nombre)
                <option value="{{ $nombre }}"></option>
            @endforeach
        </datalist>
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
                        <th style="padding: 0.75rem 1.5rem; text-align: left; font-size: 0.875rem; font-weight: 500; color: #bfc6d1ff;">Nombre</th>
                        <th style="padding: 0.75rem 1.5rem; text-align: left; font-size: 0.875rem; font-weight: 500; color: #bfc6d1ff;">Descripción</th>
                        <th style="padding: 0.75rem 1.5rem; text-align: left; font-size: 0.875rem; font-weight: 500; color: #bfc6d1ff;">Activo</th>
                        <th style="padding: 0.75rem 1.5rem; text-align: left; font-size: 0.875rem; font-weight: 500; color: #bfc6d1ff; width: 180px;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($grupos as $g)
                    <tr style="border-bottom: 1px solid #f1f5f9;">
                        <td style="padding: 1rem 1.5rem;">
                                <input type="text" 
                                       name="nombre" 
                                       value="{{ $g->nombre }}" 
                                       list="nombres-grupos"
                                       required 
                                       form="form-update-{{ $g->id }}"
                                       style="width: 100%; padding: 0.375rem 0.5rem; border: 1px solid #e2e8f0; border-radius: 4px; font-size: 0.875rem; outline: none; transition: border-color 0.2s;"
                                       onfocus="this.style.borderColor='#3b82f6'" 
                                       onblur="this.style.borderColor='#e2e8f0'">
                        </td>
                        <td style="padding: 1rem 1.5rem;">
                                <input type="text" 
                                       name="descripcion" 
                                       value="{{ $g->descripcion }}" 
                                       form="form-update-{{ $g->id }}"
                                       style="width: 100%; padding: 0.375rem 0.5rem; border: 1px solid #e2e8f0; border-radius: 4px; font-size: 0.875rem; outline: none; transition: border-color 0.2s;"
                                       onfocus="this.style.borderColor='#3b82f6'" 
                                       onblur="this.style.borderColor='#e2e8f0'">
                        </td>
                        <td style="padding: 1rem 1.5rem;">
                                <input type="checkbox" 
                                       name="activo" 
                                       value="1" 
                                       {{ $g->activo ? 'checked' : '' }} 
                                       form="form-update-{{ $g->id }}"
                                       style="width: 18px; height: 18px; accent-color: #3b82f6; cursor: pointer;">
                        </td>
                        <td style="padding: 1rem 1.5rem;">
                            <div style="display: flex; gap: 0.5rem;">
                                <form id="form-update-{{ $g->id }}" method="POST" action="{{ route('grupos.update', $g->id) }}" style="display:inline;">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" 
                                            style="background: #3b82f6; color: white; padding: 0.375rem 0.875rem; border: none; border-radius: 4px; font-size: 0.8125rem; font-weight: 500; cursor: pointer; transition: background 0.2s;"
                                            onmouseover="this.style.background='#2563eb'" 
                                            onmouseout="this.style.background='#3b82f6'">
                                        Actualizar
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('grupos.destroy', $g->id) }}" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            onclick="return confirm('¿Eliminar este tipo?')" 
                                            style="background: white; color: #dc2626; padding: 0.375rem 0.875rem; border: 1px solid #fecaca; border-radius: 4px; font-size: 0.8125rem; font-weight: 500; cursor: pointer; transition: all 0.2s;"
                                            onmouseover="this.style.background='#fef2f2'; this.style.borderColor='#dc2626'" 
                                            onmouseout="this.style.background='white'; this.style.borderColor='#fecaca'">
                                        Eliminar
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" style="padding: 2rem; text-align: center; color: #94a3b8; font-size: 0.875rem;">
                            No hay tipos de datos configurados.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection