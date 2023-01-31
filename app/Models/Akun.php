<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Akun extends Authenticatable
{
    use HasFactory;
    protected $guarded = ["id"];

    public function Pengguna()
    {
        return $this->hasMany(Pengguna::class);
    }

    public function BagianAkun()
    {
        return $this->hasMany(BagianAkun::class);
    }
}
