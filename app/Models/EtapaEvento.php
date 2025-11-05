<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EtapaEvento extends Model
{
    use HasFactory;

    protected $table = 'etapas_eventos';

    protected $fillable = [
        'ID_doc',
        'etapa',
        'fecha',
    ];

    protected $casts = [
        'fecha' => 'date',
    ];

    public function documento()
    {
        return $this->belongsTo(Documento::class, 'ID_doc', 'ID_doc');
    }
}