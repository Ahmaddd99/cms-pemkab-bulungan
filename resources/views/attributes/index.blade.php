@extends('layouts.master')
@section('content')
<div class="row">
    <div class="col">
        <strong>Pengaturan</strong>
        <h1 class="h3 mb-3"><strong>Kelola Label</strong></h1>
    </div>
</div>
@include('attributes.modal')
    <div class="row">
        <div class="card">
            <div class="card-header">
                <button type="button" class="btn btn-success btn-sm btn-add-attribute" data-toggle="modal" data-target="#ModalAttribute"><i class="fa-solid fa-plus"></i> Tambah Atribut</button>
            </div>
            <div class="card-body">
                <table id="TableAttributes" class="table table-bordered table-hover table-striped"
                    style="width: 100%">
                    <thead>
                        <tr>
                            <th>Nama Atribut</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
@section('js')
    let datatable = $("#TableAttributes").DataTable({
        "responsive": true,
        "processing": true,
        "searching": true,
        "paging": true,
        "language": {
            processing: '<span style="font-size:22px"><i class="fa fa-spinner fa-spin fa-fw"></i> Loading..</span>',
            search : '',
            searchPlaceholder: "Cari Label"
        },
        "serverSide": true,
        "ajax": "{{ route('submenu.attribute.datatables') }}",
        "info": true,
        "order": [],
        "dom": "frtip",
        "pageLength": 10,
        "aLengthMenu": [
            [10, 25, 50, 100, -1],
            [10, 25, 50, 100, "All"]
        ],
        "columns": [{
                data: "name",
                name: "name",
                width: "50%"
            },
            {
                data: "actions",
                name: "actions",
                width: "10%"
            }
        ],
        "columnDefs": [{
                "width": "",
                "targets": 0
            },
            {
                "width": "",
                "targets": 1,
                "className": "dt-center"
            }
        ]
    });

    // partials
    function afterAction() {
        $('#FormAttribute')[0].reset();
        $('#ModalAttribute').modal('hide');
    }

    function reloadDatatable() {
        $('#TableAttributes').DataTable().ajax.reload(null, false);
    }

    $("#ModalAttribute").on("show.bs.modal", function() {
        $(".id").val("");
        $('#FormAttribute')[0].reset();
    });

    $("#ModalAttribute").on("hidden.bs.modal", function(e) {
        e.preventDefault();
        $(".id").val("");
        afterAction();
        reloadDatatable();
    });

    $(".add-new-banner").on("click", function() {
        $(".id").val("");
        afterAction();
    });

    $('.close-banner').on("click", function() {
        $('.id').val("");
        afterAction();
        reloadDatatable();
    });
    // end partials

    $("#FormAttribute").on("submit", function(e){
        e.preventDefault();
        postAttribute($(this).serialize());
    });

    function postAttribute(data){
        axios.post('{{route('submenu.attribute.post')}}', data)
            .then(function(response){
                console.log(response);
                afterAction();
                reloadDatatable();
            })
            .catch(function(error){
                console.log("Terjadi masalah saat post data ", error);
            });
    }

    $(document).on("click", ".btn-edit-attribute", function(){
        let id = $(this).data('id');
        //console.log(id);
        getAttribute(id);
    });

    function getAttribute(id){
        axios.get('./get/' + id)
            .then(function(response){
                let data = response.data.attribute;
                $(".id").val(data.id);
                $(".name").val(data.name);
            })
    }

    $(document).on("click", ".btn-delete-attribute", function(){
        let id = $(this).data("id");
        let conf = confirm("Apakah anda yakin ingin menghapus data ini?");
        if(conf){
            deleteAttribute(id);
        }
    });

    function deleteAttribute(id){
        axios.delete('./delete/' + id)
            .then(function(response){
                reloadDatatable();
            })
    }
@endsection
