<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Parte extends Model
{
    protected $table = 'parte';
    protected $primaryKey = 'ID_parte';
    public $timestamps = false;
    
    protected $fillable = ['desc'];
}