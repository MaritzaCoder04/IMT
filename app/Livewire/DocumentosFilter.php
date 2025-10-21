<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Documento;
use App\Models\Libro;

class DocumentosFilter extends Component
{
    public $palabra = '';
    public $designacion = '';
    public $libro = '';
    public $anio = '';

    public function render()
    {
        // Obtener libros para el select dinámico
        $libros = Libro::orderBy('desc')->get();

        // Construir la consulta con filtros
        $query = Documento::with(['info', 'libroRelacion', 'temaRelacion', 'parteRelacion', 'tituloRelacion']);

        // Filtro por palabra (busca en nombre, origen y designación)
        if (!empty($this->palabra)) {
            $query->where(function($q) {
                $q->where('nombre', 'like', '%' . $this->palabra . '%')
                  ->orWhere('origen', 'like', '%' . $this->palabra . '%')
                  ->orWhereHas('info', function($subQ) {
                      $subQ->where('designacion', 'like', '%' . $this->palabra . '%');
                  });
            });
        }

        // Filtro por designación específica
        if (!empty($this->designacion)) {
            $query->whereHas('info', function($q) {
                $q->where('designacion', 'like', '%' . $this->designacion . '%');
            });
        }

        // Filtro por libro
        if (!empty($this->libro)) {
            $query->where('libro', $this->libro);
        }

        $documentos = $query->get();

        // Procesar documentos agrupados (lógica original)
        $documentosAgrupados = $documentos->groupBy(function ($documento) {
            return $documento->nombre . '|' . $documento->tipo . '|' . $documento->libro . '|' . $documento->tema . '|' . $documento->parte . '|' . $documento->titulo . '|' . $documento->capitulo;
        });

        $documentosProcesados = [];

        foreach ($documentosAgrupados as $grupo) {
            $documentoPrincipal = $grupo->first();
            
            $fechas = $grupo->pluck('anio')->filter()->sort()->values();
            $primeraFecha = $fechas->first();
            $actualizaciones = $fechas->slice(1)->toArray();

            $documentoPrincipal->fecha_nueva = $primeraFecha;
            $documentoPrincipal->fechas_actualizacion = !empty($actualizaciones) ? implode(', ', $actualizaciones) : null;

            // Filtro por año - busca en fecha nueva y actualizaciones
            $incluirDocumento = true;
            if (!empty($this->anio)) {
                $anioCoincide = false;
                
                // Verificar si el año coincide con la fecha nueva
                if ($primeraFecha && $primeraFecha == $this->anio) {
                    $anioCoincide = true;
                }
                
                // Verificar si el año coincide con alguna actualización
                if (!$anioCoincide && !empty($actualizaciones)) {
                    foreach ($actualizaciones as $fechaActualizacion) {
                        if ($fechaActualizacion == $this->anio) {
                            $anioCoincide = true;
                            break;
                        }
                    }
                }
                
                if (!$anioCoincide) {
                    $incluirDocumento = false;
                }
            }

            if ($incluirDocumento) {
                $documentosProcesados[] = $documentoPrincipal;
            }
        }

        return view('livewire.documentos-filter', [
            'documentosProcesados' => $documentosProcesados,
            'libros' => $libros
        ]);
    }

    public function limpiar()
    {
        $this->palabra = '';
        $this->designacion = '';
        $this->libro = '';
        $this->anio = '';
    }
}
