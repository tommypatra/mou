<?php

namespace Database\Seeders;

use App\Models\Akun;
use App\Models\Grup;
use App\Models\Menu;
use App\Models\Admin;
use App\Models\Jenis;
use App\Models\Modul;
use App\Models\Pihak;
use App\Helpers\MyApp;
use App\Models\Bagian;
use App\Models\Kategori;
use App\Models\Pengguna;
use App\Models\Provinsi;

use App\Models\Kabupaten;
use App\Models\BagianAkun;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        //nilai default grup
        $dtdef = [
            "Admin", "Pengguna",
        ];
        foreach ($dtdef as $dt) {
            Grup::create([
                'grup' => $dt,
                'aktif' => "1",
            ]);
        }


        //untuk admin
        Akun::create([
            'nama' => 'Administrator',
            'email' => 'admin@thisapp.com', //email login
            'password' => bcrypt('00000000'), // password default login admin
            'kel' => 'L',
            'glrdepan' => '',
            'tempatlahir' => 'Kendari',
            'tanggallahir' => date('Y-m-d'),
            'alamat' => 'BTN Kendari',
            'nohp' => '0852001019876'
        ]);
        //dibuatkan adminnya
        Pengguna::create([
            'akun_id' => 1,
            'grup_id' => 1,
            'token' => \MyApp::generateToken(),
            'aktif' => "1"
        ]);
        //dibuatkan adminnya
        Pengguna::create([
            'akun_id' => 1,
            'grup_id' => 2,
            'token' => \MyApp::generateToken(),
            'aktif' => "1"
        ]);

        //SEEDER pakai Factories data faker untuk akun
        Akun::factory(30)->create();
        //loop 30 untuk pengguna 
        for ($i = 2; $i <= 30; $i++) {
            Pengguna::create([
                'akun_id' => $i,
                'grup_id' => 2,
                'token' => \MyApp::generateToken(),
                'aktif' => "1"
            ]);
        }

        //nilai default bagian
        $dtdef = [
            "Rektorat", "LPPM", "LPM", "SPI", "FATIK", "SYARIAH", "FUAD", "FEBI",
            "PASCASARJANA", "UPT TIPD", "UPT MAHAD", "UPT PENGEMBANGAN BAHASA", "UPT PERPUSTAKAAN",
        ];
        foreach ($dtdef as $dt) {
            Bagian::create([
                'bagian' => $dt,
                'akun_id' => 1,
                'aktif' => "1"
            ]);
        }

        //nilai default bagian akun
        //untuk admin
        for ($i = 1; $i <= 13; $i++) {
            BagianAkun::create([
                'akun_id' => 1,
                'bagian_id' => $i,
                'aktif' => "1"
            ]);
        }

        //loop 30 user id random untuk bagian nya
        for ($i = 1; $i <= 30; $i++) {
            for ($i = 1; $i <= rand(1, 13); $i++) {
                BagianAkun::create([
                    'akun_id' => $dt,
                    'bagian_id' => $i,
                    'aktif' => "1"
                ]);
            }
        }

        //nilai default jenis
        $dtdef = [
            "MoU", "PKS",
        ];
        foreach ($dtdef as $dt) {
            Jenis::create([
                'jenis' => $dt,
                'akun_id' => 1,
            ]);
        }


        //nilai default kategori
        $dtdef = [
            "LOKAL", "NASIONAL", "INTERNASIONAL",
        ];
        foreach ($dtdef as $dt) {
            Kategori::create([
                'kategori' => $dt,
                'akun_id' => 1,
            ]);
        }

        //nilai default modul
        $dtdef = [
            ['menu' => 'Kerja Sama', 'link' => '{{ kerjasama.lnk }}', 'icon' => '<i class="bi bi-hdd-network"></i>'],
            ['menu' => 'Pihak', 'link' => '{{ pihak.lnk }}', 'icon' => '<i class="bi bi-journal-text"></i>'],
            ['menu' => 'Akun', 'link' => '#', 'icon' => '<i class="bi bi-people"></i>'],
            ['menu' => 'Grup', 'link' => '{{ akun-grup.lnk }}', 'icon' => '<i class="bi bi-circle"></i>'],
            ['menu' => 'Hak Akses', 'link' => '{{ akses-grup.lnk }}', 'icon' => '<i class="bi bi-circle"></i>'],
            ['menu' => 'Daftar Pengguna', 'link' => '{{ pengguna-grup.lnk }}', 'icon' => '<i class="bi bi-circle"></i>'],
            ['menu' => 'Referensi', 'link' => '#', 'icon' => '<i class="bi bi-collection"></i>'],
            ['menu' => 'Jenis', 'link' => '{{ jenis-referensi.lnk }}', 'icon' => '<i class="bi bi-circle"></i>'],
            ['menu' => 'Kategori', 'link' => '{{ kategori-referensi.lnk }}', 'icon' => '<i class="bi bi-circle"></i>'],
            ['menu' => 'Modul', 'link' => '{{ modul-referensi.lnk }}', 'icon' => '<i class="bi bi-circle"></i>'],
            ['menu' => 'Menu', 'link' => '{{ menu-referensi.lnk }}', 'icon' => '<i class="bi bi-circle"></i>'],
        ];
        foreach ($dtdef as $i => $dt) {
            Modul::create([
                'menu' => $dt['menu'],
                'link' => $dt['link'],
                'icon' => $dt['icon'],
                'akun_id' => 1,
            ]);
        }

        //nilai default Menu Admin
        $dtdef = [
            ['grup_id' => '1', 'urut' => 1, 'modul_id' => '1', 'menu_id' => null],
            ['grup_id' => '1', 'urut' => 2, 'modul_id' => '2', 'menu_id' => null],
            ['grup_id' => '1', 'urut' => 3, 'modul_id' => '3', 'menu_id' => null],
            ['grup_id' => '1', 'urut' => 4, 'modul_id' => '4', 'menu_id' => 3],
            ['grup_id' => '1', 'urut' => 5, 'modul_id' => '5', 'menu_id' => 3],
            ['grup_id' => '1', 'urut' => 6, 'modul_id' => '6', 'menu_id' => 3],
            ['grup_id' => '1', 'urut' => 7, 'modul_id' => '7', 'menu_id' => null],
            ['grup_id' => '1', 'urut' => 8, 'modul_id' => '8', 'menu_id' => 7],
            ['grup_id' => '1', 'urut' => 9, 'modul_id' => '9', 'menu_id' => 7],
            ['grup_id' => '1', 'urut' => 10, 'modul_id' => '10', 'menu_id' => 7],
            ['grup_id' => '1', 'urut' => 11, 'modul_id' => '11', 'menu_id' => 7],
            ['grup_id' => '2', 'urut' => 1, 'modul_id' => '1', 'menu_id' => null],
            ['grup_id' => '2', 'urut' => 2, 'modul_id' => '2', 'menu_id' => null],
            ['grup_id' => '2', 'urut' => 3, 'modul_id' => '3', 'menu_id' => null],
        ];
        foreach ($dtdef as $i => $dt) {
            Menu::create([
                'grup_id' => $dt['grup_id'],
                'modul_id' => $dt['modul_id'],
                'menu_id' => $dt['menu_id'],
                'urut' => $dt['urut'],
                'akun_id' => 1,
            ]);
        }

        //nilai default provinsi
        $dtdef = [
            "SULAWESI TENGGARA", "JAWA TIMUR",
        ];
        foreach ($dtdef as $dt) {
            Provinsi::create([
                'provinsi' => $dt,
                'akun_id' => 1,
            ]);
        }

        //nilai default kabupaten
        $dtdef = [
            ["kabupaten" => "KOTA KENDARI", "provinsi_id" => "1"], //1
            ["kabupaten" => "KOTA BAU-BAU", "provinsi_id" => "1"],
            ["kabupaten" => "KABUPATEN KONAWE SELATAN", "provinsi_id" => "1"],
            ["kabupaten" => "KABUPATEN KONAWE UTARA", "provinsi_id" => "1"],
            ["kabupaten" => "KABUPATEN KONAWE KEPULAUAN", "provinsi_id" => "1"], //5
            ["kabupaten" => "KABUPATEN KONAWE", "provinsi_id" => "1"],
            ["kabupaten" => "KABUPATEN KOLAKA", "provinsi_id" => "1"],
            ["kabupaten" => "KABUPATEN KOLAKA TIMUR", "provinsi_id" => "1"],
            ["kabupaten" => "KABUPATEN KOLAKA UTARA", "provinsi_id" => "1"],
            ["kabupaten" => "KABUPATEN WAKATOBI", "provinsi_id" => "1"], //10
            ["kabupaten" => "KABUPATEN BUTON", "provinsi_id" => "1"],
            ["kabupaten" => "KABUPATEN BUTON UTARA", "provinsi_id" => "1"],
            ["kabupaten" => "KABUPATEN BUTON TENGAH", "provinsi_id" => "1"],
            ["kabupaten" => "KABUPATEN BUTON SELATAN", "provinsi_id" => "1"],
            ["kabupaten" => "KABUPATEN MUNA", "provinsi_id" => "1"], //15
            ["kabupaten" => "KABUPATEN MUNA BARAT", "provinsi_id" => "1"],
            ["kabupaten" => "KABUPATEN BOMBANA", "provinsi_id" => "1"],
            ["kabupaten" => "KOTA SURABAYA", "provinsi_id" => "2"], //18
            ["kabupaten" => "KABUPATEN SIDOARJO", "provinsi_id" => "2"],
            ["kabupaten" => "KABUPATEN MALANG", "provinsi_id" => "2"],
            ["kabupaten" => "KABUPATEN JEMBER", "provinsi_id" => "2"],
        ];
        foreach ($dtdef as $dt) {
            Kabupaten::create([
                'kabupaten' => $dt['kabupaten'],
                'provinsi_id' => $dt['provinsi_id'],
                'akun_id' => 1,
            ]);
        }

        //nilai default pihak
        $dtdef = [
            ["pihak" => "UNIVERSITAS HALUOLEO", "kabupaten_id" => "1"],
            ["pihak" => "PEMERINTAH DAERAH PROVINSI SULAWESI TENGGARA", "kabupaten_id" => "1"],
            ["pihak" => "PEMERINTAH DAERAH KABUPATEN WAKAOTIB", "kabupaten_id" => "10"],
            ["pihak" => "UNIVERSITAS ISLAM NEGERI SURABAYA", "kabupaten_id" => "18"],
        ];
        foreach ($dtdef as $dt) {
            Pihak::create([
                'pihak' => $dt['pihak'],
                'kabupaten_id' => $dt['kabupaten_id'],
                'akun_id' => 1,
            ]);
        }
    }
}
