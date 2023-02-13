<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    public function pihak()
    {
        return $this->belongsTo(Pihak::class);
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
