@extends('layouts.master')
@section('content')
@include('categories.modal')
    <div class="row">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <h5>Kategori</h5>
                    <button type="button" class="btn btn-success btn-sm btn-add-category" data-toggle="modal" data-target="#ModalCategory">Tambah Kategori</button>
                </div>
            </div>
            <div class="card-body">
                <table id="TableCategory" class="table table-bordered table-hover table-striped" style="width: 100%">
                    <thead>
                        <tr style="font-size: 0.9em">
                            <th>Nama</th>
                            <th>Publish</th>
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
    let datatable = $("#TableCategory").DataTable({
        "responsive": true,
        "processing": true,
        "searching": true,
        "paging": true,
        "language": {
            processing: '<span style="font-size:22px"><i class="fa fa-spinner fa-spin fa-fw"></i> Loading..</span>'
        },
        "serverSide": true,
        "ajax": "{{ route('category.datatables') }}",
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
                width: "30%"
            },
            {
                data: "published",
                name: "published",
                width: "10%"
            },
            {
                data: "image",
                name: "image",
                width: "40%"
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
            },
            {
                "width": "",
                "targets": 3,
                "className": "dt-center"
            }
        ]
    });

    // partials
    function afterAction() {
        $('#FormCategory')[0].reset();
        $('#ModalCategory').modal('hide');
    }

    function reloadDatatable() {
        $('#TableCategory').DataTable().ajax.reload(null, false);
    }

    $("#ModalCategory").on("show.bs.modal", function() {
        $(".id").val("");
        $('#FormCategory')[0].reset();
    });

    $("#ModalCategory").on("hidden.bs.modal", function(e) {
        e.preventDefault();
        $(".id").val("");
        afterAction();
        reloadDatatable();
        $('#gambar-category').addClass('d-none');
        $('#gambar-category img').attr('src', '');
    });

    $(".add-new-category").on("click", function() {
        $(".id").val("");
        afterAction();
    });

    $('.close-category').on("click", function() {
        $('.id').val("");
        afterAction();
        reloadDatatable();
    });
    // end partials

    // preview
    $('.image-category').on('change', function() {
        $('#gambar-category').removeClass('d-none');
        const file = this.files[0];
        if(file) {
            let reader = new FileReader();
            reader.onload = function(event) {
                $('#gambar-category').find('img').attr('src', event.target.result);
            }
            reader.readAsDataURL(file);
        }
    });


    // post
    $("#FormCategory").on("submit", function(e){
        e.preventDefault();
        // let id = $(".id").val();
        // let name = $(".name").val();
        // let published = $(".published").val();
        // let image = $(".image-category")[0].files[0];

        // let data = {
        //     id:id,
        //     name:name,
        //     published:published,
        //     image:image
        // }
        let data = new FormData($(this)[0]);

        postCategory(data);
    });

    function postCategory(data){
        axios.post('{{route('category.post')}}', data, {
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
            console.log("Terjadi error ", error);
        });
    }
    // end post

    // edit
    $(document).on("click", ".btn-edit-category", function(){
        let id = $(this).data('id');
        console.log(id);
        getCategory(id);
    });

    function getCategory(id){
        axios.get('./get/' + id)
            .then(function(response){
                let data = response.data.category;
                console.log(data);

                $(".id").val(data.id);
                $(".name").val(data.name);
                $(".published").val(data.published).trigger("change");

                $(".current_image_category").val(data.image);
                $("#gambar-category").removeClass('d-none');
                $('#gambar-category').find('img').attr('src' , data.image);
            })
            .catch(function(response){
                console.log("Data tidak ditemukan ", error);
            });
    }
    // end edit

    // delete
    $(document).on("click", ".btn-delete-category", function(){
        let id = $(this).data('id');
        console.log(id);
        let conf = confirm("Apakah anda yakin ingin menghapus kategori ini?");
        if(conf){
            deleteCategory(id);
        }
    });

    function deleteCategory(id){
        axios.delete('./delete/' + id)
            .then(function(response){
                reloadDatatable();
            })
            .catch(function(error){
                console.log("Gagal menghapus data ", error);
            });
    }
@endsection
