<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Akun;

class LoginController extends Controller
{
    public function index()
    {
        return view('akun.login');
    }

    public function authenticate(Request $request)
    {
        $retval = array("status" => false, "messages" => ["login gagal, hubungi admin"]);
        //dd($request->all());
        $credentials = $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required|alpha_num|min:8',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $groups = \MyApp::listgroups($credentials['email']);
            session(
                [
                    'akses' => $groups->first()['id'],
                    'groups' => $groups,
                ]
            );
            $retval = array("status" => true, "messages" => ["Login berhasil, user ditemukan. Tunggu sedang diarahkan ke laman dashboard"]);
        } else {
            $retval['messages'] = ["login tidak berhasil, user atau password tidak ditemukan"];
        }
        return response()->json($retval);
    }

    public function logout()
    {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect()->route('login.lnk');
    }
}
