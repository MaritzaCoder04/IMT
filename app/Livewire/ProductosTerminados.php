<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Documento;
use App\Models\Parte;

class ProductosTerminados extends Component
{
    public $busqueda = '';

    public function limpiar()
    {
        $this->busqueda = '';
    }

    public function render()
    {
        // Primero obtener todos los documentos vigentes
        $documentos = Documento::where('vigente', 1)
            ->orderBy('libro')
            ->orderBy('tema')
            ->orderBy('parte')
            ->orderBy('titulo')
            ->orderBy('capitulo')
            ->orderBy('origen')
            ->orderBy('anio')
            ->get();

        // Luego filtrar solo los que tienen etapas completas
        $documentosConEtapasCompletas = [];
        
        foreach ($documentos as $documento) {
            $etapas = \App\Models\Etapa::where('ID_doc', $documento->ID_doc)->first();
            
            if ($etapas && 
                !empty($etapas->{'3a'}) && 
                !empty($etapas->{'3b'}) && 
                !empty($etapas->{'3c'}) && 
                !empty($etapas->{'3d'}) && 
                !empty($etapas->{'3e'})) {
                
                // Cargar las relaciones necesarias
                $documento->load(['libroRelacion', 'parteRelacion', 'info']);
                $documento->etapas = $etapas;
                $documentosConEtapasCompletas[] = $documento;
            }
        }

        $documentosAgrupados = collect($documentosConEtapasCompletas)->groupBy('nombre');
        $documentosProcesados = [];

        foreach ($documentosAgrupados as $nombre => $grupo) {
            $grupoOrdenado = $grupo->sortBy('anio');

            $primeraFecha = $grupoOrdenado->first()->anio;
            $actualizaciones = $grupoOrdenado->skip(1)->pluck('anio')->toArray();

            $documentoPrincipal = $grupoOrdenado->first();
            $documentoPrincipal->fecha_nueva = $primeraFecha;
            $documentoPrincipal->fechas_actualizacion = !empty($actualizaciones)
                ? implode(', ', $actualizaciones)
                : null;

            // Determinar la última fecha y su tipo
            $ultimaFecha = $primeraFecha;
            $tipoUltimaFecha = 'nueva';
            $documentoUltimaFecha = $documentoPrincipal; // Por defecto el primer documento
            
            if (!empty($actualizaciones)) {
                $ultimaActualizacion = max($actualizaciones);
                if ($ultimaActualizacion > $primeraFecha) {
                    $ultimaFecha = $ultimaActualizacion;
                    $tipoUltimaFecha = 'actualizacion';
                    // Buscar el documento que corresponde a la última actualización
                    $documentoUltimaFecha = $grupoOrdenado->where('anio', $ultimaActualizacion)->first();
                }
            }
            
            // Asignar los datos del documento con la última fecha
            $documentoPrincipal->ultima_fecha = $ultimaFecha;
            $documentoPrincipal->tipo_ultima_fecha = $tipoUltimaFecha;
            $documentoPrincipal->origen_ultima_fecha = $documentoUltimaFecha->origen;
            $documentoPrincipal->designacion_ultima_fecha = $documentoUltimaFecha->designacion;

            // Ya sabemos que todos los documentos tienen etapas completas, así que agregamos directamente
            $documentosProcesados[] = $documentoPrincipal;
        }

        // Aplicar filtro de búsqueda si existe
        if (!empty($this->busqueda)) {
            $documentosProcesados = array_filter($documentosProcesados, function($documento) {
                return stripos($documento->nombre, $this->busqueda) !== false ||
                       stripos($documento->designacion, $this->busqueda) !== false ||
                       stripos($documento->origen, $this->busqueda) !== false;
            });
        }

        $partes = Parte::all();

        return view('livewire.productos-terminados', compact('documentos', 'documentosProcesados', 'partes'));
    }
}