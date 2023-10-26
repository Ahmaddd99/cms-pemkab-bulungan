@extends('layouts.master')
@section('content')
@include('galleries.modal')
    <div class="row">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <h5>Galeri Konten</h5>
                    <button type="button" class="btn btn-success btn-sm btn-add-gallery" data-toggle="modal" data-target="#ModalGallery">Tambah Foto</button>
                </div>
            </div>
            <div class="card-body">
                <table id="TableGallery" class="table table-bordered table-hover table-striped" style="width: 100%">
                    <thead>
                        <tr style="font-size: 0.9em">
                            <th>Nama Konten</th>
                            <th>Gambar</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody style="font-size: 0.8em"></tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
@section('js')
    let datatable = $("#TableGallery").DataTable({
        "responsive": true,
        "processing": true,
        "searching": true,
        "paging": true,
        "language": {
            processing: '<span style="font-size:22px"><i class="fa fa-spinner fa-spin fa-fw"></i> Loading..</span>'
        },
        "serverSide": true,
        "ajax": "{{ route('gallery.datatables') }}",
        "info": true,
        "order": [],
        "dom": "frtip",
        "pageLength": 10,
        "aLengthMenu": [
            [10, 25, 50, 100, -1],
            [10, 25, 50, 100, "All"]
        ],
        "columns": [{
                data: "content",
                name: "content",
                width: "30%"
            },
            {
                data: "image",
                name: "image",
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
            },
            {
                "width": "",
                "targets": 2,
                "className": "dt-center"
            }
        ]
    });

    // partials
    function afterAction() {
        $('#FormGallery')[0].reset();
        $('#ModalGallery').modal('hide');
    }

    function reloadDatatable() {
        $('#TableGallery').DataTable().ajax.reload(null, false);
    }

    $("#ModalGallery").on("show.bs.modal", function() {
        $(".id").val("");
        $('#FormGallery')[0].reset();
        getContent();
    });

    $("#ModalGallery").on("hidden.bs.modal", function(e) {
        e.preventDefault();
        $(".id").val("");
        afterAction();
        reloadDatatable();
        $('#gambar-gallery').empty();
    });

    $(".add-new-gallery").on("click", function() {
        $(".id").val("");
        afterAction();
        getContent();
    });

    $('.close-gallery').on("click", function() {
        $('.id').val("");
        afterAction();
        reloadDatatable();
        $('#gambar-gallery').empty();
    });
    // end partials

    function getContent(){
        axios.get('./content')
        .then(function(response){
            let data = response.data.content;
            let option = "<option selected disabled>Pilih konten</option>";
            $.each(data, function(key, val){
                option += `<option value="${val.id}">${val.title}</option>`
            })
            $("#content_id").html(option);
        })
    }

    // post
    $("#FormGallery").on("submit", function(e){
        e.preventDefault();
        let id = $(".id").val();
        let content_id = $(".content_id").val();
        let image = $(".image")[0].files[0];

        let data = {
            id:id,
            content_id:content_id,
            image:image
        }
        postData(data);
    });

    function postData(data){
        axios.post('{{route('gallery.post')}}', data, {
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

    // get
    $(document).on("click", ".btn-edit-gallery", function(){
        let id = $(this).data('id');
        getData(id);
    });

    function getData(id){
        axios.get('./get/' + id)
            .then(function(response){
                let data = response.data.gallery;
                console.log(data);
                $(".id").val(data.id);
                $("#content_id").val(data.content_id).trigger("change");

                let gambar = `<div class="form-group mt-3">
                    <label for="image">*Gambar sebelumnya</label><br>
                    <img src="${data.image}" alt="Gambar belum tersedia" style="width: 10em">
                </div>`
                $("#gambar-gallery").html(gambar);
            })
    }
    // end get

    // delete
    $(document).on("click", ".btn-delete-gallery", function(){
        let id = $(this).data('id');
        let conf = confirm("Apakah anda yakin ingin menghapus data ini?");
        if(conf){
            deleteData(id);
        }
    });

    function deleteData(id){
        axios.delete('./delete/' + id)
            .then(function(response){
                console.log(response);
                reloadDatatable();
            })
            .catch(function(error){
                console.log("Terjadi masalah saat menghapus data ", error);
            })
    }
@endsection
