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
            ->addColumn('no', function ($row) {
                return "";
            })
            ->addColumn('no', function ($row) {
                return "";
            })
            ->addColumn('action', function ($row) {
                $btn = '<button type="button" class="btn btn-sm btn-success btn-ganti" data-id="' . $row->id . '"><i class="bi bi-pencil-square"></i></button>
                        <button type="button" class="btn btn-sm btn-danger btn-hapus" data-id="' . $row->id . '"><i class="bi bi-trash3"></i></button>';
                return $btn;
            })
            ->rawColumns(['no', 'aktif', 'action'])
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
            $data = Akun::where('id', $request['id'])->first();
            if ($data) {
                $data->delete();
                $retval = array("status" => true, "messages" => ["data berhasil dihapus"]);
            }
        } catch (\Throwable $e) {
            $retval['messages'] = [$e->getMessage()];
        }
        return response()->json($retval);
    }
}
