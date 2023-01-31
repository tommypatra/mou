<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    public function File()
    {
        return $this->hasMany(File::class);
    }
}
