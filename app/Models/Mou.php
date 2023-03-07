<?php

namespace App\Models;

use App\Models\Jenis;
use App\Models\Kategori;
use App\Models\Bagian;
use App\Models\Pengguna;
use App\Models\ParaPihak;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Mou extends Model
{
    use HasFactory;
    protected $guarded = ["id"];

    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class);
    }

    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }

    public function bagian()
    {
        return $this->belongsTo(Bagian::class);
    }

    public function paraPihak()
    {
        return $this->hasMany(ParaPihak::class);
    }

    public function jenis()
    {
        return $this->belongsTo(Jenis::class);
    }


    public function file()
    {
        return $this->hasMany(File::class);
    }
}
