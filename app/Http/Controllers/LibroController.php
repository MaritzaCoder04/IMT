<?php

namespace App\Http\Controllers;

use App\Models\Libro;
use Illuminate\Http\Request;

class LibroController extends Controller
{
    public function index()
    {
        // Evitar errores al generar URLs cuando existan registros sin clave primaria
        $libros = Libro::whereNotNull('ID_libro')
            ->orderBy('desc')
            ->get();
        return view('libros.index', compact('libros'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'desc' => 'required|string|max:255',
            'clave' => 'nullable|string|max:50',
        ]);

        // Ensure primary key exists to avoid UrlGenerationException when rendering update URLs
        $nextId = (Libro::max('ID_libro') ?? 0) + 1;
        $libro = new Libro();
        $libro->ID_libro = $nextId;
        $libro->desc = $validated['desc'];
        $libro->clave = $validated['clave'] ?? null;
        $libro->save();

        $target = route('libros.index');
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

        $libro = Libro::findOrFail($id);
        $libro->update($validated);
        $target = route('libros.index');
        if ($request->boolean('modal')) {
            return redirect()->to($target.'?modal=1&saved=1');
        }
        return redirect()->to($target);
    }

    public function destroy($id)
    {
        $libro = Libro::findOrFail($id);
        $libro->delete();
        $target = route('libros.index');
        if (request()->boolean('modal')) {
            return redirect()->to($target.'?modal=1&deleted=1');
        }
        return redirect()->to($target);
    }
}