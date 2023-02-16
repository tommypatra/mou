<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\Akun;
use App\Models\Pengguna;
use App\Models\Bagian;
use App\Models\Grup;
use App\Models\BagianAkun;
use DataTables;


class AkunController extends Controller
{
    public function index()
    {
        return view('dashboard.akun');
    }

    public function read(Request $request)
    {
        DB::statement(DB::raw('set @rownum=0'));
        $data = Akun::select(DB::raw('@rownum := @rownum + 1 AS no'), 'id', 'nama', 'email', 'kel', 'alamat', 'nohp', 'aktif', 'foto')
            ->with(['pengguna.grup', 'bagianakun.bagian']);

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
            ->addColumn('bagian', function ($row) {
                $ret = "";
                if (isset($row->bagianakun))
                    foreach ($row->bagianakun as $dp) {
                        if ($dp->aktif == "0")
                            $ret .= '<span class="badge bg-danger"><i class="bi bi-exclamation-triangle me-1"></i> ' . $dp->bagian->bagian . '</span>';
                        else
                            $ret .= '<span class="badge bg-success"><i class="bi bi-check-circle me-1"></i> ' . $dp->bagian->bagian . '</span>';
                        $ret .= " ";
                    }
                return $ret;
            })
            ->addColumn('grup', function ($row) {
                $ret = "";
                if (isset($row->pengguna))
                    foreach ($row->pengguna as $dp) {
                        if ($dp->aktif == "0")
                            $ret .= '<span class="badge bg-danger"><i class="bi bi-exclamation-triangle me-1"></i> ' . $dp->grup->grup . '</span>';
                        else
                            $ret .= '<span class="badge bg-success"><i class="bi bi-check-circle me-1"></i> ' . $dp->grup->grup . '</span>';
                        $ret .= " ";
                    }
                return $ret;
            })
            ->addColumn('no', function ($row) {
                return $row->no;
            })
            ->addColumn('action', function ($row) {
                $btn = '<div class="btn-group me-1 mb-1">
                            <div class="dropdown">
                                <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>
                                <div class="dropdown-menu dropdown-menu-end" style="">
                                    <a class="dropdown-item btn-ganti" data-id="' . $row->id . '" href="#"><i class="bi bi-pencil-square"></i> Ganti</a>
                                    <a class="dropdown-item btn-atur-bagian" data-id="' . $row->id . '" href="#"><i class="bi bi-view-list"></i> Atur Bagian</a>
                                    <a class="dropdown-item btn-atur-grup" data-id="' . $row->id . '" href="#"><i class="bi bi-window-stack"></i> Atur Grup</a>
                                    <a class="dropdown-item btn-hapus" data-id="' . $row->id . '" href="#"><i class="bi bi-trash"></i> Hapus</a>
                                </div>
                            </div>
                        </div>';
                // $btn = '<button type="button" class="btn btn-sm btn-success btn-ganti" data-id="' . $row->id . '"><i class="bi bi-pencil-square"></i></button>
                //         <button type="button" class="btn btn-sm btn-danger btn-hapus" data-id="' . $row->id . '"><i class="bi bi-trash3"></i></button>';
                return $btn;
            })
            ->rawColumns(['no', 'bagian', 'cek', 'aktif', 'foto', 'grup', 'action'])
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
            'email' => 'required|email',
            //'email' => 'required|email:dns',
            'kel' => 'required',
            'tempatlahir' => 'required|min:3',
            'tanggallahir' => 'required',
            'nohp' => 'required|min:9',
            'aktif' => 'required',
        ], [], [
            'kel' => 'jenis kelamin',
            'tempatlahir' => 'tempat lahir',
            'tanggallahir' => 'tanggal lahir',
            'nohp' => 'nomor handphone',
        ]);

        if ($request['password']) {
            $this->validate($request, [
                'password' => 'required|alpha_num|min:8',
            ]);
            $datapost['password'] = Hash::make($request['password']);
        }
        $retval['insert'] = $insert;

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

        try {
            DB::beginTransaction();
            //untuk cari
            if (!$insert)
                $cari = Akun::where("id", $request['id'])->first();

            if ($insert) {
                $id = Akun::create($datapost)->id;
                //simpan sebagai pengguna
                $pengguna = Pengguna::create([
                    'akun_id' => $id,
                    'grup_id' => 2,
                    'token' => \MyApp::generateToken(),
                    'aktif' => "1",
                ]);
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

    public function grupakun(Request $request)
    {
        $id = $request['id'];
        $data = Grup::with(['pengguna' => function ($pengguna) use ($id) {
            $pengguna->where('akun_id', $id);
        }])
            ->where("aktif", "1")
            ->get();
        $html = '';
        foreach ($data as $i => $dp) {
            $cek = isset($dp['pengguna'][0]['id']) ? "checked" : "";
            $stsaktif = ($cek && $dp['pengguna'][0]['aktif'] == "1") ? "checked" : "";
            $penggunaid = ($cek) ? $dp['pengguna'][0]['id'] : null;

            $html .= '<div class="row">';
            $html .= '<div class="col-2">' . ($i + 1) . '</div>';
            $html .= '<div class="col-8">';
            $html .= '<div class="form-check">
                        <input class="form-check-input cekgrup" data-penggunaid="' . $penggunaid . '" data-id="' . $dp['id'] . '"  type="checkbox" id="grup' . $i . '" ' . $cek . '>
                            <label class="form-check-label" for="grup' . $i . '">
                                ' . $dp['grup'] . '
                            </label>
                    </div>';
            $html .= '</div>';
            $html .= '<div class="col-2">
                        <div class="form-check form-switch">
                            <input class="form-check-input cekgrupstatus" data-id="' . $penggunaid . '"  type="checkbox" id="aktif' . $i . '" ' . $stsaktif . '>
                        </div>
                    </div>';
            $html .= '</div>';
        }
        $retval = array('status' => true, 'pesan' => 'ditemukan', 'html' => $html);
        return response()->json($retval);
    }


    public function bagianakun(Request $request)
    {
        $id = $request['id'];
        $data = Bagian::with(['bagianakun' => function ($pengguna) use ($id) {
            $pengguna->where('akun_id', $id);
        }])
            ->where("aktif", "1")
            ->get();
        $html = '';
        foreach ($data as $i => $dp) {
            //echo $dp;
            $cek = isset($dp['bagianakun'][0]['id']) ? "checked" : "";
            $stsaktif = ($cek && $dp['bagianakun'][0]['aktif'] == "1") ? "checked" : "";
            $bagianakunid = ($cek) ? $dp['bagianakun'][0]['id'] : null;

            $html .= '<div class="row">';
            $html .= '<div class="col-2">' . ($i + 1) . '</div>';
            $html .= '<div class="col-8">';
            $html .= '<div class="form-check">
                        <input class="form-check-input cekbagian" data-bagianakunid="' . $bagianakunid . '"  data-id="' . $dp['id'] . '"  type="checkbox" id="bagian' . $i . '" ' . $cek . '>
                            <label class="form-check-label" for="bagian' . $i . '">
                                ' . $dp['bagian'] . '
                            </label>
                    </div>';
            $html .= '</div>';
            $html .= '<div class="col-2">
                        <div class="form-check form-switch">
                            <input class="form-check-input cekbagianstatus" data-id="' . $bagianakunid . '" type="checkbox" id="aktif' . $i . '" ' . $stsaktif . '>
                        </div>
                    </div>';
            $html .= '</div>';
        }
        $retval = array('status' => true, 'pesan' => 'ditemukan', 'html' => $html);
        return response()->json($retval);
    }

    public function pengaturan(Request $request)
    {
        $retval = array("status" => false, "messages" => ["maaf, gagal dilakukan"]);
        try {
            $id = $request['id'];
            $akunid = $request['akunid'];
            switch ($request['jenis']) {
                case "bagian":
                    if ($request['cek'] == 'true') {
                        BagianAkun::create([
                            'akun_id' => $akunid,
                            'bagian_id' => $request['bagianid'],
                            'token' => \MyApp::generateToken(),
                            'aktif' => "1",
                        ]);
                        $retval = array("status" => true, "messages" => ["data berhasil tersimpan"]);
                    } else {
                        $data = BagianAkun::where('id', $request['bagianakunid'])->first();
                        if ($data) {
                            $data->delete();
                            $retval = array("status" => true, "messages" => ["data berhasil terhapus"]);
                        }
                    }
                    break;
                case "bagianstatus":
                    $data = BagianAkun::where('id', $request['bagianakunid'])->first();
                    if ($data) {
                        $datapost['aktif'] = ($request['cek'] == "true") ? "1" : "0";
                        $data->update($datapost);
                        $retval = array("status" => true, "messages" => ["data berhasil tersimpan"]);
                    }
                    break;
                case "grup":
                    if ($request['cek'] == 'true') {
                        Pengguna::create([
                            'akun_id' => $akunid,
                            'grup_id' => $request['grupid'],
                            'token' => \MyApp::generateToken(),
                            'aktif' => "1",
                        ]);
                        $retval = array("status" => true, "messages" => ["data berhasil tersimpan"]);
                    } else {
                        $data = Pengguna::where('id', $request['penggunaid'])->first();
                        if ($data) {
                            $data->delete();
                            $retval = array("status" => true, "messages" => ["data berhasil terhapus"]);
                        }
                    }
                    break;
                case "grupstatus":
                    $data = Pengguna::where('id', $request['penggunaid'])->first();
                    if ($data) {
                        $datapost['aktif'] = ($request['cek'] == "true") ? "1" : "0";
                        $data->update($datapost);
                        $retval = array("status" => true, "messages" => ["data berhasil tersimpan"]);
                    }
                    break;
            }
        } catch (\Throwable $e) {
            $retval['messages'] = [$e->getMessage()];
        }
        return response()->json($retval);
    }

    public function search(Request $request)
    {
        $retval = Akun::with(['pengguna.grup', 'bagianakun.bagian'])
            ->where('nama', 'like', '%' . $request['cari'] . '%')
            ->orwhere('email', 'like', '%' . $request['cari'] . '%')
            ->orwhere('tempatlahir', 'like', '%' . $request['cari'] . '%')
            ->orwhere('alamat', 'like', '%' . $request['cari'] . '%')
            ->orwhere('nohp', 'like', '%' . $request['cari'] . '%')
            ->get();
        return response()->json($retval);
    }
}
