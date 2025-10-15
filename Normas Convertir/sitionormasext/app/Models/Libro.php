<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class Libro extends Model
{
    use HasFactory, Notifiable;

    protected $table = 'libro';

    protected $fillable = [
        'ID_libro',
        'clave',
        'desc'
    ];
}
