<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Libro extends Model
{
    protected $table = 'libro';
    protected $primaryKey = 'ID_libro';
    public $timestamps = false;
    
    protected $fillable = ['clave', 'desc'];
}