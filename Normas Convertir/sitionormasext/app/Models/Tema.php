<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class Tema extends Model
{
    use HasFactory, Notifiable;

    protected $table = 'tema';

    protected $fillable = [
        'ID_tema',
        'clave',
        'desc'
    ];
}
