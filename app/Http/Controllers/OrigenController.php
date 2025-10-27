<?php

namespace App\Http\Controllers;

use App\Models\Origen;
use Illuminate\Http\Request;

class OrigenController extends Controller
{
    public function index()
    {
        $origenes = Origen::orderBy('desc')->get();
        return view('origenes.index', compact('origenes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'desc' => 'required|string|max:255',
        ]);

        Origen::create($validated);
        return redirect()->route('origenes.index');
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'desc' => 'required|string|max:255',
        ]);

        $origen = Origen::findOrFail($id);
        $origen->update($validated);
        return redirect()->route('origenes.index');
    }

    public function destroy($id)
    {
        $origen = Origen::findOrFail($id);
        $origen->delete();
        return redirect()->route('origenes.index');
    }
}