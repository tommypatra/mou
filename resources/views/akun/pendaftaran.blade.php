@extends('akun')

@section('head')
  <link href="plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css" rel="stylesheet">
@endsection

@section('container')
<div class="pt-4 pb-2">
  <h5 class="card-title text-center pb-0 fs-4">Pendaftaran Akun Baru</h5>
  <p class="text-center small">Silahkan mengisi form untuk membuat akun</p>
</div>

<form id="fweb" class="row g-3 needs-validation" novalidate>
  @csrf
  <div class="col-12">
    <label for="nama" class="form-label">Nama (tanpa gelar)</label>
    <input type="text" name="nama" class="form-control" id="nama" required>
    <div class="invalid-feedback">masukan nama anda!</div>
  </div>

  <div class="col-12">
    <label for="yourEmail" class="form-label">Email</label>
    <div class="input-group has-validation">
      <span class="input-group-text" id="inputGroupPrepend"><i class="bi bi-envelope"></i></span>
      <input type="email" name="email" class="form-control" id="yourEmail" required>
      <div class="invalid-feedback">masukan email anda!</div>
    </div>
</div>

  <div class="col-12">
    <label for="kel" class="form-label">Jenis Kelamin</label>
    <select name="kel" id="kel" class="form-control" required>
    </select>
    <div class="invalid-feedback">pilih jenis kelamin!</div>
  </div>

  <div class="col-12">
    <label for="lhrtgl" class="form-label">Tanggal Lahir</label>
    <input type="text" name="tanggallahir" class="form-control datepicker" id="tanggallahir" required>
    <div class="invalid-feedback">masukan tanggal lahir anda!</div>
  </div>

  <div class="col-12">
    <label for="fldpass" class="form-label">Password</label>
    <input type="password" name="password" class="form-control" id="password" required>
    <div class="invalid-feedback">ketik password anda!</div>
  </div>

  <div class="col-12">
    <div class="form-check">
      <input class="form-check-input" name="terms" type="checkbox" value="setuju" id="acceptTerms" required>
      <label class="form-check-label" for="acceptTerms">Saya menyetujuan <a href="#">syarat dan kondisi</a></label>
      <div class="invalid-feedback">Anda harus setuju sebelum mendaftar.</div>
    </div>
  </div>
  <div class="col-12">
    <button class="btn btn-primary w-100" type="submit">Buat Sekarang</button>
  </div>
  <div class="col-12">
    <p class="small mb-0">Akun sudah terdaftar? <a href="{{ route('login.lnk') }}">Masuk disini</a></p>
  </div>
</form>
@endsection

@section("scriptJs")
<script src='plugins/bootstrap-material-moment/moment.js'></script>
<script src='plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js'></script>
<script src="js/select2lib.js"></script>

  <script>
    sel2_jeniskelamin("#kel");
    $("#tanggallahir").val(vTgl_sql);
    $('.datepicker').bootstrapMaterialDatePicker({
        weekStart: 0,
        time: false,
    });

    function resetform(){
      $('#fweb')[0].reset();
      $("#tanggallahir").val(vTgl_sql);
      $('#kel').val("").trigger('change');
    };

    $("#fweb").submit(function(e) {
      e.preventDefault();
      var form = $("#fweb")[0];
      let formVal = $(this).serialize();
      let isValid = form.checkValidity();
      if(isValid){
          appAjax('{{ route("simpan-pendaftaran.lnk") }}', formVal,"post",20000).done(function(vRet) {
            showmymessage(vRet.messages,vRet.status);
            if(vRet.status){
              //resetform();
              window.setTimeout(function() {
                window.location.href = '{{ route("login.lnk") }}';
              }, 3000);           
            }
          });
      }
    });    
  </script>
@endsection