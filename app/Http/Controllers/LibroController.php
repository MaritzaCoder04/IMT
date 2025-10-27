<?php

namespace App\Http\Controllers;

use App\Models\Libro;
use Illuminate\Http\Request;

class LibroController extends Controller
{
    public function index()
    {
        $libros = Libro::orderBy('desc')->get();
        return view('libros.index', compact('libros'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'desc' => 'required|string|max:255',
            'clave' => 'nullable|string|max:50',
        ]);

        Libro::create($validated);
        return redirect()->route('libros.index');
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'desc' => 'required|string|max:255',
            'clave' => 'nullable|string|max:50',
        ]);

        $libro = Libro::findOrFail($id);
        $libro->update($validated);
        return redirect()->route('libros.index');
    }

    public function destroy($id)
    {
        $libro = Libro::findOrFail($id);
        $libro->delete();
        return redirect()->route('libros.index');
    }
}