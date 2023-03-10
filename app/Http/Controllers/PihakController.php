<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use DataTables;
use App\Models\Pihak;
use App\Models\Kabupaten;
use App\Models\JenisPihak;


class PihakController extends Controller
{
    public function index()
    {
        $jenispihak = JenisPihak::select('id', 'jenis as text')->where("aktif", "1")->get();
        return view('dashboard.pihak', ['jenispihak' => $jenispihak]);
    }

    public function read()
    {
        DB::statement(DB::raw('set @rownum=0'));
        $data = Pihak::select(DB::raw('@rownum := @rownum + 1 AS no'), 'id', 'pihak', 'alamat', 'jenis_pihak_id', 'kabupaten_id')
            ->with(["kabupaten.provinsi", "jenisPihak"]);

        return Datatables::of($data)->addIndexColumn()
            ->addColumn('no', function ($row) {
                return $row->no;
            })
            ->addColumn('jenis', function ($row) {
                return isset($row->jenisPihak) ? $row->jenisPihak->jenis : "";
            })
            ->addColumn('kabupaten', function ($row) {
                return isset($row->kabupaten) ? $row->kabupaten->kabupaten : "";
            })
            ->addColumn('provinsi', function ($row) {
                return isset($row->kabupaten->provinsi) ? $row->kabupaten->provinsi->provinsi : "";
            })
            ->addColumn('cek', function ($row) {
                return "<input type='checkbox' class='cekbaris' value='" . $row->id . "'>";
            })
            ->addColumn('action', function ($row) {
                $btn = '<button type="button" class="btn btn-sm btn-success btn-ganti" data-id="' . $row->id . '"><i class="bi bi-pencil-square"></i></button>
                        <button type="button" class="btn btn-sm btn-danger btn-hapus" data-id="' . $row->id . '"><i class="bi bi-trash3"></i></button>';
                return $btn;
            })
            ->rawColumns(['no', 'action', 'kabupaten', 'cek'])
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
            'pihak' => 'required|min:3',
            'jenis_pihak_id' => 'required',
            'alamat' => 'required|min:3',
            'kabupaten_id' => 'required',
        ]);
        $datapost['akun_id'] = auth()->user()->id;
        //dd($datapost);
        $retval['insert'] = $insert;
        try {
            DB::beginTransaction();
            if ($insert)
                $id = Pihak::create($datapost)->id;
            else {
                $cari = Pihak::where("id", $request['id'])->first();
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
            $data = Pihak::where('id', $request['id'])
                ->with(["kabupaten.provinsi"])
                ->first();
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
            Pihak::whereIn('id', $ids)->delete();
            $retval = array("status" => true, "messages" => ["data berhasil dihapus"]);
        } catch (\Throwable $e) {
            $retval['messages'] = [$e->getMessage()];
        }
        return response()->json($retval);
    }

    public function search(Request $request)
    {
        $dt = Pihak::with(["kabupaten.provinsi"])
            ->where('pihak', 'like', '%' . $request['cari'] . '%')
            ->orWhere('alamat', 'like', '%' . $request['cari'] . '%')
            ->get();
        return response()->json($dt);
    }
}
