<?php

namespace App\Http\Controllers;

use App\Models\Documento;
use App\Models\Etapa;
use App\Models\EtapaEvento;
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

        // Registrar eventos (checks de entrega 2x y terminación 3x) según inputs de check
        $checks = [
            // Inicio (1x) – no afecta la barra, pero permite sombrear y avisos
            '1a' => $request->boolean('complete_1a'),
            '1b' => $request->boolean('complete_1b'),
            '1c' => $request->boolean('complete_1c'),
            '1d' => $request->boolean('complete_1d'),
            '1e' => $request->boolean('complete_1e'),
            '2a' => $request->boolean('complete_2a'),
            '2b' => $request->boolean('complete_2b'),
            '2c' => $request->boolean('complete_2c'),
            '2d' => $request->boolean('complete_2d'),
            '2e' => $request->boolean('complete_2e'),
            '3a' => $request->boolean('complete_3a'),
            '3b' => $request->boolean('complete_3b'),
            '3c' => $request->boolean('complete_3c'),
            '3d' => $request->boolean('complete_3d'),
            '3e' => $request->boolean('complete_3e'),
        ];

        foreach ($checks as $etapaClave => $checked) {
            $fechaEtapa = $etapas->{$etapaClave} ?? null;
            if ($checked && !empty($fechaEtapa)) {
                // Crear o asegurar evento existente para esta etapa y fecha
                EtapaEvento::firstOrCreate([
                    'ID_doc' => (int) $ID_doc,
                    'etapa' => $etapaClave,
                    'fecha' => $fechaEtapa,
                ]);
            } else {
                // Si no está marcado, eliminar eventos de esta etapa para el documento
                EtapaEvento::where('ID_doc', (int) $ID_doc)
                    ->where('etapa', $etapaClave)
                    ->delete();
            }
        }
        
        $target = route('controldeavances');
        if ($request->boolean('modal')) {
            return redirect()->to($target.'?modal=1&saved=1')->with('success');
        }
        return redirect()->to($target)->with('success');
    }
}