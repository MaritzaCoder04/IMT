<?php

namespace App\Http\Controllers;

use App\Models\GrupoTrabajo;
use App\Models\Grupo;
use App\Models\Reunion;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\GrupoTrabajoReporte;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class GrupoTrabajoController extends Controller
{
    // Vista 1: Lista de grupos de trabajo
    public function index()
    {
        $grupos = GrupoTrabajo::with('reuniones')->get();
        // Año seleccionado para calcular documentos terminados (por defecto, año actual)
        $anioSeleccionado = request()->get('anio', (int)date('Y'));

        // Precalcular estadísticas por grupo para evitar lógica en la vista
        $statsByGroup = [];
        foreach ($grupos as $grupo) {
            $totalRealizadas = $grupo->reuniones->count();
            $progreso = $grupo->meta_anual > 0 ? round(($totalRealizadas / $grupo->meta_anual) * 100) : 0;

            // Calcular reuniones por bimestre (1..6)
            $reunionesPorBimestre = [
                1 => $grupo->reuniones->filter(fn($r) => in_array($r->fecha->month, [1,2]))->count(),
                2 => $grupo->reuniones->filter(fn($r) => in_array($r->fecha->month, [3,4]))->count(),
                3 => $grupo->reuniones->filter(fn($r) => in_array($r->fecha->month, [5,6]))->count(),
                4 => $grupo->reuniones->filter(fn($r) => in_array($r->fecha->month, [7,8]))->count(),
                5 => $grupo->reuniones->filter(fn($r) => in_array($r->fecha->month, [9,10]))->count(),
                6 => $grupo->reuniones->filter(fn($r) => in_array($r->fecha->month, [11,12]))->count(),
            ];

            // Calcular documentos terminados por bimestre y total anual en función del nombre del grupo
            $terminadosPorBimestre = null;
            $terminadosTotal = null;

            $campoEtapa = $this->campoEtapaPorNombre($grupo->nombre);
            if ($campoEtapa) {
                $terminadosPorBimestre = [];
                for ($i = 1; $i <= 6; $i++) {
                    $mesInicio = ($i - 1) * 2 + 1;
                    $mesFin = $mesInicio + 1;

                    $count = DB::table('etapas')
                        ->whereNotNull($campoEtapa)
                        ->whereRaw("MONTH(STR_TO_DATE({$campoEtapa}, '%Y-%m-%d')) >= ?", [$mesInicio])
                        ->whereRaw("MONTH(STR_TO_DATE({$campoEtapa}, '%Y-%m-%d')) <= ?", [$mesFin])
                        ->whereRaw("YEAR(STR_TO_DATE({$campoEtapa}, '%Y-%m-%d')) = ?", [$anioSeleccionado])
                        ->count();

                    $terminadosPorBimestre[$i] = $count;
                }

                $terminadosTotal = DB::table('etapas')
                    ->whereNotNull($campoEtapa)
                    ->whereRaw("YEAR(STR_TO_DATE({$campoEtapa}, '%Y-%m-%d')) = ?", [$anioSeleccionado])
                    ->count();
            }

            $statsByGroup[$grupo->id] = [
                'total_realizadas' => $totalRealizadas,
                'progreso' => $progreso,
                'reuniones_por_bimestre' => $reunionesPorBimestre,
                'terminados_por_bimestre' => $terminadosPorBimestre,
                'terminados_total' => $terminadosTotal,
            ];
        }

        return view('grupotrabajo.index', compact('grupos', 'statsByGroup'));
    }

    /**
     * Detecta el campo de fecha en tabla `etapas` según el nombre del grupo.
     * Mapea APT/AFT/PPT/NP por acrónimo o texto normalizado.
     */
    private function campoEtapaPorNombre(string $nombre): ?string
    {
        $n = Str::ascii($nombre);
        $n = Str::lower($n);
        $n = preg_replace('/[^a-z0-9]/', '', $n);

        // APT - Anteproyecto Preliminar
        if (strpos($n, 'apt') !== false || strpos($n, 'anteproyectopreliminar') !== false) {
            return '3a';
        }
        // AFT - Anteproyecto Final
        if (strpos($n, 'aft') !== false || strpos($n, 'anteproyectofinal') !== false) {
            return '3b';
        }
        // PPT - Proyecto Preliminar
        if (strpos($n, 'ppt') !== false || strpos($n, 'proyectopreliminar') !== false) {
            return '3c';
        }
        // NP - Publicación de Manuales/Normas (Normas/Manuales/Publicación)
        if (
            strpos($n, 'np') !== false ||
            strpos($n, 'normas') !== false ||
            strpos($n, 'manuales') !== false ||
            strpos($n, 'publicacion') !== false ||
            strpos($n, 'publicaciondemanualesnormas') !== false ||
            strpos($n, 'publicaciondenormas') !== false ||
            strpos($n, 'publicaciondemanuales') !== false
        ) {
            return '3e';
        }

        return null;
    }

    // Vista 2: Formulario para crear grupo
    public function create()
    {
        // Construir opciones SOLO desde el catálogo de 'grupos' activos,
        // para que permanezcan disponibles aunque se borren de 'grupos_trabajo'.
        if (Schema::hasTable('grupos')) {
            try {
                $nombresGrupos = Grupo::activos()->orderBy('nombre')->pluck('nombre');
            } catch (\Exception $e) {
                $nombresGrupos = collect();
            }
        } else {
            $nombresGrupos = collect([
                'Anteproyecto Preliminar',
                'Anteproyecto Final',
                'Proyecto Preliminar',
                'Publicación de Manuales/Normas',
                'Subcomité No.4',
                'Grupo de Trabajo 1',
            ]);
        }

        $nombresGrupos = $nombresGrupos->filter()->unique()->sort()->values();

        return view('grupotrabajo.create', [
            'nombresGrupos' => $nombresGrupos,
        ]);
    }

    // Guardar grupo de trabajo
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'anio_meta' => 'sometimes|integer',
            // meta_anual se calculará automáticamente a partir de las metas bimestrales
            'meta_anual' => 'sometimes|integer|min:0',
            'meta_bimestre_1' => 'required|integer|min:0',
            'meta_bimestre_2' => 'required|integer|min:0',
            'meta_bimestre_3' => 'required|integer|min:0',
            'meta_bimestre_4' => 'required|integer|min:0',
            'meta_bimestre_5' => 'required|integer|min:0',
            'meta_bimestre_6' => 'required|integer|min:0',
        ]);

        // Asignar año de la meta (por defecto, año actual si no viene explícito)
        $validated['anio_meta'] = $request->get('anio_meta', (int)date('Y'));

        // Calcular meta anual como suma de metas bimestrales
        $validated['meta_anual'] = (
            ($validated['meta_bimestre_1'] ?? 0) +
            ($validated['meta_bimestre_2'] ?? 0) +
            ($validated['meta_bimestre_3'] ?? 0) +
            ($validated['meta_bimestre_4'] ?? 0) +
            ($validated['meta_bimestre_5'] ?? 0) +
            ($validated['meta_bimestre_6'] ?? 0)
        );

        GrupoTrabajo::create($validated);

        $target = route('grupotrabajo.index');
        if ($request->boolean('modal')) {
            return redirect()->to($target.'?modal=1&saved=1')->with('success');
        }
        return redirect()->to($target)->with('success');
    }

    // Vista 3: Agenda/Calendario
    public function agenda(Request $request)
    {
        $anio = $request->get('anio', date('Y'));
        $busqueda = $request->get('busqueda', '');

        $query = GrupoTrabajo::with(['reuniones' => function($q) use ($anio) {
            $q->whereYear('fecha', $anio);
        }]);

        // Excluir nombres no deseados de la agenda
        $excluirLower = [
            'anteproyecto preliminar',
            'anteproyecto final',
            'publicación de manuales/normas',
            'publicacion de manuales/normas',
            'publicación de manuales y normas',
            'publicacion de manuales y normas',
            'proyecto final',
            'proyecto preliminar',
        ];
        $query->where(function($q) use ($excluirLower) {
            foreach ($excluirLower as $name) {
                $q->whereRaw('LOWER(nombre) != ?', [$name]);
            }
        });

        if ($busqueda) {
            $query->where('nombre', 'like', '%' . $busqueda . '%');
        }

        $grupos = $query->get();

        // Preparar view models por grupo para evitar lógica de BD en la vista
        $grupoViewModels = [];
        foreach ($grupos as $grupo) {
            // Estadísticas básicas
            $total = $grupo->reuniones->count();
            $progreso = $grupo->meta_anual > 0 ? round(($total / $grupo->meta_anual) * 100) : 0;
            $programadas = $grupo->reuniones->where('programada', true)->count();
            $fuera = $grupo->reuniones->where('programada', false)->count();

            // Agrupar reuniones por bimestre y preparar datos planos para la vista
            $reunionesBimestres = [1=>[],2=>[],3=>[],4=>[],5=>[],6=>[]];
            foreach ($grupo->reuniones->sortBy('fecha') as $r) {
                $mes = $r->fecha->month;
                $bimestre = match (true) {
                    in_array($mes, [1,2]) => 1,
                    in_array($mes, [3,4]) => 2,
                    in_array($mes, [5,6]) => 3,
                    in_array($mes, [7,8]) => 4,
                    in_array($mes, [9,10]) => 5,
                    default => 6,
                };
                $reunionesBimestres[$bimestre][] = [
                    'id' => $r->id,
                    'fecha_display' => $r->fecha->format('d/M'),
                    'fecha_full' => $r->fecha->format('d/m/Y'),
                    'programada' => (bool)$r->programada,
                    'motivo' => $r->motivo,
                ];
            }

            $grupoViewModels[$grupo->id] = [
                'stats' => [
                    'total' => $total,
                    'progreso' => $progreso,
                    'programadas' => $programadas,
                    'fuera' => $fuera,
                ],
                'reuniones_bimestres' => $reunionesBimestres,
            ];
        }

        return view('grupotrabajo.agenda', compact('grupos', 'anio', 'busqueda', 'grupoViewModels'));
    }

    // Guardar reunión
    public function guardarReunion(Request $request)
    {
        $validated = $request->validate([
            'grupo_trabajo_id' => 'required|exists:grupos_trabajo,id',
            'fecha' => 'required|date',
            'programada' => 'required|boolean',
            'motivo' => 'nullable|string',
        ]);

        Reunion::create($validated);

        return redirect()->back()->with('success');
    }

    // Vista 4: Reporte
    public function reporte(Request $request)
    {
        // Leer año y bimestre seleccionados desde la URL (fallback al actual)
        $anioSeleccionado = $request->get('anio', date('Y'));
        $bimestreSeleccionado = $request->get('bimestre', 1);

        // Obtener grupos fijos guardados en la base de datos
        $gruposFijosDB = GrupoTrabajo::whereIn('nombre', [
            'Anteproyecto Preliminar',
            'Anteproyecto Final', 
            'Proyecto Preliminar',
            'Publicación de Manuales/Normas',
            'Subcomité No.4',
            'Grupo de Trabajo 1',
        ])->get();

        // Crear array de grupos fijos con valores por defecto o de BD
        $gruposFijos = [];
        $nombresGruposFijos = [
            'apt' => 'Anteproyecto Preliminar',
            'aft' => 'Anteproyecto Final',
            'ppt' => 'Proyecto Preliminar',
            'np' => 'Publicación de Manuales/Normas',
            'sub4' => 'Subcomité No.4',
            'gt1' => 'Grupo de Trabajo 1',
        ];

        foreach ($nombresGruposFijos as $id => $nombre) {
            $grupoGuardado = $gruposFijosDB->firstWhere('nombre', $nombre);
            $metaVisible = $grupoGuardado && ($grupoGuardado->anio_meta == $anioSeleccionado);
            
            $gruposFijos[] = (object)[
                'id' => $id,
                'nombre' => $nombre,
                'meta_anual' => $metaVisible && $grupoGuardado ? $grupoGuardado->meta_anual : null,
                'meta_bimestre_1' => $metaVisible && $grupoGuardado ? $grupoGuardado->meta_bimestre_1 : null,
                'meta_bimestre_2' => $metaVisible && $grupoGuardado ? $grupoGuardado->meta_bimestre_2 : null,
                'meta_bimestre_3' => $metaVisible && $grupoGuardado ? $grupoGuardado->meta_bimestre_3 : null,
                'meta_bimestre_4' => $metaVisible && $grupoGuardado ? $grupoGuardado->meta_bimestre_4 : null,
                'meta_bimestre_5' => $metaVisible && $grupoGuardado ? $grupoGuardado->meta_bimestre_5 : null,
                'meta_bimestre_6' => $metaVisible && $grupoGuardado ? $grupoGuardado->meta_bimestre_6 : null,
                'observaciones' => $grupoGuardado ? $grupoGuardado->observaciones : '',
                'realizados' => [],
                'total_realizado' => 0,
                'es_fijo' => true,
                'fechas_productos' => [],
                'meta_visible' => $metaVisible,
            ];
        }

        // Calcular realizados por bimestre para grupos fijos basado en tabla etapas
        foreach ($gruposFijos as $grupo) {
            $grupo->realizados = [];
            $grupo->fechas_productos = [];

            // Mapeo de grupos a campo de fecha en tabla `etapas`
            $campoFechaMap = [
                'apt' => '3a',
                'aft' => '3b',
                'ppt' => '3c',
                'np'  => '3e',
            ];
            $campoFecha = $campoFechaMap[$grupo->id] ?? null;

            for ($i = 1; $i <= 6; $i++) {
                $mesInicio = ($i - 1) * 2 + 1;
                $mesFin = $mesInicio + 1;

                if ($campoFecha) {
                    // Obtener fechas de productos terminados en este bimestre
                    $fechasProductos = \DB::table('etapas')
                        ->select($campoFecha . ' as fecha')
                        ->whereNotNull($campoFecha)
                        ->whereRaw("MONTH(STR_TO_DATE({$campoFecha}, '%Y-%m-%d')) >= ?", [$mesInicio])
                        ->whereRaw("MONTH(STR_TO_DATE({$campoFecha}, '%Y-%m-%d')) <= ?", [$mesFin])
                        ->whereRaw("YEAR(STR_TO_DATE({$campoFecha}, '%Y-%m-%d')) = ?", [$anioSeleccionado])
                        ->get();

                    $grupo->fechas_productos[$i] = $fechasProductos->pluck('fecha')->toArray();
                    $grupo->realizados[$i] = $fechasProductos->count();
                } else {
                    // Grupos fijos sin mapeo a `etapas` (p.ej. Subcomité/Grupo de Trabajo)
                    $grupo->fechas_productos[$i] = [];
                    $grupo->realizados[$i] = 0;
                }
            }

            // Total realizado en el año y todas las fechas
            if ($campoFecha) {
                $todasFechas = \DB::table('etapas')
                    ->select($campoFecha . ' as fecha')
                    ->whereNotNull($campoFecha)
                    ->whereRaw("YEAR(STR_TO_DATE({$campoFecha}, '%Y-%m-%d')) = ?", [$anioSeleccionado])
                    ->get();

                $grupo->total_realizado = $todasFechas->count();
                $grupo->todas_fechas_productos = $todasFechas->pluck('fecha')->toArray();
            } else {
                $grupo->total_realizado = 0;
                $grupo->todas_fechas_productos = [];
            }
        }

        // Obtener datos adicionales para las secciones f y g (reuniones de subcomités)
        $reunionesSubcomite = \DB::table('reuniones')
            ->join('grupos_trabajo', 'reuniones.grupo_trabajo_id', '=', 'grupos_trabajo.id')
            ->where('grupos_trabajo.nombre', 'like', '%subcomité%')
            ->orWhere('grupos_trabajo.nombre', 'like', '%Subcomité%')
            ->select('reuniones.fecha', 'grupos_trabajo.nombre as grupo_nombre', 
                \DB::raw('CASE 
                    WHEN MONTH(reuniones.fecha) IN (1,2) THEN 1
                    WHEN MONTH(reuniones.fecha) IN (3,4) THEN 2
                    WHEN MONTH(reuniones.fecha) IN (5,6) THEN 3
                    WHEN MONTH(reuniones.fecha) IN (7,8) THEN 4
                    WHEN MONTH(reuniones.fecha) IN (9,10) THEN 5
                    WHEN MONTH(reuniones.fecha) IN (11,12) THEN 6
                    END as bimestre'))
            ->whereYear('reuniones.fecha', $anioSeleccionado)
            ->orderBy('reuniones.fecha')
            ->get();

        $reunionesGrupoTrabajo = \DB::table('reuniones')
            ->join('grupos_trabajo', 'reuniones.grupo_trabajo_id', '=', 'grupos_trabajo.id')
            ->where('grupos_trabajo.nombre', 'like', '%grupo de trabajo%')
            ->orWhere('grupos_trabajo.nombre', 'like', '%Grupo de Trabajo%')
            ->select('reuniones.fecha', 'grupos_trabajo.nombre as grupo_nombre',
                \DB::raw('CASE 
                    WHEN MONTH(reuniones.fecha) IN (1,2) THEN 1
                    WHEN MONTH(reuniones.fecha) IN (3,4) THEN 2
                    WHEN MONTH(reuniones.fecha) IN (5,6) THEN 3
                    WHEN MONTH(reuniones.fecha) IN (7,8) THEN 4
                    WHEN MONTH(reuniones.fecha) IN (9,10) THEN 5
                    WHEN MONTH(reuniones.fecha) IN (11,12) THEN 6
                    END as bimestre'))
            ->whereYear('reuniones.fecha', $anioSeleccionado)
            ->orderBy('reuniones.fecha')
            ->get();

        // Obtener grupos de trabajo regulares
        $grupos = GrupoTrabajo::with('reuniones')->get();
        // Ocultar metas si no corresponde al año seleccionado
        foreach ($grupos as $g) {
            $g->meta_visible = ($g->anio_meta == $anioSeleccionado);
            if (!$g->meta_visible) {
                $g->meta_anual = null;
                $g->meta_bimestre_1 = null;
                $g->meta_bimestre_2 = null;
                $g->meta_bimestre_3 = null;
                $g->meta_bimestre_4 = null;
                $g->meta_bimestre_5 = null;
                $g->meta_bimestre_6 = null;
            }
        }

        // Normalizador de nombres para comparar sin acentos/espacios/puntuación
        $normalize = function ($s) {
            $s = Str::ascii($s);
            $s = Str::lower($s);
            return preg_replace('/[^a-z0-9]/', '', $s);
        };

        // Si existen en BD, sincronizar datos de fijos 'Subcomité No.4' y 'Grupo de Trabajo 1'
        $gruposByNorm = $grupos->groupBy(function($g) use ($normalize){ return $normalize($g->nombre); });
        for ($idx = 0; $idx < count($gruposFijos); $idx++) {
            $gf = $gruposFijos[$idx];
            if (in_array($gf->id, ['sub4','gt1'])) {
                $norm = $normalize($gf->nombre);
                if (isset($gruposByNorm[$norm]) && $gruposByNorm[$norm]->count() > 0) {
                    $bdGrupo = $gruposByNorm[$norm]->sortByDesc(function($g){ return $g->reuniones->count(); })->first();
                    // Recalcular realizados por bimestre desde reuniones del grupo BD para el año actual
                    $gf->realizados = [];
                    for ($i = 1; $i <= 6; $i++) {
                        $mesInicio = ($i - 1) * 2 + 1;
                        $mesFin = $mesInicio + 1;
                        $count = $bdGrupo->reuniones->filter(function($r) use ($mesInicio, $mesFin, $anioSeleccionado){
                            return $r->fecha->year == $anioSeleccionado && $r->fecha->month >= $mesInicio && $r->fecha->month <= $mesFin;
                        })->count();
                        $gf->realizados[$i] = $count;
                    }
                    $gf->total_realizado = $bdGrupo->reuniones->filter(function($r) use ($anioSeleccionado){ return $r->fecha->year == $anioSeleccionado; })->count();
                    $gruposFijos[$idx] = $gf;
                }
            }
        }

        // Evitar duplicados: eliminar de regulares los que coinciden con nombres fijos
        $fixedNamesNorm = collect($gruposFijos)->map(function($g) use ($normalize){ return $normalize($g->nombre); })->all();
        $removePatterns = [
            'subcomite.*(4|iv|04)',   // Subcomité No.4 (incluye IV/04)
            'grupodetrabajo.*(1|01)', // Grupo de Trabajo 1 (incluye 01)
        ];
        $grupos = $grupos->filter(function($g) use ($fixedNamesNorm, $normalize, $removePatterns){
            $norm = $normalize($g->nombre);
            if (in_array($norm, $fixedNamesNorm)) return false;
            foreach ($removePatterns as $p) {
                if (preg_match("/{$p}/", $norm)) return false;
            }
            return true;
        });

        // Deduplicar regulares por nombre normalizado, conservar el que tenga más reuniones
        $grupos = $grupos->groupBy(function($g) use ($normalize){ return $normalize($g->nombre); })
                         ->map(function($items){ return $items->sortByDesc(function($g){ return $g->reuniones->count(); })->first(); })
                         ->values();

        // Detectar si ya existen grupos en BD que representan los especiales
        $haySubcomite = $grupos->contains(function($gg){
            return stripos($gg->nombre, 'subcomité') !== false || stripos($gg->nombre, 'subcomite') !== false;
        });
        $hayGrupoTrabajo = $grupos->contains(function($gg){
            return stripos($gg->nombre, 'grupo de trabajo') !== false;
        });
        
        // Calcular realizados por bimestre para grupos regulares
        foreach ($grupos as $grupo) {
            $grupo->realizados = [];
            $grupo->es_fijo = false;
            
            for ($i = 1; $i <= 6; $i++) {
                $mesInicio = ($i - 1) * 2 + 1;
                $mesFin = $mesInicio + 1;
                
                $count = $grupo->reuniones()
                    ->whereMonth('fecha', '>=', $mesInicio)
                    ->whereMonth('fecha', '<=', $mesFin)
                    ->whereYear('fecha', $anioSeleccionado)
                    ->count();
                
                $grupo->realizados[$i] = $count;
            }
            
            $grupo->total_realizado = $grupo->reuniones()->whereYear('fecha', $anioSeleccionado)->count();
        }

        // Agregar grupos especiales para reuniones de subcomités y grupos de trabajo
        $gruposEspeciales = [
            (object)[
                'id' => 'f',
                'nombre' => 'Coordinación de reuniones del subcomité No.4',
                'meta_anual' => 0,
                'meta_bimestre_1' => 0,
                'meta_bimestre_2' => 0,
                'meta_bimestre_3' => 0,
                'meta_bimestre_4' => 0,
                'meta_bimestre_5' => 0,
                'meta_bimestre_6' => 0,
                'observaciones' => '',
                'realizados' => [],
                'total_realizado' => 0,
                'es_fijo' => false,
                'reuniones' => $reunionesSubcomite
            ],
            (object)[
                'id' => 'g',
                'nombre' => 'Coordinación de reuniones del grupo de trabajo',
                'meta_anual' => 0,
                'meta_bimestre_1' => 0,
                'meta_bimestre_2' => 0,
                'meta_bimestre_3' => 0,
                'meta_bimestre_4' => 0,
                'meta_bimestre_5' => 0,
                'meta_bimestre_6' => 0,
                'observaciones' => '',
                'realizados' => [],
                'total_realizado' => 0,
                'es_fijo' => false,
                'reuniones' => $reunionesGrupoTrabajo
            ]
        ];

        // Evitar duplicados si los grupos existen en BD
        if ($haySubcomite) {
            $gruposEspeciales = array_values(array_filter($gruposEspeciales, function($g){
                return $g->id !== 'f';
            }));
        }
        if ($hayGrupoTrabajo) {
            $gruposEspeciales = array_values(array_filter($gruposEspeciales, function($g){
                return $g->id !== 'g';
            }));
        }

        // Calcular realizados por bimestre para grupos especiales
        foreach ($gruposEspeciales as $grupo) {
            $grupo->realizados = [];
            
            for ($i = 1; $i <= 6; $i++) {
                $count = $grupo->reuniones->where('bimestre', $i)->count();
                $grupo->realizados[$i] = $count;
            }
            
            $grupo->total_realizado = $grupo->reuniones->count();
        }

        // Combinar todos los grupos
        // Se ocultan las secciones especiales de coordinación del reporte
        $todosLosGrupos = collect($gruposFijos)->merge($grupos);

        // Inventario de reportes guardados (para indicadores de completado)
        $inventario = collect();
        $reportesPorAnio = collect();
        $reportesGuardados = [];
        if (Schema::hasTable('grupo_trabajo_reportes')) {
            try {
                $inventario = GrupoTrabajoReporte::select('anio', 'bimestre')
                    ->selectRaw('MAX(created_at) as created_at')
                    ->selectRaw('MAX(updated_at) as updated_at')
                    ->groupBy('anio', 'bimestre')
                    ->orderBy('anio', 'desc')
                    ->orderBy('bimestre', 'asc')
                    ->get();
                $reportesPorAnio = $inventario->groupBy('anio');
                foreach ($reportesPorAnio as $anio => $reportes) {
                    foreach ($reportes as $reporte) {
                        $reportesGuardados[$anio][$reporte->bimestre] = true;
                    }
                }
                $ultimoReporte = GrupoTrabajoReporte::orderBy('anio', 'desc')
                    ->orderBy('bimestre', 'desc')
                    ->first();
            } catch (\Exception $e) {
                $ultimoReporte = null;
            }
        } else {
            $ultimoReporte = null;
        }

        return view('grupotrabajo.reporte', compact(
            'todosLosGrupos',
            'anioSeleccionado',
            'bimestreSeleccionado',
            'reportesPorAnio',
            'reportesGuardados',
            'ultimoReporte'
        ));
    }

    // Actualizar observaciones
    public function actualizarObservaciones(Request $request, $id)
    {
        $grupo = GrupoTrabajo::findOrFail($id);
        $grupo->observaciones = $request->observaciones;
        $grupo->save();

        return redirect()->back()->with('success');
    }

    // Descargar PDF que refleja el bimestre y año actuales y replica la tabla inferior
    public function descargarPDF(Request $request)
    {
        $anioSeleccionado = (int) $request->get('anio', date('Y'));
        $bimestreSeleccionado = (int) $request->get('bimestre', 1);
        $bimestreActual = $bimestreSeleccionado;

        // Obtener grupos fijos guardados en la base de datos
        $gruposFijosDB = GrupoTrabajo::whereIn('nombre', [
            'Anteproyecto Preliminar',
            'Anteproyecto Final', 
            'Proyecto Preliminar',
            'Publicación de Manuales/Normas',
            'Subcomité No.4',
            'Grupo de Trabajo 1',
        ])->get();

        // Crear array de grupos fijos con valores por defecto o de BD
        $gruposFijos = [];
        $nombresGruposFijos = [
            'apt' => 'Anteproyecto Preliminar',
            'aft' => 'Anteproyecto Final',
            'ppt' => 'Proyecto Preliminar',
            'np' => 'Publicación de Manuales/Normas',
            'sub4' => 'Subcomité No.4',
            'gt1' => 'Grupo de Trabajo 1',
        ];

        foreach ($nombresGruposFijos as $id => $nombre) {
            $grupoGuardado = $gruposFijosDB->firstWhere('nombre', $nombre);
            $metaVisible = $grupoGuardado && ($grupoGuardado->anio_meta == $anioSeleccionado);
            
            $gruposFijos[] = (object)[
                'id' => $id,
                'nombre' => $nombre,
                'meta_anual' => $metaVisible && $grupoGuardado ? $grupoGuardado->meta_anual : null,
                'meta_bimestre_1' => $metaVisible && $grupoGuardado ? $grupoGuardado->meta_bimestre_1 : null,
                'meta_bimestre_2' => $metaVisible && $grupoGuardado ? $grupoGuardado->meta_bimestre_2 : null,
                'meta_bimestre_3' => $metaVisible && $grupoGuardado ? $grupoGuardado->meta_bimestre_3 : null,
                'meta_bimestre_4' => $metaVisible && $grupoGuardado ? $grupoGuardado->meta_bimestre_4 : null,
                'meta_bimestre_5' => $metaVisible && $grupoGuardado ? $grupoGuardado->meta_bimestre_5 : null,
                'meta_bimestre_6' => $metaVisible && $grupoGuardado ? $grupoGuardado->meta_bimestre_6 : null,
                'observaciones' => $grupoGuardado ? $grupoGuardado->observaciones : '',
                'realizados' => [],
                'total_realizado' => 0,
                'es_fijo' => true
            ];
        }

        // Calcular realizados por bimestre para grupos fijos basado en tabla etapas
        foreach ($gruposFijos as $grupo) {
            $grupo->realizados = [];

            // Mapeo de grupos a campo de fecha en tabla `etapas`
            $campoFechaMap = [
                'apt' => '3a',
                'aft' => '3b',
                'ppt' => '3c',
                'np'  => '3e',
            ];
            $campoFecha = $campoFechaMap[$grupo->id] ?? null;

            for ($i = 1; $i <= 6; $i++) {
                $mesInicio = ($i - 1) * 2 + 1;
                $mesFin = $mesInicio + 1;

                if ($campoFecha) {
                    // Contar documentos terminados en este bimestre
                    $count = \DB::table('etapas')
                        ->whereNotNull($campoFecha)
                        ->whereRaw("MONTH(STR_TO_DATE({$campoFecha}, '%Y-%m-%d')) >= ?", [$mesInicio])
                        ->whereRaw("MONTH(STR_TO_DATE({$campoFecha}, '%Y-%m-%d')) <= ?", [$mesFin])
                        ->whereRaw("YEAR(STR_TO_DATE({$campoFecha}, '%Y-%m-%d')) = ?", [$anioSeleccionado])
                        ->count();

                    $grupo->realizados[$i] = $count;
                } else {
                    $grupo->realizados[$i] = 0;
                }
            }

            // Total realizado en el año
            if ($campoFecha) {
                $grupo->total_realizado = \DB::table('etapas')
                    ->whereNotNull($campoFecha)
                    ->whereRaw("YEAR(STR_TO_DATE({$campoFecha}, '%Y-%m-%d')) = ?", [$anioSeleccionado])
                    ->count();
            } else {
                $grupo->total_realizado = 0;
            }
        }
        
        // Obtener todos los grupos de trabajo regulares y normalizar/deduplicar
        $grupos = GrupoTrabajo::with('reuniones')->get();

        $normalize = function ($s) {
            $s = Str::ascii($s);
            $s = Str::lower($s);
            return preg_replace('/[^a-z0-9]/', '', $s);
        };

        // Sincronizar datos para fijos 'Subcomité No.4' y 'Grupo de Trabajo 1' si existen en BD
        $gruposByNorm = $grupos->groupBy(function($g) use ($normalize){ return $normalize($g->nombre); });
        for ($idx = 0; $idx < count($gruposFijos); $idx++) {
            $gf = $gruposFijos[$idx];
            if (in_array($gf->id, ['sub4','gt1'])) {
                $norm = $normalize($gf->nombre);
                if (isset($gruposByNorm[$norm]) && $gruposByNorm[$norm]->count() > 0) {
                    $bdGrupo = $gruposByNorm[$norm]->sortByDesc(function($g){ return $g->reuniones->count(); })->first();
                    // Recalcular realizados por bimestre desde reuniones del grupo BD para el año seleccionado
                    $gf->realizados = [];
                    for ($i = 1; $i <= 6; $i++) {
                        $mesInicio = ($i - 1) * 2 + 1;
                        $mesFin = $mesInicio + 1;
                        $count = $bdGrupo->reuniones->filter(function($r) use ($mesInicio, $mesFin, $anioSeleccionado){
                            return $r->fecha->year == $anioSeleccionado && $r->fecha->month >= $mesInicio && $r->fecha->month <= $mesFin;
                        })->count();
                        $gf->realizados[$i] = $count;
                    }
                    $gf->total_realizado = $bdGrupo->reuniones->filter(function($r) use ($anioSeleccionado){ return $r->fecha->year == $anioSeleccionado; })->count();
                    $gruposFijos[$idx] = $gf;
                }
            }
        }

        // Evitar duplicados: eliminar de regulares los que coinciden con nombres fijos o patrones
        $fixedNamesNorm = collect($gruposFijos)->map(function($g) use ($normalize){ return $normalize($g->nombre); })->all();
        $removePatterns = [
            'subcomite.*(4|iv|04)',
            'grupodetrabajo.*(1|01)',
        ];
        $grupos = $grupos->filter(function($g) use ($fixedNamesNorm, $normalize, $removePatterns){
            $norm = $normalize($g->nombre);
            if (in_array($norm, $fixedNamesNorm)) return false;
            foreach ($removePatterns as $p) {
                if (preg_match("/{$p}/", $norm)) return false;
            }
            return true;
        });

        // Deduplicar regulares por nombre normalizado
        $grupos = $grupos->groupBy(function($g) use ($normalize){ return $normalize($g->nombre); })
                         ->map(function($items){ return $items->sortByDesc(function($g){ return $g->reuniones->count(); })->first(); })
                         ->values();

        // Ocultar metas si el año de meta no coincide
        $grupos = $grupos->map(function($g) use ($anioSeleccionado){
            $g->meta_visible = ($g->anio_meta == $anioSeleccionado);
            if (!$g->meta_visible) {
                $g->meta_anual = null;
                $g->meta_bimestre_1 = null;
                $g->meta_bimestre_2 = null;
                $g->meta_bimestre_3 = null;
                $g->meta_bimestre_4 = null;
                $g->meta_bimestre_5 = null;
                $g->meta_bimestre_6 = null;
            }
            return $g;
        });

        // Calcular realizados por bimestre para grupos regulares
        foreach ($grupos as $grupo) {
            $grupo->realizados = [];
            $grupo->es_fijo = false;
            for ($i = 1; $i <= 6; $i++) {
                $mesInicio = ($i - 1) * 2 + 1;
                $mesFin = $mesInicio + 1;
                $count = $grupo->reuniones()
                    ->whereMonth('fecha', '>=', $mesInicio)
                    ->whereMonth('fecha', '<=', $mesFin)
                    ->whereYear('fecha', $anioSeleccionado)
                    ->count();
                $grupo->realizados[$i] = $count;
            }
            $grupo->total_realizado = $grupo->reuniones()->whereYear('fecha', $anioSeleccionado)->count();
        }

        // Agregar grupos especiales para reuniones de subcomités y grupos de trabajo (año seleccionado)
        $reunionesSubcomite = \DB::table('reuniones')
            ->join('grupos_trabajo', 'reuniones.grupo_trabajo_id', '=', 'grupos_trabajo.id')
            ->where(function($q){
                $q->where('grupos_trabajo.nombre', 'like', '%subcomité%')
                  ->orWhere('grupos_trabajo.nombre', 'like', '%Subcomité%')
                  ->orWhere('grupos_trabajo.nombre', 'like', '%subcomite%')
                  ->orWhere('grupos_trabajo.nombre', 'like', '%Subcomite%');
            })
            ->select('reuniones.fecha', 'grupos_trabajo.nombre as grupo_nombre', 
                \DB::raw('CASE 
                    WHEN MONTH(reuniones.fecha) IN (1,2) THEN 1
                    WHEN MONTH(reuniones.fecha) IN (3,4) THEN 2
                    WHEN MONTH(reuniones.fecha) IN (5,6) THEN 3
                    WHEN MONTH(reuniones.fecha) IN (7,8) THEN 4
                    WHEN MONTH(reuniones.fecha) IN (9,10) THEN 5
                    WHEN MONTH(reuniones.fecha) IN (11,12) THEN 6
                    END as bimestre'))
            ->whereYear('reuniones.fecha', $anioSeleccionado)
            ->orderBy('reuniones.fecha')
            ->get();

        $reunionesGrupoTrabajo = \DB::table('reuniones')
            ->join('grupos_trabajo', 'reuniones.grupo_trabajo_id', '=', 'grupos_trabajo.id')
            ->where(function($q){
                $q->where('grupos_trabajo.nombre', 'like', '%grupo de trabajo%')
                  ->orWhere('grupos_trabajo.nombre', 'like', '%Grupo de Trabajo%');
            })
            ->select('reuniones.fecha', 'grupos_trabajo.nombre as grupo_nombre',
                \DB::raw('CASE 
                    WHEN MONTH(reuniones.fecha) IN (1,2) THEN 1
                    WHEN MONTH(reuniones.fecha) IN (3,4) THEN 2
                    WHEN MONTH(reuniones.fecha) IN (5,6) THEN 3
                    WHEN MONTH(reuniones.fecha) IN (7,8) THEN 4
                    WHEN MONTH(reuniones.fecha) IN (9,10) THEN 5
                    WHEN MONTH(reuniones.fecha) IN (11,12) THEN 6
                    END as bimestre'))
            ->whereYear('reuniones.fecha', $anioSeleccionado)
            ->orderBy('reuniones.fecha')
            ->get();

        $haySubcomite = $grupos->contains(function($gg){
            return stripos($gg->nombre, 'subcomité') !== false || stripos($gg->nombre, 'subcomite') !== false;
        });
        $hayGrupoTrabajo = $grupos->contains(function($gg){
            return stripos($gg->nombre, 'grupo de trabajo') !== false;
        });

        $gruposEspeciales = [
            (object)[
                'id' => 'f',
                'nombre' => 'Coordinación de reuniones del subcomité No.4',
                'meta_anual' => 0,
                'meta_bimestre_1' => 0,
                'meta_bimestre_2' => 0,
                'meta_bimestre_3' => 0,
                'meta_bimestre_4' => 0,
                'meta_bimestre_5' => 0,
                'meta_bimestre_6' => 0,
                'observaciones' => '',
                'realizados' => [],
                'total_realizado' => 0,
                'es_fijo' => false,
                'reuniones' => $reunionesSubcomite
            ],
            (object)[
                'id' => 'g',
                'nombre' => 'Coordinación de reuniones del grupo de trabajo',
                'meta_anual' => 0,
                'meta_bimestre_1' => 0,
                'meta_bimestre_2' => 0,
                'meta_bimestre_3' => 0,
                'meta_bimestre_4' => 0,
                'meta_bimestre_5' => 0,
                'meta_bimestre_6' => 0,
                'observaciones' => '',
                'realizados' => [],
                'total_realizado' => 0,
                'es_fijo' => false,
                'reuniones' => $reunionesGrupoTrabajo
            ]
        ];

        if ($haySubcomite) {
            $gruposEspeciales = array_values(array_filter($gruposEspeciales, function($g){
                return $g->id !== 'f';
            }));
        }
        if ($hayGrupoTrabajo) {
            $gruposEspeciales = array_values(array_filter($gruposEspeciales, function($g){
                return $g->id !== 'g';
            }));
        }

        foreach ($gruposEspeciales as $grupo) {
            $grupo->realizados = [];
            for ($i = 1; $i <= 6; $i++) {
                $grupo->realizados[$i] = $grupo->reuniones->where('bimestre', $i)->count();
            }
            $grupo->total_realizado = $grupo->reuniones->count();
        }

        // Unir fijos + regulares (sin secciones de coordinación)
        $todosLosGrupos = collect($gruposFijos)->merge($grupos);

        // Recuperar las notas del reporte guardado para el período actual (g.3) si la tabla existe
        $notasReporte = null;
        if (Schema::hasTable('grupo_trabajo_reportes')) {
            try {
                $notasReporte = GrupoTrabajoReporte::where('anio', $anioSeleccionado)
                    ->where('bimestre', $bimestreActual)
                    ->orderBy('updated_at', 'desc')
                    ->value('notas');
            } catch (\Exception $e) {
                $notasReporte = null;
            }
        }
        // Permitir override con lo que está visible en la vista
        if ($request->has('notas')) {
            $notasReporte = $request->get('notas');
        }

        // Observaciones del bloque (6.1.4 → g)) tal cual se ve
        $observacionesBloque = null;
        if ($request->has('observaciones_bloque')) {
            $observacionesBloque = $request->get('observaciones_bloque');
        } else {
            $observacionesBloque = collect($todosLosGrupos ?? [])->where('id','gt1')->first()->observaciones ?? '';
        }

        // Sincronizar la observación del bloque con el grupo fijo gt1 para que la vista lo refleje
        try {
            $todosLosGrupos = collect($todosLosGrupos ?? [])->map(function ($g) use ($observacionesBloque) {
                $gid = is_array($g) ? ($g['id'] ?? null) : ($g->id ?? null);
                if ($gid === 'gt1') {
                    if (is_array($g)) {
                        $g['observaciones'] = $observacionesBloque;
                    } else {
                        $g->observaciones = $observacionesBloque;
                    }
                }
                return $g;
            });
        } catch (\Throwable $e) {
            // En caso de estructura inesperada, continuar sin bloquear la descarga
        }

        $pdf = Pdf::loadView('grupotrabajo.pdf', compact('todosLosGrupos', 'anioSeleccionado', 'bimestreActual', 'notasReporte', 'observacionesBloque'));
        return $pdf->download('reporte-grupos-trabajo-' . $anioSeleccionado . '-b' . $bimestreActual . '.pdf');
    }

    public function edit($id)
    {
        $grupo = GrupoTrabajo::findOrFail($id);
        $nombresGrupos = GrupoTrabajo::orderBy('nombre')->pluck('nombre');
        if (Schema::hasTable('grupos')) {
            try {
                $nombresGrupos = $nombresGrupos
                    ->merge(Grupo::activos()->orderBy('nombre')->pluck('nombre'));
            } catch (\Exception $e) {
            }
        } else {
            $fallback = collect([
                'Anteproyecto Preliminar',
                'Anteproyecto Final',
                'Proyecto Preliminar',
                'Publicación de Manuales/Normas'
            ]);
            $nombresGrupos = $nombresGrupos->merge($fallback);
        }

        $nombresGrupos = $nombresGrupos->unique()->sort()->values();

        return view('grupotrabajo.edit', [
            'grupo' => $grupo,
            'nombresGrupos' => $nombresGrupos,
        ]);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'anio_meta' => 'sometimes|integer',
            // meta_anual se calculará automáticamente a partir de las metas bimestrales
            'meta_anual' => 'sometimes|integer|min:0',
            'meta_bimestre_1' => 'required|integer|min:0',
            'meta_bimestre_2' => 'required|integer|min:0',
            'meta_bimestre_3' => 'required|integer|min:0',
            'meta_bimestre_4' => 'required|integer|min:0',
            'meta_bimestre_5' => 'required|integer|min:0',
            'meta_bimestre_6' => 'required|integer|min:0',
        ]);

        $grupo = GrupoTrabajo::findOrFail($id);
        // Mantener el año de meta anterior si no se envía uno nuevo
        $validated['anio_meta'] = $request->get('anio_meta', $grupo->anio_meta ?? (int)date('Y'));

        // Calcular meta anual como suma de metas bimestrales
        $validated['meta_anual'] = (
            ($validated['meta_bimestre_1'] ?? 0) +
            ($validated['meta_bimestre_2'] ?? 0) +
            ($validated['meta_bimestre_3'] ?? 0) +
            ($validated['meta_bimestre_4'] ?? 0) +
            ($validated['meta_bimestre_5'] ?? 0) +
            ($validated['meta_bimestre_6'] ?? 0)
        );

        $grupo->update($validated);

        $target = route('grupotrabajo.index');
        if ($request->boolean('modal')) {
            return redirect()->to($target.'?modal=1&saved=1')->with('success');
        }
        return redirect()->to($target)->with('success');
    }

    public function destroy($id)
    {
        try {
            $grupo = GrupoTrabajo::findOrFail($id);
            $grupo->delete();

            return redirect()->route('grupotrabajo.index')->with('success');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al eliminar el grupo de trabajo: ' . $e->getMessage());
        }
    }
    
    public function reportes(Request $request)
    {
        $anioSeleccionado = $request->get('anio', date('Y'));
        $bimestreSeleccionado = $request->get('bimestre', 1);
    
    // Obtener grupos fijos guardados en la base de datos
    $gruposFijosDB = GrupoTrabajo::whereIn('nombre', [
        'Anteproyecto Preliminar',
        'Anteproyecto Final', 
        'Proyecto Preliminar',
        'Publicación de Manuales/Normas',
        'Subcomité No.4',
        'Grupo de Trabajo 1',
    ])->get();

    // Crear array de grupos fijos con valores por defecto o de BD
    $gruposFijos = [];
    $nombresGruposFijos = [
        'apt' => 'Anteproyecto Preliminar',
        'aft' => 'Anteproyecto Final',
        'ppt' => 'Proyecto Preliminar',
        'np' => 'Publicación de Manuales/Normas',
        'sub4' => 'Subcomité No.4',
        'gt1' => 'Grupo de Trabajo 1',
    ];

    foreach ($nombresGruposFijos as $id => $nombre) {
        $grupoGuardado = $gruposFijosDB->firstWhere('nombre', $nombre);
        $metaVisible = $grupoGuardado && ($grupoGuardado->anio_meta == $anioSeleccionado);
        
        $gruposFijos[] = (object)[
            'id' => $id,
            'nombre' => $nombre,
            'meta_anual' => $metaVisible && $grupoGuardado ? $grupoGuardado->meta_anual : null,
            'meta_bimestre_1' => $metaVisible && $grupoGuardado ? $grupoGuardado->meta_bimestre_1 : null,
            'meta_bimestre_2' => $metaVisible && $grupoGuardado ? $grupoGuardado->meta_bimestre_2 : null,
            'meta_bimestre_3' => $metaVisible && $grupoGuardado ? $grupoGuardado->meta_bimestre_3 : null,
            'meta_bimestre_4' => $metaVisible && $grupoGuardado ? $grupoGuardado->meta_bimestre_4 : null,
            'meta_bimestre_5' => $metaVisible && $grupoGuardado ? $grupoGuardado->meta_bimestre_5 : null,
            'meta_bimestre_6' => $metaVisible && $grupoGuardado ? $grupoGuardado->meta_bimestre_6 : null,
            'observaciones' => $grupoGuardado ? $grupoGuardado->observaciones : '',
            'realizados' => [],
            'total_realizado' => 0,
            'es_fijo' => true
        ];
    }

    // Calcular realizados por bimestre para grupos fijos basado en tabla etapas
    foreach ($gruposFijos as $grupo) {
        $grupo->realizados = [];

        // Mapeo de grupos a campo de fecha en tabla `etapas`
        $campoFechaMap = [
            'apt' => '3a',
            'aft' => '3b',
            'ppt' => '3c',
            'np'  => '3e',
        ];
        $campoFecha = $campoFechaMap[$grupo->id] ?? null;

        for ($i = 1; $i <= 6; $i++) {
            $mesInicio = ($i - 1) * 2 + 1;
            $mesFin = $mesInicio + 1;

            if ($campoFecha) {
                // Contar documentos terminados en este bimestre
                $count = \DB::table('etapas')
                    ->whereNotNull($campoFecha)
                    ->whereRaw("MONTH(STR_TO_DATE({$campoFecha}, '%Y-%m-%d')) >= ?", [$mesInicio])
                    ->whereRaw("MONTH(STR_TO_DATE({$campoFecha}, '%Y-%m-%d')) <= ?", [$mesFin])
                    ->whereRaw("YEAR(STR_TO_DATE({$campoFecha}, '%Y-%m-%d')) = ?", [$anioSeleccionado])
                    ->count();

                $grupo->realizados[$i] = $count;
            } else {
                $grupo->realizados[$i] = 0;
            }
        }

        // Total realizado en el año
        if ($campoFecha) {
            $grupo->total_realizado = \DB::table('etapas')
                ->whereNotNull($campoFecha)
                ->whereRaw("YEAR(STR_TO_DATE({$campoFecha}, '%Y-%m-%d')) = ?", [$anioSeleccionado])
                ->count();
        } else {
            $grupo->total_realizado = 0;
        }
    }
    
        // Obtener todos los grupos de trabajo regulares (sin excluir por nombre);
        // deduplicaremos contra fijos y especiales más adelante.
        $grupos = GrupoTrabajo::with('reuniones')->get();

        // Normalizador de nombres
        $normalize = function ($s) {
            $s = Str::ascii($s);
            $s = Str::lower($s);
            return preg_replace('/[^a-z0-9]/', '', $s);
        };

        // Sincronizar datos de fijos 'Subcomité No.4' y 'Grupo de Trabajo 1' si existen en BD
        $gruposByNorm = $grupos->groupBy(function($g) use ($normalize){ return $normalize($g->nombre); });
        for ($idx = 0; $idx < count($gruposFijos); $idx++) {
            $gf = $gruposFijos[$idx];
            if (in_array($gf->id, ['sub4','gt1'])) {
                $norm = $normalize($gf->nombre);
                if (isset($gruposByNorm[$norm]) && $gruposByNorm[$norm]->count() > 0) {
                    $bdGrupo = $gruposByNorm[$norm]->sortByDesc(function($g){ return $g->reuniones->count(); })->first();
                    // Recalcular realizados por bimestre desde reuniones del grupo BD para el año seleccionado
                    $gf->realizados = [];
                    for ($i = 1; $i <= 6; $i++) {
                        $mesInicio = ($i - 1) * 2 + 1;
                        $mesFin = $mesInicio + 1;
                        $count = $bdGrupo->reuniones->filter(function($r) use ($mesInicio, $mesFin, $anioSeleccionado){
                            return $r->fecha->year == $anioSeleccionado && $r->fecha->month >= $mesInicio && $r->fecha->month <= $mesFin;
                        })->count();
                        $gf->realizados[$i] = $count;
                    }
                    $gf->total_realizado = $bdGrupo->reuniones->filter(function($r) use ($anioSeleccionado){ return $r->fecha->year == $anioSeleccionado; })->count();
                    $gruposFijos[$idx] = $gf;
                }
            }
        }

        // Evitar duplicados: eliminar de regulares los que coinciden con nombres fijos
        $fixedNamesNorm = collect($gruposFijos)->map(function($g) use ($normalize){ return $normalize($g->nombre); })->all();
        $removePatterns = [
            'subcomite.*(4|iv|04)',   // Subcomité No.4 (incluye IV/04)
            'grupodetrabajo.*(1|01)', // Grupo de Trabajo 1 (incluye 01)
        ];
        $grupos = $grupos->filter(function($g) use ($fixedNamesNorm, $normalize, $removePatterns){
            $norm = $normalize($g->nombre);
            if (in_array($norm, $fixedNamesNorm)) return false;
            foreach ($removePatterns as $p) {
                if (preg_match("/{$p}/", $norm)) return false;
            }
            return true;
        });

        // Deduplicar regulares por nombre normalizado
        $grupos = $grupos->groupBy(function($g) use ($normalize){ return $normalize($g->nombre); })
                         ->map(function($items){ return $items->sortByDesc(function($g){ return $g->reuniones->count(); })->first(); })
                         ->values();

        // Ocultar metas de grupos regulares si el año de meta no coincide
        $grupos = $grupos->map(function($g) use ($anioSeleccionado){
            $g->meta_visible = ($g->anio_meta == $anioSeleccionado);
            if (!$g->meta_visible) {
                $g->meta_anual = null;
                $g->meta_bimestre_1 = null;
                $g->meta_bimestre_2 = null;
                $g->meta_bimestre_3 = null;
                $g->meta_bimestre_4 = null;
                $g->meta_bimestre_5 = null;
                $g->meta_bimestre_6 = null;
            }
            return $g;
        });

        // Calcular realizados por bimestre para grupos regulares
        foreach ($grupos as $grupo) {
            $grupo->realizados = [];
            $grupo->es_fijo = false;
        
        for ($i = 1; $i <= 6; $i++) {
            $mesInicio = ($i - 1) * 2 + 1;
            $mesFin = $mesInicio + 1;
            
            $count = $grupo->reuniones()
                ->whereMonth('fecha', '>=', $mesInicio)
                ->whereMonth('fecha', '<=', $mesFin)
                ->whereYear('fecha', $anioSeleccionado)
                ->count();
            
            $grupo->realizados[$i] = $count;
        }
        
        $grupo->total_realizado = $grupo->reuniones()->whereYear('fecha', $anioSeleccionado)->count();
    }

    // Agregar grupos especiales para reuniones de subcomités y grupos de trabajo
    $reunionesSubcomite = \DB::table('reuniones')
        ->join('grupos_trabajo', 'reuniones.grupo_trabajo_id', '=', 'grupos_trabajo.id')
        ->where(function($q){
            $q->where('grupos_trabajo.nombre', 'like', '%subcomité%')
              ->orWhere('grupos_trabajo.nombre', 'like', '%Subcomité%')
              ->orWhere('grupos_trabajo.nombre', 'like', '%subcomite%')
              ->orWhere('grupos_trabajo.nombre', 'like', '%Subcomite%');
        })
        ->select('reuniones.fecha', 'grupos_trabajo.nombre as grupo_nombre', 
            \DB::raw('CASE 
                WHEN MONTH(reuniones.fecha) IN (1,2) THEN 1
                WHEN MONTH(reuniones.fecha) IN (3,4) THEN 2
                WHEN MONTH(reuniones.fecha) IN (5,6) THEN 3
                WHEN MONTH(reuniones.fecha) IN (7,8) THEN 4
                WHEN MONTH(reuniones.fecha) IN (9,10) THEN 5
                WHEN MONTH(reuniones.fecha) IN (11,12) THEN 6
                END as bimestre'))
        ->whereYear('reuniones.fecha', $anioSeleccionado)
        ->orderBy('reuniones.fecha')
        ->get();

    $reunionesGrupoTrabajo = \DB::table('reuniones')
        ->join('grupos_trabajo', 'reuniones.grupo_trabajo_id', '=', 'grupos_trabajo.id')
        ->where(function($q){
            $q->where('grupos_trabajo.nombre', 'like', '%grupo de trabajo%')
              ->orWhere('grupos_trabajo.nombre', 'like', '%Grupo de Trabajo%');
        })
        ->select('reuniones.fecha', 'grupos_trabajo.nombre as grupo_nombre',
            \DB::raw('CASE 
                WHEN MONTH(reuniones.fecha) IN (1,2) THEN 1
                WHEN MONTH(reuniones.fecha) IN (3,4) THEN 2
                WHEN MONTH(reuniones.fecha) IN (5,6) THEN 3
                WHEN MONTH(reuniones.fecha) IN (7,8) THEN 4
                WHEN MONTH(reuniones.fecha) IN (9,10) THEN 5
                WHEN MONTH(reuniones.fecha) IN (11,12) THEN 6
                END as bimestre'))
        ->whereYear('reuniones.fecha', $anioSeleccionado)
        ->orderBy('reuniones.fecha')
        ->get();

    // Detectar si ya existen grupos en BD que representan los especiales
    $haySubcomite = $grupos->contains(function($gg){
        return stripos($gg->nombre, 'subcomité') !== false || stripos($gg->nombre, 'subcomite') !== false;
    });
    $hayGrupoTrabajo = $grupos->contains(function($gg){
        return stripos($gg->nombre, 'grupo de trabajo') !== false;
    });

    $gruposEspeciales = [
        (object)[
            'id' => 'f',
            'nombre' => 'Coordinación de reuniones del subcomité No.4',
            'meta_anual' => 0,
            'meta_bimestre_1' => 0,
            'meta_bimestre_2' => 0,
            'meta_bimestre_3' => 0,
            'meta_bimestre_4' => 0,
            'meta_bimestre_5' => 0,
            'meta_bimestre_6' => 0,
            'observaciones' => '',
            'realizados' => [],
            'total_realizado' => 0,
            'es_fijo' => false,
            'reuniones' => $reunionesSubcomite
        ],
        (object)[
            'id' => 'g',
            'nombre' => 'Coordinación de reuniones del grupo de trabajo',
            'meta_anual' => 0,
            'meta_bimestre_1' => 0,
            'meta_bimestre_2' => 0,
            'meta_bimestre_3' => 0,
            'meta_bimestre_4' => 0,
            'meta_bimestre_5' => 0,
            'meta_bimestre_6' => 0,
            'observaciones' => '',
            'realizados' => [],
            'total_realizado' => 0,
            'es_fijo' => false,
            'reuniones' => $reunionesGrupoTrabajo
        ]
    ];

    // Si ya hay grupo(s) en BD que cubren el subcomité o grupo de trabajo,
    // no agregamos el especial correspondiente para evitar duplicados.
    if ($haySubcomite) {
        $gruposEspeciales = array_values(array_filter($gruposEspeciales, function($g){
            return $g->id !== 'f';
        }));
    }
    if ($hayGrupoTrabajo) {
        $gruposEspeciales = array_values(array_filter($gruposEspeciales, function($g){
            return $g->id !== 'g';
        }));
    }

    // Calcular realizados por bimestre para grupos especiales
    foreach ($gruposEspeciales as $grupo) {
        $grupo->realizados = [];
        for ($i = 1; $i <= 6; $i++) {
            $grupo->realizados[$i] = $grupo->reuniones->where('bimestre', $i)->count();
        }
        $grupo->total_realizado = $grupo->reuniones->count();
    }

    // Se ocultan las secciones especiales de coordinación del reporte
    $todosLosGrupos = collect($gruposFijos)->merge($grupos);
    
    // Obtener inventario de reportes usando tabla normalizada (por año y bimestre)
    $inventario = collect();
    $reportesPorAnio = collect();
    $reportesGuardados = [];

    if (Schema::hasTable('grupo_trabajo_reportes')) {
        try {
            $inventario = GrupoTrabajoReporte::select('anio', 'bimestre')
                ->selectRaw('MAX(created_at) as created_at')
                ->selectRaw('MAX(updated_at) as updated_at')
                ->groupBy('anio', 'bimestre')
                ->orderBy('anio', 'desc')
                ->orderBy('bimestre', 'asc')
                ->get();
            $reportesPorAnio = $inventario->groupBy('anio');
            foreach ($reportesPorAnio as $anio => $reportes) {
                foreach ($reportes as $reporte) {
                    $reportesGuardados[$anio][$reporte->bimestre] = true;
                }
            }
            $ultimoReporte = GrupoTrabajoReporte::orderBy('anio', 'desc')
                ->orderBy('bimestre', 'desc')
                ->first();
        } catch (\Exception $e) {
            $ultimoReporte = null;
        }
    } else {
        $ultimoReporte = null;
    }
    
    return view('grupotrabajo.reporte', compact(
        'todosLosGrupos',
        'anioSeleccionado',
        'bimestreSeleccionado',
        'reportesPorAnio',
        'reportesGuardados',
        'ultimoReporte'
    ));
}

public function guardarReporte(Request $request)
{
    $request->validate([
        'anio' => 'required|integer',
        'bimestre' => 'required|integer|between:1,6',
        'datos_grupos' => 'required|json'
    ]);
    
    try {
        // Actualizar observaciones en los grupos
        $datosGrupos = json_decode($request->datos_grupos, true);
        foreach ($datosGrupos as $dato) {
            GrupoTrabajo::where('id', $dato['grupo_id'])
                ->update(['observaciones' => $dato['observaciones']]);
        }
        
        // Obtener datos completos para el reporte
        $grupos = GrupoTrabajo::with('reuniones')->get();
        $datosReporte = [];
        
        foreach ($grupos as $grupo) {
            $metaBimestral = $grupo->{'meta_bimestre_' . $request->bimestre};
            
            $realizadoBimestre = $grupo->reuniones->filter(function($r) use ($request) {
                $mes = $r->fecha->month;
                $inicio = ($request->bimestre - 1) * 2 + 1;
                $fin = $request->bimestre * 2;
                return $mes >= $inicio && $mes <= $fin;
            })->count();
            
            $totalAcumulado = $grupo->reuniones->filter(function($r) use ($request) {
                $mes = $r->fecha->month;
                $fin = $request->bimestre * 2;
                return $mes <= $fin;
            })->count();
            
            $datosReporte[] = [
                'grupo_id' => $grupo->id,
                'nombre' => $grupo->nombre,
                'meta_anual' => $grupo->meta_anual,
                'meta_bimestral' => $metaBimestral,
                'realizado_bimestre' => $realizadoBimestre,
                'total_acumulado' => $totalAcumulado,
                'porc_bimestral' => $metaBimestral > 0 ? round(($realizadoBimestre / $metaBimestral) * 100) : 0,
                'porc_anual' => $grupo->meta_anual > 0 ? round(($totalAcumulado / $grupo->meta_anual) * 100) : 0,
                'observaciones' => $grupo->observaciones
            ];
        }

        // Persistir reporte normalizado por grupo y bimestre
        foreach ($datosReporte as $dato) {
            GrupoTrabajoReporte::updateOrCreate(
                [
                    'grupo_trabajo_id' => $dato['grupo_id'],
                    'anio' => (int)$request->anio,
                    'bimestre' => (int)$request->bimestre,
                ],
                [
                    'meta_bimestral' => $dato['meta_bimestral'],
                    'realizado_bimestre' => $dato['realizado_bimestre'],
                    'total_acumulado' => $dato['total_acumulado'],
                    'porc_bimestral' => $dato['porc_bimestral'],
                    'porc_anual' => $dato['porc_anual'],
                    'observaciones' => $dato['observaciones'],
                    'notas' => $request->notas,
                ]
            );
        }

        // Persistir únicamente en la tabla normalizada
        return redirect()->route('grupotrabajo.reportes', [
            'anio' => $request->anio,
            'bimestre' => $request->bimestre
        ])->with('success');
        
    } catch (\Exception $e) {
        return back()->with('error', 'Error al guardar el reporte: ' . $e->getMessage());
    }
}

public function eliminarReporte($anio, $bimestre)
{
    try {
        // Eliminar todos los registros normalizados para el periodo indicado
        GrupoTrabajoReporte::where('anio', $anio)
            ->where('bimestre', $bimestre)
            ->delete();
        
        return redirect()->route('grupotrabajo.reportes')
            ->with('success');
    } catch (\Exception $e) {
        return back()->with('error', 'Error al eliminar el reporte');
    }
}

// Métodos para gestionar reuniones
public function editReunion($id)
{
    try {
        $reunion = Reunion::findOrFail($id);
        $grupos = GrupoTrabajo::orderBy('nombre')->get();
        
        return view('grupotrabajo.edit-reunion', compact('reunion', 'grupos'));
    } catch (\Exception $e) {
        return back()->with('error', 'Reunión no encontrada');
    }
}

public function updateReunion(Request $request, $id)
{
    try {
        $validated = $request->validate([
            'grupo_trabajo_id' => 'required|exists:grupos_trabajo,id',
            'fecha' => 'required|date',
            'programada' => 'required|boolean',
            'motivo' => 'nullable|string',
        ]);

        $reunion = Reunion::findOrFail($id);
        $reunion->update($validated);

        return redirect()->route('grupotrabajo.agenda')
            ->with('success');
    } catch (\Exception $e) {
        return back()->with('error', 'Error al actualizar la reunión: ' . $e->getMessage());
    }
}

public function deleteReunion($id)
{
    try {
        $reunion = Reunion::findOrFail($id);
        $reunion->delete();
        
        return redirect()->route('grupotrabajo.agenda')
            ->with('success');
    } catch (\Exception $e) {
        return back()->with('error', 'Error al eliminar la reunión');
    }
}
}