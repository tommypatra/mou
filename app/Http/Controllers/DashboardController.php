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
        //session()->get("bagians");
        //akses user yang login dari auth
        //dd(auth()->user());
        //dd(auth()->user()->email);

        // $hakakses = \MyApp::hakakses("/x");
        // $r = isset($hakakses->r) ? $hakakses->r : "0";
        // if ($hakakses->r == "1")
        //     echo "boleh";
        // die;

        return view('dashboard.index');
    }
}
