<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Materia extends Model
{
    protected $table = 'materia';
    protected $primaryKey = 'ID_mat';
    public $timestamps = false;
    
    protected $fillable = ['denominacion', 'orden'];
}