<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

use DataTables;
use App\Models\Mou;
use App\Models\Jenis;
use App\Models\Bagian;
use App\Models\Kategori;
use App\Models\Pengguna;
use App\Models\Pihak;
use App\Models\ParaPihak;
use App\Models\File;
use Facade\FlareClient\Http\Response;

class KerjaSamaController extends Controller
{

    public function index()
    {
        // $ids = [];
        // foreach (session()->get("bagians") as $bagian) {
        //     $ids[] = $bagian['id'];
        // }
        // $dtjenis = Jenis::select('id', 'jenis as text')->where('aktif', '1')->get();
        // $dtkategori = Kategori::select('id', 'kategori as text')->where('aktif', '1')->get();
        // $dtbagian = Bagian::select('id', 'bagian as text')->whereIn('id', $ids)->get();
        // $dtpihak = Pihak::select('id', 'pihak as text')->get();

        // return view('dashboard.kerjasama', ['dtbagian' => $dtbagian, 'dtjenis' => $dtjenis, 'dtpihak' => $dtpihak, 'dtkategori' => $dtkategori]);
        return view('dashboard.kerjasama');
    }

    public function loadResources()
    {
        $retval = array("status" => true, "messages" => ["data ditemukan"]);

        $ids = [];
        foreach (session()->get("bagians") as $bagian) {
            $ids[] = $bagian['id'];
        }
        $retval['dtjenis'] = Jenis::select('id', 'jenis as text')->where('aktif', '1')->get();
        $retval['dtkategori'] = Kategori::select('id', 'kategori as text')->where('aktif', '1')->get();
        $retval['dtbagian'] = Bagian::select('id', 'bagian as text')->whereIn('id', $ids)->get();
        $retval['dtpihak'] = Pihak::select('id', 'pihak as text')->get();

        return response()->json($retval);
    }

    public function read()
    {
        //DB::statement(DB::raw('set @rownum=0'));
        $data = Mou::select(
            //DB::raw('@rownum := @rownum + 1 AS no'),
            'id',
            'tentang',
            'ruang_lingkup',
            //'no_surat_internal',
            //'no_surat_eksternal',
            //'tgl',
            'tgl_berlaku',
            'tgl_berakhir',
            'pengguna_id',
            'bagian_id',
            'jenis_id',
            'kategori_id',
        )
            // ->with(["paraPihak.pihak.kabupaten.provinsi"])
            ->with(["paraPihak.pihak"])
            ->with(["bagian"])
            ->with(["jenis"])
            ->with(["file"])
            ->with(["kategori"])
            ->with(["pengguna.akun"]);
        //->orderBy('tgl_berlaku', 'DESC');
        return Datatables::of($data)->addIndexColumn()
            ->addColumn('no', function ($row) {
                return '';
            })
            ->editColumn('provinsi', function ($row) {
                return (isset($row->pihak->kabupaten->provinsi)) ? $row->pihak->kabupaten->provinsi->provinsi : "";
            })
            ->editColumn('kabupaten', function ($row) {
                return (isset($row->pihak->kabupaten)) ? $row->pihak->kabupaten->kabupaten : "";
            })
            ->addColumn('bagian', function ($row) {
                return isset($row->bagian) ? $row->bagian->bagian : "";
            })
            ->addColumn('jenis', function ($row) {
                return isset($row->jenis) ? $row->jenis->jenis : "";
            })
            ->addColumn('file_det', function ($row) {
                $retval = '<form id="fupload' . $row->id . '"><input class="form-control fileupload" data-mouid="' . $row->id . '" type="file" nama="fileupload"></form>';
                if (count($row->file) > 0) {
                    $retval .= '<ul>';
                    foreach ($row->file as $dt) {
                        $file = json_decode($dt->detail);
                        $url = asset('storage') . '/' . $dt->source;
                        $retval .= '<li>';
                        $retval .= '<a href="' . $url . '" target="_blank">' . $file->originalName . '</a> ';
                        $retval .= '<button type="button" class="btn btn-sm btn-hapus-upload" data-id="' . $dt->id . '"><i class="bi bi-trash3"></i></button>';
                        $retval .= '</li>';
                    }
                    $retval .= '</ul>';
                }
                return $retval;
            })
            ->addColumn('pihak_det', function ($row) {
                $retval = "";
                //dd($row->parapihak);
                if (count($row->paraPihak) > 0) {
                    $retval .= '<ul>';
                    foreach ($row->paraPihak as $dt) {
                        $retval .= '<li>';
                        $retval .= $dt->pihak->pihak;
                        $retval .= '</li>';
                    }
                    $retval .= '</ul>';
                }
                return $retval;
            })
            ->addColumn('kategori', function ($row) {
                return isset($row->kategori) ? $row->kategori->kategori : "";
            })
            ->addColumn('pengguna', function ($row) {
                return isset($row->pengguna->akun) ? $row->pengguna->akun->nama : "";
            })
            ->addColumn('cek', function ($row) {
                return "<input type='checkbox' class='cekbaris' value='" . $row->id . "'>";
            })
            ->addColumn('action', function ($row) {
                $btn = '<button type="button" class="btn btn-sm btn-success btn-ganti" data-id="' . $row->id . '"><i class="bi bi-pencil-square"></i></button>
                        <button type="button" class="btn btn-sm btn-danger btn-hapus" data-id="' . $row->id . '"><i class="bi bi-trash3"></i></button>';
                return $btn;
            })
            ->rawColumns(['no', 'bagian', 'pihak_det', 'action', 'cek', 'kategori', 'pengguna', 'jenis', 'file_det'])
            ->make(true);
    }

