<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class Parte extends Model
{
    use HasFactory, Notifiable;

    protected $table = 'parte';

    protected $fillable = [
        'ID_parte',
        'desc'
    ];
}
