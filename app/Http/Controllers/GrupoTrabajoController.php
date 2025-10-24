<?php

namespace App\Http\Controllers;

use App\Models\GrupoTrabajo;
use App\Models\Grupo;
use App\Models\Reunion;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\ReporteBimestral;

class GrupoTrabajoController extends Controller
{
    // Vista 1: Lista de grupos de trabajo
    public function index()
    {
        $grupos = GrupoTrabajo::with('reuniones')->get();
        return view('grupotrabajo.index', compact('grupos'));
    }

    // Vista 2: Formulario para crear grupo
    public function create()
    {
        $grupos = Grupo::activos()->orderBy('nombre')->get();
        return view('grupotrabajo.create', compact('grupos'));
    }

    // Guardar grupo de trabajo
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'meta_anual' => 'required|integer|min:0',
            'meta_bimestre_1' => 'required|integer|min:0',
            'meta_bimestre_2' => 'required|integer|min:0',
            'meta_bimestre_3' => 'required|integer|min:0',
            'meta_bimestre_4' => 'required|integer|min:0',
            'meta_bimestre_5' => 'required|integer|min:0',
            'meta_bimestre_6' => 'required|integer|min:0',
        ]);

        GrupoTrabajo::create($validated);

        return redirect()->route('grupotrabajo.index')->with('success');
    }

    // Vista 3: Agenda/Calendario
    public function agenda(Request $request)
    {
        $anio = $request->get('anio', date('Y'));
        $busqueda = $request->get('busqueda', '');

        $query = GrupoTrabajo::with(['reuniones' => function($q) use ($anio) {
            $q->whereYear('fecha', $anio);
        }]);

        if ($busqueda) {
            $query->where('nombre', 'like', '%' . $busqueda . '%');
        }

        $grupos = $query->get();

        return view('grupotrabajo.agenda', compact('grupos', 'anio', 'busqueda'));
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
    public function reporte()
    {
        $grupos = GrupoTrabajo::with('reuniones')->get();
        
        // Calcular realizados por bimestre
        foreach ($grupos as $grupo) {
            $grupo->realizados = [];
            for ($i = 1; $i <= 6; $i++) {
                $mesInicio = ($i - 1) * 2 + 1;
                $mesFin = $mesInicio + 1;
                
                $count = $grupo->reuniones()
                    ->whereMonth('fecha', '>=', $mesInicio)
                    ->whereMonth('fecha', '<=', $mesFin)
                    ->whereYear('fecha', date('Y'))
                    ->count();
                
                $grupo->realizados[$i] = $count;
            }
            
            $grupo->total_realizado = $grupo->reuniones()->whereYear('fecha', date('Y'))->count();
        }

        return view('grupotrabajo.reporte', compact('grupos'));
    }

    // Actualizar observaciones
    public function actualizarObservaciones(Request $request, $id)
    {
        $grupo = GrupoTrabajo::findOrFail($id);
        $grupo->observaciones = $request->observaciones;
        $grupo->save();

        return redirect()->back()->with('success');
    }

    // Descargar PDF
    public function descargarPDF()
    {
        $grupos = GrupoTrabajo::with('reuniones')->get();
        
        foreach ($grupos as $grupo) {
            $grupo->realizados = [];
            for ($i = 1; $i <= 6; $i++) {
                $mesInicio = ($i - 1) * 2 + 1;
                $mesFin = $mesInicio + 1;
                
                $count = $grupo->reuniones()
                    ->whereMonth('fecha', '>=', $mesInicio)
                    ->whereMonth('fecha', '<=', $mesFin)
                    ->whereYear('fecha', date('Y'))
                    ->count();
                
                $grupo->realizados[$i] = $count;
            }
            
            $grupo->total_realizado = $grupo->reuniones()->whereYear('fecha', date('Y'))->count();
        }

        $pdf = Pdf::loadView('grupotrabajo.pdf', compact('grupos'));
        return $pdf->download('reporte-grupos-trabajo-' . date('Y-m-d') . '.pdf');
    }

    public function edit($id)
    {
        $grupo = GrupoTrabajo::findOrFail($id);
        $grupos = Grupo::activos()->orderBy('nombre')->get();
        return view('grupotrabajo.edit', compact('grupo', 'grupos'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'meta_anual' => 'required|integer|min:0',
        ]);

        $grupo = GrupoTrabajo::findOrFail($id);
        $grupo->update($validated);

        return redirect()->route('grupotrabajo.index')->with('success');
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
    
    $grupos = GrupoTrabajo::with('reuniones')->get();
    
    // Obtener todos los reportes guardados agrupados por año
    $reportesPorAnio = ReporteBimestral::orderBy('anio', 'desc')
        ->orderBy('bimestre', 'asc')
        ->get()
        ->groupBy('anio');
    
    // Verificar qué bimestres ya están guardados
    $reportesGuardados = [];
    foreach ($reportesPorAnio as $anio => $reportes) {
        foreach ($reportes as $reporte) {
            $reportesGuardados[$anio][$reporte->bimestre] = true;
        }
    }
    
    $ultimoReporte = ReporteBimestral::latest()->first();
    
    return view('grupotrabajo.reporte', compact(
        'grupos',
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
        
        // Guardar o actualizar reporte
        ReporteBimestral::updateOrCreate(
            [
                'anio' => $request->anio,
                'bimestre' => $request->bimestre
            ],
            [
                'datos_grupos' => $datosReporte,
                'notas' => $request->notas
            ]
        );
        
        return redirect()->route('grupotrabajo.reportes', [
            'anio' => $request->anio,
            'bimestre' => $request->bimestre
        ])->with('success');
        
    } catch (\Exception $e) {
        return back()->with('error', 'Error al guardar el reporte: ' . $e->getMessage());
    }
}

public function eliminarReporte($id)
{
    try {
        $reporte = ReporteBimestral::findOrFail($id);
        $reporte->delete();
        
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
        $grupos = Grupo::activos()->get();
        
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