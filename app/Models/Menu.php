<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Menu extends Model
{
    use HasFactory;
    protected $guarded = ["id"];

    public function Grup()
    {
        return $this->belongsTo(Grup::class);
    }

    public function Akun()
    {
        return $this->belongsTo(Akun::class);
    }

    public function Modul()
    {
        return $this->belongsTo(Modul::class);
    }

    public function Menu()
    {
        return $this->belongsTo(Menu::class);
    }

    public function Generate()
    {
        $dt = Menu::with(['modul'])->where('grup_id', '1')->orderBy('urut', 'asc')->get()->toArray();
        return \MyApp::buildTree($dt, null, "id", "menu_id");
    }
}
