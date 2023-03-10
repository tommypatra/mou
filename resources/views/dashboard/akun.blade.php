@extends('dashboard')

@section('head')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" type="text/css" href="plugins/datatables/datatables.min.css"/>        
    <link href="plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css" rel="stylesheet">
    <link href="plugins/dropzone/min/dropzone.min.css" rel="stylesheet">
    <style>
        .select2-container {
            z-index: 2050;
        } 
    </style>
@endsection

@section('pagetitle')
    <div class="pagetitle">
        <h1>Akun</h1>
        <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('login') }}">Home</a></li>
            <li class="breadcrumb-item ">Akun</li>
            <li class="breadcrumb-item active">Daftar</li>
        </ol>
        </nav>
    </div><!-- End Page Title -->
@endsection

@section('container')

<div class="col-lg-12">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center mb-3">            
            <h3 class="card-title d-flex">Daftar Akun</h3>
            <div class="list-inline d-flex">
                <div class="buttons">
                    <a href="#" class="btn icon btn-primary btn-tambah"><i class="bi bi-plus-circle"></i></a>
                    <a href="#" class="btn icon btn-primary btn-refresh"><i class="bi bi-arrow-clockwise"></i></a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">

                {{-- @foreach($data as $label)
                    {{ $label." " }}
                @endforeach --}}

                <table class="table table-bordered datatable">
                    <thead>
                        <tr>
                            <th><input type="checkbox" class="cekSemua"></th>
                            <th>No</th>
                            <th>Foto</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Jenis Kelamin</th>
                            <th>Alamat</th>
                            <th>HP</th>
                            <th>Bagian</th>
                            <th>Grup</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
                <button class="btn btn-danger hapusTerpilih"><i class="bi bi-trash"></i> Hapus Terpilih</button>
            </div>
        </div>


    </div>
</div>

<!-- MULAI MODAL FORM AKUN -->
<div class="modal fade" id="modal-form-web" role="dialog">
    <div class="modal-dialog modal-lg">
        <form id="fweb" class="row g-3 needs-validation" novalidate>
            @csrf
            <input type="hidden" name="id" id="id">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">FORM APLIKASI</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body ">

                    <div class="row">
                        <div class="col-8">
                            <div class="row">
                                <div class="col-12"> 
                                    <label for="nama" class="form-label">Nama Lengkap (tanpa title)</label>
                                    <input type="text" name="nama" class="form-control" id="nama" required>
                                    <div class="invalid-feedback">Nama lengkap anda!</div>
                                </div>
                                <div class="col-12">
                                    <label for="email" class="form-label">Email</label>
                                    <div class="input-group has-validation">
                                        <span class="input-group-text" id="inputGroupPrepend"><i class="bi bi-envelope"></i></span>
                                        <input type="email" name="email" class="form-control" id="email" required>
                                        <div class="invalid-feedback">masukan email anda!</div>
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                        <div class="col-4  text-center">
                            <label for="kel" class="form-label">Foto Profil</label>
                            <input class="form-control" type="file" id="foto" nama="foto">
                            <img src="images/user-avatar.png" alt="Profile" id="fotoprofil" width="100px" class="rounded-circle">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-4">
                            <label for="kel" class="form-label">Jenis Kelamin</label>
                            <select name="kel" id="kel" required>
                            </select>
                            <div class="invalid-feedback">pilih jenis kelamin!</div>
                        </div>
                        <div class="col-4"> 
                            <label for="tempatlahir" class="form-label">Tempat Lahir</label>
                            <input type="text" name="tempatlahir" class="form-control" id="tempatlahir" required>
                            <div class="invalid-feedback">Tempat lahir anda!</div>
                        </div>
                        <div class="col-4">
                            <label for="tanggallahir" class="form-label">Tanggal Lahir</label>
                            <div class="input-group">
                                <span class="input-group-text" id="inputGroupPrepend"><i class="bi bi-calendar-date"></i></span>
                                <input type="text" name="tanggallahir" class="form-control mydatepicker" id="tanggallahir" required>
                                <div class="invalid-feedback">Tanggal lahir anda! format tahun-bulan-tanggal</div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12"> 
                            <label for="alamat" class="form-label">Alamat</label>
                            <textarea  name="alamat" class="form-control" id="alamat" rows="4" required></textarea>
                            <div class="invalid-feedback">Alamat anda!</div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-5">
                            <label for="nohp" class="form-label">No. HP</label>
                            <input type="text" name="nohp" class="form-control" id="nohp" required>
                            <div class="invalid-feedback">No.HP anda!</div>
                        </div>
                        <div class="col-5">
                            <label for="fldpass" class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" id="password" >
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-4">
                            <label for="aktif" class="form-label">Status</label>
                            <select name="aktif" id="aktif" required>
                            </select>
                            <div class="invalid-feedback">pilih status aktif!</div>
                        </div>
                    </div>
                </div>
                    
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- MULAI MODAL PENGATURAN -->
<div class="modal fade" id="modal-atur" role="dialog">
    <div class="modal-dialog">
        <form id="fatur" class="row g-3 needs-validation" novalidate>
            @csrf
            <input type="hidden" name="akunid" id="akunid">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">FORM PENGATURAN</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body ">
                    <div id="html-atur"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- AKHIR MODAL PENGATURAN GRUP -->

