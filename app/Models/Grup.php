<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grup extends Model
{
    use HasFactory;
    protected $guarded = ["id"];

    public function Pengguna()
    {
        return $this->hasMany(Pengguna::class);
    }

    public function Menu()
    {
        return $this->hasMany(Menu::class);
    }
}
