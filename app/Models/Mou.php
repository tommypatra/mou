<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mou extends Model
{
    use HasFactory;
    protected $guarded = ["id"];

    public function Pengguna()
    {
        return $this->belongsTo(Pengguna::class);
    }

    public function Pihak()
    {
        return $this->belongsTo(Pihak::class);
    }

    public function Jenis()
    {
        return $this->belongsTo(Jenis::class);
    }

    public function Kategori()
    {
        return $this->belongsTo(Jenis::class);
    }
}
