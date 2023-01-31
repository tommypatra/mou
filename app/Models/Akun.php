<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Akun extends Authenticatable
{
    use HasFactory;
    protected $guarded = ["id"];

    public function Kabupaten()
    {
        return $this->hasMany(Kabupaten::class);
    }

    public function Jenis()
    {
        return $this->hasMany(Jenis::class);
    }

    public function Menu()
    {
        return $this->hasMany(Menu::class);
    }

    public function Akses()
    {
        return $this->hasMany(Akses::class);
    }

    public function Pengguna()
    {
        return $this->hasMany(Pengguna::class);
    }

    public function Grup()
    {
        return $this->hasMany(Grup::class);
    }

    public function Modul()
    {
        return $this->hasMany(Modul::class);
    }

    public function Pihak()
    {
        return $this->hasMany(Pihak::class);
    }

    public function Provinsi()
    {
        return $this->hasMany(Provinsi::class);
    }

    public function Kategori()
    {
        return $this->hasMany(Kategori::class);
    }

    public function Bagian()
    {
        return $this->hasMany(Bagian::class);
    }
}
