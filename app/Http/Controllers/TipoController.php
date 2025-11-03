<?php

namespace App\Http\Controllers;

use App\Models\Tipo;
use Illuminate\Http\Request;

class TipoController extends Controller
{
    public function index()
    {
        $tipos = Tipo::orderBy('desc')->get();
        return view('tipos.index', compact('tipos'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'desc' => 'required|string|max:255',
            'clave' => 'required|string|max:50',
        ]);

        Tipo::create($validated);
        $target = route('tipos.index');
        if ($request->boolean('modal')) {
            return redirect()->to($target.'?modal=1&saved=1');
        }
        return redirect()->to($target);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'desc' => 'required|string|max:255',
            'clave' => 'required|string|max:50',
        ]);

        $tipo = Tipo::findOrFail($id);
        $tipo->update($validated);
        return redirect()->route('tipos.index');
    }

    public function destroy($id)
    {
        $tipo = Tipo::findOrFail($id);
        $tipo->delete();
        return redirect()->route('tipos.index');
    }
}