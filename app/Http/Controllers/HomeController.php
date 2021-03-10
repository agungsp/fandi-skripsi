<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // return view('client.home');
        if (Auth::user()->role == 'admin') {
            return redirect()->route('transaksi');
        }
        else {
            return redirect()->route('analisa');
        }
    }
}
