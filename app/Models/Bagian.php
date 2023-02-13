<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bagian extends Model
{
    use HasFactory;
    protected $guarded = ["id"];

    public function bagianAkun()
    {
        return $this->hasMany(BagianAkun::class);
    }
}
