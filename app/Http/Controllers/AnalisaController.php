<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\File;
use App\Exports\ResultExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\Snappy\Facades\SnappyPdf;
use App\Models\Result;

class AnalisaController extends Controller
{
    public function index()
    {
        $files = File::orderBy('created_at', 'desc')->get();
        return view('client.analisa', compact('files'));
    }

    public function toExcel($file_name)
    {
        $file    = File::where('name', $file_name)->first();
        return Excel::download(new ResultExport($file), explode('.', $file->name)[0] . ' - ' . now()->format('Ymd_his') .'.xlsx');
    }

    public function toPdf($file_name)
    {
        $file = File::where('name', $file_name)->first();
        $results = Result::where('file_id', $file->id)->get();
        $pdf = SnappyPdf::loadView('pdf.analisa', compact('file', 'results'));
        return $pdf->download(explode('.', $file->name)[0] . ' - ' . now()->format('Ymd_his') .'.pdf');
    }
}
