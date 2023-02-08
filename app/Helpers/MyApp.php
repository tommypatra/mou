<?php

namespace App\Helpers;

//use Illuminate\Support\Collection;

class MyApp
{

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

    public function listgroups($email = null)
    {
        $dt = \App\Models\Akun::with(['pengguna.grup'])->where("email", $email)->get();
        $groups = [];
        foreach ($dt as $dp) {
            foreach ($dp->pengguna as $pg) {
                $tmp = $pg->grup;
                $groups[] = ["id" => $tmp->id, "grup" => $tmp->grup];
            }
        }
        return collect($groups);
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

    function buildMenu($array, &$menu = "")
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
}
