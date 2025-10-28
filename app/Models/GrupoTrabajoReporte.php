<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GrupoTrabajoReporte extends Model
{
    use HasFactory;

    protected $table = 'grupo_trabajo_reportes';

    protected $fillable = [
        'grupo_trabajo_id',
        'anio',
        'bimestre',
        'meta_bimestral',
        'realizado_bimestre',
        'total_acumulado',
        'porc_bimestral',
        'porc_anual',
        'observaciones',
        'notas',
    ];

    public function grupoTrabajo()
    {
        return $this->belongsTo(GrupoTrabajo::class, 'grupo_trabajo_id');
    }
}