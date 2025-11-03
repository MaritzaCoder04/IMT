@extends('home')

@section('contenido')

<div class="all-form">
        
        <form action="{{ route('grupotrabajo.store') }}" method="POST">
            @csrf
            <input type="hidden" name="modal" value="{{ request('modal') ? 1 : '' }}">
            
            <div class="form-group">
                <label for="nombre">Nombre del Grupo</label>
                <input type="text" id="nombre" name="nombre" list="grupos-list" required placeholder="Escribe un grupo o selecciona uno" oninput="actualizarEtiquetas()">
                <datalist id="grupos-list">
                    @foreach($nombresGrupos as $nombre)
                        <option value="{{ $nombre }}"></option>
                    @endforeach
                </datalist>
            </div>



            <div class="form-row">
                <div class="form-group">
                    <label for="meta_bimestre_1" id="label_bimestre_1">Bimestre 1 (Ene-Feb)</label>
                    <input type="number" id="meta_bimestre_1" name="meta_bimestre_1" min="0" required>
                </div>

                <div class="form-group">
                    <label for="meta_bimestre_2" id="label_bimestre_2">Bimestre 2 (Mar-Abr)</label>
                    <input type="number" id="meta_bimestre_2" name="meta_bimestre_2" min="0" required>
                </div>

                <div class="form-group">
                    <label for="meta_bimestre_3" id="label_bimestre_3">Bimestre 3 (May-Jun)</label>
                    <input type="number" id="meta_bimestre_3" name="meta_bimestre_3" min="0" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="meta_bimestre_4" id="label_bimestre_4">Bimestre 4 (Jul-Ago)</label>
                    <input type="number" id="meta_bimestre_4" name="meta_bimestre_4" min="0" required>
                </div>

                <div class="form-group">
                    <label for="meta_bimestre_5" id="label_bimestre_5">Bimestre 5 (Sep-Oct)</label>
                    <input type="number" id="meta_bimestre_5" name="meta_bimestre_5" min="0" required>
                </div>

                <div class="form-group">
                    <label for="meta_bimestre_6" id="label_bimestre_6">Bimestre 6 (Nov-Dic)</label>
                    <input type="number" id="meta_bimestre_6" name="meta_bimestre_6" min="0" required>
                </div>
            </div>

            <div class="actions">
                <button type="submit" class="btn btn-secondary">Guardar</button>
                <button type="button" class="btn btn-secondary2" onclick="(function(){var p=new URLSearchParams(location.search); var isModal=p.get('modal')==='1' || window.top!==window.self; if(isModal){ try{ window.parent && window.parent.postMessage({ type:'modal-close', saved:false }, '*'); }catch(e){} } else { window.location='{{ route('grupotrabajo.index') }}'; } })()">Cancelar</button>
            </div>
        </form>
    </div>
</div>

<script>
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