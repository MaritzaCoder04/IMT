<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class Documento extends Model
{
    use HasFactory, Notifiable;

    protected $table = 'documento';

    protected $fillable = [
        'ID_doc',
        'nombre',
        'tipo',
        'libro',
        'tema',
        'parte',
        'titulo',
        'capitulo',
        'anio_simple',
        'anio',
        'terracerias',    
        'estructuras',
        'drenaje',
        'pavimentos',
        'tuneles',
        'cimentaciones',
        'senalamiento',
        'obras_marginales',
        'SIT',
        'novedades',
        'vigente'
    ];
}
