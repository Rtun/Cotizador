<?php

namespace App\Http\Controllers;

use App\Models\Prueba;
use Illuminate\Http\Request;

class PruebaController extends Controller
{
    public function index(){
        $pruebas = Prueba::all();

        return view('index', compact('pruebas'));
    }
}
