@extends('layouts.master')
@section('content')
@include('subcategories.modal')
    <div class="row">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <h5>Subkategori</h5>
                    <button type="button" class="btn btn-success btn-sm btn-add-subcategory" data-toggle="modal" data-target="#ModalSub">Tambah Subkategori</button>
                </div>
            </div>
            <div class="card-body">
                <table id="TableSub" class="table table-bordered table-hover table-striped" style="width: 100%">
                    <thead>
                        <tr>
                            <th>Kategori</th>
                            <th>Nama</th>
                            <th>Gambar</th>
                            <th>Published</th>
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
    let datatable = $("#TableSub").DataTable({
        "responsive": true,
        "processing": true,
        "searching": true,
        "paging": true,
        "language": {
            processing: '<span style="font-size:22px"><i class="fa fa-spinner fa-spin fa-fw"></i> Loading..</span>'
        },
        "serverSide": true,
        "ajax": "{{ route('subcategory.datatables') }}",
        "info": true,
        "order": [],
        "dom": "frtip",
        "pageLength": 10,
        "aLengthMenu": [
            [10, 25, 50, 100, -1],
            [10, 25, 50, 100, "All"]
        ],
        "columns": [{
                data: "category_id",
                name: "category_id",
                width: ""
            },
            {
                data: "name",
                name: "name",
                width: "30%"
            },
            {
                data: "published",
                name: "published",
                width: "15%"
            },
            {
                data: "image",
                name: "image",
                width: "40%"
            },
            {
                data: "actions",
                name: "actions",
                width: "13%"
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
            },
            {
                "width": "",
                "targets": 2,
                "className": "dt-center"
            },
            {
                "width": "",
                "targets": 3,
                "className": "dt-center"
            },
            {
                "width": "",
                "targets": 4,
                "className": "dt-center"
            }
        ]
    });

    // partials
    function afterAction() {
        $('#FormSub')[0].reset();
        $('#ModalSub').modal('hide');
    }

    function reloadDatatable() {
        $('#TableSub').DataTable().ajax.reload(null, false);
    }

    $("#ModalSub").on("show.bs.modal", function() {
        $(".id").val("");
        $('#FormSub')[0].reset();
        getCategoryId();
    });

    $("#ModalSub").on("hidden.bs.modal", function(e) {
        e.preventDefault();
        $(".id").val("");
        afterAction();
        reloadDatatable();
        $('#gambar-subcategory').empty();
    });

    $(".add-new-subcategory").on("click", function() {
        $(".id").val("");
        afterAction();
    });

    $('.close-subcategory').on("click", function() {
        $('.id').val("");
        afterAction();
        reloadDatatable();
        $('#gambar-subcategory').empty();
    });
    // end partials

    // get category id
    function getCategoryId(){
        axios.get('./categoryid')
            .then(function(response){
                let data = response.data.getCategoryId;
                //console.log(data);
                let option = "<option selected disabled>Pilih Kategori</option>"
                $.each(data, function(key, val){
                    option += `<option value="${val.id}">${val.name}</option>`
                });
                $("#category_id").html(option);
            })
    }
    // end get

    // post subcategory
    $("#FormSub").on("submit", function(e){
        e.preventDefault();
        let id = $(".id").val();
        let category_id = $(".category_id").val();
        let name = $(".name").val();
        let published = $(".published").val();
        let image = $(".image-sub")[0].files[0];

        let data = {
            id:id,
            category_id:category_id,
            name:name,
            published:published,
            image:image
        }
        postSubcategory(data);
    });

    function postSubcategory(data){
        axios.post('{{route('subcategory.post')}}', data, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        })
        .then(function(response){
            console.log(response);
            afterAction();
            reloadDatatable();
        })
        .catch(function(error){
            console.log("Gagal saat post data ", error);
        });
    }

    $(document).on("click", ".btn-edit-subcategory", function(){
        let id = $(this).data("id");
        console.log(id);
        getSubcategory(id);
    });

    function getSubcategory(id){
        axios.get('./get/' + id)
            .then(function(response){
                let data = response.data.subcategory;

                $(".id").val(data.id);
                $(".category_id").val(data.category_id).trigger("change");
                $(".name").val(data.name);
                $(".published").val(data.published);

                let gambar = `<div class="form-group mt-3">
                    <label for="gambar">*Gambar Subkategori Sebelumnya</label><br>
                    <img src="${data.image}" alt="gambar subkategori belum tersedia" id="gambar" style="width: 15em"></div>`;
                $("#gambar-subcategory").html(gambar);
            })
    }

    $(document).on("click", ".btn-delete-subcategory", function(){
        let id = $(this).data('id');
        console.log(id);
        let conf = confirm("Apakah anda yakin ingin menghapus subkategori ini?");
        if(conf){
            deleteSubcategory(id);
        }
    })

    function deleteSubcategory(id){
        axios.delete('./delete/' + id)
            .then(function(response){
                console.log(response);
                reloadDatatable();
            })
            .catch(function(error){
                console.log("Terjadi kesalahan saat menghapus data ", error);
            });
    }
@endsection
