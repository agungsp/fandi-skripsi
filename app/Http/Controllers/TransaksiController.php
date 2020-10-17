<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\File;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use App\Imports\FilesImport;
use Maatwebsite\Excel\Facades\Excel;

class TransaksiController extends Controller
{
    public function index()
    {
        $setting = Setting::find(Auth::id());
        $files   = File::orderBy('created_at', 'desc')->get();
        return view('client.transaksi', compact('setting', 'files'));
    }

    public function upload(Request $request)
    {
        $request->validate([
            'excelFile' => 'required | file'
        ]);

        $setting  = Setting::find(Auth::id());
        $fileName = uniqid('excel-file-') . '.' . $request->excelFile->getClientOriginalExtension();
        $request->excelFile->move('excelFiles', $fileName);

        File::create([
            'name'       => $fileName,
            'confidence' => $setting->confidence,
            'support'    => $setting->support
        ]);

        return redirect()->route('transaksi')->with('status', 'Excel file has been uploaded.');
    }

    public function calculate(Request $request)
    {
        $file = File::find($request->file_id);
        Excel::import(new FilesImport($request->file_id), public_path('excelFiles/'.$file->name));
        $file->calculated = true;
        $file->save();
        $file->refresh();
        return $file->calculated;
    }

    public function getSetting($file_id)
    {
        $file = File::find($file_id);
        return $file;
    }

    public function setSetting(Request $request)
    {
        Transaction::where('file_id', $request->file_id)->delete();
        return File::find($request->file_id)->update([
            'confidence' => $request->confidence,
            'support'    => $request->support,
            'calculated' => false,
        ]);
    }
}
