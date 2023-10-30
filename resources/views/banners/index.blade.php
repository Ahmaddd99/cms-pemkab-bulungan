@extends('layouts.master')
@section('content')
    @include('banners.modal')
    <div class="row">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <h5>Banner</h5>
                    <button type="button" class="btn btn-success btn-sm add-new-banner" data-toggle="modal"
                        data-target="#ModalBanner">Tambah Banner</button>
                </div>
            </div>
            <div class="card-body">
                <table id="TableBanner" class="table table-bordered table-hover table-striped" style="width: 100%">
                    <thead>
                        <tr style="font-size: 0.9em">
                            <th>Keterangan</th>
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
    let datatable = $("#TableBanner").DataTable({
        "responsive": true,
        "processing": true,
        "searching": true,
        "paging": true,
        "language": {
            processing: '<span style="font-size:22px"><i class="fa fa-spinner fa-spin fa-fw"></i> Loading..</span>'
        },
        "serverSide": true,
        "ajax": "{{ route('banner.datatables') }}",
        "info": true,
        "order": [],
        "dom": "frtip",
        "pageLength": 10,
        "aLengthMenu": [
            [10, 25, 50, 100, -1],
            [10, 25, 50, 100, "All"]
        ],
        "columns": [{
                data: "keterangan",
                name: "keterangan",
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
        $('#FormBanner')[0].reset();
        $('#ModalBanner').modal('hide');
    }

    function reloadDatatable() {
        $('#TableBanner').DataTable().ajax.reload(null, false);
    }

    $("#ModalBanner").on("show.bs.modal", function() {
        $(".id").val("");
        $('#FormBanner')[0].reset();
    });

    $("#ModalBanner").on("hidden.bs.modal", function(e) {
        e.preventDefault();
        $(".id").val("");
        afterAction();
        reloadDatatable();
        $('#gambar-banner').empty();
    });

    $(".add-new-banner").on("click", function() {
        $(".id").val("");
        afterAction();
    });

    $('.close-banner').on("click", function() {
        $('.id').val("");
        afterAction();
        reloadDatatable();
        $('#gambar-banner').empty();
    });
    // end partials

    // preview
    $('#input-banner').on('change', function() {
        $('#gambar-banner').removeClass('d-none');
        const file = this.files[0];
        if(file) {
            let reader = new FileReader();
            reader.onload = function(event) {
                $('#gambar-banner').find('img').attr('src', event.target.result);
            }
            reader.readAsDataURL(file);
        }
    });

    // post
    $('#FormBanner').on('submit', function(e) {
        e.preventDefault();
        // let id = $('.id').val();
        // let keterangan = $('.keterangan').val();
        // let image = $('.image-banner')[0].files[0];

        // let data = {
        //     id: id,
        //     keterangan: keterangan,
        //     image: image
        // }

        let data = new FormData($(this)[0]);

        postBanner(data);
    });

    function postBanner(a) {
        axios.post('{{ route('banner.post') }}', a, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            })
            .then(function(response) {
                console.log(response);
                afterAction();
                reloadDatatable();
            })
            .catch(function(error) {
                console.log("Terjadi kesalahan saat post data", error)
            });
    }
    // end post

    // edit
    $(document).on("click", ".btn-edit-banner", function() {
        let id = $(this).data('id');
        console.log(id);
        editBanner(id);
    });

    function editBanner(id) {
        axios.get('./get/' + id)
            .then(function(response) {
                console.log(response);
                let data = response.data.banner;
                $('.id').val(data.id);
                $('.keterangan').val(data.keterangan);
                $(".current_image_banner").val(data.image);
                // kalo ada gambar
                //let gambar = `<div class="form-group">
                //    <label for="">*Gambar yang sudah ada sebelumnya</label><br>
                //    <img src="${data.image}" alt="belum ada banner" style="width: 15em"></div>`;
                //$('#gambar-banner').html(gambar);

                $("#gambar-banner").removeClass('d-none');
                $('#gambar-banner').find('img').attr('src' , data.image);
            })
    }
    // end edit

    // delete
    $(document).on("click", ".btn-delete-banner", function(){
        let id = $(this).data('id');
        //console.log(id);
        let conf = confirm("Anda yakin ingin menghapus data tersebut?");
        if(conf){
            deleteBanner(id);
        }
    });

    function deleteBanner(id){
        axios.delete('./delete/'+id)
            .then(function(response){
                reloadDatatable();
            })
            .catch(function(response){
                console.log("terjadi kesalahan saat menghapus data " , error);
            });
    }
@endsection
