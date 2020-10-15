<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\File;
use Illuminate\Support\Facades\Auth;

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
            'fileName'   => $fileName,
            'confidence' => $setting->confidence,
            'support'    => $setting->support
        ]);

        return redirect()->route('transaksi')->with('status', 'Excel file has been uploaded.');
    }
}
