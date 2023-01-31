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

    public function Akun()
    {
        return $this->belongsTo(Akun::class);
    }

    public function Grup()
    {
        return $this->belongsTo(Grup::class);
    }

    public function Mou()
    {
        return $this->hasMany(Mou::class);
    }

    public function isAdmin()
    {
        return pengguna::with('akun')
            ->with('grup', function ($grup) {
                $grup->where([
                    ['id', 1],
                ]);
            })
            ->where('akun_id', auth()->user()->id)
            ->get();
    }

    public function isPengguna()
    {
        return Akun::with("pengguna")
            ->with('grup', function ($grup) {
                $grup->where([
                    ['id', 2],
                    ['aktif', 1],
                ]);
            })
            ->where('id', auth()->user()->id)
            ->get();
    }
}
