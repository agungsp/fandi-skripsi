<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\File;
use App\Exports\ResultExport;
use Maatwebsite\Excel\Facades\Excel;

class AnalisaController extends Controller
{
    public function index()
    {
        $files = File::orderBy('created_at', 'desc')->get();
        return view('client.analisa', compact('files'));
    }

    public function view($file_name)
    {
        $file    = File::where('name', $file_name)->first();
        return Excel::download(new ResultExport($file), 'Apriori Result ' . now()->format('Ymd_his') .'.xlsx');
    }
}
