<?php

namespace App\Http\Controllers;

// use Illuminate\Http\Request;

class DashboardController extends Controller
{
    // protected $hakakses;
    // public function __construct()
    // {
    //     $this->hakakses = \MyApp::hakakses("Dashboard");
    // }

    public function index()
    {
        //akses session
        //dd(session()->all());
        //dd(session()->get("akses"));
        //session()->get("bagians");
        //akses user yang login dari auth
        //dd(auth()->user());
        //dd(auth()->user()->email);
        return view('dashboard.index');
    }
}
