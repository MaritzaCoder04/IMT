@extends('home')

@section('contenido')
<div class="container" style="max-width: 900px; margin: 0 auto; padding: 2rem 1rem;">
    <!-- Header -->
    <div style="margin-bottom: 2rem;">
        <h2 style="color: #1e40af; font-weight: 600; font-size: 1.75rem; margin-bottom: 0.5rem;">
            Editar Grupo de Trabajo
        </h2>
        <a href="{{ route('grupotrabajo.index') }}" 
           style="display: inline-flex; align-items: center; color: #64748b; text-decoration: none; font-size: 0.875rem; transition: color 0.2s;"
           onmouseover="this.style.color='#1e40af'" 
           onmouseout="this.style.color='#64748b'">
            ← Volver a grupos de trabajo
        </a>
    </div>

    <!-- Formulario -->
    <form action="{{ route('grupotrabajo.update', $grupo->id) }}" method="POST" 
          style="background: white; border: 1px solid #e0e7ff; border-radius: 8px; padding: 2rem;">
        @csrf
        @method('PUT')

        <!-- Nombre del grupo -->
        <div style="margin-bottom: 1.5rem;">
            <label for="nombre" style="display: block; color: #475569; font-size: 0.875rem; font-weight: 500; margin-bottom: 0.5rem;">
                Nombre del grupo
            </label>
            <select id="nombre" 
                    name="nombre" 
                    required
                    style="width: 100%; padding: 0.625rem 0.75rem; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 0.875rem; outline: none; transition: border-color 0.2s; background: white;"
                    onfocus="this.style.borderColor='#3b82f6'" 
                    onblur="this.style.borderColor='#cbd5e1'">
                <option value="">Selecciona un grupo...</option>
                @foreach($nombresGrupos as $nombre)
                    <option value="{{ $nombre }}" {{ $grupo->nombre == $nombre ? 'selected' : '' }}>
                        {{ $nombre }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Meta anual -->
        <div style="margin-bottom: 2rem;">
            <label for="meta_anual" style="display: block; color: #475569; font-size: 0.875rem; font-weight: 500; margin-bottom: 0.5rem;">
                Meta anual
            </label>
            <input type="number" 
                   id="meta_anual" 
                   name="meta_anual" 
                   value="{{ $grupo->meta_anual }}"
                   style="width: 100%; padding: 0.625rem 0.75rem; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 0.875rem; outline: none; transition: border-color 0.2s;"
                   onfocus="this.style.borderColor='#3b82f6'" 
                   onblur="this.style.borderColor='#cbd5e1'">
        </div>

        <!-- Separador -->
        <div style="border-top: 1px solid #e2e8f0; margin: 2rem 0;"></div>

        <!-- Título Metas Bimestrales -->
        <h3 style="color: #1e40af; font-weight: 500; font-size: 1.125rem; margin-bottom: 1.5rem;">
            Metas Bimestrales
        </h3>

        <!-- Grid de metas bimestrales -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.25rem; margin-bottom: 2rem;">
            <!-- Bimestre 1 -->
            <div>
                <label for="meta_bimestre_1" style="display: block; color: #475569; font-size: 0.875rem; font-weight: 500; margin-bottom: 0.5rem;">
                    Bimestre 1 <span style="color: #94a3b8; font-weight: 400;">(Ene-Feb)</span>
                </label>
                <input type="number" 
                       id="meta_bimestre_1" 
                       name="meta_bimestre_1" 
                       min="0" 
                       value="{{ $grupo->meta_bimestre_1 }}"
                       style="width: 100%; padding: 0.625rem 0.75rem; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 0.875rem; outline: none; transition: border-color 0.2s;"
                       onfocus="this.style.borderColor='#3b82f6'" 
                       onblur="this.style.borderColor='#cbd5e1'">
            </div>

            <!-- Bimestre 2 -->
            <div>
                <label for="meta_bimestre_2" style="display: block; color: #475569; font-size: 0.875rem; font-weight: 500; margin-bottom: 0.5rem;">
                    Bimestre 2 <span style="color: #94a3b8; font-weight: 400;">(Mar-Abr)</span>
                </label>
                <input type="number" 
                       id="meta_bimestre_2" 
                       name="meta_bimestre_2" 
                       min="0" 
                       value="{{ $grupo->meta_bimestre_2 }}"
                       style="width: 100%; padding: 0.625rem 0.75rem; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 0.875rem; outline: none; transition: border-color 0.2s;"
                       onfocus="this.style.borderColor='#3b82f6'" 
                       onblur="this.style.borderColor='#cbd5e1'">
            </div>

            <!-- Bimestre 3 -->
            <div>
                <label for="meta_bimestre_3" style="display: block; color: #475569; font-size: 0.875rem; font-weight: 500; margin-bottom: 0.5rem;">
                    Bimestre 3 <span style="color: #94a3b8; font-weight: 400;">(May-Jun)</span>
                </label>
                <input type="number" 
                       id="meta_bimestre_3" 
                       name="meta_bimestre_3" 
                       min="0" 
                       value="{{ $grupo->meta_bimestre_3 }}"
                       style="width: 100%; padding: 0.625rem 0.75rem; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 0.875rem; outline: none; transition: border-color 0.2s;"
                       onfocus="this.style.borderColor='#3b82f6'" 
                       onblur="this.style.borderColor='#cbd5e1'">
            </div>

            <!-- Bimestre 4 -->
            <div>
                <label for="meta_bimestre_4" style="display: block; color: #475569; font-size: 0.875rem; font-weight: 500; margin-bottom: 0.5rem;">
                    Bimestre 4 <span style="color: #94a3b8; font-weight: 400;">(Jul-Ago)</span>
                </label>
                <input type="number" 
                       id="meta_bimestre_4" 
                       name="meta_bimestre_4" 
                       min="0" 
                       value="{{ $grupo->meta_bimestre_4 }}"
                       style="width: 100%; padding: 0.625rem 0.75rem; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 0.875rem; outline: none; transition: border-color 0.2s;"
                       onfocus="this.style.borderColor='#3b82f6'" 
                       onblur="this.style.borderColor='#cbd5e1'">
            </div>

            <!-- Bimestre 5 -->
            <div>
                <label for="meta_bimestre_5" style="display: block; color: #475569; font-size: 0.875rem; font-weight: 500; margin-bottom: 0.5rem;">
                    Bimestre 5 <span style="color: #94a3b8; font-weight: 400;">(Sep-Oct)</span>
                </label>
                <input type="number" 
                       id="meta_bimestre_5" 
                       name="meta_bimestre_5" 
                       min="0" 
                       value="{{ $grupo->meta_bimestre_5 }}"
                       style="width: 100%; padding: 0.625rem 0.75rem; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 0.875rem; outline: none; transition: border-color 0.2s;"
                       onfocus="this.style.borderColor='#3b82f6'" 
                       onblur="this.style.borderColor='#cbd5e1'">
            </div>

            <!-- Bimestre 6 -->
            <div>
                <label for="meta_bimestre_6" style="display: block; color: #475569; font-size: 0.875rem; font-weight: 500; margin-bottom: 0.5rem;">
                    Bimestre 6 <span style="color: #94a3b8; font-weight: 400;">(Nov-Dic)</span>
                </label>
                <input type="number" 
                       id="meta_bimestre_6" 
                       name="meta_bimestre_6" 
                       min="0" 
                       value="{{ $grupo->meta_bimestre_6 }}"
                       style="width: 100%; padding: 0.625rem 0.75rem; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 0.875rem; outline: none; transition: border-color 0.2s;"
                       onfocus="this.style.borderColor='#3b82f6'" 
                       onblur="this.style.borderColor='#cbd5e1'">
            </div>
        </div>

        <!-- Botón guardar -->
        <div style="display: flex; gap: 0.75rem; justify-content: flex-end; padding-top: 1rem; border-top: 1px solid #e2e8f0;">
            <a href="{{ route('grupotrabajo.index') }}" 
               style="padding: 0.625rem 1.5rem; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 0.875rem; font-weight: 500; color: #64748b; text-decoration: none; transition: all 0.2s; display: inline-block;"
               onmouseover="this.style.background='#f8fafc'; this.style.borderColor='#94a3b8'" 
               onmouseout="this.style.background='white'; this.style.borderColor='#cbd5e1'">
                Cancelar
            </a>
            <button type="submit" 
                    style="background: #3b82f6; color: white; padding: 0.625rem 1.5rem; border: none; border-radius: 6px; font-size: 0.875rem; font-weight: 500; cursor: pointer; transition: background 0.2s;"
                    onmouseover="this.style.background='#2563eb'" 
                    onmouseout="this.style.background='#3b82f6'">
                Guardar cambios
            </button>
        </div>
    </form>
</div>
@endsection
