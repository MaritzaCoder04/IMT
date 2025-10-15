<?php

namespace App\Http\Controllers;

use App\Models\Documento;

class ControlDeAvancesController extends Controller
{
    public function index()
    {
        // Cargar TODOS con sus etapas y relaciones
        $documentos = Documento::with(['libroRelacion', 'temaRelacion', 'parteRelacion', 'tituloRelacion', 'etapas'])->get();
        
        return view('controldeavances', compact('documentos'));
    }
}