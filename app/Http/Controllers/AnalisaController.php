<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AnalisaController extends Controller
{
    public function index()
    {
        return view('client.analisa');
    }
}
