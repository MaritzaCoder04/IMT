<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReporteBimestral extends Model
{
    protected $table = 'reportes_bimestrales';
    
    protected $fillable = [
        'anio',
        'bimestre',
        'datos_grupos',
        'notas'
    ];
    
    protected $casts = [
        'datos_grupos' => 'array'
    ];
}