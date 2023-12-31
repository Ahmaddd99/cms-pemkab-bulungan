@extends('layouts.master')
@section('content')
<div class="row">
    <div class="col">
        <strong>Konten</strong>
        <h1 class="h3 mb-3"><strong>Kelola Subkategori</strong></h1>
    </div>
</div>
@include('subcategories.modal')
    <div class="row">
        <div class="card">
            <div class="card-header">
                <button type="button" class="btn btn-success btn-sm btn-add-subcategory" data-toggle="modal" data-target="#ModalSub"><i class="fa-solid fa-plus"></i> Tambah Subkategori</button>
            </div>
            <div class="card-body">
                <table id="TableSub" class="table table-bordered table-hover table-striped" style="width: 100%">
                    <thead>
                        <tr>
                            <th>Gambar</th>
                            <th>Kategori</th>
                            <th>Nama Subkategori</th>
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
            processing: '<span style="font-size:22px"><i class="fa fa-spinner fa-spin fa-fw"></i> Loading..</span>',
            search : '',
            searchPlaceholder: "Cari Subkategori"
        },
        "serverSide": true,
        "ajax": "{{ route('menu.subcategory.datatables') }}",
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
                data: "category",
                name: "category",
                width: "21%"
            },
            {
                data: "name",
                name: "name",
                width: "21%"
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
        $("#gambar-subcategory").addClass('d-none');
        $("#gambar-subcategory img").attr('src', '');
        $("#gambar-placeholder-subcategory img").attr('src', '{{asset('placeholder/no_image.png')}}');
    });

    $(".add-new-subcategory").on("click", function() {
        $(".id").val("");
        afterAction();
    });

    $('.close-subcategory').on("click", function() {
        $('.id').val("");
        afterAction();
        reloadDatatable();
    });
    // end partials

    // preview
    $('#image-subcategory').on('change', function() {
        $('#gambar-subcategory').removeClass('d-none');
        const file = this.files[0];
        if(file) {
            let reader = new FileReader();
            reader.onload = function(event) {
                $('#gambar-subcategory').find('img').attr('src', event.target.result);
            }
            reader.readAsDataURL(file);
        }
    });

    $('.image-placeholder-sub').on('change', function() {
        $('#gambar-placeholder-subcategory').removeClass('d-none');
        const file = this.files[0];
        if(file) {
            let reader = new FileReader();
            reader.onload = function(event) {
                $('#gambar-placeholder-subcategory').find('img').attr('src', event.target.result);
            }
            reader.readAsDataURL(file);
        }
    });

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
        let data = new FormData($(this)[0]);
        postSubcategory(data);
    });

    function postSubcategory(data){
        axios.post('{{route('menu.subcategory.post')}}', data, {
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
            console.log("Gagal saat post data ", error);
        });
    }

    $(document).on("click", ".btn-edit-subcategory", function(){
        let id = $(this).data("id");
        console.log(id);
        getSubcategory(id);
        $('#gambar-placeholder-subcategory img').attr('src', '');
    });

    function getSubcategory(id){
        axios.get('./get/' + id)
            .then(function(response){
                let data = response.data.subcategory;

                $(".id").val(data.id);
                $(".category_id").val(data.category_id).trigger("change");
                $(".name").val(data.name);
                $(".published").val(data.published).trigger("change");

                $('.current_placeholder_subcategory').val('data.image_placeholder');
                $('#gambar-placeholder-subcategory').find('img').attr('src', `../../placeholder/${data.image_placeholder}`);

                $(".current_image_subcategory").val(data.image);
                $("#gambar-subcategory").removeClass('d-none');
                $('#gambar-subcategory').find('img').attr('src' , `../../subcategory/${data.image}`);
            })
    }

    $(document).on("click", ".btn-delete-subcategory", function(){
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
                deleteSubcategory(id);
            }
        });
    })

    function deleteSubcategory(id){
        axios.delete('./delete/' + id)
            .then(function(response){
                Swal.fire({
                    title: "Terhapus!",
                    text: "Subkategori berhasil terhapus!",
                    icon: "success"
                });
                console.log(response);
                reloadDatatable();
            })
            .catch(function(error){
                console.log("Terjadi kesalahan saat menghapus data ", error);
            });
    }
@endsection
