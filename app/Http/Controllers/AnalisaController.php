<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\File;
use App\Models\Result;
use PDF;

class AnalisaController extends Controller
{
    public function index()
    {
        $files = File::orderBy('created_at', 'desc')->get();
        return view('client.analisa', compact('files'));
    }

    public function view($file_name)
    {
        $file = File::where('name', $file_name)->first();
        $results = Result::where('file_id', $file->id)->limit(10)->get();
        return view('pdf.analisa', compact('file', 'results'));
    }
}
