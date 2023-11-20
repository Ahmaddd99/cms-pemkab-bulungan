@extends('layouts.master')
@section('content')
<div class="row">
    <div class="col">
        <strong>Pengaturan</strong>
        <h1 class="h3 mb-3"><strong>Kelola Fitur</strong></h1>
    </div>
</div>
@include('features.modal')
    <div class="row">
        <div class="card">
            <div class="card-header">
                <button type="button" class="btn btn-success btn-sm btn-add-feature" data-toggle="modal" data-target="#ModalFeature"><i class="fa-solid fa-plus"></i> Tambah Fitur</button>
            </div>
            <div class="card-body">
                <table id="TableFeature" class="table table-bordered table-hover table-striped" style="width: 100%">
                    <thead>
                        <tr style="font-size: 0.9em">
                            <th>Gambar</th>
                            <th>Judul</th>
                            <th>Order</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody style="font-size: 0.8em">
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
@section('js')
    let datatable = $("#TableFeature").DataTable({
        "responsive": true,
        "processing": true,
        "searching": true,
        "paging": true,
        "language": {
            processing: '<span style="font-size:22px"><i class="fa fa-spinner fa-spin fa-fw"></i> Loading..</span>',
            search : '',
            searchPlaceholder: "Cari Fitur"
        },
        "serverSide": true,
        "ajax": "{{ route('submenu.feature.datatables') }}",
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
                width: "15%"
            },
            {
                data: "title",
                name: "title",
                width: ""
            },
            {
                data: "order",
                name: "order",
                width: ""
            },
            {
                data: "published",
                name: "published",
                width: "13%"
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
            },
        ]
    });

    // partials
    function afterAction() {
        $('#FormFeature')[0].reset();
        $('#ModalFeature').modal('hide');
    }

    function reloadDatatable() {
        $('#TableFeature').DataTable().ajax.reload(null, false);
    }

    $("#ModalFeature").on("show.bs.modal", function() {
        $(".id").val("");
        $('#FormFeature')[0].reset();
    });

    $("#ModalFeature").on("hidden.bs.modal", function(e) {
        e.preventDefault();
        $(".id").val("");
        afterAction();
        reloadDatatable();
        $('.published').val('').trigger('change');
        $("#preview-feature").addClass('d-none');
        $("#preview-feature img").empty();
    });

    $(".add-new-feature").on("click", function() {
        $(".id").val("");
        afterAction();
    });

    $('.close-feature').on("click", function() {
        $('.id').val("");
        afterAction();
        reloadDatatable();
    });
    // end partials

    // preview
    $('#image').on('change', function() {
        $('#preview-feature').removeClass('d-none');
        const file = this.files[0];
        if(file) {
            let reader = new FileReader();
            reader.onload = function(event) {
                $('#preview-feature').find('img').attr('src', event.target.result);
            }
            reader.readAsDataURL(file);
        }
    });

    $("#FormFeature").on("submit", function(e){
        e.preventDefault();
        let data = new FormData($(this)[0]);
        postFeature(data);
    });

    function postFeature(data){
        axios.post('{{route('submenu.feature.post')}}', data, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        })
        .then(function(response){
            Swal.fire({
                title: "Berhasil!",
                text: "Data yang anda masukan tersimpan dengan baik!",
                icon: "success"
            });
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
    $(document).on("click", ".btn-edit-feature", function(){
        let id = $(this).data('id');
        editFeature(id);
    });

    function editFeature(id){
        axios.get('./get/' + id)
            .then(function(response){
                let data = response.data.feature;
                console.log(data);
                $(".id").val(data.id);
                $(".title-feature").val(data.title);
                $(".order").val(data.order);
                $(".published").val(data.published).trigger("change");

                $(".current_image_feature").val(data.image);
                $("#preview-feature").removeClass('d-none');
                let gambar = '/feature/' + data.image;
                //console.log(gambar);
                $("#preview-feature").find('img').attr('src' , gambar);
            })
    }
    // end edit

    // delete
    $(document).on("click", ".btn-delete-feature", function(){
        let id = $(this).data('id');
        Swal.fire({
            title: "Anda yakin?",
            text: "Data yang sudah terhapus tidak bisa dikembalikan!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            cancelButtonText: "Kembali",
            confirmButtonText: "Hapus!"
        }).then((result) => {
            if (result.isConfirmed) {
                deleteFeature(id);
            }
        });
    });

    function deleteFeature(id){
        axios.delete('./delete/' + id)
            .then(function(response){
                Swal.fire({
                    title: "Terhapus!",
                    text: "Data anda berhasil terhapus!",
                    icon: "success"
                });
                reloadDatatable();
            })
            .catch(function(error){
                console.log("Terjadi kesalahan saat menghapus data ", error);
            });
    }

@endsection
