@extends('home')

@section('contenido')

<div class="all-form">
        
        <form id="create-grupo-form" action="{{ route('grupotrabajo.store') }}" method="POST" style="background: white; border-radius: 8px; padding: 2rem;">
            @csrf
            <input type="hidden" name="modal" value="{{ request('modal') ? 1 : '' }}">
            
            <div style="margin-bottom: 1.5rem;">
                <label for="nombre" style="display: block; color: #475569; font-size: 0.875rem; font-weight: 500; margin-bottom: 0.5rem;">Nombre del grupo</label>
                <input type="text" id="nombre" name="nombre" list="grupos-list" required placeholder="Escribe un grupo o selecciona uno" oninput="actualizarEtiquetas()" style="width: 100%; padding: 0.625rem 0.75rem; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 0.875rem; outline: none; transition: border-color 0.2s; background: white;" onfocus="this.style.borderColor='#3b82f6'" onblur="this.style.borderColor='#cbd5e1'">
                <datalist id="grupos-list">
                    @foreach($nombresGrupos as $nombre)
                        <option value="{{ $nombre }}"></option>
                    @endforeach
                </datalist>
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label for="anio_meta" style="display: block; color: #475569; font-size: 0.875rem; font-weight: 500; margin-bottom: 0.5rem;">Año</label>
                <input type="number" id="anio_meta" name="anio_meta" required placeholder="Ej: {{ date('Y') }}" value="{{ request('anio') }}" style="width: 100%; padding: 0.625rem 0.75rem; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 0.875rem; outline: none; transition: border-color 0.2s; background: white;" onfocus="this.style.borderColor='#3b82f6'" onblur="this.style.borderColor='#cbd5e1'">
                <small style="color:#64748b;">El grupo se guardará asociado a este año.</small>
            </div>
            <div style="border-top: 1px solid #e2e8f0; margin: 2rem 0;"></div>



            <div style="display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 1.25rem; margin-bottom: 1.25rem;">
                <div class="form-group">
                    <label for="meta_bimestre_1" id="label_bimestre_1" style="display: block; color: #475569; font-size: 0.875rem; font-weight: 500; margin-bottom: 0.5rem;">Bimestre 1 <span style="color: #94a3b8; font-weight: 400;">(Ene-Feb)</span></label>
                    <input type="number" id="meta_bimestre_1" name="meta_bimestre_1" min="0" required style="width: 100%; padding: 0.625rem 0.75rem; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 0.875rem; outline: none; transition: border-color 0.2s;" onfocus="this.style.borderColor='#3b82f6'" onblur="this.style.borderColor='#cbd5e1'">
                </div>

                <div class="form-group">
                    <label for="meta_bimestre_2" id="label_bimestre_2" style="display: block; color: #475569; font-size: 0.875rem; font-weight: 500; margin-bottom: 0.5rem;">Bimestre 2 <span style="color: #94a3b8; font-weight: 400;">(Mar-Abr)</span></label>
                    <input type="number" id="meta_bimestre_2" name="meta_bimestre_2" min="0" required style="width: 100%; padding: 0.625rem 0.75rem; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 0.875rem; outline: none; transition: border-color 0.2s;" onfocus="this.style.borderColor='#3b82f6'" onblur="this.style.borderColor='#cbd5e1'">
                </div>

                <div class="form-group">
                    <label for="meta_bimestre_3" id="label_bimestre_3" style="display: block; color: #475569; font-size: 0.875rem; font-weight: 500; margin-bottom: 0.5rem;">Bimestre 3 <span style="color: #94a3b8; font-weight: 400;">(May-Jun)</span></label>
                    <input type="number" id="meta_bimestre_3" name="meta_bimestre_3" min="0" required style="width: 100%; padding: 0.625rem 0.75rem; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 0.875rem; outline: none; transition: border-color 0.2s;" onfocus="this.style.borderColor='#3b82f6'" onblur="this.style.borderColor='#cbd5e1'">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 1.25rem; margin-bottom: 2rem;">
                <div class="form-group">
                    <label for="meta_bimestre_4" id="label_bimestre_4" style="display: block; color: #475569; font-size: 0.875rem; font-weight: 500; margin-bottom: 0.5rem;">Bimestre 4 <span style="color: #94a3b8; font-weight: 400;">(Jul-Ago)</span></label>
                    <input type="number" id="meta_bimestre_4" name="meta_bimestre_4" min="0" required style="width: 100%; padding: 0.625rem 0.75rem; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 0.875rem; outline: none; transition: border-color 0.2s;" onfocus="this.style.borderColor='#3b82f6'" onblur="this.style.borderColor='#cbd5e1'">
                </div>

                <div class="form-group">
                    <label for="meta_bimestre_5" id="label_bimestre_5" style="display: block; color: #475569; font-size: 0.875rem; font-weight: 500; margin-bottom: 0.5rem;">Bimestre 5 <span style="color: #94a3b8; font-weight: 400;">(Sep-Oct)</span></label>
                    <input type="number" id="meta_bimestre_5" name="meta_bimestre_5" min="0" required style="width: 100%; padding: 0.625rem 0.75rem; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 0.875rem; outline: none; transition: border-color 0.2s;" onfocus="this.style.borderColor='#3b82f6'" onblur="this.style.borderColor='#cbd5e1'">
                </div>

                <div class="form-group">
                    <label for="meta_bimestre_6" id="label_bimestre_6" style="display: block; color: #475569; font-size: 0.875rem; font-weight: 500; margin-bottom: 0.5rem;">Bimestre 6 <span style="color: #94a3b8; font-weight: 400;">(Nov-Dic)</span></label>
                    <input type="number" id="meta_bimestre_6" name="meta_bimestre_6" min="0" required style="width: 100%; padding: 0.625rem 0.75rem; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 0.875rem; outline: none; transition: border-color 0.2s;" onfocus="this.style.borderColor='#3b82f6'" onblur="this.style.borderColor='#cbd5e1'">
                </div>
            </div>

            <div style="display: flex; gap: 0.75rem; justify-content: flex-end; padding-top: 1rem;">
                @if(request('modal'))
                <a href="#" onclick="try{window.parent && window.parent.postMessage({ type: 'modal-close' }, '*');}catch(e){} return false;" style="padding: 0.625rem 1.5rem; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 0.875rem; font-weight: 500; color: #64748b; text-decoration: none; transition: all 0.2s; display: inline-block;" onmouseover="this.style.background='#f8fafc'; this.style.borderColor='#94a3b8'" onmouseout="this.style.background='white'; this.style.borderColor='#cbd5e1'">Cancelar</a>
                @else
                <a href="{{ route('grupotrabajo.index') }}@if(request('anio'))?anio={{ request('anio') }}@endif" style="padding: 0.625rem 1.5rem; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 0.875rem; font-weight: 500; color: #64748b; text-decoration: none; transition: all 0.2s; display: inline-block;" onmouseover="this.style.background='#f8fafc'; this.style.borderColor='#94a3b8'" onmouseout="this.style.background='white'; this.style.borderColor='#cbd5e1'">Cancelar</a>
                @endif
                <button type="submit" style="background: #3b82f6; color: white; padding: 0.625rem 1.5rem; border: none; border-radius: 6px; font-size: 0.875rem; font-weight: 500; cursor: pointer; transition: background 0.2s;" onmouseover="this.style.background='#2563eb'" onmouseout="this.style.background='#3b82f6'">Guardar</button>
            </div>
        </form>
    </div>
