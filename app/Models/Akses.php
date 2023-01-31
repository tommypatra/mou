<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Akses extends Model
{
    use HasFactory;
    protected $guarded = ["id"];

    public function Modul()
    {
        return $this->belongsTo(Modul::class);
    }

    public function Grup()
    {
        return $this->belongsTo(Grup::class);
    }
}
