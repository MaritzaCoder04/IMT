<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Origen extends Model
{
    protected $table = 'origen';
    protected $primaryKey = 'ID_origen';
    public $timestamps = false;
    
    protected $fillable = ['desc'];
}