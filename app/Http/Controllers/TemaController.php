<?php

namespace App\Http\Controllers;

use App\Models\Tema;
use Illuminate\Http\Request;

class TemaController extends Controller
{
    public function index()
    {
        $temas = Tema::orderBy('desc')->get();
        return view('temas.index', compact('temas'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'desc' => 'required|string|max:255',
            'clave' => 'nullable|string|max:50',
        ]);

        // Ensure primary key exists to avoid UrlGenerationException when rendering update URLs
        $nextId = (Tema::max('ID_tema') ?? 0) + 1;
        $tema = new Tema();
        $tema->ID_tema = $nextId;
        $tema->desc = $validated['desc'];
        $tema->clave = $validated['clave'] ?? null;
        $tema->save();

        $target = route('temas.index');
        if ($request->boolean('modal')) {
            return redirect()->to($target.'?modal=1&saved=1');
        }
        return redirect()->to($target);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'desc' => 'required|string|max:255',
            'clave' => 'nullable|string|max:50',
        ]);

        $tema = Tema::findOrFail($id);
        $tema->update($validated);
        return redirect()->route('temas.index');
    }

    public function destroy($id)
    {
        $tema = Tema::findOrFail($id);
        $tema->delete();
        return redirect()->route('temas.index');
    }
}