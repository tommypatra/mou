<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use DataTables;
use App\Models\Grup;
use App\Models\Menu;
use App\Models\Akses;
use App\Models\Modul;


class MenuController extends Controller
{
    public function index()
    {
        $dtgrp = Grup::select('id', 'grup as text')->where('aktif', '1')->get();
        $dtmodul = Modul::select('id', DB::raw('CONCAT(menu," (",link,")") AS text'))
            ->where('aktif', '1')
            ->orderBy('menu', 'ASC')
            ->get();
        return view('dashboard.menu', ['dtgrp' => $dtgrp, 'dtmodul' => $dtmodul]);
    }

    public function buildMenu($array, &$menu = "")
    {
        $menu .= '<ul class="no-list">';
        foreach ($array as $item) {
            $menu .= '<li class="no-list">';
            $menu .= $item['modul']['icon'] . ' ' . $item['modul']['menu'] . '&nbsp;';
            if ($item['modul']['link'] != '#')
                $menu .= '<a href="' . $item['modul']['link'] . '" target="_blank"><i class="bi bi-arrow-up-right-square"></i></a> ';

            $menu .= '<a href="#" class="btn-hapus" data-id="' . $item['id'] . '"><i class="bi bi-trash3"></i></a>';
            if (isset($item['children'])) {
                MenuController::buildMenu($item['children'], $menu);
            }
            $menu .= '</li>';
        }
        $menu .= '</ul>';
        return $menu;
    }

    public function read(Request $request)
    {
        DB::statement(DB::raw('set @rownum=0'));
        $data = Grup::select(DB::raw('@rownum := @rownum + 1 AS no'), 'id', 'grup', 'aktif')
            ->with([
                'menu' => function ($menu) {
                    $menu->select('id', 'modul_id', 'grup_id', 'urut', 'menu_id as parent_id');
                    $menu->orderBy('urut', 'ASC');
                    $menu->orderBy('menu_id', 'ASC');
                    $menu->with(['modul']);
                },
            ]);

        return Datatables::of($data)->addIndexColumn()
            ->editColumn('aktif', function ($row) {
                return ($row->aktif) ? "Aktif" : "Tidak Aktif";
            })
            ->addColumn('dtmenu', function ($row) {
                $ret = "";
                $row = $row->toArray();
                if (isset($row['menu'])) {
                    $prepareMenu = \MyApp::buildTree($row['menu']);
                    $ret = MenuController::buildMenu($prepareMenu);
                }
                return $ret;
            })
            ->addColumn('no', function ($row) {
                return $row->no;
            })
            ->rawColumns(['no', 'aktif', 'dtmenu'])
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
            'grup_id' => 'required',
            'modul_id' => 'required',
            'urut' => 'required',
            'aktif' => 'required',
        ]);
        if ($request['menu_id'])
            $datapost['menu_id'] = $request['menu_id'];
        //dd($datapost);
        $retval['insert'] = $insert;
        try {
            DB::beginTransaction();
            if ($insert) {
                $id = Menu::create($datapost)->id;

                $datapost = [
                    'grup_id' => $datapost['grup_id'],
                    'menu_id' => $id,
                    'c' => '1',
                    'r' => '0',
                    'u' => '0',
                    'd' => '0',
                    's' => '0',
                ];
                Akses::create($datapost);
            } else {
                $cari = Menu::where("id", $request['id'])->first();
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
            $data = Menu::where('id', $request['id'])->first();
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
            Menu::whereIn('id', $ids)->delete();
            $retval = array("status" => true, "messages" => ["data berhasil dihapus"]);
        } catch (\Throwable $e) {
            $retval['messages'] = [$e->getMessage()];
        }
        return response()->json($retval);
    }

    public function search(Request $request)
    {
        $retval = array("status" => false, "messages" => ["maaf, tidak ditemukan"], "data" => []);
        try {
            $id = $request['id'];

            // $data = Menu::with(["modul"])
            //     ->select('id', 'modul_id', 'grup_id', 'urut', 'menu_id as parent_id')
            //     ->where('grup_id', $id)
            //     ->get();

            $data = DB::table('menus as m')
                ->select('m.id', 'm.menu_id as parent_id', 'd.menu as text')
                ->leftJoin('moduls as d', 'm.modul_id', '=', 'd.id')
                ->where('m.grup_id', $id)
                ->where('d.link', '#')
                ->get();

            $data = array_map(function ($item) {
                return (array)$item;
            }, $data->toArray());

            // $treedata = \MyApp::buildTree($data->toArray(), null, "id",  "parent_id", "children");
            $retval = array("status" => true, "messages" => ["data ditemukan"], "data" => $data);
        } catch (\Throwable $e) {
            $retval['messages'] = [$e->getMessage()];
        }
        return response()->json($retval);
    }
}
