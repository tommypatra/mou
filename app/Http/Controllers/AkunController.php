<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
        $data = Akun::select('id', 'nama', 'email', 'kel', 'alamat', 'nohp', 'aktif')
            ->with('pengguna.grup')
            ->get();
        return Datatables::of($data)->addIndexColumn()
            ->editColumn('aktif', function ($row) {
                return ($row->aktif) ? "Aktif" : "Tidak Aktif";
            })
            ->addColumn('cek', function ($row) {
                return "<input type='checkbox' class='cekbaris' value='" . $row->id . "'>";
            })
            ->addColumn('grup', function ($row) {
                $ret = "";
                if (isset($row->pengguna))
                    foreach ($row->pengguna as $dp) {
                        if ($dp->aktif == "0")
                            $ret = '<span class="badge bg-success"><i class="bi bi-exclamation-triangle me-1"></i> ' . $dp->grup->grup . '</span>';
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
                $btn = '<button type="button" class="btn btn-sm btn-success btn-ganti" data-id="' . $row->id . '"><i class="bi bi-pencil-square"></i></button>
                        <button type="button" class="btn btn-sm btn-danger btn-hapus" data-id="' . $row->id . '"><i class="bi bi-trash3"></i></button>';
                return $btn;
            })
            ->rawColumns(['no', 'cek', 'aktif', 'grup', 'action'])
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
            'grup' => 'required|min:3',
            'aktif' => 'required',
        ]);
        $datapost['akun_id'] = auth()->user()->id;
        //dd($datapost);
        $retval['insert'] = $insert;
        try {
            DB::beginTransaction();
            if ($insert)
                $id = Akun::create($datapost)->id;
            else {
                $cari = Akun::where("id", $request['id'])->first();
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
            $ids = $request['id'];
            Akun::whereIn('id', $ids)->delete();
            $retval = array("status" => true, "messages" => ["data berhasil dihapus"]);
        } catch (\Throwable $e) {
            $retval['messages'] = [$e->getMessage()];
        }
        return response()->json($retval);
    }
}
