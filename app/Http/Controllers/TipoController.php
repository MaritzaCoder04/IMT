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

        // Ensure primary key exists to avoid UrlGenerationException when rendering update URLs
        $nextId = (Tipo::max('ID_tipo') ?? 0) + 1;
        $tipo = new Tipo();
        $tipo->ID_tipo = $nextId;
        $tipo->desc = $validated['desc'];
        $tipo->clave = $validated['clave'];
        $tipo->save();

        $target = route('tipos.index');
        if ($request->boolean('modal')) {
            return redirect()->to($target.'?modal=1&saved=1');
        }
        return redirect()->to($target);
    }

    public function update(Request $request, Tipo $tipo)
    {
        $validated = $request->validate([
            'desc' => 'required|string|max:255',
            'clave' => 'required|string|max:50',
        ]);

        $tipo->update($validated);
        return redirect()->route('tipos.index');
    }

    public function destroy(Tipo $tipo)
    {
        $tipo->delete();
        return redirect()->route('tipos.index');
    }
}