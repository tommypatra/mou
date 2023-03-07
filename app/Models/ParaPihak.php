<?php

namespace App\Models;

use App\Models\Mou;
use App\Models\Pihak;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ParaPihak extends Model
{
    use HasFactory;
    protected $guarded = ["id"];

    public function mou()
    {
        return $this->belongsTo(Mou::class);
    }

    public function pihak()
    {
        return $this->belongsTo(Pihak::class);
    }
}
