<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    //
    public function index()
    {
        //akses session
        //dd(session()->all());
        //dd(session()->get("akses"));

        //akses user yang login dari auth
        //dd(auth()->user());
        //dd(auth()->user()->email);

        return view('dashboard.index');
    }
}
