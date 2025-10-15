<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Titulo extends Model
{
    protected $table = 'titulo';
    protected $primaryKey = 'ID_titulo';
    public $timestamps = false;
    
    protected $fillable = ['desc'];
}