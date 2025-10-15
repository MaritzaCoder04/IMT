<?php

namespace App\Http\Controllers;

use App\Models\Documento;
use App\Models\Etapa;
use Illuminate\Http\Request;

class EtapaController extends Controller
{
    /*public function show($ID_doc)
    {
        $documento = Documento::findOrFail($ID_doc);
        
        // Buscar o crear etapas para este documento
        //$etapas = Etapa::firstOrNew(['ID_doc' => $ID_doc]);
        ///$etapas = Etapa::where('ID_doc', $ID_doc)->first();
        //$etapas = Etapa::firstOrCreate(['ID_doc' => $ID_doc]);
        $etapas = Etapa::where('ID_doc', $documento->ID_doc)->first();

        
        return view('etapas', compact('documento', 'etapas'));
    }*/
    public function show($ID_doc)
{
    $documento = Documento::findOrFail($ID_doc);

    // Buscar o crear una fila de etapas si no existe
    $etapas = Etapa::firstOrCreate(['ID_doc' => $documento->ID_doc]);

    return view('etapas', compact('documento', 'etapas'));
}

    
    public function guardar(Request $request, $ID_doc)
    {
        $etapas = Etapa::firstOrNew(['ID_doc' => $ID_doc]);
        
        // Guardar todas las fechas (solo las que tengan valor)
        $etapas->fill([
            '1a' => $request->etapa1_periodo1,
            '2a' => $request->etapa1_periodo2,
            '3a' => $request->etapa1_periodo3,
            
            '1b' => $request->etapa2_periodo1,
            '2b' => $request->etapa2_periodo2,
            '3b' => $request->etapa2_periodo3,
            
            '1c' => $request->etapa3_periodo1,
            '2c' => $request->etapa3_periodo2,
            '3c' => $request->etapa3_periodo3,
            
            '1d' => $request->etapa4_periodo1,
            '2d' => $request->etapa4_periodo2,
            '3d' => $request->etapa4_periodo3,
            
            '1e' => $request->etapa5_periodo1,
            '2e' => $request->etapa5_periodo2,
            '3e' => $request->etapa5_periodo3,
        ]);
        
        $etapas->save();
        
        return redirect()->route('controldeavances')->with('success');
    }
}