    public function create(Request $request)
    {
        $retval = array("status" => false, "insert" => true, "messages" => ["gagal, hubungi admin"]);
        $id = null;
        //cek apakah id ada atau tidak, kalau ada maka status edit dan jika tidak ada maka insert
        $insert = true;
        if ($request['id']) {
            $insert = false;
        }

        $rules = [
            'jenis_id' => 'required',
            'pihak_id' => 'required',
            'bagian_id' => 'required',
            //'no_surat_internal' => 'required',
            'tentang' => 'required',
            'kategori_id' => 'required',
            //'tgl' => 'required|date',
            'tgl_berlaku' => 'required|date',
            //'tgl_berakhir' => 'required|date|after:tgl_berlaku',
            'tgl_berakhir' => 'required|date|after_or_equal:tgl_berlaku',
        ];

        $niceNames = [
            'jenis_id' => 'jenis kerjasama',
            'pihak_id' => 'pihak kerjasama',
            'bagian_id' => 'bagian kerjasama',
            //'no_surat_internal' => 'no surat internal',
            'kategori_id' => 'kategori',
            // 'tgl' => 'tanggal kerjasama',
            'tgl_berlaku' => 'tanggal berlaku',
            'tgl_berakhir' => 'tanggal berakhir',
        ];
        $datapost = $this->validate($request, $rules, [], $niceNames);

        $retval['insert'] = $insert;
        //$datapost['no_surat_internal'] = "";
        unset($rules['pihak_id']);
        try {
            DB::beginTransaction();
            if ($insert) {
                $pengguna_id = Pengguna::where("akun_id", "=", auth()->user()->id)->first()->id;
                $datapost['pengguna_id'] = auth()->user()->id;

                $id = Mou::create($datapost)->id;
            } else {
                $id = $request['id'];
                $cari = Mou::where("id", $request['id'])->first();
                $cari->update($datapost);
            }

            ParaPihak::where('mou_id', $id)->delete();
            foreach ($request['pihak_id'] as $i => $dp) {
                $datapost = [
                    "pihak_id" => $dp,
                    "mou_id" => $id,
                ];
                ParaPihak::create($datapost)->id;
            }
            //$cariParaPihak = Mou::where("mou_id", $id)->first();

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
            $data = Mou::where('id', $request['id'])
                ->with(["paraPihak.pihak"])
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
            ParaPihak::whereIn('mou_id', $ids)->delete();
            Mou::whereIn('id', $ids)->delete();
            $retval = array("status" => true, "messages" => ["data berhasil dihapus"]);
        } catch (\Throwable $e) {
            $retval['messages'] = [$e->getMessage()];
        }
        return response()->json($retval);
    }

    public function search(Request $request)
    {
        $data = Mou::with(["pihak.kabupaten.provinsi"])
            ->with(["jenis"])
            ->with(["file"])
            ->with(["kategori"])
            ->with(["pengguna.akun"])
            ->where('tentang', 'like', '%' . $request['cari'] . '%')
            ->orWhere('tahun', 'like', '%' . $request['cari'] . '%')
            ->orWhere('ruang_lingkup', 'like', '%' . $request['cari'] . '%')
            ->get();
        return response()->json($dt);
    }

    public function upload(Request $request)
    {
        $retval = array("status" => false, "insert" => true, "messages" => ["gagal, hubungi admin"]);
        //cek apakah id ada atau tidak, kalau ada maka status edit dan jika tidak ada maka insert
        $insert = true;
        $datapost = $this->validate($request, [
            'mou_id' => 'required',
        ], [], [
            'mou_id' => 'MoU/PKS',
        ]);
        $retval['insert'] = $insert;

        if ($request->hasFile('fileupload')) {
            $this->validate($request, [
                'fileupload' => ['mimes:jpeg,png,jpg,gif,svg,pdf', 'max:1024'],
            ]);

            try {
                $file = $request->file('fileupload');
                $ext = $file->getClientOriginalExtension();
                $det =   [
                    "originalName" => $file->getClientOriginalName(),
                    "size" => ceil($file->getSize() / 1000),
                    "mime" => $file->getMimeType(),
                    "ext" => $ext,
                ];
                $datapost['detail'] = json_encode($det);
                $datapost['is_image'] = "1";
                if (strtolower($ext) == "pdf") {
                    $datapost['is_image'] = "0";
                }
                $destinationPath = 'uploads/kerjasama/' . date('Y') . '/' . date('m');

                $datapost['is_file'] = "1";
                $datapost['source'] = $file->store($destinationPath);


                DB::beginTransaction();
                $id = File::create($datapost)->id;
                $retval["status"] = true;
                $retval["messages"] = ["Simpan data berhasil dilakukan"];
                DB::commit();
            } catch (\Throwable $e) {
                $retval['messages'] = [$e->getMessage()];
                DB::rollBack();
            }
        }
        return response()->json($retval);
    }

    public function uploadDelete(Request $request)
    {
        $retval = array("status" => false, "messages" => ["maaf, gagal dilakukan"]);
        try {
            DB::beginTransaction();

            $cari = File::where("id", $request['id'])->first();

            if (isset($cari->id)) {
                $file = $cari->source;
                if (Storage::disk('public')->exists($file)) {
                    Storage::delete($file);
                }
                $cari->delete();
                $retval = array("status" => true, "messages" => ["hapus file berhasil dilakukan"]);
            }
            DB::commit();
        } catch (\Throwable $e) {
            $retval['messages'] = [$e->getMessage()];
            DB::rollBack();
        }
        return $retval;
    }
}
