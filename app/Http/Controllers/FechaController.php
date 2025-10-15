<?php

namespace App\Http\Controllers;

use App\Http\Requests\GuardarClasificacionRequest;

class FechaController extends Controller
{
    public function create()
    {
        return view('formfechas'); // resources/views/formfechas.blade.php
    }

    public function store(GuardarClasificacionRequest $request)
    {
        $data = $request->validated(); // IDs validados (libro, parte, tema, título)
        // TODO: guarda $data donde corresponda (tabla fechas/documentos, etc.)
        return back()->with('ok','Clasificación guardada');
    }
}
