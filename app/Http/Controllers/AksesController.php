<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Grup;
use App\Models\Menu;
use App\Models\Akses;
use DataTables;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx\Rels;

class AksesController extends Controller
{

    public function index(Request $request)
    {
        return view('dashboard.akses', ["hakakses" => $request->hakakses]);
    }

    public function read()
    {
        DB::statement(DB::raw('set @rownum=0'));
        $data = Menu::select(DB::raw('@rownum := @rownum + 1 AS no'), 'id', 'urut', 'grup_id', 'modul_id')
            ->with(["grup", "modul", "akses"]);
        // ->orderBy('grup_id', 'asc')
        // ->orderBy('modul_id', 'asc');

        return Datatables::of($data)->addIndexColumn()
            ->addColumn('namagrup', function ($row) {
                return $row->grup->grup;
            })
            ->addColumn('menu', function ($row) {
                return $row->modul->menu;
            })
            ->addColumn('c', function ($row) {
                $ret = '';
                if (isset($row->akses)) {
                    $cek = ($row->akses->c == "1") ? "checked" : "";
                    $ret = "<input type='checkbox' class='updakses' data-akses='c' data-menu_id='" . $row->id . "' value='" . $row->akses->id . "' " . $cek . ">";
                }
                return $ret;
            })
            ->addColumn('r', function ($row) {
                $ret = '';
                if (isset($row->akses)) {
                    $cek = ($row->akses->r == "1") ? "checked" : "";
                    $ret = "<input type='checkbox' class='updakses' data-akses='r' data-menu_id='" . $row->id . "' value='" . $row->akses->id . "' " . $cek . ">";
                }
                return $ret;
            })
            ->addColumn('u', function ($row) {
                $ret = '';
                if (isset($row->akses)) {
                    $cek = ($row->akses->u == "1") ? "checked" : "";
                    $ret = "<input type='checkbox' class='updakses' data-akses='u' data-menu_id='" . $row->id . "' value='" . $row->akses->id . "' " . $cek . ">";
                }
                return $ret;
            })
            ->addColumn('d', function ($row) {
                $ret = '';
                if (isset($row->akses)) {
                    $cek = ($row->akses->d == "1") ? "checked" : "";
                    $ret = "<input type='checkbox' class='updakses' data-akses='d' data-menu_id='" . $row->id . "' value='" . $row->akses->id . "' " . $cek . ">";
                }
                return $ret;
            })
            ->addColumn('s', function ($row) {
                $ret = '';
                if (isset($row->akses)) {
                    $cek = ($row->akses->s == "1") ? "checked" : "";
                    $ret = "<input type='checkbox' class='updakses' data-akses='s' data-menu_id='" . $row->id . "' value='" . $row->akses->id . "' " . $cek . ">";
                }
                return $ret;
            })
            ->addColumn('no', function ($row) {
                return $row->no;
            })
            ->addColumn('cek', function ($row) {
                return "<input type='checkbox' class='cekbaris' value='" . $row->id . "'>";
            })
            ->addColumn('action', function ($row) {
                $btn = '';
                if (!isset($row->akses))
                    $btn = '<button type="button" class="btn btn-sm btn-primary btn-tambah" data-menucaption="' . $row->modul->menu . '" data-grupcaption="' . $row->grup->grup . '" data-id="' . $row->id . '"><i class="bi bi-plus-circle"></i></button>';
                else
                    $btn = '<button type="button" class="btn btn-sm btn-danger btn-hapus" data-id="' . $row->akses->id . '"><i class="bi bi-trash3"></i></button>';
                return $btn;
            })
            ->rawColumns(['no', 'c', 'r', 'u', 'd', 's', 'aktif', 'action', 'cek'])
            ->make(true);
    }

    public function create(Request $request)
    {
        $retval = array("status" => false, "insert" => true, "messages" => ["gagal, hubungi admin"]);
        //cek apakah id ada atau tidak, kalau ada maka status edit dan jika tidak ada maka insert
        $insert = true;
        if ($request['id'])
            $insert = false;

        $this->validate(
            $request,
            ['menu_id' => 'required'],
            [],
            ['menu_id' => "menu web"]
        );

        $retval['insert'] = $insert;
        try {
            DB::beginTransaction();
            if ($insert) {
                $datapost = [
                    'menu_id' => $request['menu_id'],
                    'c' => isset($request['create']) ? "1" : "0",
                    'r' => isset($request['read']) ? "1" : "0",
                    'u' => isset($request['update']) ? "1" : "0",
                    'd' => isset($request['delete']) ? "1" : "0",
                    's' => isset($request['special']) ? "1" : "0",
                ];
                $id = Akses::create($datapost)->id;
            } else {
                $datapost = array(
                    $request['akses'] => ($request['cek'] == 'true') ? "1" : "0",
                );
                $cari = Akses::where("id", $request['id'])->first();
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
            $data = Akses::where('id', $request['id'])->first();
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
            Akses::whereIn('id', $ids)->delete();
            $retval = array("status" => true, "messages" => ["data berhasil dihapus"]);
        } catch (\Throwable $e) {
            $retval['messages'] = [$e->getMessage()];
        }
        return response()->json($retval);
    }

    public function search(Request $request)
    {
        $retval = Menu::with(["grup", "modul", "akses"])
            ->where('grup_id', 'like', '%' . $request['cari'] . '%')
            ->orwhere('modul_id', 'like', '%' . $request['cari'] . '%')
            ->get();
        return response()->json($retval);
    }
}
