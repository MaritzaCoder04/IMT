<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Etapa extends Model
{
    protected $table = 'etapas';
    
    protected $fillable = [
        'ID_doc',
        '1a', '2a', '3a',
        '1b', '2b', '3b',
        '1c', '2c', '3c',
        '1d', '2d', '3d',
        '1e', '2e', '3e',
    ];
    
    public function documento()
    {
        return $this->belongsTo(Documento::class, 'ID_doc', 'ID_doc');
    }
}