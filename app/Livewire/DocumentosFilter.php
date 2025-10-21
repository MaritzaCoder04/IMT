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
        $query = Documento::with(['info', 'libroRelacion', 'temaRelacion', 'parteRelacion', 'tituloRelacion', 'tipoRelacion']);

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

    public function descargarSQL()
    {
        // Obtener los mismos datos que se muestran en la tabla
        $libros = Libro::orderBy('desc')->get();
        $query = Documento::with(['info', 'libroRelacion', 'temaRelacion', 'parteRelacion', 'tituloRelacion', 'tipoRelacion']);

        // Aplicar los mismos filtros
        if (!empty($this->palabra)) {
            $query->where(function($q) {
                $q->where('nombre', 'like', '%' . $this->palabra . '%')
                  ->orWhere('origen', 'like', '%' . $this->palabra . '%')
                  ->orWhereHas('info', function($subQ) {
                      $subQ->where('designacion', 'like', '%' . $this->palabra . '%');
                  });
            });
        }

        if (!empty($this->designacion)) {
            $query->whereHas('info', function($q) {
                $q->where('designacion', 'like', '%' . $this->designacion . '%');
            });
        }

        if (!empty($this->libro)) {
            $query->where('libro', $this->libro);
        }

        $documentos = $query->get();

        // Procesar documentos con la misma lógica de agrupación
        $documentosAgrupados = $documentos->groupBy(function ($documento) {
            return $documento->nombre . '|' . $documento->tipo . '|' . $documento->libro . '|' . $documento->tema . '|' . $documento->parte . '|' . $documento->titulo . '|' . $documento->capitulo;
        });

        $documentosProcesados = [];

        foreach ($documentosAgrupados as $grupo) {
            $documentoPrincipal = $grupo->first();
            
            $fechas = $grupo->pluck('anio')->filter()->sort()->values();
            $primeraFecha = $fechas->first();
            $actualizaciones = $fechas->slice(1)->toArray();

            // Aplicar filtro por año
            $incluirDocumento = true;
            if (!empty($this->anio)) {
                $anioCoincide = false;
                
                if ($primeraFecha && $primeraFecha == $this->anio) {
                    $anioCoincide = true;
                }
                
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

        // Generar contenido SQL con la estructura de la tabla documento
        $sqlContent = "-- Exportación de datos de la tabla documento\n";
        $sqlContent .= "-- Generado el: " . date('Y-m-d H:i:s') . "\n\n";
        
        if (!empty($documentosProcesados)) {
            $sqlContent .= "-- Total de registros: " . count($documentosProcesados) . "\n\n";
            
            foreach ($documentosProcesados as $documento) {
                // Usar los valores originales de la tabla documento
                $ID_doc = $documento->ID_doc;
                $nombre = addslashes($documento->nombre);
                $tipo = $documento->tipo;
                $libro = $documento->libro;
                $tema = $documento->tema;
                $parte = $documento->parte;
                $titulo = $documento->titulo;
                $capitulo = $documento->capitulo ?? 0;
                $origen = $documento->origen;
                $anio_simple = $documento->anio_simple ?? 0;
                $anio = $documento->anio;
                $terracerias = $documento->terracerias ?? 0;
                $estructuras = $documento->estructuras ?? 0;
                $drenaje = $documento->drenaje ?? 0;
                $pavimentos = $documento->pavimentos ?? 0;
                $tuneles = $documento->tuneles ?? 0;
                $cimentaciones = $documento->cimentaciones ?? 0;
                $senalamiento = $documento->senalamiento ?? 0;
                $obras_marginales = $documento->obras_marginales ?? 0;
                $SIT = $documento->SIT ?? 0;
                $novedades = addslashes($documento->novedades ?? '0');
                $vigente = $documento->vigente ?? 1;
                
                $sqlContent .= "INSERT INTO `documento` (`ID_doc`, `nombre`, `tipo`, `libro`, `tema`, `parte`, `titulo`, `capitulo`, `origen`, `anio_simple`, `anio`, `terracerias`, `estructuras`, `drenaje`, `pavimentos`, `tuneles`, `cimentaciones`, `senalamiento`, `obras_marginales`, `SIT`, `novedades`, `vigente`) VALUES\n";
                $sqlContent .= "(" . $ID_doc . ", '" . $nombre . "', " . $tipo . ", " . $libro . ", " . $tema . ", " . $parte . ", " . $titulo . ", " . $capitulo . ", " . $origen . ", " . $anio_simple . ", " . ($anio ? $anio : 'NULL') . ", " . $terracerias . ", " . $estructuras . ", " . $drenaje . ", " . $pavimentos . ", " . $tuneles . ", " . $cimentaciones . ", " . $senalamiento . ", " . $obras_marginales . ", " . $SIT . ", '" . $novedades . "', " . $vigente . ");\n\n";
            }
        } else {
            $sqlContent .= "-- No hay registros para exportar con los filtros aplicados\n";
        }

        // Crear respuesta de descarga
        $fileName = 'documento_' . date('Y-m-d_H-i-s') . '.sql';
        
        return response()->streamDownload(function() use ($sqlContent) {
            echo $sqlContent;
        }, $fileName, [
            'Content-Type' => 'application/sql',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"'
        ]);
    }
}
