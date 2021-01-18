<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\File;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use App\Helpers\AprioriHelper;
use App\Models\Result;
use App\Jobs\RunAprioriProcess;
use App\Jobs\SetItemsetCode;

class TransaksiController extends Controller
{
    public function index()
    {
        $setting = Setting::find(Auth::id());
        $files   = File::where('user_id', Auth::id())->orderBy('created_at', 'desc')->get();
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
            'support'    => $setting->support,
            'user_id'    => Auth::id(),
        ]);

        return redirect()->route('transaksi')->with('status', 'Excel file has been uploaded.');
    }

    public function import(Request $request)
    {
        ini_set('max_execution_time', 3600);
        $file = File::find($request->file_id);
        dispatch(new SetItemsetCode($file->id))->afterResponse();
        return File::find($request->file_id)->imported;
    }

    public function calculate(Request $request)
    {
        $file = File::find($request->file_id);
        dispatch(
            new RunAprioriProcess($file->id)
        )->afterResponse();
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
        Result::where('file_id', $request->file_id)->delete();
        return File::find($request->file_id)->update([
            'confidence' => $request->confidence,
            'support'    => $request->support,
            'calculated' => false,
        ]);
    }
}
