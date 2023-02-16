<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Exception;
use App\Models\Akun;
use App\Models\Pengguna;
use App\Http\Controllers\LoginController;

class GoogleController extends Controller
{
    // untuk login google
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }



    public function callback(Request $request)
    {
        try {
            $user_google    = Socialite::driver('google')->user();

            $extemail = \MyApp::extractemail($user_google->getEmail());
            if ($extemail[2] != 'iainkendari.ac.id') {
                $request->session()->flash('pesan', 'Hanya boleh menggunakan email institusi @iainkendari.ac.id');
                return redirect()->route('login');
            }

            $user = Akun::where('email', $user_google->getEmail())->first();
            if ($user) {
                Auth::login($user);
                LoginController::setSession($user_google->getEmail());
                return redirect()->route('dashboard');
            } else {
                $newuser = Akun::Create([
                    'email' => $user_google->getEmail(),
                    'nama' => $user_google->getName(),
                    'kel' => 'L',
                    'tempatlahir' => '-',
                    'tanggallahir' => date('Y-m-d'),
                    'alamat' => '-',
                    'nohp' => '000000000000',
                    'password' => null,
                ]);
                //simpan sebagai pengguna dan langsung aktif
                Pengguna::create([
                    'akun_id' => $newuser->id,
                    'grup_id' => 2,
                    'token' => \MyApp::generateToken(),
                    'aktif' => "1",
                ]);

                Auth::login($newuser);
                LoginController::setSession($user_google->getEmail());
                return redirect()->route('dashboard');
            }
        } catch (\Throwable $e) {
            // return redirect()->route('login');

            $request->session()->flash('pesan', $e->getMessage());
            return redirect()->route('login');
        }
    }
}
