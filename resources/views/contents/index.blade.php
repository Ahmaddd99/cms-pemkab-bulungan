@extends('layouts.master')
@section('content')
@include('contents.modal')
    <div class="row">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <h5>Konten</h5>
                    <button type="button" class="btn btn-success btn-sm btn-add-content" data-toggle="modal" data-target="#ModalContent">Tambah Konten</button>
                </div>
            </div>
            <div class="card-body">
                <table id="TableContent" class="table table-bordered table-hover table-striped" style="width: 100%">
                    <thead>
                        <tr style="font-size: 0.9em">
                            <th>Kategori</th>
                            <th>Subkategori</th>
                            <th>Gambar</th>
                            <th>Judul</th>
                            <th>Body</th>
                            <th>Meta</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody style="0.8em"></tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
@section('js')
    let datatable = $("#TableContent").DataTable({
        "responsive": true,
        "processing": true,
        "searching": true,
        "paging": true,
        "language": {
            processing: '<span style="font-size:22px"><i class="fa fa-spinner fa-spin fa-fw"></i> Loading..</span>'
        },
        "serverSide": true,
        "ajax": "{{ route('content.datatables') }}",
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
                data: "subcategory_id",
                name: "subcategory_id",
                width: ""
            },
            {
                data: "image",
                name: "image",
                width: ""
            },
            {
                data: "title",
                name: "title",
                width: ""
            },
            {
                data: "body",
                name: "body",
                width: ""
            },
            {
                data: "meta",
                name: "meta",
                width: ""
            },
            {
                data: "actions",
                name: "actions",
                width: ""
            },
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
                "targets": 2
            },
            {
                "width": "",
                "targets": 3
            },
            {
                "width": "",
                "targets": 4
            },
            {
                "width": "",
                "targets": 5
            },
            {
                "width": "",
                "targets": 6
            },
        ]
    });

    // partials
    function afterAction() {
        $('#FormContent')[0].reset();
        $('#ModalContent').modal('hide');
    }

    function reloadDatatable() {
        $('#TableContent').DataTable().ajax.reload(null, false);
    }

    $("#ModalContent").on("show.bs.modal", function() {
        $(".id").val("");
        $('#FormContent')[0].reset();
        categoryId();
        subcategoryId();
        feature();
    });

    $("#ModalContent").on("hidden.bs.modal", function(e) {
        e.preventDefault();
        $(".id").val("");
        afterAction();
        reloadDatatable();
        $('#gambar-content').empty();
    });

    $(".add-new-content").on("click", function() {
        $(".id").val("");
        afterAction();
    });

    $('.close-content').on("click", function() {
        $('.id').val("");
        afterAction();
        reloadDatatable();
        $('#gambar-content').empty();
    });
    // end partials

    // select 2
    function categoryId(){
        axios.get('./category')
            .then(function(response){
                let data = response.data.category;
                let option = "<option selected disabled>Pilih Kategori</option>";
                $.each(data, function(key, val){
                    option += `<option value="${val.id}">${val.name}</option>`
                });
                $("#category_id").html(option);
            })
    }

    function subcategoryId(){
        axios.get('./subcategory')
            .then(function(response){
                let data = response.data.subcategory;
                let option = "<option selected disabled>Pilih Subkategori</option>";
                $.each(data, function(key, val){
                    option += `<option value="${val.id}">${val.name}</option>`
                });
                $("#subcategory_id").html(option);
            })
    }

    function feature(){
        axios.get('./feature')
            .then(function(response){
                let data = response.data.feature;
                let option = "<option selected disabled>Pilih Fitur</option>";
                $.each(data, function(key, val){
                    option += `<option value="${val.id}">${val.title}</option>`
                });
                $("#feature_id").html(option);
            })
    }
    // end select 2

    // post
    $("#FormContent").on("submit", function(e){
        e.preventDefault();
        let id = $(".id").val();
        let category_id = $(".category_id").val();
        let subcategory_id = $(".subcategory_id").val();
        let title = $(".title").val();
        let body = $(".body").val();
        let meta = $(".meta").val();
        let image = $(".image-content")[0].files[0];
        let feature_id = $(".feature_id").val();
        let id_featureValue = $(".id_featureValue").val();

        let data = {
            id:id,
            category_id:category_id,
            title:title,
            body:body,
            meta:meta,
            image:image,
            feature_id:feature_id,
            id_featureValue:id_featureValue
        }
        if(subcategory_id){
            data.subcategory_id = subcategory_id;
        }

        postContent(data);
    })

    function postContent(data){
        axios.post('{{route('content.post')}}', data, {
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
            console.log("Terjadi kesalahan saat post data ", error);
        });
    }
    // end post

    // edit
    $(document).on("click", ".btn-edit-content", function(){
        let id = $(this).data('id');
        console.log(id);
        getContent(id)
    });

    function getContent(id){
        axios.get('./get/' + id)
            .then(function(response){
                let data = response.data.content;
                let feature = response.data.content.feature_value;
                console.log(feature);
                console.log(data);
                $('.id').val(data.id);
                $('.category_id').val(data.category_id).trigger('change');
                $('.subcategory_id').val(data.subcategory_id).trigger('change');
                $('.title').val(data.title);
                $('.body').val(data.body);
                $('.meta').val(data.meta);
                let gambar = `<div class="form-group">
                    <label for="">*Gambar yang sudah ada sebelumnya</label><br>
                     <img src="${data.image}" alt="belum ada gambar sebelumnya" style="width: 15em"></div>`;
                 $('#gambar-content').html(gambar);

                $('.feature_id').val(feature.feature_id).trigger('change');
                $('.id_featureValue').val(feature.id);

                console.log(data.image);

            })
            .catch(function(error){
                console.log("terjadi kesalahan saat mengambil data ", error);
            });
    }
    // end get

    // delete
    $(document).on("click", ".btn-delete-content", function(){
        let id = $(this).data('id');
        let conf = confirm("apakah anda yakin ingin menghapus data ini?");
        if(conf){
            deleteContent(id);
        }
    });

    function deleteContent(id){
        axios.delete('./delete/' + id)
            .then(function(response){
                console.log(response);
                reloadDatatable();
            })
        }
@endsection
