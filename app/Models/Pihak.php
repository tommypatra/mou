<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pihak extends Model
{
    use HasFactory;
    protected $guarded = ["id"];

    public function Kabupaten()
    {
        return $this->belongsTo(Kabupaten::class);
    }

    public function Mou()
    {
        return $this->hasMany(Mou::class);
    }
}
