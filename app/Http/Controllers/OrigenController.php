<?php

namespace App\Http\Controllers;

use App\Models\Origen;
use Illuminate\Http\Request;

class OrigenController extends Controller
{
    public function index()
    {
        $origenes = Origen::whereNotNull('ID_origen')
            ->orderBy('desc')
            ->get();
        return view('origenes.index', compact('origenes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'desc' => 'required|string|max:255',
        ]);

        // Ensure primary key exists to avoid UrlGenerationException when rendering update URLs
        $nextId = (Origen::max('ID_origen') ?? 0) + 1;
        $origen = new Origen();
        $origen->ID_origen = $nextId;
        $origen->desc = $validated['desc'];
        $origen->save();

        $target = route('origenes.index');
        if ($request->boolean('modal')) {
            return redirect()->to($target.'?modal=1&saved=1');
        }
        return redirect()->to($target);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'desc' => 'required|string|max:255',
        ]);

        $origen = Origen::findOrFail($id);
        $origen->update($validated);
        $target = route('origenes.index');
        if ($request->boolean('modal')) {
            return redirect()->to($target.'?modal=1&saved=1');
        }
        return redirect()->to($target);
    }

    public function destroy($id)
    {
        $origen = Origen::findOrFail($id);
        $origen->delete();
        $target = route('origenes.index');
        if (request()->boolean('modal')) {
            return redirect()->to($target.'?modal=1&deleted=1');
        }
        return redirect()->to($target);
    }
}