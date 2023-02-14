@extends('dashboard')

@section('head')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" type="text/css" href="plugins/datatables/datatables.min.css"/>
    <link href="plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css" rel="stylesheet">
@endsection

@section('pagetitle')
    <div class="pagetitle">
        <h1>KerjaSama</h1>
        <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('login') }}">Home</a></li>
            <li class="breadcrumb-item">Akun</li>
            <li class="breadcrumb-item active">Kerja Sama</li>
        </ol>
        </nav>
    </div><!-- End Page Title -->
@endsection

@section('container')

<div class="col-lg-12">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center mb-3">            
            <h3 class="card-title d-flex">Data Kerja Sama</h3>
            <div class="list-inline d-flex">
                <div class="buttons">
                    <a href="#" class="btn icon btn-primary btn-filter"><i class="bi bi-funnel"></i></a>
                    <a href="#" class="btn icon btn-primary btn-tambah"><i class="bi bi-plus-circle"></i></a>
                    <a href="#" class="btn icon btn-primary btn-refresh"><i class="bi bi-arrow-clockwise"></i></a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered datatable">
                    <thead>
                        <tr>
                            <th><input type="checkbox" class="cekSemua"></th>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Pihak</th>
                            <th>Kerja Sama</th>
                            <th>Jenis</th>
                            <th>Kategori</th>
                            <th>File Arsip</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
                <button class="btn btn-danger hapusTerpilih"><i class="bi bi-trash"></i> Hapus Terpilih</button>
            </div>
        </div>
    </div>
</div>

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
                <div class="modal-body">

                    <div class="row">
                        <div class="col-4">
                            <label for="jenis_id" class="form-label">Jenis</label>
                            <select name="jenis_id" id="jenis_id" required>
                            </select>
                            <div class="invalid-feedback">pilih jenis kerjasama !</div>
                        </div>

                        <div class="col-8">
                            <label for="pihak_id" class="form-label">Pihak Kerjasama</label>
                            <select name="pihak_id" id="pihak_id" required>
                            </select>
                            <div class="invalid-feedback">pilih pihak!</div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <label for="bagian_id" class="form-label">Bagian Internal</label>
                            <select name="bagian_id" id="bagian_id" required>
                            </select>
                            <div class="invalid-feedback">pilih bagian!</div>
                        </div>
                        <div class="col-6">
                            <label for="no_surat_internal" class="form-label">No. Surat Perjanjian</label>
                            <input type="text" name="no_surat_internal" class="form-control" id="no_surat_internal"  required>
                            <div class="invalid-feedback">masukan no. surat perjanjian!</div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <label for="tentang" class="form-label">Judul Kerjasama</label>
                            <textarea name="tentang" class="form-control" id="tentang" rows="3" required></textarea>
                            <div class="invalid-feedback">ketik judul kerjasama !</div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <label for="ruang_lingkup" class="form-label">Ruang Lingkup</label>
                            <textarea name="ruang_lingkup" class="form-control" id="ruang_lingkup" rows="5" ></textarea>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-4">
                            <label for="kategori_id" class="form-label">Kategori</label>
                            <select name="kategori_id" id="kategori_id" required></select>
                            <div class="invalid-feedback">pilih kategori!</div>
                        </div>
                    </div>
                    <hr>

                    <div class="row">
                        <div class="col-4">
                            <label for="tgl" class="form-label">Tanggal Pelaksanaan</label>
                            <input type="text" name="tgl" class="form-control datepicker" id="tgl" value="{{ date('Y-m-d') }}" required>
                            <div class="invalid-feedback">masukan tanggal kegiatan!</div>
                        </div>
                        <div class="col-4">
                            <label for="tgl_berlaku" class="form-label">Tanggal Berlaku</label>
                            <input type="text" name="tgl_berlaku" class="form-control datepicker" id="tgl_berlaku"  value="{{ date('Y-m-d') }}" required>
                            <div class="invalid-feedback">masukan tanggal mulai berlaku!</div>
                        </div>
                        <div class="col-4">
                            <label for="tgl_berakhir" class="form-label">Tanggal Berakhir</label>
                            <input type="text" name="tgl_berakhir" class="form-control datepicker" id="tgl_berakhir" value="{{ date('Y-m-d') }}" required>
                            <div class="invalid-feedback">masukan tanggal berakhir!</div>
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
@endsection

