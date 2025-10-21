<?php

namespace App\Http\Controllers;

use App\Models\Documento;

class ControlDeAvancesController extends Controller
{
    public function index()
    {
        return view('controldeavances');
    }
}