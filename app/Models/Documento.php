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

    // Relación con DocumentoInfo
    public function info()
    {
        return $this->hasOne(DocumentoInfo::class, 'ID_doc', 'ID_doc');
    }

    public function etapas()
    {
        return $this->hasOne(Etapa::class, 'ID_doc', 'ID_doc');
    }

    public function origenRelacion()
    {
        return $this->belongsTo(Origen::class, 'origen', 'ID_origen');
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

    public function tipoRelacion()
    {
        return $this->belongsTo(Tipo::class, 'tipo', 'ID_tipo');
    }

    public function eventos()
    {
        return $this->hasMany(\App\Models\EtapaEvento::class, 'ID_doc', 'ID_doc');
    }

}