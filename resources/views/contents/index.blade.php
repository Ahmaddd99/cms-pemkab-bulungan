@extends('layouts.master')
@section('css')
    <style>
    </style>
@endsection
@section('content')
    @include('contents.modal')
    @include('attributes.modal')
    <div class="row">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <h5>Konten</h5>
                    <button type="button" class="btn btn-success btn-sm btn-add-content" data-toggle="modal"
                        data-target="#ModalContent">Tambah Konten</button>
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
            },
            {
                "width": "",
                "targets": 2,
                "className": "dt-center"
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
            }
        ]
    });

    // partials
    function afterAction() {
        $('#FormContent')[0].reset();
        $('#ModalContent').modal('hide');
        $("#loop-attribute").empty();
    }

    function reloadDatatable() {
        $('#TableContent').DataTable().ajax.reload(null, false);
    }

    $("#ModalContent").on("show.bs.modal", function() {
        $(".id").val("");
        $('#FormContent')[0].reset();
        $(".attribute-group-additional").empty();
        categoryId();
        subcategoryId();
        feature();
        attribute();
    });

    $("#ModalContent").on("hidden.bs.modal", function(e) {
        e.preventDefault();
        $(".id").val("");
        afterAction();
        reloadDatatable();
        $('#gambar-content').empty();
        //$("#loop-attribute").empty();
        showAttributeForm('show');
    });

    $(".btn-add-content").on("click", function() {
        $(".id").val("");
        afterAction();
        $("#loop-attribute").empty();
        //$("#tambah-attribute").trigger('click');
    });

    $('.close-content').on("click", function() {
        $('.id').val("");
        afterAction();
        reloadDatatable();
        $('#gambar-content').empty();
        //$("#loop-attribute").empty();
    });
    // end partials

    // select 2
    function categoryId() {
        axios.get('./category')
            .then(function(response) {
                let data = response.data.category;
                let option = "<option selected disabled>Pilih Kategori</option>";
                $.each(data, function(key, val) {
                    option += `<option value="${val.id}">${val.name}</option>`
                });
                $("#category_id").html(option);
            })
    }

    function subcategoryId() {
        axios.get('./subcategory')
            .then(function(response) {
                let data = response.data.subcategory;
                let option = "<option selected disabled>Pilih Subkategori</option>";
                $.each(data, function(key, val) {
                    option += `<option value="${val.id}">${val.name}</option>`
                });
                $("#subcategory_id").html(option);
            })
    }

    function feature() {
        axios.get('./feature')
            .then(function(response) {
                let data = response.data.feature;
                let option = "<option selected disabled>Pilih Fitur</option>";
                $.each(data, function(key, val) {
                    option += `<option value="${val.id}">${val.title}</option>`
                });
                $("#feature_id").html(option);
            })
    }

    function attribute() {
        axios.get('./attribute')
            .then(function(response) {
                let data = response.data.attribute;
                let option = "<option selected disabled>Pilih Attribut</option>";
                $.each(data, function(key, val) {
                    option += `<option value="${val.id}">${val.name}</option>`
                });
                $('.get-attribute').html(option);
            })
    }
    attribute();
    // end select 2

    // submit new attribute
    $("#FormAttribute").on("submit", function(e){
        e.preventDefault();
        postAttribute($(this).serialize());
    })

    function postAttribute(att){
        axios.post('{{route('content.post.attribute')}}', att)
            .then(function(response){
                $("#ModalAttribute").modal('hide');
                $("#FormAttribute")[0].reset();
                attribute();
            })
    }

    // delete attribute value
    function deleteAttVal(id){
        axios.delete('./delAttVal/' + id)
            .then(function(response){
                console.log(response);
                reloadDatatable();
            })
    }

    // add attribute
    $(".tambah-attribute").on("click", function() {
        axios.get('./attribute')
            .then(function(response) {
                let data = response.data.attribute;
                let optionadditional = "<option selected disabled>Pilih Attribut</option>";
                $.each(data, function(key, val) {
                    optionadditional += `<option value="${val.id}">${val.name}</option>`;
                });

                // biar unique
                let newAttributeId = Date.now();
                let newAttributeGroup = `
                <div id="loop-attribute" class="attribute-group-additional">
                    <hr>
                    <div class="form-group mb-3 col-6">
                        <label class="required" for="attribute_id">Attribute</label>
                        <select name="attribute_id[]" class="form-control custom-select get-attribute-additional attribute_id" style="width: 100%" required>${optionadditional}</select>
                    </div>
                    <div class="form-group">
                        <input type="hidden" name="attribute_value_id[]" class="form-control attribute_value_id" readonly>
                    </div>
                    <div class="form-group col-12">
                        <label class="required" for="description">Deskripsi Attribut</label>
                        <textarea name="description[]" cols="3" class="form-control description" required
                            placeholder="Masukan deskripsi attribut" style="height:10em"></textarea>
                    </div>
                    <div class="form-row col-6">
                        <div class="form-group col-8">
                            <label class="required" for="order">Order</label>
                            <input type="number" name="order[]" id="order" class="form-control order" required>
                        </div>
                        <div class="col-4">
                            <button type="button" class="btn btn-sm btn-danger btn-hapus-attribute" style="width: 100%;margin-top:2.5em"><i class="bi bi-trash"></i></button>
                        </div>
                    </div>
                </div>
            `;
                $("#form-group-body").append(newAttributeGroup);
                $(".custom-select").select2({
                    // dropdownParent: $('#ModalContent')
                });

                $("#form-group-body").on("click", ".btn-hapus-attribute", function(){
                    $(this).closest(".attribute-group-additional").remove();
                })
            });
    });
    //end add attribute

    // preview upload
    $('#image').on('change', function() {
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

    $('#image_gallery').on('change', function() {
        $('#preview-upload-galleries').removeClass('d-none');
        const file = this.files[0];
        if(file) {
            let reader = new FileReader();
            reader.onload = function(event) {
                $('#preview-upload-galleries').find('img').attr('src', event.target.result);
            }
            reader.readAsDataURL(file);
        }
    });

    // post
    $("#FormContent").on("submit", function(e) {
        e.preventDefault();
        // let id = $(".id").val();
        // let category_id = $(".category_id").val();
        // let subcategory_id = $(".subcategory_id").val();
        // let title = $(".title").val();
        // let body = $(".body").val();
        // let meta = $(".meta").val();
        // let image = $(".image-content")[0].files[0];
        // let feature_id = $(".feature_id").val();
        // let id_featureValue = $(".id_featureValue").val();
        // let attribute_id = $(".attribute_id").val();
        // let attribute_value_id = $(".attribute_value_id").val();
        // let description = $(".description").val();
        // let order = $(".order").val();

        // let data = {
        //     id:id,
        //     category_id:category_id,
        //     title:title,
        //     body:body,
        //     meta:meta,
        //     image:image,
        //     feature_id:feature_id,
        //     id_featureValue:id_featureValue,
        //     attribute_id:attribute_id,
        //     attribute_value_id:attribute_value_id,
        //     description:description,
        //     order:order
        // }

        let formData = new FormData($(this)[0]);

        //if ($(".subcategory_id").val()) {
        //    formData.append("subcategory_id", $(".subcategory_id").val());
        //}

        postContent(formData);
        //console.log($(this).serialize());
    })

    function postContent(post) {
        axios.post('{{ route('content.post') }}', post, {
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
                console.log("Terjadi kesalahan saat post data ", error);
            });
    }
    // end post

    // edit
    $(document).on("click", ".btn-edit-content", function() {
        let id = $(this).data('id');
        console.log(id);
        getContent(id);
        showAttributeForm('hide');
    });

    function showAttributeForm(cond) {
        let _form = '';
        if(cond === 'show') {
            _form = `<div class="attribute-group">
                <div class="form-group mb-3 col-6">
                    <label class="required" for="attribute_id">Label</label>
                    <select name="attribute_id[]" id="attribute_id" class="form-control custom-select get-attribute attribute_id" style="width: 100%" required></select>
                </div>
                <div class="form-group">
                    <input type="hidden" id="attribute_value_id[]" name="attribute_value_id" class="form-control attribute_value_id" readonly>
                </div>
                <div class="form-group mb-3 col-12">
                    <label class="required" for="description">Deskripsi Attribut</label>
                    <textarea name="description[]" id="description" rows="2" class="form-control description" required placeholder="Masukan deskripsi attribut"></textarea>
                </div>
                <div class="form-row col-12">
                    <div class="form-group col-3">
                        <label for="order">Order</label>
                        <input type="number" name="order[]" id="order" class="form-control text-center order" value="0" onclick="this.select()" required>
                    </div>
                    <div class="col-2">
                        <button type="button" class="btn btn-sm btn-outline-danger btn-hapus-attribute" style="width: 100%;margin-top:2.5em"><i class="bi bi-trash"></i></button>
                    </div>
                </div>
            </div>`;
            $('#form-group-body').html(_form);
        } else {
            $('#form-group-body').html('');
        }
    }

    function getContent(id) {
        axios.get('./get/' + id)
            .then(function(response) {
                let data = response.data.content;
                let feature = response.data.content.feature_value;
                let attribute = response.data.content.attribut_value;
                let galleries = response.data.content.galleries;
                console.log(galleries);
                //console.log(attribute);
                //console.log(feature);
                console.log(data);
                $('.id').val(data.id);
                $('.category_id').val(data.category_id).trigger('change');
                $('.subcategory_id').val(data.subcategory_id);
                $('.title').val(data.title);
                $('.body').val(data.body);
                $('.meta').val(data.meta);
                $('.current_image').val(data.image);
                //let gambar = `<div class="form-group">
                //    <label for="">*Gambar yang sudah ada sebelumnya</label><br>
                //     <img src="${data.image}" alt="belum ada gambar sebelumnya" style="width: 15em"></div>`;
                $("#gambar-content").removeClass('d-none');
                $('#gambar-content').find('img').attr('src' , data.image);

                if (feature === null) {
                    $('.feature_id').val();
                    $('.id_featureValue').val("");
                } else {

                    $('.feature_id').val(feature.feature_id).trigger('change');
                    $('.id_featureValue').val(feature.id);
                }

                // loop image galleries
                let galeri = '';
                $.each(galleries, function(key, val){
                    galeri += `<div class="gallery-item mb-3">
                        <img class="img-fluid float-left img-thumbnail mx-1" style="width:100px" src="../../gallery/${val.image}" alt="">
                        <button type="button" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                    </div>
                    `
                })
                $("#koleksi-galeri").removeClass('d-none').html(galeri);

                for (let i = 0; i < attribute.length; i++) {
                    let ambil = attribute[i];
                    $("#form-group-body").append(
                        `<div id="loop-attribute" class="attribute-group-additional">
                            <div class="form-group mb-3 col-6">
                                <label class="required" for="attribute_id">Attribute</label>
                                <select name="attribute_id[]" class="form-control custom-select get-attribute-additional attribute_id" style="width: 100%" required>
                                    <option value="${ambil.attribut_id}">${ambil.attribut.name}</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <input type="hidden" name="attribute_value_id[]" class="form-control attribute_value_id" value="${ambil.id}" readonly>
                            </div>
                            <div class="form-group col-12">
                                <label class="required" for="description">Deskripsi Attribut</label>
                                <textarea name="description[]" cols="3" class="form-control description" required
                                    placeholder="Masukan deskripsi attribut" style="height: 10em">${ambil.description}</textarea>
                            </div>
                            <div class="form-row col-6">
                                <div class="form-group col-8">
                                    <label class="required" for="order">Order</label>
                                    <input type="number" name="order[]" id="order" class="form-control order" value="${ambil.order}" placeholder="cth:0" required>
                                </div>
                                <div class="col-4">
                                    <button type="button" class="btn btn-sm btn-danger btn-hapus-attribute" style="width: 100%;margin-top:2.5em"><i class="bi bi-trash"></i></button>
                                </div>
                            </div>
                        </div>`
                    )
                }
                $(".btn-hapus-attribute").on("click", function(){
                    let attValId = $(this).closest(".attribute-group-additional").find(".attribute_value_id").val();
                    //console.log(attValId);
                    let conf = confirm("Apakah anda yakin ingin menghapus atribut ini?");
                    if(conf){
                        deleteAttVal(attValId);
                        $(this).closest(".attribute-group-additional").remove();
                    }
                });
            })
            .catch(function(error) {
                console.log("terjadi kesalahan saat mengambil data ", error);
            });
    }
    // end get

    // delete
    $(document).on("click", ".btn-delete-content", function() {
        let id = $(this).data('id');
        let conf = confirm("apakah anda yakin ingin menghapus data ini?");
        if (conf) {
            deleteContent(id);
        }
    });

    function deleteContent(id) {
        axios.delete('./delete/' + id)
            .then(function(response) {
                console.log(response);
                reloadDatatable();
            })
    }
@endsection
