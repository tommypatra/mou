@extends('dashboard')

@section('head')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" type="text/css" href="plugins/datatables/datatables.min.css"/>
@endsection

@section('pagetitle')
    <div class="pagetitle">
        <h1>Jenis Pihak</h1>
        <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('login') }}">Home</a></li>
            <li class="breadcrumb-item">Akun</li>
            <li class="breadcrumb-item active">Jenis Pihak</li>
        </ol>
        </nav>
    </div><!-- End Page Title -->
@endsection

@section('container')

<div class="col-lg-12">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center mb-3">            
            <h3 class="card-title d-flex">Data Jenis Pihak</h3>
            <div class="list-inline d-flex">
                <div class="buttons">
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
                            <th>Jenis Pihak</th>
                            <th>Status</th>
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
                        <div class="col-8">
                            <label for="jenis" class="form-label">Jenis Pihak</label>
                            <div class="input-group has-validation">
                                <input type="text" name="jenis" class="form-control" id="jenis" required>
                                <div class="invalid-feedback">Ketik jenis anda!</div>
                            </div>
                        </div>

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
@endsection

@section("scriptJs")
    <script type="text/javascript" src="plugins/datatables/datatables.min.js"></script>
    <script src="js/select2lib.js"></script>
    <script type="text/javascript">

        sel2_aktif2("#aktif");

        var dtTable = $('.datatable').DataTable({
            processing: true,
            autoWidth: false,
            serverSide: true,
            lengthMenu: [
                [25, 50, 75, -1],
                ["25", "50", "75", "Semua"]
            ],
            ajax: {
                url: "{{ route('jenispihak-read') }}",
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
                {data: 'no'},
                {data: 'jenis'},
                {data: 'aktif', },
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
            $('#jenis').val(dt.jenis);
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
            $("#jenis").focus();
        });

        //ganti
        $(document).on("click",".btn-ganti",function(){
            resetform();
            var formVal={_token:$("input[name=_token]").val(),id:$(this).data("id")};
            appAjax("{{ route('jenispihak-update') }}", formVal).done(function(vRet) {
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
                appAjax("{{ route('jenispihak-delete') }}", formVal).done(function(vRet) {
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
                appAjax('{{ route("jenispihak-create") }}', formVal).done(function(vRet) {
                    if(vRet.status){
                        if(vRet.insert)
                            resetform();
                        reloadTable();
                        $("#jenis").focus();
                    }
                    showmymessage(vRet.messages,vRet.status);
                });
            }
        });    


</script>
@endsection
