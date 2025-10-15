<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class Tipo extends Model
{
    use HasFactory, Notifiable;

    protected $table = 'tipo';

    protected $fillable = [
        'ID_tipo',
        'clave',
        'desc'
    ];
}
