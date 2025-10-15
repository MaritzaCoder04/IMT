<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Documento extends Model
{
    protected $table = 'documento';
    protected $primaryKey = 'ID_doc';
    public $timestamps = false;
    
    protected $fillable = [
        'nombre',
        'tipo',
        'origen',
        'libro',
        'tema',
        'parte',
        'titulo',
        'capitulo',
        'anio_simple',
        'anio',
        'vigente'
    ];

    public function etapas()
    {
        return $this->hasOne(Etapa::class, 'ID_doc', 'ID_doc');
    }

    public function origenRelacion()
    {
        return $this->belongsTo(Libro::class, 'origen', 'ID_origen');
    }

    public function libroRelacion()
    {
        return $this->belongsTo(Libro::class, 'libro', 'ID_libro');
    }
    
    public function parteRelacion()
    {
        return $this->belongsTo(Parte::class, 'parte', 'ID_parte');
    }

    public function temaRelacion()
    {
        return $this->belongsTo(Tema::class, 'tema', 'ID_tema');
    }

    public function tituloRelacion()
    {
        return $this->belongsTo(Titulo::class, 'titulo', 'ID_titulo');
    }

    public function capituloRelacion()
    {
        return $this->belongsTo(Capitulo::class, 'capitulo', 'ID_capitulo');
    }
}