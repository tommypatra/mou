<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Menu extends Model
{
    use HasFactory;
    protected $guarded = ["id"];

    public function grup()
    {
        return $this->belongsTo(Grup::class);
    }

    public function akses()
    {
        return $this->hasOne(Akses::class);
    }

    public function modul()
    {
        return $this->belongsTo(Modul::class);
    }

    public function generate()
    {
        $dt = Menu::with(['modul'])->where('grup_id', '1')->orderBy('urut', 'asc')->get()->toArray();
        return \MyApp::buildTree($dt, null, "id", "menu_id");
    }
}
