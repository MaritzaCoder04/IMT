@extends('home')

@section('contenido')
<div class="container" style="max-width: 1200px; margin: 0 auto; padding: 2rem 1rem;">

    <!-- Formulario Agregar -->
    <div style="background: white; border: 1px solid #e0e7ff; border-radius: 8px; padding: 1.5rem; margin-bottom: 1.5rem;">
        <h4 style="font-weight: 500; font-size: 1rem; margin-bottom: 1rem;">
            Agregar nuevo tipo
        </h4>
        <form method="POST" action="{{ route('grupos.store') }}" style="display: flex; gap: 0.75rem; flex-wrap: wrap; align-items: center;">
            @csrf
            @if(request('modal'))
            <input type="hidden" name="modal" value="1">
            @endif
            <input type="text" 
                   name="nombre" 
                   placeholder="Nombre" 
                   list="nombres-grupos"
                   required 
                   style="flex: 1; min-width: 200px; padding: 0.5rem 0.75rem; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 0.875rem; outline: none; transition: border-color 0.2s;"
                   onfocus="this.style.borderColor='#3b82f6'" 
                   onblur="this.style.borderColor='#cbd5e1'">
            
            <button type="submit" 
                    style="background: #3b82f6; color: white; padding: 0.5rem 1.25rem; border: none; border-radius: 6px; font-size: 0.875rem; font-weight: 500; cursor: pointer; transition: background 0.2s;">
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
        
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #f8fafc; border-top: 1px solid #e2e8f0; border-bottom: 1px solid #e2e8f0;">
                        <th style="padding: 0.75rem 1.5rem; text-align: left; font-size: 0.875rem; font-weight: 500; color: #bfc6d1ff;">Nombre</th>
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
                                    @if(request('modal'))
                                    <input type="hidden" name="modal" value="1">
                                    @endif
                                    <button type="submit" class="btn-icon" title="Actualizar">
                                        <img src="{{ asset('img/pencil.png') }}" alt="Actualizar">
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('grupos.destroy', $g->id) }}" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    @if(request('modal'))
                                    <input type="hidden" name="modal" value="1">
                                    @endif
                                    <button type="submit" class="btn-icon" title="Eliminar" onclick="return confirm('¿Eliminar este tipo?')">
                                        <img src="{{ asset('img/delete.png') }}" alt="Eliminar">
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