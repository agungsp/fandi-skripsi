<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use App\Models\Result;
use App\Models\File;

class ResultExport implements FromView
{

    private $file;

    public function __construct(File $file)
    {
        $this->file = $file;
    }


    public function view(): View
    {
        $file   = $this->file;
        $results = Result::where('file_id', $file->id)->get();
        return view('export.result', compact('file', 'results'));
    }
}
