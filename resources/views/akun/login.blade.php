@extends('akun')

@section('container')
  <div class="pt-4 pb-2">
    <h5 class="card-title text-center pb-0 fs-4">Masuk menggunakan akun terdaftar</h5>
    <p class="text-center small">Masukan email dan password anda untuk masuk</p>
  </div>

  <form id="fweb" class="row g-3 needs-validation" novalidate>
    @csrf
    <div class="col-12">
      <label for="yourEmail" class="form-label">Email</label>
      <div class="input-group has-validation">
        <span class="input-group-text" id="inputGroupPrepend"><i class="bi bi-envelope"></i></span>
        <input type="email" name="email" class="form-control" id="yourEmail" required>
        <div class="invalid-feedback">Ketik email anda!</div>
      </div>
    </div>

    <div class="col-12">
      <label for="password" class="form-label">Password</label>
      <input type="password" name="password" class="form-control" id="password" required>
      <div class="invalid-feedback">Ketik password anda!</div>
    </div>

    <div class="col-12">
      <div class="form-check">
        <input class="form-check-input" type="checkbox" name="remember" value="true" id="rememberMe">
        <label class="form-check-label" for="rememberMe">Ingat saya</label>
      </div>
    </div>
    <div class="col-12">
      <button class="btn btn-primary w-100" type="submit">Masuk</button>
    </div>
    <div class="col-12">
      <p class="small mb-0">Belum ada akun? <a href="{{ route('pendaftaran') }}">Daftar disini</a></p>
    </div>
  </form>
@endsection

@section("scriptJs")
  <script>
    $("#fweb").submit(function(e) {
      e.preventDefault();
      var form = $("#fweb")[0];
      let formVal = $(this).serialize();
      let isValid = form.checkValidity();
      if(isValid){
          appAjax('{{ route("ceklogin") }}', formVal).done(function(vRet) {
            showmymessage(vRet.messages,vRet.status);
            if(vRet.status){
              //console.log(vRet.groups);
              //resetform();
              $('#fweb *').prop('disabled', true);
              window.setTimeout(function() {
                window.location.href = '{{ route("dashboard") }}';
              }, 3000);           
            }else{
              $('#password').val("");
              $('#email').focus();
            }
          });
      }
    });    
  </script>
@endsection