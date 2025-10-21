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
        $documentos = Documento::with(['libroRelacion', 'parteRelacion', 'etapas'])
            ->where('vigente', 1)
            ->orderBy('libro')
            ->orderBy('tema')
            ->orderBy('parte')
            ->orderBy('titulo')
            ->orderBy('capitulo')
            ->orderBy('origen')
            ->orderBy('anio')
            ->get();

        $documentosAgrupados = $documentos->groupBy('nombre');
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

            $etapas = $documentoPrincipal->etapas;

            if (
                $etapas &&
                $etapas->{'3a'} &&
                $etapas->{'3b'} &&
                $etapas->{'3c'} &&
                $etapas->{'3d'} &&
                $etapas->{'3e'}
            ) {
                $documentosProcesados[] = $documentoPrincipal;
            }
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