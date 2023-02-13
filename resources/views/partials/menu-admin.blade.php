    <li class="nav-item">
      <a class="nav-link collapsed" data-bs-target="#charts-nav" data-bs-toggle="collapse" href="#">
        <i class="bi bi-people"></i><span>Akun</span><i class="bi bi-chevron-down ms-auto"></i>
      </a>
      <ul id="charts-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
        <li>
          <a href="{{ route('akun') }}">
            <i class="bi bi-circle"></i><span>Daftar Pengguna</span>
          </a>
        </li>
        <li>
          <a href="{{ route('akses') }}">
            <i class="bi bi-circle"></i><span>Hak Akses</span>
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
          <a href="{{ route('grup') }}">
            <i class="bi bi-circle"></i><span>Grup</span>
          </a>
        </li>
        <li>
          <a href="{{ route('bagian') }}">
            <i class="bi bi-circle"></i><span>Bagian</span>
          </a>
        </li>
        <li>
          <a href="{{ route('jenis') }}">
            <i class="bi bi-circle"></i><span>Jenis</span>
          </a>
        </li>
        <li>
          <a href="{{ route('kategori') }}">
            <i class="bi bi-circle"></i><span>Kategori</span>
          </a>
        </li>
        <li>
          <a href="{{ route('modul') }}">
            <i class="bi bi-circle"></i><span>Modul</span>
          </a>
        </li>
        <li>
          <a href="{{ route('menu') }}">
            <i class="bi bi-circle"></i><span>Menu</span>
          </a>
        </li>
        <li>
          <a href="{{ route('kabupaten') }}">
            <i class="bi bi-circle"></i><span>Kabupaten</span>
          </a>
        </li>
        <li>
          <a href="{{ route('provinsi') }}">
            <i class="bi bi-circle"></i><span>Provinsi</span>
          </a>
        </li>
      </ul>
    </li><!-- End Icons Nav -->