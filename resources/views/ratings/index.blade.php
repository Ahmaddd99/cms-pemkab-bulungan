@extends('layouts.master')
@section('content')
    <div class="row">
        <div class="col">
            <strong>Pengaturan</strong>
            <h1 class="h3 mb-3"><strong>Kelola Rating</strong></h1>
        </div>
    </div>
    @include('ratings.modal')
    <div class="row">
        <div class="card">
            <div class="card-header">
                <button type="button" class="btn btn-success btn-sm btn-add-rating" data-toggle="modal"
                    data-target="#ModalRating"><i class="fa-solid fa-plus"></i> Tambah Rating</button>
            </div>
            <div class="card-body">
                <table id="TableRating" class="table table-bordered table-hover table-striped" style="width: 100%">
                    <thead>
                        <tr>
                            <th>Ikon</th>
                            <th>Nama</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
@section('js')
    let datatable = $("#TableRating").DataTable({
        "responsive": true,
        "processing": true,
        "searching": true,
        "paging": true,
        "language": {
            processing: '<span style="font-size:22px"><i class="fa fa-spinner fa-spin fa-fw"></i> Loading..</span>',
            search: '',
            searchPlaceholder: "Cari Rating"
        },
        "serverSide": true,
        "ajax": "{{ route('submenu.rating.datatables') }}",
        "info": true,
        "order": [],
        "dom": "frtip",
        "pageLength": 10,
        "aLengthMenu": [
            [10, 25, 50, 100, -1],
            [10, 25, 50, 100, "All"]
        ],
        "columns": [{
                data: "icon",
                name: "icon",
                width: "15%"
            },
            {
                data: "name",
                name: "name",
                width: ""
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
                "targets": 1
            },
            {
                "width": "",
                "targets": 2,
                "className": "dt-center"
            },
        ]
    });

    function afterAction() {
        $('#FormRating')[0].reset();
        $('#ModalRating').modal('hide');
    }

    function reloadDatatable() {
        $('#TableRating').DataTable().ajax.reload(null, false);
    }

    $(".btn-add-rating").on("click", function() {
        $('#preview-icon').addClass('d-none');
        $('#preview-icon').find('img').attr('src', '');
    });

    $("#ModalRating").on("hidden.bs.modal", function() {
        afterAction();
    });

    $('#icon').on('change', function() {
        $('#preview-icon').removeClass('d-none');
        const file = this.files[0];
        if (file) {
            let reader = new FileReader();
            reader.onload = function(event) {
                $('#preview-icon').find('img').attr('src', event.target.result);
            }
            reader.readAsDataURL(file);
        }
    });

    $("#FormRating").on("submit", function(e) {
        e.preventDefault();
        let formData = new FormData($(this)[0]);
        postRating(formData);
    });

    function postRating(data) {
        axios.post('{{ route('submenu.rating.post') }}', data, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            })
            .then(function(response) {
                Swal.fire({
                    title: "Berhasil!",
                    text: "Data yang anda masukan tersimpan dengan baik!",
                    icon: "success"
                });
                console.log(response);
                reloadDatatable();
                afterAction();
            })
            .catch(function(error) {
                console.log(`Terjadi Masalah saat post data` + error)
                alert("Terjadi kesalahan saat submit, perhatikan kembali form yang anda isi");
            });
    }

    $(document).on('click', '.btn-edit-icon', function() {
        let id = $(this).data('id');
        editRating(id);
    });

    function editRating(id) {
        axios.get(`./get/${id}`)
            .then(function(response) {
                let data = response.data.data;
                // console.log(data);
                $('.id').val(data.id);
                $('.name').val(data.name);

                $("#preview-icon").removeClass('d-none');
                $('#preview-icon').find('img').attr('src', `../../rating/${data.icon}`);
            })
    }

    function deleteData(id) {
        axios.delete(`./delete/${id}`)
            .then(function(response) {
                Swal.fire({
                    title: "Terhapus!",
                    text: "Data anda berhasil terhapus!",
                    icon: "success"
                });
                reloadDatatable();
            })
            .catch(function(error) {
                console.error("Error deleting data: ", error);
            });
    }

    $(document).on('click', '.btn-delete-icon', function() {
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
                deleteData(id);
            }
        });
    });

@endsection
