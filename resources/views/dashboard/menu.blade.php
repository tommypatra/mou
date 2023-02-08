@extends('dashboard')

@section('head')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" type="text/css" href="plugins/datatables/datatables.min.css"/>
    <link rel="stylesheet" type="text/css" href="plugins/select2-to-tree/src/select2totree.css"/>
    
@endsection

@section('pagetitle')
    <div class="pagetitle">
        <h1>Menu</h1>
        <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('login') }}">Home</a></li>
            <li class="breadcrumb-item">Akun</li>
            <li class="breadcrumb-item active">Menu</li>
        </ol>
        </nav>
    </div><!-- End Page Title -->
@endsection

@section('container')

<div class="col-lg-12">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center mb-3">            
            <h3 class="card-title d-flex">Data Menu</h3>
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
                            <th>Grup</th>
                            <th>Menu</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
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
                            <label for="grup_id" class="form-label">Grup</label>
                            <select name="grup_id" id="grup_id" required>
                            </select>
                            <div class="invalid-feedback">pilih grup!</div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-8">
                            <label for="modul_id" class="form-label">Modul</label>
                            <select name="modul_id" id="modul_id" required>
                            </select>
                            <div class="invalid-feedback">pilih modul!</div>
                        </div>
                        <div class="col-2">
                            <label for="modul_id" class="form-label">Urut</label>
                            <input type="number" name="urut" class="form-control" id="urut" required>
                            <div class="invalid-feedback">masukan no urut!</div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-4">
                            <label for="menu_id" class="form-label">Parent Menu</label>
                            <div class="input-group has-validation">
                                <select name="menu_id" id="menu_id">
                                </select>
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
    <script type="text/javascript" src="js/select2lib.js"></script>
    <script type="text/javascript" src="plugins/select2-to-tree/src/select2totree.js"></script>
    
    <script type="text/javascript">

        sel2_aktif2("#aktif");
        sel2_datalokal("#grup_id",  {!! $dtgrp !!} , "#modal-form-web");
        sel2_datalokal("#modul_id",  {!! $dtmodul !!} , "#modal-form-web");
        sel2_datalokal("#menu_id",  {} , "#modal-form-web",true);

        var dtTable = $('.datatable').DataTable({
            processing: true,
            autoWidth: false,
            serverSide: true,
            pageLength: 25,
	        deferRender: true,            
            lengthMenu: [
                [25, 50, 75, -1],
                ["25", "50", "75", "Semua"]
            ],
            ajax: {
                url: "{{ route('menu-read') }}",
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
                {data: 'no', width:"3%",},
                {data: 'grup', width:"20%",},
                {data: 'dtmenu'},
                {data: 'aktif', width:"3%", },
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
            $('#grup_id').val("").trigger('change');
            $('#modul_id').val("").trigger('change');
            $('#aktif').val("1").trigger('change');
            $("#fweb").removeClass("was-validated");
        };

        function fillform(dt){
            $('#id').val(dt.id);
            $('#menu').val(dt.menu);
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
            $("#menu").focus();
        });

        $('#grup_id').on("change", function(e) { 
            let vid =$(this).val();
            let formVal={_token:$("input[name=_token]").val(),id:vid};
            //$("#menu_id").select2('data', null);
            $('#menu_id').empty();
            if(vid>0){
                appAjax("{{ route('menu-search') }}", formVal).done(function(vRet) {
                    $("#menu_id").append($('<option>', {value:'', text: ''}));
                    $.each( vRet.data, function( key, dp ) {
                        $("#menu_id").append($('<option>', {value:dp.id, text: dp.text}));
                    });
                });
            }
        })

        //fungsi umum menghapus
        function hapus(idTerpilih){
            var formVal={_token:$("input[name=_token]").val(),id:idTerpilih};
            if(idTerpilih.length > 0 && confirm("apakah anda yakin?")){
                appAjax("{{ route('menu-delete') }}", formVal).done(function(vRet) {
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

        $("#fweb").submit(function(e) {
            e.preventDefault();
            var form = $("#fweb")[0];
            let formVal = $(this).serialize();
            let isValid = form.checkValidity();
            if(isValid){
                appAjax('{{ route("menu-create") }}', formVal).done(function(vRet) {
                    if(vRet.status){
                        if(vRet.insert)
                            resetform();
                        reloadTable();
                        $("#menu").focus();
                    }
                    showmymessage(vRet.messages,vRet.status);
                });
            }
        });    


</script>
@endsection
