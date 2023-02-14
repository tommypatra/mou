<?php

namespace App\Helpers;

//use Illuminate\Support\Collection;

class MyApp
{

    public function decodeNIK($vdata = null)
    {
        $retval = [];
        //inisiasi tahun sekarang
        $thnskrng = date("Y");
        //menyiapkan temporari tahun
        $tmpthn = (int)substr($thnskrng, 0, 2);

        $retval["prov"] = substr($vdata, 0, 2);
        $retval["kab"] = substr($vdata, 2, 2);
        $retval["kec"] = substr($vdata, 4, 2);

        $tgl = (int)substr($vdata, 6, 2);
        $bln = (int)substr($vdata, 8, 2);
        $thn = (int)substr($vdata, 10, 2);

        $thnlahir = ($tmpthn . $thn);
        if ((int)$thnlahir > (int)$thnskrng) {
            $thnlahir = ($tmpthn - 1) . $thn;
        }

        $retval["kel"] = "L";
        if ($tgl > 40) {
            $tgl = $tgl - 40;
            $retval["kel"] = "P";
        }
        $retval["tgllahir"] = date("Y-m-d");
        if (checkdate($bln, $tgl, $thnlahir))
            $retval["tgllahir"] = $thnlahir . "-" . $bln . "-" . $tgl;
        //echo $retval["tgllahir"];
        //die;
        return $retval;
    }

    public function allowheader($content_type = "application/json")
    {
        $allow = [
            'mou.iainkendari.ac.id',
        ];

        //debug($_SERVER);
        $http_origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : "https://mou.iainkendari.ac.id";
        $web_origin = parse_url($http_origin);

        Header("Access-Control-Allow-Origin: " . $http_origin);
        Header("Access-Control-Allow-Headers: *");
        header("Access-Control-Allow-Credentials: true");
        Header("Access-Control-Allow-Methods: GET, POST");
        header("Content-Type: " . $content_type . "; charset=utf-8");

        $CI = get_instance();
        //if (!in_array($web_origin['host'], $allow) || !$CI->input->is_ajax_request()) {
        if (!in_array($web_origin['host'], $allow)) {
            $retval = array("status" => false, "pesan" => ["tidak diperbolehkan"]);
            die(json_encode(($retval)));
        }
    }

    public function waktu_lalu($timestamp = null)
    {
        $waktu = "";
        if ($timestamp) {
            $phpdate = strtotime($timestamp);
            $mysqldate = date('Y-m-d H:i:s', $phpdate);

            $selisih = time() - strtotime($mysqldate);
            $detik = $selisih;
            $menit = round($selisih / 60);
            $jam = round($selisih / 3600);
            $hari = round($selisih / 86400);
            $minggu = round($selisih / 604800);
            $bulan = round($selisih / 2419200);
            $tahun = round($selisih / 29030400);
            if ($detik <= 60) {
                $waktu = $detik . ' detik lalu';
            } else if ($menit <= 60) {
                $waktu = $menit . ' menit lalu';
            } else if ($jam <= 24) {
                $waktu = $jam . ' jam lalu';
            } else if ($hari <= 7) {
                $waktu = $hari . ' hari lalu';
            } else if ($minggu <= 4) {
                $waktu = $minggu . ' minggu lalu';
            } else if ($bulan <= 12) {
                $waktu = $bulan . ' bulan lalu';
            } else {
                $waktu = $tahun . ' tahun lalu';
            }
        }
        return $waktu;
    }

    public static function generateToken($length = 32)
    {
        $randomString = "";
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString .= date("s") . $characters[rand(0, $charactersLength - 1)];
        $randomString .= date("m") . $characters[rand(0, $charactersLength - 1)];
        $randomString .= date("y") . $characters[rand(0, $charactersLength - 1)];
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        $randomString .= date("i") . $characters[rand(0, $charactersLength - 1)];
        $randomString .= date("H") . $characters[rand(0, $charactersLength - 1)];
        $randomString .= date("d") . $characters[rand(0, $charactersLength - 1)];
        return $randomString;
    }

    public function detailLogin($email = null)
    {
        $dt = \App\Models\Akun::with(['pengguna.grup', 'bagianAkun.bagian'])->where("email", $email)->get();
        $groups = [];
        $bagians = [];
        foreach ($dt as $dp) {
            foreach ($dp->pengguna as $pg) {
                $tmp = $pg->grup;
                $groups[] = ["id" => $tmp->id, "grup" => $tmp->grup];
            }
            foreach ($dp->bagianAkun as $pg) {
                $tmp = $pg->bagian;
                $bagians[] = ["id" => $tmp->id, "grup" => $tmp->bagian];
            }
        }
        $ret = ['groups' => collect($groups), 'bagians' => collect($bagians)];
        return $ret;
    }

    public function buildTree(array $elements, $parentId = null, $id = "id", $idp = "parent_id", $cld = "children")
    {
        $branch = [];
        foreach ($elements as $element) {
            if ($element[$idp] == $parentId) {
                //echo $idp . "=" . $element[$idp];
                //die;
                $children = MyApp::buildTree($elements, $element[$id], $id, $idp);
                if ($children) {
                    $element[$cld] = $children;
                }
                $branch[] = $element;
            }
        }
        return $branch;
    }

    public function buildMenu($array, &$menu = "")
    {
        $menu .= '<ul>';
        foreach ($array as $item) {
            $menu .= '<li>';
            $menu .= '<a href="' . $item['modul']['link'] . '">' . $item['modul']['menu'] . '</a>';
            if (isset($item['children'])) {
                MyApp::buildMenu($item['children'], $menu);
            }
            $menu .= '</li>';
        }
        $menu .= '</ul>';
        return $menu;
    }

    public function format_rupiah($vuang = 0, $vkoma = 0)
    {
        return number_format($vuang, $vkoma, ",", ".");
    }

    public function extractemail($email)
    {
        $retval = array();
        preg_match("/^(.+)@([^\(\);:,<>]+\.[a-zA-Z]+)/", $email, $retval);
        return $retval;
    }
}
