<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class Titulo extends Model
{
    use HasFactory, Notifiable;

    protected $table = 'titulo';

    protected $fillable = [
        'ID_titulo',
        'desc'
    ];
}
