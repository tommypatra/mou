<?php

namespace App\Models;

use App\Models\Mou;
use App\Models\Akun;
use App\Models\Grup;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pengguna extends Model
{
    use HasFactory;
    protected $guarded = ["id"];

    public function akun()
    {
        return $this->belongsTo(Akun::class);
    }

    public function grup()
    {
        return $this->belongsTo(Grup::class);
    }

    public function mou()
    {
        return $this->hasMany(Mou::class);
    }

    public function getAkses()
    {
        return pengguna::with('akun')
            ->with('grup')
            ->where('akun_id', auth()->user()->id)
            ->get();
    }

    public function cekAdmin()
    {
        $retval = array("status" => false, "messages" => ["akses ditolak"]);
        foreach (session()->get('groups') as $dp) {
            if ($dp['id'] == 1) {
                $retval = array("status" => true, "messages" => ["akses diterima"]);
                break;
            }
        }
        return json_encode($retval);
    }

    public function cekPengguna()
    {
        $retval = array("status" => false, "messages" => ["akses ditolak"]);
        foreach (session()->get('groups') as $dp) {
            if ($dp['id'] == 2) {
                return array("status" => true, "messages" => ["akses diterima"]);
            }
        }
        return json_encode($retval);
    }
}
