@extends('layouts.master')
@section('content')
    <div class="row">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <h5>Atribut</h5>
                    <button type="button" class="btn btn-success btn-sm btn-add-attribute" data-toggle="modal" data-target="#ModalAttribute">Tambah Atribut</button>
                </div>
            </div>
            <div class="card-body">
                <table id="TableAttributes" class="table table-responsive table-bordered table-hover table-striped"
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

    <!-- Modal -->
    <div id="ModalAttribute" class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog"
        aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <form id="FormAttribute" enctype="multipart/form-data">
            <div class="modal-dialog modal-lg">
                <div class="modal-content" style="margin: 0 auto">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Form Galeri</h5>
                        <button type="button" class="close close-gallery" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" class="form-control id" id="id" name="id" value="">
                        <div class="form-group mb-3">
                            <label class="required" for="name">Nama Atribut</label>
                            <input type="text" name="name" id="name" class="form-control name" placeholder="Tulis nama atribut disini" required></input>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Kembali</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
@section('js')
    let datatable = $("#TableAttributes").DataTable({
        "responsive": true,
        "processing": true,
        "searching": true,
        "paging": true,
        "language": {
            processing: '<span style="font-size:22px"><i class="fa fa-spinner fa-spin fa-fw"></i> Loading..</span>'
        },
        "serverSide": true,
        "ajax": "{{ route('attribute.datatables') }}",
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
        axios.post('{{route('attribute.post')}}', data)
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
