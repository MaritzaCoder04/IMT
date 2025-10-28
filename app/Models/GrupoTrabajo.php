<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GrupoTrabajo extends Model
{
    protected $table = 'grupos_trabajo';
    
    protected $fillable = [
        'nombre',
        'anio_meta',
        'meta_anual',
        'meta_bimestre_1',
        'meta_bimestre_2',
        'meta_bimestre_3',
        'meta_bimestre_4',
        'meta_bimestre_5',
        'meta_bimestre_6',
        'observaciones',
    ];

    // ✨ Atributos que no están en BD pero usas en controladores o vistas
    protected $appends = ['realizados', 'total_realizado'];

    // Inicialización de propiedades para evitar errores
    public $realizados = [];
    public $total_realizado = 0;

    public function reuniones()
    {
        return $this->hasMany(Reunion::class, 'grupo_trabajo_id');
    }
}