@section("scriptJs")
    <script src='plugins/bootstrap-material-moment/moment.js'></script>
    <script src='plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js'></script>
    <script type="text/javascript" src="plugins/datatables/datatables.min.js"></script>
    <script src="js/select2lib.js"></script>
    <script type="text/javascript">

        sel2_datalokal("#bagian_id",  {!! $dtbagian !!} , "#modal-form-web");
        sel2_datalokal("#jenis_id",  {!! $dtjenis !!} , "#modal-form-web");
        sel2_datalokal("#kategori_id",  {!! $dtkategori !!} , "#modal-form-web");
        $("#pihak_id").select2({
            minimumInputLength: 3,
            placeholder: 'Cari pihak',
            dropdownParent: $("#pihak_id").parent(),
            ajax: {
                url: "{{ route('pihak-search') }}",
                dataType: 'json',
                delay: 250,
                type:'post',
                data: function (params) {
                    return {
                        cari: params.term, // search term
                        _token: $("meta[name='csrf-token']").attr("content"),
                    };
                },
                processResults: function (data, params) {
                    return {
                        results: $.map(data, function (item) {
                            return {
                                text: item.pihak,
                                id: item.id
                            }
                        })
                    };
                },
                cache: true
            }
        });

        $('.datepicker').bootstrapMaterialDatePicker({
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
                url: "{{ route('kerjasama-read') }}",
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
            dom: '<"row"<"col-sm-6"B><"col-sm-6"f>> rt <"row"<"col-sm-4"l><"col-sm-4"i><"col-sm-4"p>>',
            buttons: [
                {
                    extend: 'copyHtml5',
                    title : $(document).attr('title'),
                    exportOptions: {
                        columns: [1,2,3]
                    }
                },
                {
                    extend: 'excelHtml5',
                    title : $(document).attr('title'),
                    exportOptions: {
                        columns: [1,2,3]
                    }
                },
                {
                    extend: 'print',
                    title : $(document).attr('title'),
                    exportOptions: {
                        columns: [1,2,3]
                    }
                },        
            ],
            columns: [
                {data: 'cek',className: "text-center", orderable: false, searchable: false},
                {data: 'no', searchable: false},
                {data: 'tgl'},
                {data: 'pihak', orderable: false, searchable: false},
                {data: 'tentang'},
                {data: 'jenis', orderable: false, searchable: false},
                {data: 'kategori', orderable: false, searchable: false},
                {data: 'file_det', orderable: false, searchable: false},
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
            $('#fweb')[0].reset();
            $('#id').val("");
            $('#aktif').val("1").trigger('change');
            $("#fweb").removeClass("was-validated");
        };

        function fillform(dt){
            $('#id').val(dt.id);
            $('#kerjasama').val(dt.kerjasama);
            $('#aktif').val(dt.aktif).trigger('change');
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
            $("#kerjasama").focus();
        });

        //ganti
        $(document).on("click",".btn-ganti",function(){
            resetform();
            var formVal={_token:$("input[name=_token]").val(),id:$(this).data("id")};
            appAjax("{{ route('kerjasama-update') }}", formVal).done(function(vRet) {
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
        })

        //--- mulai hapus ---
        //mengecek semua ceklist
        $(".cekSemua").change(function () {
            $(".cekbaris").prop('checked', $(this).prop("checked"));
        });

        //fungsi umum menghapus
        function hapus(idTerpilih){
            var formVal={_token:$("input[name=_token]").val(),id:idTerpilih};
            if(idTerpilih.length > 0 && confirm("apakah anda yakin?")){
                appAjax("{{ route('kerjasama-delete') }}", formVal).done(function(vRet) {
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
            var form = $("#fweb")[0];
            let formVal = $(this).serialize();
            let isValid = form.checkValidity();
            if(isValid){
                appAjax('{{ route("kerjasama-create") }}', formVal).done(function(vRet) {
                    if(vRet.status){
                        if(vRet.insert)
                            resetform();
                        reloadTable();
                        $("#kerjasama").focus();
                    }
                    showmymessage(vRet.messages,vRet.status);
                });
            }
        });    


</script>
@endsection