</div>

<script>
// Prefijar el año con ?anio o localStorage si no viene en la URL
document.addEventListener('DOMContentLoaded', function(){
    const anioInput = document.getElementById('anio_meta');
    if (anioInput && !anioInput.value) {
        let anio = null;
        try {
            const params = new URLSearchParams(window.location.search);
            anio = params.get('anio');
            if (!anio) { anio = localStorage.getItem('anioGlobal'); }
        } catch(e) {}
        if (!anio) { anio = new Date().getFullYear(); }
        anioInput.value = anio;
    }
});

function actualizarEtiquetas() {
    const select = document.getElementById('nombre');
    const selectedValue = select ? select.value : '';
    
    const gruposFijos = [
        'Anteproyecto Preliminar',
        'Anteproyecto Final', 
        'Proyecto Preliminar',
        'Publicación de Manuales/Normas'
    ];
    
    const esGrupoFijo = gruposFijos.includes(selectedValue);
    
    const setText = (id, text) => { const el = document.getElementById(id); if (el) el.textContent = text; };
    
    if (esGrupoFijo) {
        setText('label_meta_anual', 'Meta Anual de Documentos');
        setText('help_meta_anual', 'Número de documentos que se planea terminar en el año');
        setText('titulo_metas_bimestrales', 'Metas Bimestrales de Documentos');
        setText('label_bimestre_1', 'Documentos Bimestre 1 (Ene-Feb)');
        setText('label_bimestre_2', 'Documentos Bimestre 2 (Mar-Abr)');
        setText('label_bimestre_3', 'Documentos Bimestre 3 (May-Jun)');
        setText('label_bimestre_4', 'Documentos Bimestre 4 (Jul-Ago)');
        setText('label_bimestre_5', 'Documentos Bimestre 5 (Sep-Oct)');
        setText('label_bimestre_6', 'Documentos Bimestre 6 (Nov-Dic)');
    } else {
        setText('label_meta_anual', 'Meta Anual');
        setText('help_meta_anual', '');
        setText('titulo_metas_bimestrales', 'Metas Bimestrales');
        setText('label_bimestre_1', 'Bimestre 1 (Ene-Feb)');
        setText('label_bimestre_2', 'Bimestre 2 (Mar-Abr)');
        setText('label_bimestre_3', 'Bimestre 3 (May-Jun)');
        setText('label_bimestre_4', 'Bimestre 4 (Jul-Ago)');
        setText('label_bimestre_5', 'Bimestre 5 (Sep-Oct)');
        setText('label_bimestre_6', 'Bimestre 6 (Nov-Dic)');
    }
}
// Calcular meta anual automáticamente a partir de metas bimestrales
function calcularMetaAnual() {
    const val = (id) => parseInt(document.getElementById(id)?.value || '0', 10);
    const total = val('meta_bimestre_1') + val('meta_bimestre_2') + val('meta_bimestre_3') +
                  val('meta_bimestre_4') + val('meta_bimestre_5') + val('meta_bimestre_6');
    const metaAnualInput = document.getElementById('meta_anual');
    if (metaAnualInput) metaAnualInput.value = total;
}

['meta_bimestre_1','meta_bimestre_2','meta_bimestre_3','meta_bimestre_4','meta_bimestre_5','meta_bimestre_6']
    .forEach(id => {
        const el = document.getElementById(id);
        if (el) {
            el.addEventListener('input', calcularMetaAnual);
            el.addEventListener('change', calcularMetaAnual);
        }
    });

// Inicializar cálculo al cargar
document.addEventListener('DOMContentLoaded', calcularMetaAnual);
</script>

@endsection