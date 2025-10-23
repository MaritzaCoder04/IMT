<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reunion extends Model
{
    protected $table = 'reuniones';
    
    protected $fillable = [
        'grupo_trabajo_id',
        'fecha',
        'programada',
        'motivo',
    ];

    protected $casts = [
        'fecha' => 'date',
        'programada' => 'boolean',
    ];

    public function grupoTrabajo()
    {
        return $this->belongsTo(GrupoTrabajo::class, 'grupo_trabajo_id');
    }
}