<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use HasFactory;
    protected $guarded = ["id"];

    public function Mou()
    {
        return $this->belongsTo(Mou::class);
    }

    public function Pengguna()
    {
        return $this->belongsTo(Pengguna::class);
    }
}
