<?php

namespace App\Models;

use App\Models\Mou;
use App\Models\BagianAkun;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Bagian extends Model
{
    use HasFactory;
    protected $guarded = ["id"];

    public function bagianAkun()
    {
        return $this->hasMany(BagianAkun::class);
    }

    public function mou()
    {
        return $this->hasMany(Mou::class);
    }
}
