@extends('layouts.master')
@section('content')
<div class="row">
    <div class="col">
        <strong>Pengaturan</strong>
        <h1 class="h3 mb-3"><strong>Kelola Galeri Konten</strong></h1>
    </div>
</div>
@include('galleries.modal')
    <div class="row">
        <div class="card">
            <div class="card-header">
                <button type="button" class="btn btn-success btn-sm btn-add-gallery" data-toggle="modal" data-target="#ModalGallery"><i class="fa-solid fa-plus"></i> Tambah Foto</button>
            </div>
            <div class="card-body">
                <table id="TableGallery" class="table table-bordered table-hover table-striped" style="width: 100%">
                    <thead>
                        <tr style="font-size: 0.9em">
                            <th>Gambar</th>
                            <th>Nama Konten</th>
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
            processing: '<span style="font-size:22px"><i class="fa fa-spinner fa-spin fa-fw"></i> Loading..</span>',
            search : '',
            searchPlaceholder: "Cari Gambar Berdasarkan Konten"
        },
        "serverSide": true,
        "ajax": "{{ route('submenu.gallery.datatables') }}",
        "info": true,
        "order": [],
        "dom": "frtip",
        "pageLength": 10,
        "aLengthMenu": [
            [10, 25, 50, 100, -1],
            [10, 25, 50, 100, "All"]
        ],
        "columns": [
            {
                data: "image",
                name: "image",
                width: "50%"
            },
            {
                data: "content",
                name: "content",
                width: "30%"
            },
            {
                data: "actions",
                name: "actions",
                width: "20%"
            }
        ],
        "columnDefs": [{
                "width": "",
                "targets": 0,
                "className": "dt-center"
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
        // let id = $(".id").val();
        // let content_id = $(".content_id").val();
        // let image = $(".image")[0].files[0];

        // let data = {
        //     //id:id,
        //     content_id:content_id,
        //     image:image
        // }
        let data = new FormData($(this)[0]);
        console.log(data);
        postData(data);
    });

    function postData(data){
        axios.post('{{route('submenu.gallery.post')}}', data, {
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
        getkoleksi(id);
    });

    function getData(id){
        axios.get('./get/' + id)
            .then(function(response){
                let data = response.data.gallery;
                console.log(data);
                $(".id").val(data.id);
                $("#content_id").val(data.id).trigger("change");

                $(".test-galleries").val(data.id);
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

    // koleksi
    function getkoleksi(contentId){
        axios.get(`../gallery/${contentId}/koleksi`)
            .then(function(response){
                let data = response.data.gallery;
                console.log(data);
                koleksi = '';
                $.each(data, function(key, val){
                    koleksi += `<div class="content-gallery-image position-relative gallery-item mb-3" style="overflow:hidden;float:left">
                        <button type="button" class="btn btn-sm btn-danger position-absolute delete-gallery" data-gallery-id="${val.id}" data-content-id="${contentId}" style="top:0;left:0"><i class="bi bi-trash"></i></button>
                        <img class="img-fluid float-left img-thumbnail mx-1" style="width:100px" src="../../gallery/${val.image}" alt="">
                    </div>
                    `;
                });
                $("#koleksi-galeri").removeClass('d-none').html(koleksi);
            });
    }

    $(document).on("click", ".delete-gallery", function(){
        let idgallery = $(this).data('gallery-id');
        let idcontent = $(this).data('content-id');
        let conff = confirm("Anda yakin ingin menghapus data ini?");
        if(conff){
            deleteData(idgallery);
            getkoleksi(idcontent);
        }
    });

@endsection
