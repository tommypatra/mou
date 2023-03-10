<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use DataTables;
use App\Models\Modul;


class ModulController extends Controller
{
    public function index()
    {
        return view('dashboard.modul');
    }

    public function read()
    {
        DB::statement(DB::raw('set @rownum=0'));
        $data = Modul::select(DB::raw('@rownum := @rownum + 1 AS no'), 'id', 'controller', 'menu', 'link', 'icon', 'deskripsi', 'aktif');

        return Datatables::of($data)->addIndexColumn()
            ->editColumn('aktif', function ($row) {
                return ($row->aktif) ? "Aktif" : "Tidak Aktif";
            })
            ->addColumn('no', function ($row) {
                return $row->no;
            })
            ->addColumn('cek', function ($row) {
                return "<input type='checkbox' class='cekbaris' value='" . $row->id . "'>";
            })
            ->addColumn('action', function ($row) {
                $btn = '<button type="button" class="btn btn-sm btn-success btn-ganti" data-id="' . $row->id . '"><i class="bi bi-pencil-square"></i></button>
                        <button type="button" class="btn btn-sm btn-danger btn-hapus" data-id="' . $row->id . '"><i class="bi bi-trash3"></i></button>';
                return $btn;
            })
            ->rawColumns(['no', 'aktif', 'action', 'cek'])
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
            'menu' => 'required|min:3',
            'controller' => 'required',
            'link' => 'required',
            'aktif' => 'required',
        ]);
        $datapost['icon'] = $request['icon'];
        $datapost['deskripsi'] = $request['deskripsi'];
        //dd($datapost);
        $retval['insert'] = $insert;
        try {
            DB::beginTransaction();
            if ($insert)
                $id = Modul::create($datapost)->id;
            else {
                $cari = Modul::where("id", $request['id'])->first();
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
            $data = Modul::where('id', $request['id'])->first();
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
            Modul::whereIn('id', $ids)->delete();
            $retval = array("status" => true, "messages" => ["data berhasil dihapus"]);
        } catch (\Throwable $e) {
            $retval['messages'] = [$e->getMessage()];
        }
        return response()->json($retval);
    }

    public function search(Request $request)
    {
        $dt = Modul::where('modul', 'like', '%' . $request['cari'] . '%')->get();
        return response()->json($dt);
    }
}
