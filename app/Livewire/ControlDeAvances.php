<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Documento;

class ControlDeAvances extends Component
{
    public $busqueda = '';
    public $palabraExacta = '';

    public function limpiar()
    {
        $this->busqueda = '';
        $this->palabraExacta = '';
    }

    public function eliminar($id)
    {
        try {
            $documento = Documento::findOrFail($id);
            $documento->delete();
            
            session()->flash('success');
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

        // Filtro por palabra exacta (excluye libro y designación)
        if (!empty($this->palabraExacta)) {
            $pattern = '[[:<:]]' . preg_quote($this->palabraExacta, '/') . '[[:>:]]';
            $query->where(function($q) use ($pattern) {
                $q->whereRaw('nombre REGEXP ?', [$pattern])
                  ->orWhereHas('info', function($subQ) use ($pattern) {
                      $subQ->whereRaw('origen REGEXP ?', [$pattern]);
                  });
            });
        }
        
        $documentos = $query->get();

        // Calcular "Última Fecha" por documento: último año registrado en fechas de Entrega (2a–2e) y Terminación (3a–3e)
        foreach ($documentos as $doc) {
            $e = $doc->etapas ?? null;
            $campos = ['2a','2b','2c','2d','2e','3a','3b','3c','3d','3e'];
            $years = [];
            if ($e) {
                foreach ($campos as $c) {
                    $v = $e->{$c} ?? null;
                    if (!empty($v)) {
                        $years[] = (int) date('Y', strtotime($v));
                    }
                }
            }
            $doc->ultima_fecha = !empty($years) ? max($years) : null;
        }
        
        return view('livewire.control-de-avances', compact('documentos'));
    }
}