<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Modul extends Model
{
    use HasFactory;
    protected $guarded = ["id"];

    public function akses()
    {
        return $this->hasMany(Akses::class);
    }
}
