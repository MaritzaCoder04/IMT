<?php

namespace App\Http\Controllers;

use App\Models\Libro;
use App\Models\Parte;
use App\Models\Tema;
use App\Models\Titulo;

class ClasificacionController extends Controller
{
    public function libros()
    {
        // "desc" es palabra reservada; usa backticks con selectRaw/orderByRaw
        return Libro::selectRaw('ID_libro as id, `desc` as text')
            ->orderByRaw('`desc`')
            ->get();
    }

    public function partes()
    {
        return Parte::selectRaw('ID_parte as id, `desc` as text')
            ->orderByRaw('`desc`')
            ->get();
    }
}

    
    /*public function partesByLibro($ID_libro)
    {
        return Parte::where('ID_libro', $ID_libro)
            ->selectRaw('ID_parte as id, `desc` as text')
            ->orderByRaw('`desc`')
            ->get();

    }

    public function temasByParte($ID_parte)
    {
        return Tema::where('ID_parte', $ID_parte)
            ->selectRaw('ID_tema as id, `desc` as text')
            ->orderByRaw('`desc`')
            ->get();
    }

    public function titulosByTema($ID_tema)
    {
        return Titulo::where('ID_tema', $ID_tema)
            ->selectRaw('ID_titulo as id, `desc` as text')
            ->orderByRaw('`desc`')
            ->get();
    }

}*/
