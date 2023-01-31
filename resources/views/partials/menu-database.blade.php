<?php
$menu = \App\Models\Menu::Generate();
//dd($menu);

function recursive($data,$child=false)
{  
  foreach($data as $key => $value){
    $tmp = $value["modul"];
    $adachild =isset($value['children'])?true:false;
    if($adachild)
      echo'
      <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#menu-'.$value['id'].'" data-bs-toggle="collapse" href="#">
          '.$tmp['icon'].'
          <span>'.$tmp['menu'].'</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>';
    else
      echo'
      <li class="nav-item">
        <a class="nav-link collapsed" href="'.$tmp['link'].'">
          '.$tmp['icon'].'
          <span>'.$tmp['menu'].'</span>
        </a>
      </li>';

    if(isset($value['children'])){
      echo '<ul id="menu-'.$value['id'].'" class="nav-content collapse " data-bs-parent="#sidebar-nav">';
      recursive($value['children'],true);
      echo '</ul>';
    }    
  }
}

recursive($menu);

?>

<li class="nav-item">
      <a class="nav-link collapsed" href="users-profile.html">
        <i class="bi bi-hdd-network"></i>
        <span>Kerja Sama</span>
      </a>
    </li><!-- End Profile Page Nav -->

    <li class="nav-item">
      <a class="nav-link collapsed" href="users-profile.html">
        <i class="bi bi-journal-text"></i>
        <span>Pihak</span>
      </a>
    </li><!-- End Profile Page Nav -->

    <li class="nav-item">
      <a class="nav-link collapsed" data-bs-target="#charts-nav" data-bs-toggle="collapse" href="#">
        <i class="bi bi-people"></i><span>Akun</span><i class="bi bi-chevron-down ms-auto"></i>
      </a>
      <ul id="charts-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
        <li>
          <a href="charts-chartjs.html">
            <i class="bi bi-circle"></i><span>Grup</span>
          </a>
        </li>
        <li>
          <a href="charts-apexcharts.html">
            <i class="bi bi-circle"></i><span>Hak Akses</span>
          </a>
        </li>
        <li>
          <a href="charts-echarts.html">
            <i class="bi bi-circle"></i><span>Daftar Pengguna</span>
          </a>
        </li>
      </ul>
    </li><!-- End Charts Nav -->

    <li class="nav-item">
      <a class="nav-link collapsed" data-bs-target="#icons-nav" data-bs-toggle="collapse" href="#">
        <i class="bi bi-collection"></i>
        <span>Referensi</span><i class="bi bi-chevron-down ms-auto"></i>
      </a>
      <ul id="icons-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
        <li>
          <a href="icons-bootstrap.html">
            <i class="bi bi-circle"></i><span>Jenis</span>
          </a>
        </li>
        <li>
          <a href="icons-remix.html">
            <i class="bi bi-circle"></i><span>Kategori</span>
          </a>
        </li>
        <li>
          <a href="icons-boxicons.html">
            <i class="bi bi-circle"></i><span>Modul</span>
          </a>
        </li>
        <li>
          <a href="icons-boxicons.html">
            <i class="bi bi-circle"></i><span>Menu</span>
          </a>
        </li>
      </ul>
    </li><!-- End Icons Nav -->