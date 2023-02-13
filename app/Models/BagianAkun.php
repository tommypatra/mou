<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BagianAkun extends Model
{
    use HasFactory;
    protected $guarded = ["id"];

    public function akun()
    {
        return $this->belongsTo(Akun::class);
    }

    public function bagian()
    {
        return $this->belongsTo(Bagian::class);
    }
}
