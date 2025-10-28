<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Grupo extends Model
{
    protected $table = 'grupos';
    public $timestamps = false;
    
    protected $fillable = [
        'no',
        'nombre',
        'descripcion',
        'activo',
        'unidad_medida'
    ];

    protected $attributes = [
        'unidad_medida' => 'Reunión',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    // Scope para obtener solo grupos activos
    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }
}
