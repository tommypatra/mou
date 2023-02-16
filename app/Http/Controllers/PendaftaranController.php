<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Models\Akun;
use App\Models\Pengguna;
use App\Mail\MailPendaftaran;
use app\Jobs\KirimEmail;
use Illuminate\Database\Eloquent\ModelNotFoundException;
//use App\Helpers\Web;

class PendaftaranController extends Controller
{
    public function index()
    {
        return view('akun.pendaftaran');
    }

    public function simpanpendaftaran(Request $request)
    {
        $retval = array("status" => false, "messages" => ["gagal, hubungi admin"]);

        $datapost = $this->validate($request, [
            'nama' => 'required|min:3',
            'email' => 'required|email:dns|unique:akuns,email',
            'kel' => 'required',
            'tanggallahir' => 'required',
            'password' => 'required|alpha_num|min:8',
            'terms' => 'required',
        ]);
        $datapost['password'] = Hash::make($datapost['password']);
        unset($datapost['terms']);
        //dd($datapost);

        $extemail = \MyApp::extractemail($datapost['email']);
        if ($extemail[2] != 'iainkendari.ac.id') {
            $retval['messages'] = ['Hanya boleh menggunakan email institusi @iainkendari.ac.id'];
            return response()->json($retval);
        }

        try {
            DB::beginTransaction();
            $id = Akun::create($datapost)->id;
            $token = \MyApp::generateToken();

            //simpan sebagai pengguna
            $pengguna = Pengguna::create([
                'akun_id' => $id,
                'grup_id' => 2,
                'token' => $token,
                'aktif' => "0",
            ]);
            $retval = array("status" => true, "messages" => ["Pendaftaran Berhasil, Cek email " . $datapost['email'] . " untuk melakukan aktivasi. Dalam beberapa saat anda akan di arahkan ke halaman login"]);
            $golink = url('/aktivasi-pengguna/' . $token);
            $details = [
                'title' => 'Pendaftaran Akun Berhasil',
                'body' => 'Terima kasih telah melakukan pendaftaran, untuk aktivasi akun silahkan klik link berikut ' . $golink,
            ];
            DB::commit();
            Mail::to($datapost['email'])->queue(new MailPendaftaran($details));
        } catch (\Throwable $e) {
            $retval['messages'] = [$e->getMessage()];
            DB::rollBack();
            //return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
        return response()->json($retval);
    }


    public function aktivasi_pengguna($token = null)
    {
        $retval = array("status" => false, "messages" => ["gagal, hubungi admin"]);
        if ($token) {
            $cari = Pengguna::where([
                ['token', $token],
                ['aktif', '!=', '1'],
            ])->first();
            if ($cari) {
                try {
                    DB::beginTransaction();
                    $data = [
                        'aktif' => "1"
                    ];
                    $cari->update($data);
                    DB::commit();
                    $retval = array("status" => true, "messages" => ["Aktifasi berhasil dilakukan, Silahkan login terlebih dahulu... beberapa saat lagi anda akan di arahkan ke halaman login"]);
                } catch (\Throwable $e) {
                    $retval['messages'] = [$e->getMessage()];
                    DB::rollBack();
                }
            }
        }
        return response()->json($retval);
    }
}
