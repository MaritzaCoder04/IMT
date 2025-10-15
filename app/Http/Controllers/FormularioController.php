<?php

namespace App\Http\Controllers;

use App\Models\Libro;
use App\Models\Documento;
use Illuminate\Http\Request;

class FormularioController extends Controller
{
    public function create()
    {
        $libros = Libro::select(['ID_libro','desc'])->orderByRaw('`desc`')->get();
        return view('formulario', compact('libros'));
    }

    public function guardar(Request $request)
{
    // Validar los datos
    $validatedData = $request->validate([
        'tipoDocumento' => 'required',
        'ID_libro' => 'required',
        'nombre' => 'required',
        'fechaPublicacion' => 'nullable',
    ]);

    // Crear el documento en la base de datos
    $documento = new Documento();
    $documento->nombre = $request->nombre;
    
    // Convertir 'm' = 1 (Manual) y 'n' = 2 (Norma)
    $documento->tipo = ($request->tipoDocumento == 'm') ? 1 : 2;
    
    $documento->libro = $request->ID_libro;
    
    // Estos campos esperan números, así que los ponemos en 0
    $documento->tema = 0;
    $documento->parte = 0;
    $documento->titulo = 0;
    $documento->capitulo = 0;
    
    // Extraer el año de la fecha de publicación
    $anio = $request->fechaPublicacion ?? date('Y');
    $documento->anio = $anio;
    $documento->anio_simple = substr($anio, -2);
    
    // Campos booleanos
    $documento->terracerias = 0;
    $documento->estructuras = 0;
    $documento->drenaje = 0;
    $documento->pavimentos = 0;
    $documento->tuneles = 0;
    $documento->cimentaciones = 0;
    $documento->senalamiento = 0;
    $documento->obras_marginales = 0;
    $documento->SIT = 0;
    $documento->novedades = "0";
    $documento->vigente = 1;
    
    $documento->save();

    return redirect()->route('controldeavances')->with('success', 'Documento registrado correctamente');
    }
}