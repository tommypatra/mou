@extends('dashboard')

@section('pagetitle')
    <div class="pagetitle">
        <h1>Halaman Utama</h1>
        <nav>
          <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="{{ route('login.lnk') }}">Home</a></li>
          </ol>
        </nav>
    </div>
@endsection

@section('container')
    <div class="col-lg-12">

        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Dashboard</h5>
                <p>Selamat Datang</p>
            </div>
        </div>
    </div>

  </div>
  
@endsection

@section("scriptJs")
  <script>
    //alert("selamat datang");
  </script>
@endsection