<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CorreoController extends Controller
{
    public function config()
    {
        $data = [
            'email' => '',
            'mensaje' => '',
        ];
        if (Storage::disk('local')->exists('notifications.json')) {
            $json = json_decode(Storage::disk('local')->get('notifications.json'), true);
            if (is_array($json)) {
                $data['email'] = $json['email'] ?? '';
                $data['mensaje'] = $json['mensaje'] ?? '';
            }
        }
        return view('correo.config', $data);
    }

    public function guardar(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'mensaje' => 'nullable|string|max:2000',
        ]);

        // Persistir configuración en storage/app/notifications.json
        Storage::disk('local')->put('notifications.json', json_encode([
            'email' => $validated['email'],
            'mensaje' => $validated['mensaje'] ?? '',
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        // Si es modal, pedir cierre al padre
        if ($request->query('modal')) {
            return response()->view('correo.config_guardado', [
                'email' => $validated['email'],
            ]);
        }

        return redirect()->route('controldeavances')->with('success', 'Configuración de correo guardada');
    }
}