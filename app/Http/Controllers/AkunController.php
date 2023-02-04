<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\Akun;
use DataTables;


class AkunController extends Controller
{
    public function index()
    {
        return view('dashboard.akun');
    }

    public function read()
    {
        $data = Akun::select('id', 'nama', 'email', 'kel', 'alamat', 'nohp', 'aktif', 'foto')
            ->with('pengguna.grup')
            ->get();
        return Datatables::of($data)->addIndexColumn()
            ->editColumn('aktif', function ($row) {
                return ($row->aktif) ? "Aktif" : "Tidak Aktif";
            })
            ->editColumn('foto', function ($row) {
                $ret = ($row->foto == "images/user-avatar.png") ? $row->foto : asset('storage') . '/' . $row->foto;
                return '<img src="' . $ret . '" alt="Profile" height="100px" class="rounded-circle">';
            })
            ->addColumn('cek', function ($row) {
                return "<input type='checkbox' class='cekbaris' value='" . $row->id . "'>";
            })
            ->addColumn('grup', function ($row) {
                $ret = "";
                if (isset($row->pengguna))
                    foreach ($row->pengguna as $dp) {
                        if ($dp->aktif == "0")
                            $ret = '<span class="badge bg-danger"><i class="bi bi-exclamation-triangle me-1"></i> ' . $dp->grup->grup . '</span>';
                        else
                            $ret .= '<span class="badge bg-success"><i class="bi bi-check-circle me-1"></i> ' . $dp->grup->grup . '</span>';
                        $ret .= "<br>";
                    }
                return $ret;
            })
            ->addColumn('no', function ($row) {
                return "";
            })
            ->addColumn('action', function ($row) {
                $btn = '<div class="btn-group me-1 mb-1">
                            <div class="dropdown">
                                <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>
                                <div class="dropdown-menu dropdown-menu-end" style="">
                                    <a class="dropdown-item btn-ganti" data-id="' . $row->id . '" href="#"><i class="bi bi-pencil-square"></i> Ganti</a>
                                    <a class="dropdown-item btn-ganti" data-id="' . $row->id . '" href="#"><i class="bi bi-pencil-square"></i> Atur Grup</a>
                                    <a class="dropdown-item btn-hapus" data-id="' . $row->id . '" href="#"><i class="bi bi-trash"></i> Hapus</a>
                                </div>
                            </div>
                        </div>';
                // $btn = '<button type="button" class="btn btn-sm btn-success btn-ganti" data-id="' . $row->id . '"><i class="bi bi-pencil-square"></i></button>
                //         <button type="button" class="btn btn-sm btn-danger btn-hapus" data-id="' . $row->id . '"><i class="bi bi-trash3"></i></button>';
                return $btn;
            })


            ->rawColumns(['no', 'cek', 'aktif', 'foto', 'grup', 'action'])
            ->make(true);
    }

    public function create(Request $request)
    {
        $retval = array("status" => false, "insert" => true, "messages" => ["gagal, hubungi admin"]);
        //cek apakah id ada atau tidak, kalau ada maka status edit dan jika tidak ada maka insert
        $insert = true;
        if ($request['id'])
            $insert = false;

        $datapost = $this->validate($request, [
            'nama' => 'required|min:3',
            'alamat' => 'required|min:3',
            //'email' => 'required|email:dns|unique:akuns',
            'email' => 'required|email:dns',
            'kel' => 'required',
            'tempatlahir' => 'required|min:3',
            'tanggallahir' => 'required',
            'nohp' => 'required|min:9',
            'aktif' => 'required',
        ]);

        if ($request['password']) {
            $this->validate($request, [
                'password' => 'required|alpha_num|min:8',
            ]);
            $datapost['password'] = Hash::make($request['password']);
        }
        $retval['insert'] = $insert;
        try {
            DB::beginTransaction();
            //untuk cari
            if (!$insert)
                $cari = Akun::where("id", $request['id'])->first();

            if ($request->hasFile('foto')) {
                $this->validate($request, [
                    'foto' => ['image', 'mimes:jpeg,png,jpg,gif,svg', 'max:1024'],
                ]);
                $file = $request->file('foto');
                $ext = $file->getClientOriginalExtension();
                $det =   [
                    "size" => ceil($file->getSize() / 1000),
                    "mime" => $file->getMimeType()
                ];
                $destinationPath = 'uploads/foto-profil';

                $datapost['foto'] = $file->store($destinationPath);
                if (!$insert) {
                    AkunController::hapusfile($cari->foto);
                }
            }

            if ($insert) {
                $id = Akun::create($datapost)->id;
            } else {
                $cari->update($datapost);
            }
            $retval["status"] = true;
            $retval["messages"] = ["Simpan data berhasil dilakukan"];
            DB::commit();
        } catch (\Throwable $e) {
            $retval['messages'] = [$e->getMessage()];
            DB::rollBack();
        }
        return response()->json($retval);
    }

    public function update(Request $request)
    {
        $retval = array("status" => false, "messages" => ["maaf, data tidak ditemukan"], "data" => []);
        try {
            $data = Akun::where('id', $request['id'])->first();
            if ($data)
                $retval = array("status" => true, "messages" => ["data ditemukan"], "data" => $data);
        } catch (\Throwable $e) {
            $retval['messages'] = [$e->getMessage()];
        }
        return response()->json($retval);
    }

    public function delete(Request $request)
    {
        $retval = array("status" => false, "messages" => ["maaf, gagal dilakukan"]);
        try {
            $data = Akun::whereIn('id', $request['id'])->first();
            if ($data) {
                AkunController::hapusfile($data->foto);
                $data->delete();
                $retval = array("status" => true, "messages" => ["data berhasil dihapus"]);
            }
        } catch (\Throwable $e) {
            $retval['messages'] = [$e->getMessage()];
        }
        return response()->json($retval);
    }

    public function hapusfile($file = null)
    {
        $retval = array("status" => false, "messages" => ["maaf, gagal dilakukan"]);
        if ($file !== 'images/user-avatar.png') {
            if (Storage::disk('public')->exists($file)) {
                Storage::delete($file);
                $retval = array("status" => true, "messages" => ["hapus file berhasil dilakukan"]);
            } else {
                $retval['messages'] = ["file tidak ditemukan"];
            }
        }
        return $retval;
    }
}
