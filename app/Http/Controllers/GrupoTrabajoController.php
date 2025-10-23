<?php

namespace App\Http\Controllers;

use App\Models\GrupoTrabajo;
use App\Models\Reunion;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

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
        return view('grupotrabajo.create');
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
        return view('grupotrabajo.edit', compact('grupo'));
    }

}