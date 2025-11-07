<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Documento;
use App\Models\Parte;
use App\Models\Etapa;
use App\Models\EtapaEvento;

class ProductosTerminados extends Component
{
    public $busqueda = '';
    public $palabraExacta = '';

    public function limpiar()
    {
        $this->busqueda = '';
        $this->palabraExacta = '';
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

        // Luego filtrar solo los que tienen checklist de terminación completo (3a, 3b, 3c, 3d, 3e)
        $documentosConEtapasCompletas = [];
        $etapasRequeridas = ['3a', '3b', '3c', '3d', '3e'];
        
        foreach ($documentos as $documento) {
            // Verificar existencia de eventos para todas las etapas requeridas (checklist marcado)
            $etapasEvento = EtapaEvento::where('ID_doc', $documento->ID_doc)
                ->whereIn('etapa', $etapasRequeridas)
                ->distinct()
                ->pluck('etapa')
                ->toArray();

            $tieneChecklistCompleto = count(array_intersect($etapasRequeridas, $etapasEvento)) === count($etapasRequeridas);

            if ($tieneChecklistCompleto) {
                // Cargar relaciones y las fechas de etapas para desplegar en la tabla
                $documento->load(['libroRelacion', 'parteRelacion', 'info', 'origenRelacion']);
                $documento->etapas = Etapa::where('ID_doc', $documento->ID_doc)->first();
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
                return (
                    isset($documento->nombre) && stripos($documento->nombre, $this->busqueda) !== false
                ) || (
                    isset($documento->designacion) && stripos($documento->designacion, $this->busqueda) !== false
                ) || (
                    // origen textual suele venir desde info
                    (isset($documento->info) && isset($documento->info->origen) && stripos($documento->info->origen, $this->busqueda) !== false)
                    || (isset($documento->origen) && stripos((string)$documento->origen, $this->busqueda) !== false)
                );
            });
        }

        // Filtro por palabra exacta (excluye libro y designación)
        if (!empty($this->palabraExacta)) {
            $pattern = '/\\b' . preg_quote($this->palabraExacta, '/') . '\\b/i';
            $documentosProcesados = array_filter($documentosProcesados, function($documento) use ($pattern) {
                $matchNombre = isset($documento->nombre) && preg_match($pattern, $documento->nombre);
                $matchOrigen = (isset($documento->info) && isset($documento->info->origen) && preg_match($pattern, $documento->info->origen))
                               || (isset($documento->origen) && preg_match($pattern, (string)$documento->origen));
                return $matchNombre || $matchOrigen;
            });
        }

        $partes = Parte::all();

        return view('livewire.productos-terminados', compact('documentos', 'documentosProcesados', 'partes'));
    }
}