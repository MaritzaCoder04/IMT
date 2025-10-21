<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tipo extends Model
{
    protected $table = 'tipo';
    protected $primaryKey = 'ID_tipo';
    public $timestamps = false;
    
    protected $fillable = [
        'clave',
        'desc'
    ];
    
    public function documentos()
    {
        return $this->hasMany(Documento::class, 'tipo', 'ID_tipo');
    }
}