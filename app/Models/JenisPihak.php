<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisPihak extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function pihak()
    {
        return $this->hasMany(Pihak::class);
    }
}
