<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Grup;
use App\Models\Pengguna;
use DataTables;

class GrupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Pengguna::isAdmin();
        //return view('dashboard.grup');
    }

    public function read()
    {
        //$no = 1;
        $data = Grup::select('grups.id', 'grups.grup', 'grups.aktif')->with("pengguna.akun")->get();
        return Datatables::of($data)->addIndexColumn()
            ->editColumn('aktif', function ($row) {
                return ($row->aktif) ? "Aktif" : "Tidak Aktif";
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
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
                $id = Grup::create($datapost)->id;
            else {
                $cari = Grup::where("id", $request['id'])->first();
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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $retval = array("status" => false, "messages" => ["maaf, data tidak ditemukan"], "data" => []);
        try {
            $data = Grup::where('id', $request['id'])->first();
            if ($data)
                $retval = array("status" => true, "messages" => ["data ditemukan"], "data" => $data);
        } catch (\Throwable $e) {
            $retval['messages'] = [$e->getMessage()];
        }
        return response()->json($retval);
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        $retval = array("status" => false, "messages" => ["maaf, gagal dilakukan"]);
        try {
            DB::beginTransaction();
            $data = Grup::where('id', $request['id'])->first();
            if ($data) {
                $data->delete();
                $retval = array("status" => true, "messages" => ["data berhasil dihapus"]);
                DB::commit();
            }
        } catch (\Throwable $e) {
            $retval['messages'] = [$e->getMessage()];
            DB::rollBack();
        }
        return response()->json($retval);
    }
}