@endsection

@section("scriptJs")
    <script src="plugins/datatables/datatables.min.js"></script>
    <script src='plugins/bootstrap-material-moment/moment.js'></script>
    <script src='plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js'></script>
    <script src='plugins/dropzone/min/dropzone.min.js'></script>

    <script src='js/select2lib.js'></script>

    <script>
        sel2_jeniskelamin("#kel");
        sel2_jeniskelamin("#kel2");
        sel2_aktif2("#aktif");

        resetform();

        $('.mydatepicker').bootstrapMaterialDatePicker({
            weekStart: 0,
            time: false,
        });

        var dtTable = $('.datatable').DataTable({
            processing: true,
            autoWidth: false,
            serverSide: true,
            lengthMenu: [
                [25, 50, 75, -1],
                ["25", "50", "75", "Semua"]
            ],
            ajax: {
                url: "{{ route('akun-read') }}",
                dataType: "json",
                type: "POST",
                data: function (d) {
                    d._token = $("meta[name='csrf-token']").attr("content");  
                },
                dataSrc: function (json) {
                    return json.data;
                },
            },
            "order": [
                [1, "asc"],
            ],
            dom: '<"row"<"col-sm-6"B><"col-sm-6"f>> rt <"row"<"col-sm-4"l><"col-sm-4"i><"col-sm-4"pi>>',
            buttons: [
                {
                    extend: 'copyHtml5',
                    title : $(document).attr('title'),
                    exportOptions: {
                        columns: [1,3,4,5,6,7]
                    }
                },
                {
                    extend: 'excelHtml5',
                    title : $(document).attr('title'),
                    exportOptions: {
                        columns: [1,3,4,5,6,7]
                    }
                },
                {
                    extend: 'print',
                    title : $(document).attr('title'),
                    exportOptions: {
                        columns: [1,3,4,5,6,7]
                    }
                },        
            ],
            columns: [
                {data: 'cek',className: "text-center", orderable: false, searchable: false},
                {data: 'no'},
                {data: 'foto',className: "text-center", orderable: false, searchable: false},
                {data: 'nama'},
                {data: 'email'},
                {data: 'kel'},
                {data: 'alamat'},
                {data: 'nohp'},
                {data: 'bagian'},
                {data: 'grup'},
                {data: 'aktif' },
                {data: 'action', className: "text-center", orderable: false, searchable: false},
            ],
            initComplete: function (e) {
                var api = this.api();
                $('#' + e.sTableId + '_filter input').off('.DT').on('keyup.DT', function (e) {
                    if (e.keyCode == 13) {
                        api.search(this.value).draw();
                    }
                });
            },
        });

        function resetform(){
            $('#id').val('');
            $('#fweb')[0].reset();
            $("#tanggallahir").val(vTgl_sql);
            $('#kel').val("L").trigger('change');
            $('#aktif').val("1").trigger('change');

            let time = (new Date()).getTime();
            let path = 'images/user-avatar.png?t='+time;
            $("#fotoprofil").attr("src",path);

            $("#fweb").removeClass("was-validated");
        };

        function fillform(dt){
            $('#id').val(dt.id);
            $('#nama').val(dt.nama);
            $('#email').val(dt.email);
            $('#tempatlahir').val(dt.tempatlahir);
            $('#tanggallahir').val(dt.tanggallahir);
            $('#alamat').val(dt.alamat);
            $('#nohp').val(dt.nohp);
            $('#kel').val(dt.kel).trigger('change');
            $('#aktif').val(dt.aktif).trigger('change');

            let time = (new Date()).getTime();
            let path = dt.foto;
            if(dt.foto!=='images/user-avatar.png')
                path = '{{ asset("storage")."/" }}'+dt.foto;
            $("#fotoprofil").attr("src",path+'?t='+time);
        }

        function reloadTable() {
            if (dtTable)
                $('.datatable').DataTable().ajax.reload(null, false);
        }

        $(".btn-refresh").click(function(){
            reloadTable();
        })

        $(".btn-tambah").click(function(){
            resetform();
            var myModal = new bootstrap.Modal(document.getElementById('modal-form-web'), {
                backdrop: 'static',
                keyboard: false,
            });
            myModal.toggle();
            $("#nama").focus();
        });

        //ganti
        $(document).on("click",".btn-ganti",function(){
            resetform();
            var formVal={_token:$("input[name=_token]").val(),id:$(this).data("id")};
            appAjax("{{ route('akun-update') }}", formVal).done(function(vRet) {
                if(vRet.status){
                    var myModal = new bootstrap.Modal(document.getElementById('modal-form-web'), {
                        backdrop: 'static',
                        keyboard: false,
                    });
                    myModal.toggle();
                    fillform(vRet.data);
                }else{
                    showmymessage(vRet.messages,vRet.status);
                }
            });
        });

        //atur bagian
        function databagian(id){
            let formVal={_token:$("input[name=_token]").val(),id:id};
            appAjax("{{ route('akun-bagian') }}", formVal).done(function(vRet) {
                if(vRet.status){
                    $("#html-atur").html(vRet.html);
                }
            });
        }

        $(document).on("click",".btn-atur-bagian",function(){
            let id=$(this).data("id");
            $("#akunid").val(id);
            var myModal = new bootstrap.Modal(document.getElementById('modal-atur'), {
                        backdrop: 'static',
                        keyboard: false,
            });
            myModal.toggle();
            databagian($("#akunid").val());
        });

        //atur grup
        function datagrup(id){
            let formVal={_token:$("input[name=_token]").val(),id:id};
            appAjax("{{ route('akun-grup') }}", formVal).done(function(vRet) {
                if(vRet.status){
                    $("#html-atur").html(vRet.html);
                }
            });
        }

        $(document).on("click",".btn-atur-grup",function(){
            let id=$(this).data("id");
            $("#akunid").val(id);
            var myModal = new bootstrap.Modal(document.getElementById('modal-atur'), {
                        backdrop: 'static',
                        keyboard: false,
            });
            myModal.toggle();
            datagrup($("#akunid").val());
        });

        $(document).on("click",".cekbagian",function(){
            let formVal={
                _token:$("input[name=_token]").val(),
                cek:$(this).is(":checked"),
                jenis:'bagian',
                bagianid:$(this).data("id"),
                akunid:$("#akunid").val(),
                bagianakunid:$(this).data('bagianakunid'),                
            };
            //if(confirm("apakah anda yakin?")){                
                appAjax("{{ route('akun-atur') }}", formVal).done(function(vRet) {
                    if(vRet.status){
                        databagian($("#akunid").val());
                        reloadTable();
                    }
                    showmymessage(vRet.messages,vRet.status);
                });
            //}
        });

        $(document).on("click",".cekbagianstatus",function(){
            let formVal={
                _token:$("input[name=_token]").val(),
                cek:$(this).is(":checked"),
                jenis:'bagianstatus',
                bagianakunid:$(this).data("id"),
            };
            //if(confirm("apakah anda yakin?")){                
                appAjax("{{ route('akun-atur') }}", formVal).done(function(vRet) {
                    if(vRet.status){
                        databagian($("#akunid").val());
                        reloadTable();
                    }
                    showmymessage(vRet.messages,vRet.status);
                });
            //}
        });

        // untuk grup        
        $(document).on("click",".cekgrup",function(){
            let formVal={
                _token:$("input[name=_token]").val(),
                cek:$(this).is(":checked"),
                jenis:'grup',
                grupid:$(this).data("id"),
                akunid:$("#akunid").val(),
                penggunaid:$(this).data('penggunaid'),                
            };
            //if(confirm("apakah anda yakin?")){                
                appAjax("{{ route('akun-atur') }}", formVal).done(function(vRet) {
                    if(vRet.status){
                        datagrup($("#akunid").val());
                        reloadTable();
                    }
                    showmymessage(vRet.messages,vRet.status);
                });
            //}
        });

        $(document).on("click",".cekgrupstatus",function(){
            let formVal={
                _token:$("input[name=_token]").val(),
                cek:$(this).is(":checked"),
                jenis:'grupstatus',
                penggunaid:$(this).data("id"),
            };
            //if(confirm("apakah anda yakin?")){                
                appAjax("{{ route('akun-atur') }}", formVal).done(function(vRet) {
                    if(vRet.status){
                        datagrup($("#akunid").val());
                        reloadTable();
                    }
                    showmymessage(vRet.messages,vRet.status);

                });
            //}
        });

        //--- mulai hapus ---
        //mengecek semua ceklist
        $(".cekSemua").change(function () {
            $(".cekbaris").prop('checked', $(this).prop("checked"));
        });

        //fungsi umum menghapus
        function hapus(idTerpilih){
            var formVal={_token:$("input[name=_token]").val(),id:idTerpilih};
            if(idTerpilih.length > 0 && confirm("apakah anda yakin?")){
                appAjax("{{ route('akun-delete') }}", formVal).done(function(vRet) {
                    if(vRet.status){
                        reloadTable();
                    }
                    showmymessage(vRet.messages,vRet.status);
                });                
            }
        }

        //tombol btn-hapus dari datatables
        $(document).on("click",".btn-hapus",function(){
            hapus([$(this).data("id")]);
        })

        //menghapus banyak data dari ceklist datatables 	
        $(".hapusTerpilih").click(function () {
            let idTerpilih = [];
            $('.cekbaris').each(function (i) {
                if ($(this).is(':checked')) {
                    idTerpilih.push($(this).val());
                }
            });                
            hapus(idTerpilih);
        })
        //--- akhir hapus ---

        $("#fweb").submit(function(e) {
            e.preventDefault();
            var form = $(this)[0];
            let formVal = new FormData(form);
            formVal.append("foto", $("#foto")[0].files[0]); 
            let isValid = form.checkValidity();
            if(isValid){
                appAjaxUpload('{{ route("akun-create") }}', formVal).done(function(vRet) {
                    if(vRet.status){
                        if(vRet.insert)
                            resetform();
                        reloadTable();
                        $("#nama").focus();
                    }
                    showmymessage(vRet.messages,vRet.status);
                });
            }
        });    

        //$(document).on('click','.gambardet',function(){
          //  var time = (new Date()).getTime();
          //  $("#fotoprofil").attr("src","");
        //});

</script>
@endsection
