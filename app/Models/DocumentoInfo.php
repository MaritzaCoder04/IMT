<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentoInfo extends Model
{
    protected $table = 'documentoinfo'; // ← Singular
    protected $primaryKey = 'ID_doc';
    public $timestamps = false;
    
    protected $fillable = [
        'ID_doc',
        'nombre',
        'tipo',
        'libro',
        'tema',
        'parte',
        'desc_parte',
        'titulo',
        'desc_titulo',
        'capitulo',
        'designacion',
        'origen',
        'anio_simple',
        'anio'
    ];
    
    public function documento()
    {
        return $this->belongsTo(Documento::class, 'ID_doc', 'ID_doc');
    }
}