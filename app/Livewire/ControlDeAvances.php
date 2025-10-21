<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Documento;

class ControlDeAvances extends Component
{
    public $busqueda = '';

    public function buscar()
    {
        // Este método se ejecutará cuando se presione el botón de buscar
        $this->render();
    }

    public function limpiar()
    {
        $this->busqueda = '';
    }

    public function eliminar($id)
    {
        try {
            $documento = Documento::findOrFail($id);
            $documento->delete();
            
            session()->flash('success', 'Documento eliminado correctamente.');
        } catch (\Exception $e) {
            session()->flash('error', 'Error al eliminar el documento: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $query = Documento::with(['libroRelacion', 'temaRelacion', 'parteRelacion', 'tituloRelacion', 'etapas', 'info']);
        
        if (!empty($this->busqueda)) {
            $query->where(function($q) {
                $q->where('nombre', 'like', '%' . $this->busqueda . '%')
                  ->orWhereHas('info', function($subQuery) {
                      $subQuery->where('designacion', 'like', '%' . $this->busqueda . '%');
                  });
            });
        }
        
        $documentos = $query->get();
        
        return view('livewire.control-de-avances', compact('documentos'));
    }
}