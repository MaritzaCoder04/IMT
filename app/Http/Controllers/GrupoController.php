<?php

namespace App\Http\Controllers;

use App\Models\Grupo;
use App\Models\GrupoTrabajo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class GrupoController extends Controller
{
    public function index()
    {
        $grupos = Grupo::orderBy('nombre')->get();

        // Combinar nombres de tipos de grupo y de grupos de trabajo existentes
        $nombresTipos = $grupos->pluck('nombre');
        $nombresTrabajo = GrupoTrabajo::orderBy('nombre')->pluck('nombre');
        $nombresGrupos = $nombresTipos->merge($nombresTrabajo)
            ->filter()
            ->unique()
            ->sort()
            ->values();

        return view('grupos.index', compact('grupos', 'nombresGrupos'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'activo' => 'nullable|boolean',
            'unidad_medida' => 'nullable|string|max:255',
        ]);

        $validated['activo'] = $request->boolean('activo');
        // Asignar consecutivo para columna 'no' si existe y es obligatoria
        if (Schema::hasColumn('grupos', 'no') && !isset($validated['no'])) {
            $validated['no'] = ((int) DB::table('grupos')->max('no')) + 1;
        }
        // Establecer unidad de medida por defecto si la columna existe
        if (Schema::hasColumn('grupos', 'unidad_medida') && empty($validated['unidad_medida'])) {
            $validated['unidad_medida'] = 'Reunión';
        }
        Grupo::create($validated);

        return redirect()->route('grupos.index')->with('success');
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'activo' => 'nullable|boolean',
        ]);

        $grupo = Grupo::findOrFail($id);
        $grupo->update([
            'nombre' => $validated['nombre'],
            'descripcion' => $validated['descripcion'] ?? null,
            'activo' => $request->boolean('activo'),
        ]);

        return redirect()->route('grupos.index')->with('success');
    }

    public function destroy($id)
    {
        $grupo = Grupo::findOrFail($id);
        $grupo->delete();
        return redirect()->route('grupos.index')->with('success');
    }
}