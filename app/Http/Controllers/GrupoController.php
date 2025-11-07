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
        // Cargar tipos de grupo desde 'grupos' con fallback si la tabla no existe
        $grupos = collect();
        if (Schema::hasTable('grupos')) {
            try {
                $grupos = Grupo::orderBy('nombre')->get();
            } catch (\Throwable $e) {
                $grupos = collect();
            }
        }

        // Sugerencias SOLO desde la BD de 'grupos' (catálogo de tipos)
        $nombresGrupos = $grupos
            ->pluck('nombre')
            ->filter()
            ->unique()
            ->sort()
            ->values();

        // Filtrar por contexto (scope) si viene de una agenda específica
        $scope = request()->get('scope');
        if ($scope === 'productos') {
            $permitidos = [
                'Anteproyecto Preliminar',
                'Anteproyecto Final',
                'Proyecto Preliminar',
                'Publicación de Manuales/Normas',
            ];
            $nombresGrupos = $nombresGrupos->filter(fn($n) => in_array($n, $permitidos))->values();
            $grupos = $grupos->filter(fn($g) => in_array($g->nombre ?? '', $permitidos))->values();
        } elseif ($scope === 'representaciones') {
            $permitidos = [
                'Subcomité No.4',
                'Grupo de Trabajo 1',
            ];
            $nombresGrupos = $nombresGrupos->filter(fn($n) => in_array($n, $permitidos))->values();
            $grupos = $grupos->filter(fn($g) => in_array($g->nombre ?? '', $permitidos))->values();
        } elseif ($scope === 'otros') {
            $excluir = [
                'Anteproyecto Preliminar',
                'Anteproyecto Final',
                'Proyecto Preliminar',
                'Publicación de Manuales/Normas',
            ];
            $nombresGrupos = $nombresGrupos->filter(fn($n) => !in_array($n, $excluir))->values();
            $grupos = $grupos->filter(fn($g) => !in_array($g->nombre ?? '', $excluir))->values();
        }

        return view('grupos.index', compact('grupos', 'nombresGrupos'));
    }

    public function store(Request $request)
    {
        // Impedir operaciones si la tabla de 'grupos' no existe
        if (!Schema::hasTable('grupos')) {
            return redirect()->route('grupos.index')->with('error', 'No se puede crear: la tabla "grupos" no existe.');
        }

        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'activo' => 'nullable|boolean',
            'unidad_medida' => 'nullable|string|max:255',
        ]);

        // Establecer activo=true por defecto si no se envía en el formulario
        $validated['activo'] = $request->has('activo') ? $request->boolean('activo') : true;
        // Asignar consecutivo para columna 'no' si existe
        if (Schema::hasTable('grupos') && Schema::hasColumn('grupos', 'no') && !isset($validated['no'])) {
            $validated['no'] = ((int) DB::table('grupos')->max('no')) + 1;
        }
        // Establecer unidad de medida por defecto si la columna existe
        if (Schema::hasTable('grupos') && Schema::hasColumn('grupos', 'unidad_medida') && empty($validated['unidad_medida'])) {
            $validated['unidad_medida'] = 'Reunión';
        }

        try {
            Grupo::create($validated);
        } catch (\Throwable $e) {
            return redirect()->route('grupos.index')->with('error', 'Error al crear el tipo de grupo: ' . $e->getMessage());
        }

        $target = route('grupos.index');
        $scope = $request->get('scope');
        if ($request->boolean('modal')) {
            $redir = $target.'?modal=1&saved=1';
            if ($scope) { $redir .= '&scope=' . urlencode($scope); }
            return redirect()->to($redir);
        }
        if ($scope) { $target .= '?scope=' . urlencode($scope); }
        return redirect()->to($target)->with('success');
    }

    public function update(Request $request, $id)
    {
        if (!Schema::hasTable('grupos')) {
            return redirect()->route('grupos.index')->with('error', 'No se puede actualizar: la tabla "grupos" no existe.');
        }

        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'activo' => 'nullable|boolean',
        ]);

        try {
            $grupo = Grupo::findOrFail($id);
            $grupo->update([
                'nombre' => $validated['nombre'],
                'descripcion' => $validated['descripcion'] ?? null,
                'activo' => $request->boolean('activo'),
            ]);
        } catch (\Throwable $e) {
            return redirect()->route('grupos.index')->with('error', 'Error al actualizar: ' . $e->getMessage());
        }

        $target = route('grupos.index');
        $scope = $request->get('scope');
        if ($request->boolean('modal')) {
            $redir = $target.'?modal=1&saved=1';
            if ($scope) { $redir .= '&scope=' . urlencode($scope); }
            return redirect()->to($redir);
        }
        if ($scope) { $target .= '?scope=' . urlencode($scope); }
        return redirect()->to($target)->with('success');
    }

    public function destroy(Request $request, $id)
    {
        if (!Schema::hasTable('grupos')) {
            return redirect()->route('grupos.index')->with('error', 'No se puede eliminar: la tabla "grupos" no existe.');
        }

        try {
            $grupo = Grupo::findOrFail($id);
            $grupo->delete();
        } catch (\Throwable $e) {
            return redirect()->route('grupos.index')->with('error', 'Error al eliminar: ' . $e->getMessage());
        }
        $target = route('grupos.index');
        $scope = $request->get('scope');
        if ($request->boolean('modal')) {
            $redir = $target.'?modal=1&saved=1';
            if ($scope) { $redir .= '&scope=' . urlencode($scope); }
            return redirect()->to($redir);
        }
        if ($scope) { $target .= '?scope=' . urlencode($scope); }
        return redirect()->to($target)->with('success');
    }
}