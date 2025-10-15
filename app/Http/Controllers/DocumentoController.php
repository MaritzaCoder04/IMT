<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Documento;
use App\Models\Etapa;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use App\Models\Parte;

class DocumentoController extends Controller
{

    public function exportarProcesadosSQL()
    {
        $documentos = Documento::with(['libroRelacion', 'temaRelacion'])
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
            $documentoPrincipal->fechas_actualizacion = !empty($actualizaciones) ? implode(', ', $actualizaciones) : null;

            $documentosProcesados[] = $documentoPrincipal;
        }

        // Generar el SQL
        $sql = "INSERT INTO documento (tipo, libro, tema, parte, titulo, capitulo, designacion, nombre, origen, fecha_nueva, fechas_actualizacion) VALUES\n";

        $values = [];
        foreach ($documentosProcesados as $d) {
            $values[] = sprintf(
                "(%d, '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s')",
                $d->tipo,
                addslashes($d->libroRelacion->desc ?? $d->libro),
                addslashes($d->temaRelacion->desc ?? $d->tema),
                addslashes($d->parte),
                addslashes($d->titulo),
                addslashes($d->capitulo),
                addslashes($d->designacion ?? '--'),
                addslashes($d->nombre ?? '--'),
                addslashes($d->origen ?? '--'),
                addslashes($d->fecha_nueva ?? '--'),
                addslashes($d->fechas_actualizacion ?? '--')
            );
        }

        $sql .= implode(",\n", $values) . ";";

        $fileName = 'documentos_procesados.sql';
        return Response::make($sql, 200, [
            'Content-Type' => 'application/sql',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ]);
    }


    public function index()
    {
        $documentos = Documento::with(['libroRelacion', 'parteRelacion'])
        ->where('vigente', 1)
        ->orderBy('libro')
        ->orderBy('anio')
        ->get();

        $documentos = Documento::where('vigente', 1)
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
            $documentoPrincipal->fechas_actualizacion = !empty($actualizaciones) ? implode(', ', $actualizaciones) : null;
            
            $documentosProcesados[] = $documentoPrincipal;
        }

        $partes = Parte::all();

        
        return view('todoslosdocumentos', compact('documentos', 'documentosProcesados', 'partes'));
    }


    public function etapas($ID_doc)
    {
        $documento = Documento::findOrFail($ID_doc);
        $etapas = Etapa::firstOrCreate(['ID_doc' => $documento->ID_doc]);

        return view('documentos.etapas', compact('documento', 'etapas'));
    }


    /*public function etapas($ID_doc)
    {
        $documento = Documento::findOrFail($ID_doc);
        return view('documentos.etapas', compact('documento'));
    }

    
    public function etapas($ID_doc)
{
    return redirect()->route('documentos.etapas', $ID_doc);
}*/

    
    public function edit($ID_doc)
    {
        $documento = Documento::findOrFail($ID_doc);
        return view('documentos.edit', compact('documento'));
    }

    public function update(Request $request, $ID_doc)
    {
        $documento = Documento::findOrFail($ID_doc);
        
        $documento->nombre = $request->nombre;
        $documento->tipo = $request->tipoDocumento;
        $documento->libro = $request->libro;
        
        $anio = $request->fechaPublicacion ?? date('Y');
        $documento->anio = $anio;
        $documento->anio_simple = substr($anio, -2);
        
        $documento->save();
        
        return redirect()->route('controldeavances')->with('success');
    }

    public function archive($ID_doc)
    {
        $documento = Documento::findOrFail($ID_doc);
        
        // Alternar entre archivado (0) y activo (1)
        $documento->vigente = $documento->vigente == 1 ? 0 : 1;
        $documento->save();
        
        
        return redirect()->back()->with('success');
    }

    public function inicio()
    {
        return view('inicio');
    }

    public function formulario()
    {
        return view('formulario');
    }

    public function fechas()
    {
        return view('formfechas');
    }

    public function representaciones()
    {
        return view('representaciones');
    }

    public function organismos()
    {
        return view('organismos');
    }

    public function busqueda()
    {
        return view('busqueda');
    }

    public function productosterminados()
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

        $partes = Parte::all();

        return view('productosterminados', compact('documentos', 'documentosProcesados', 'partes'));
    }

    public function buscarDocumentos(Request $request)
{
    $query = Documento::query();

    if ($request->filled('cp')) {
        $query->where(function ($q) use ($request) {
            $q->where('nombre', 'like', "%{$request->cp}%")
              ->orWhere('designacion', 'like', "%{$request->cp}%")
              ->orWhere('tema', 'like', "%{$request->cp}%");
        });
    }

    if ($request->filled('tema')) {
        $query->where('tema', 'like', "%{$request->tema}%");
    }

    if ($request->filled('titulo')) {
        $query->where('titulo', 'like', "%{$request->titulo}%");
    }

    if ($request->filled('time')) {
        $query->where('anio', $request->time);
    }

    if ($request->uv) {
        $query->orderByDesc('anio')->take(1);
    }

    $documentos = $query->get();

    return response()->json($documentos);
}



    public function informes()
    {
        return view('informes');
    }

    public function registroinformes()
    {
        return view('registroinformes');
    }

    public function todoslosdocumentos()
    {
        return view('todoslosdocumentos');
    }

    public function controldeavances()
    {
        return view('controldeavances');
    }



    public function guardarFormulario(Request $request)
    {
        // Lógica para guardar el formulario
        return redirect()->route('formulario')->with('success', 'Documento registrado');
    }

    public function guardarFechas(Request $request)
    {
        // Lógica para guardar las fechas
        return redirect()->route('fechas')->with('success', 'Fechas guardadas');
    }

    public function guardarRepresentaciones(Request $request)
    {
        return redirect()->route('representaciones')->with('success', 'Representacion guardada');
    }

    public function guardarOrganismos(Request $request)
    {
        return redirect()->route('organismos')->with('success', 'Organismo guardado');
    }

    public function guardarBusqueda(Request $request)
    {
        return redirect()->route('busqueda')->with('success', 'Busqueda guardada');
    }

    public function guardarProductosterminados(Request $request)
    {
        return redirect()->route('productosterminados')->with('success', 'Tabla guardada');
    }

    public function guardarInformes(Request $request)
    {
        return redirect()->route('informes')->with('success', 'Informe guardado');
    }

    public function guardarRegistroInformes(Request $request)
    {
        return redirect()->route('registroinformes')->with('success', 'Informe guardado');
    }

    public function guardarTodoslosdocumentos(Request $request)
    {
        return redirect()->route('todoslosdocumentos')->with('success');
    }

    public function guardarControldeavances(Request $request)
    {
        return redirect()->route('controldeavances')->with('success');
    }
}
