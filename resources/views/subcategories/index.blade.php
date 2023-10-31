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
                            <th>Nama Subkategori</th>
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
                data: "image",
                name: "image",
                width: "30%"
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
    $('.current_image_subcategory').on('change', function() {
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
                $(".published").val(data.published).trigger("change");
                $(".current_image_subcategory").val(data.image);
                $("#gambar-subcategory").removeClass('d-none');
                $('#gambar-subcategory').find('img').attr('src' , data.image);
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
