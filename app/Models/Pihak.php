<?php

namespace App\Models;

use App\Models\Kabupaten;
use App\Models\ParaPihak;
use App\Models\JenisPihak;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pihak extends Model
{
    use HasFactory;
    protected $guarded = ["id"];

    public function kabupaten()
    {
        return $this->belongsTo(Kabupaten::class);
    }

    public function jenisPihak()
    {
        return $this->belongsTo(JenisPihak::class);
    }

    public function paraPihak()
    {
        return $this->hasMany(ParaPihak::class);
    }
}
