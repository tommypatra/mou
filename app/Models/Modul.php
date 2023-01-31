<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Modul extends Model
{
    use HasFactory;
    protected $guarded = ["id"];

    public function Akun()
    {
        return $this->belongsTo(Akun::class);
    }

    public function Menu()
    {
        return $this->hasMany(Menu::class);
    }

    public function Akses()
    {
        return $this->hasMany(Akses::class);
    }
}
