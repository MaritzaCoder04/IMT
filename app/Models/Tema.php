<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tema extends Model
{
    protected $table = 'tema';
    protected $primaryKey = 'ID_tema';
    public $timestamps = false;
    
    protected $fillable = ['clave', 'desc'];
}