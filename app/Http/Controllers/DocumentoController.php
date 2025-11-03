<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Documento;
use App\Models\Etapa;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use App\Models\Parte;
use App\Models\Libro;
use App\Models\Tema;
use App\Models\Origen;
use App\Models\Titulo;

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
        $documentos = Documento::with(['libroRelacion', 
        'temaRelacion', 
        'parteRelacion', 
        'tituloRelacion',
        'info'])
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
        $documento = Documento::with(['libroRelacion', 'temaRelacion', 'parteRelacion', 'tituloRelacion'])
            ->findOrFail($ID_doc);

        $libros = Libro::select(['ID_libro','desc','clave'])->orderBy('desc')->get();
        $temas = Tema::select(['ID_tema','desc','clave'])->orderBy('desc')->get();
        $origenes = Origen::select(['ID_origen','desc'])->orderBy('desc')->get();
        $partes = Parte::select(['ID_parte','desc'])->orderBy('ID_parte')->get();
        $titulos = Titulo::select(['ID_titulo','desc'])->orderBy('ID_titulo')->get();

        return view('documentos.edit', compact('documento','libros','temas','origenes','partes','titulos'));
    }

    public function update(Request $request, $ID_doc)
    {
        $documento = Documento::findOrFail($ID_doc);
        
        $documento->nombre = $request->nombre;
        $documento->tipo = $request->tipoDocumento;
        $documento->libro = $request->libro;
        $documento->tema = $request->tema ?? 0;
        $documento->origen = $request->origen ?? $documento->origen;
        $documento->parte = (int)($request->parte ?? $documento->parte);
        $documento->titulo = (int)($request->titulo ?? $documento->titulo);
        $documento->capitulo = (int)($request->capitulo ?? $documento->capitulo);
        
        $anio = $request->fechaPublicacion ?? date('Y');
        $documento->anio = $anio;
        $documento->anio_simple = substr($anio, -2);
        
        $documento->save();
        
        $target = route('controldeavances');
        if ($request->boolean('modal')) {
            return redirect()->to($target.'?modal=1&saved=1')->with('success');
        }
        return redirect()->to($target)->with('success');
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

    public function buscar(Request $request)
{
    $query = Documento::query();

    if ($request->filled('cp')) {
        $query->where('nombre', 'like', '%' . $request->cp . '%');
    }
    if ($request->filled('libro')) {
        $query->where('libro', $request->libro);
    }
    if ($request->filled('time')) {
        $query->whereYear('fecha_nueva', $request->time);
    }

    $resultados = $query->with(['info', 'libroRelacion', 'temaRelacion'])->get();

    return response()->json($resultados);
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
        return redirect()->route('formulario')->with('success');
    }

    public function guardarFechas(Request $request)
    {
        // Lógica para guardar las fechas
        return redirect()->route('fechas')->with('success');
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

    public function exportarTodoslosdocumentosSQL(Request $request)
    {
        // Obtener los filtros de la request
        $palabra = $request->get('palabra', '');
        $designacion = $request->get('designacion', '');
        $libro = $request->get('libro', '');
        $anio = $request->get('anio', '');

        // Replicar la misma lógica que usa DocumentosFilter para obtener los datos
        $query = Documento::with(['info', 'libroRelacion', 'temaRelacion', 'parteRelacion', 'tituloRelacion']);

        // Aplicar los mismos filtros que en DocumentosFilter
        // Filtro por palabra (busca en nombre, origen y designación)
        if (!empty($palabra)) {
            $query->where(function($q) use ($palabra) {
                $q->where('nombre', 'like', '%' . $palabra . '%')
                  ->orWhere('origen', 'like', '%' . $palabra . '%')
                  ->orWhereHas('info', function($subQ) use ($palabra) {
                      $subQ->where('designacion', 'like', '%' . $palabra . '%');
                  });
            });
        }

        // Filtro por designación específica
        if (!empty($designacion)) {
            $query->whereHas('info', function($q) use ($designacion) {
                $q->where('designacion', 'like', '%' . $designacion . '%');
            });
        }

        // Filtro por libro
        if (!empty($libro)) {
            $query->where('libro', $libro);
        }

        $documentos = $query->get();

        // Procesar documentos agrupados (misma lógica que DocumentosFilter)
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

            // Filtro por año - busca en fecha nueva y actualizaciones (igual que en DocumentosFilter)
            $incluirDocumento = true;
            if (!empty($anio)) {
                $anioCoincide = false;
                
                // Verificar si el año coincide con la fecha nueva
                if ($primeraFecha && $primeraFecha == $anio) {
                    $anioCoincide = true;
                }
                
                // Verificar si el año coincide con alguna actualización
                if (!$anioCoincide && !empty($actualizaciones)) {
                    foreach ($actualizaciones as $fechaActualizacion) {
                        if ($fechaActualizacion == $anio) {
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

        // Generar el SQL
        $sql = "-- Exportación de datos de Todos los Documentos\n";
        $sql .= "-- Fecha de exportación: " . date('Y-m-d H:i:s') . "\n\n";
        $sql .= "INSERT INTO todoslosdocumentos (id, tipo, libro, tema, parte, titulo, capitulo, designacion, nombre, origen, fecha_nueva, fechas_actualizacion) VALUES\n";

        $values = [];
        foreach ($documentosProcesados as $d) {
            $tipo = $d->tipo == 1 ? 'Manual' : 'Norma';
            $libro = $d->libroRelacion->desc ?? $d->libro;
            $tema = $d->temaRelacion->desc ?? ($d->tema == 0 ? '-' : $d->tema);
            $parte = $d->info->desc_parte ?? ($d->parte == 0 ? '-' : $d->parte);
            $titulo = $d->info->desc_titulo ?? ($d->titulo == 0 ? '-' : $d->titulo);
            $capitulo = $d->capitulo;
            $designacion = $d->info->designacion ?? '-';
            $nombre = $d->nombre ?? '-';
            $origen = $d->info->origen ?? '-';
            $fecha_nueva = $d->fecha_nueva;
            $fechas_actualizacion = $d->fechas_actualizacion ?? '-';

            $values[] = sprintf(
                "(%d, '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s')",
                $d->ID_doc,
                addslashes($tipo),
                addslashes($libro),
                addslashes($tema),
                addslashes($parte),
                addslashes($titulo),
                addslashes($capitulo),
                addslashes($designacion),
                addslashes($nombre),
                addslashes($origen),
                addslashes($fecha_nueva),
                addslashes($fechas_actualizacion)
            );
        }

        $sql .= implode(",\n", $values) . ";";

        $fileName = 'todoslosdocumentos_' . date('Y-m-d_H-i-s') . '.sql';
        return Response::make($sql, 200, [
            'Content-Type' => 'application/sql',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ]);
    }
}
