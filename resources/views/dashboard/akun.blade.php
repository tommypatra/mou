@extends('dashboard')

@section('scriptCss')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" type="text/css" href="plugins/datatables/datatables.min.css"/>
@endsection

@section('pagetitle')
    <div class="pagetitle">
        <h1>Akun</h1>
        <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('login.lnk') }}">Home</a></li>
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
                <table class="table table-bordered datatable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Jenis Kelamin</th>
                            <th>Alamat</th>
                            <th>HP</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

<div class="modal fade" id="modal-form-web" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form id="fweb" class="row g-3 needs-validation" novalidate>
            @csrf
            <input type="hidden" name="id" id="id">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">FORM APLIKASI</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body row">

                    <div class="col-8">
                        <label for="grup" class="form-label">Nama Grup</label>
                        <div class="input-group has-validation">
                            <input type="text" name="grup" class="form-control" id="grup" required>
                            <div class="invalid-feedback">Ketik grup anda!</div>
                        </div>
                    </div>

                    <div class="col-4">
                        <label for="aktif" class="form-label">Status</label>
                        <div class="input-group has-validation">
                            <select name="aktif" id="aktif" class="form-control" required>
                            </select>
                            <div class="invalid-feedback">pilih status aktif!</div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </form>
    </div>
</div>

@section("scriptJs")
    <script type="text/javascript" src="plugins/datatables/datatables.min.js"></script>
    <script src="js/select2lib.js"></script>
    <script type="text/javascript">
        sel2_aktif4("#aktif","#modal-form-web");

        var dtTable = $('.datatable').DataTable({
            processing: true,
            autoWidth: false,
            serverSide: true,
            lengthMenu: [
                [25, 50, 75, -1],
                ["25", "50", "75", "Semua"]
            ],
            ajax: {
                url: "{{ route('akun-read.lnk') }}",
                dataType: "json",
                type: "POST",
                data: function (d) {
                    d._token = $("meta[name='csrf-token']").attr("content");  
                },
                dataSrc: function (json) {
                    return json.data;
                },
            },
            dom: '<"row"<"col-sm-6"B><"col-sm-6"f>> rt <"row"<"col-sm-4"l><"col-sm-4"i><"col-sm-4"p>>',
            buttons: [
                {
                    extend: 'copyHtml5',
                    title : $(document).attr('title'),
                    exportOptions: {
                        columns: [0,1,2]
                    }
                },
                {
                    extend: 'excelHtml5',
                    title : $(document).attr('title'),
                    exportOptions: {
                        columns: [0,1,2]
                    }
                },
                {
                    extend: 'print',
                    title : $(document).attr('title'),
                    exportOptions: {
                        columns: [0,1,2]
                    }
                },        
            ],
            columns: [
                {data: 'DT_RowIndex', width: "5%"},
                {data: 'nama'},
                {data: 'email'},
                {data: 'kel'},
                {data: 'alamat'},
                {data: 'nohp'},
                {data: 'aktif', },
                {data: 'action', className: "text-center", width: "20%", orderable: false, searchable: false},
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
            $('#aktif').val("").trigger('change');
            $("#fweb").removeClass("was-validated");
        };

        function fillform(dt){
            $('#id').val(dt.id);
            $('#grup').val(dt.grup);
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
            $("#grup").focus();
        });

        //ganti
        $(document).on("click",".btn-ganti",function(){
            resetform();
            var formVal={_token:$("input[name=_token]").val(),id:$(this).data("id")};
            appAjax("{{ route('akun-update.lnk') }}", formVal).done(function(vRet) {
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

        //hapus
        $(document).on("click",".btn-hapus",function(){
            if(confirm("apakah anda yakin?")){
                var formVal={_token:$("input[name=_token]").val(),id:$(this).data("id")};
                appAjax("{{ route('akun-delete.lnk') }}", formVal).done(function(vRet) {
                    if(vRet.status){
                        reloadTable();
                    }
                    showmymessage(vRet.messages,vRet.status);
                });                
            }
        })

        $("#fweb").submit(function(e) {
            e.preventDefault();
            var form = $("#fweb")[0];
            let formVal = $(this).serialize();
            let isValid = form.checkValidity();
            if(isValid){
                appAjax('{{ route("grup-create.lnk") }}', formVal).done(function(vRet) {
                    if(vRet.status){
                        if(vRet.insert)
                            resetform();
                        reloadTable();
                        $("#grup").focus();
                    }
                    showmymessage(vRet.messages,vRet.status);
                });
            }
        });    


</script>
@endsection
