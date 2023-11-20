@extends ('layouts.master')
@section('script-css')
<link rel="stylesheet" type="text/css" href="{{asset('assets/summernote/summernote-lite.css')}}" >
@endsection
@section('content')
<div class="row">
    <div class="col">
        <strong>Konten</strong>
        <h1 class="h3 mb-3"><strong>Kelola Isi Konten</strong></h1>
    </div>
</div>
@include('contents.modal')
@include('attributes.modal')
@include('features.modal')
<div class="row">
    <div class="card">
        <div class="card-header">
            <button type="button" class="btn btn-success btn-sm btn-add-content" data-toggle="modal"
                data-target="#ModalContent"><i class="fa-solid fa-plus"></i> Tambah Konten</button>
        </div>
        <div class="card-body">
            <table id="TableContent" class="table table-bordered table-hover table-striped" style="width: 100%">
                <thead>
                    <tr style="font-size: 0.9em">
                        <th>Gambar</th>
                        <th>Kategori</th>
                        <th>Subkategori</th>
                        <th>Judul</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody style="0.8em"></tbody>
            </table>
        </div>
    </div>
</div>
@endsection
@section('script-js')
    <script type="text/javascript" src="{{asset('assets/summernote/summernote-lite.min.js')}}"></script>
@endsection
@section('js')
let datatable = $("#TableContent").DataTable({
    "responsive": true,
    "processing": true,
    "searching": true,
    "paging": true,
    "language": {
        processing: '<span style="font-size:22px"><i class="fa fa-spinner fa-spin fa-fw"></i> Loading..</span>',
        search: '',
        searchPlaceholder: "Cari Konten"
    },
    "serverSide": true,
    "ajax": "{{ route('menu.content.datatables') }}",
    "info": true,
    "order": [],
    "dom": "frtip",
    "pageLength": 10,
    "aLengthMenu": [
        [10, 25, 50, 100, -1],
        [10, 25, 50, 100, "All"]
    ],
    "columns": [{
        data: "image",
        name: "image",
        width: "15%"
    },
    {
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
        data: "title",
        name: "title",
        width: ""
    },
    {
        data: "actions",
        name: "actions",
        width: "20%"
    },
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

$('#body').summernote({
	height: 350,
	placeholder: 'Tulis deskripsi konten',
	toolbar: [
      // ['style', ['style']],
      ['style', ['bold', 'italic', 'underline']],
      //['font', ['strikethrough', 'superscript', 'subscript']],
      ['fontsize', ['fontsize']],
      ['color', ['color']],
      ['para', ['ul', 'ol', 'paragraph']],
      // ['height', ['height']],
      // ['fontname', ['fontname']],
      ['table', ['table']],
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
$("#ModalContent").on("show.bs.modal", function () {
    // $("#FormContent")[0].reset();
    // showAttributeForm('show');
    getfeature();
    // $(document).on("", ".custom-select").select2();
    // categoryId();
    dynamicselect();
    getRating();
});

$(".btn-add-content").on("click", function () {
    categoryId();
    subcategoryId();
    // dynamicselect();
    getAllAttribute();
    getfeature();
    getAttrToModal();
    // getRating();
    // $("#form-group-body").find('.attribute-group-additional').remove();
    // $("#tambah-attribute").on("click", function(){
    //     buttonAddAttr('show');
    // });
});

$("#ModalContent").on("hidden.bs.modal", function () {
    $('#gambar-content').addClass('d-none');
    $('#gambar-content').find('img').attr('src', '');
    $("#FormContent")[0].reset();
    $('#preview-upload-galleries').addClass('d-none');
    $('#preview-upload-galleries').find('img').attr('src', '');
    $("#koleksi-galeri").empty();
    // showAttributeForm('hide');
    $("#form-group-body").find('.attribute-group-additional').remove();
    $('.id').val("");
    $('#body').summernote('code', '');
    stateAttribute = 0;
    statebutton();
    // console.log(stateAttribute);
});
// end partials


// content
// select category & subcategory
function categoryId(categoryID = null) {
    axios.get('./category')
        .then(function (response) {
            let data = response.data.category;
            let option = `<option selected>-- Pilih Kategori --</option>`;
            $.each(data, function (key, val) {
                option +=
                    `<option value="${val.id}" ${categoryID === val.id && categoryID !== null ? 'selected' : ''}>${val.name}</option>`;
            });
            $("#category_id").html(option);
        });
}

function subcategoryId(categoryID = null, subcategoryID = null) {
    if (categoryID === null) {
        $('#form-subcategory').hide();
    } else {
        axios.get(`./subcategory/${categoryID}`)
            .then(function (response) {
                let data = response.data.subcategory;
                // console.log(data);
                let option = `<option value="" >-- Pilih Subkategori --</option>`;
                $.each(data, function (key, val) {
                    option +=
                        `<option value="${val.id}" ${subcategoryID === val.id && categoryID !== null ? 'selected' : ''}>${val.name}</option>`
                });
                $("#form-subcategory").show().find(".subcategory_id").html(option);
            });
    }
}

function dynamicselect() {
    $(".category_id").on("change", function () {
        let idcategory = $(this).val();
        if (idcategory) {
            $('#form-subcategory').show();
            getsubcategory(idcategory);
        }
    });
}

function getsubcategory(categoryid) {
    axios.get(`../content/${categoryid}/select`)
        .then(function (response) {
            let data = response.data.category.subcategory;
            let select = `<option value="" >-- Pilih Subkategori --</option>`;
            $.each(data, function (key, val) {
                select += `<option value="${val.id}" >${val.name}</option>`
            });
            $("#subcategory_id").html(select);
        });
}

// image content
$('#image').on('change', function () {
    $('#gambar-content').removeClass('d-none');
    const file = this.files[0];
    if (file) {
        let reader = new FileReader();
        reader.onload = function (event) {
            $('#gambar-content').find('img').attr('src', event.target.result);
        }
        reader.readAsDataURL(file);
    }
});
// end content



// attribute
$("#FormAttribute").on("submit", function (e) {
    e.preventDefault();
    postAttribute($(this).serialize());
});

const generateRandomString = () => {
    return Math.floor(Math.random() * Date.now()).toString(36);
};



function postAttribute(att) {
    axios.post('{{ route('menu.content.post.attribute') }}', att)
        .then(function (response) {
            Swal.fire({
                title: "Berhasil!",
                text: "Label berhasil ditambah!",
                icon: "success"
            });
            $("#ModalAttribute").modal('hide');
            $("#FormAttribute")[0].reset();
            let attribute = response.data.attribute;
            $('.get-all-attribute').prepend(
                `<option value="${attribute.id}">${attribute.name}<option>`
            );
            let rand = generateRandomString();
            $('.get-all-attribute').addClass(`select-${rand}`);
            console.log(response);
            // getAllAttribute(rand);
        })
        .catch(function (error) {
            console.log(`Terjadi masalah saat menambah data attribute : ` + error);
            $("#FormAttribute")[0].reset();
        })
}
// let selectedAttribute = null;
function getAllAttribute(selectClass) {
    axios.get('./attribute')
        .then(function (response) {
            let data = response.data.attribute;
            let option = "<option value=''>-- Pilih Label --</option>";
            $.each(data, function (key, val) {
                option += `<option value="${val.id}" ${selectClass === val.id && selectClass !== null ? 'selected' : ''}>${val.name}</option>`
            });
            $(`.get-all-attribute.select-${selectClass}`).html(option);
            // if(selectedAttribute) {
            //     $('.attribute_id').val(selectedAttribute);
            // }
        });
}

function getAttrToModal() {
    axios.get('./attribute')
        .then(function (response) {
            let data = response.data.attribute;
            let option = "<option value=''>-- Pilih Label --</option>";
            $.each(data, function (key, val) {
                option += `<option value="${val.id}">${val.name}</option>`
            });
            $(`.attribute-modal`).html(option);
        });
}

let stateAttribute = 0;

function statebutton(){
    if(stateAttribute === 0){
        buttonAddAttr('hide');
    } else {
        buttonAddAttr('show');
    }
}

function buttonAddAttr(condition){
    let button = '';
    if (condition === 'show'){
            button = `<button type="button" class="btn btn-success btn-sm btn-add-attribute mb-3" data-toggle="modal" data-target="#ModalAttribute"><i class="fa fa-plus"></i> Tambah Label Baru</button>`;
            $("#tambah-attribute-baru").html(button);

    } else {
        $("#tambah-attribute-baru").html('');
    }
}

$(".tambah-attribute").on("click", async function () {
    stateAttribute++;
    // console.log(stateAttribute);
    statebutton();
    let rand = generateRandomString();
    let newAttributeGroup = `
                    <div id="loop-attribute" class="attribute-group-additional attribute-group">
                        <div class="form-group mb-3 col-6">
                            <label class="required" for="attribute_id"><strong>Label</strong></label>
                            <select name="attribute_id[]" class="form-control custom-select get-all-attribute select-${rand} attribute-additional" style="width: 100%">
                            </select>
                        </div>
                        <div class="form-group">
                            <input type="hidden" name="attribute_value_id[]" class="form-control attribute_value_id" readonly>
                        </div>
                        <div class="form-group col-12">
                            <label class="required" for="description"><strong>Deskripsi Label</strong></label>
                            <textarea name="description[]" cols="3" class="form-control description" placeholder="Masukan deskripsi label"
                                rows="2"></textarea>
                        </div>
                        <div class="form-row col-12">
                            <div class="form-group col-3">
                                <label for="order"><strong>Order</strong></label>
                                <input type="number" name="order[]" id="order" class="form-control text-center order" value="0" onclick="this.select()" required>
                            </div>
                            <div class="col-2">
                                <button type="button" class="btn btn-sm btn-outline-danger btn-hapus-attribute-additional" style="width: 100%;margin-top:2.5em"><i class="bi bi-trash"></i></button>
                            </div>
                        </div>
                    </div>
                `;
    await $("#form-group-body").append(newAttributeGroup);
    getAllAttribute(rand);

});

$(document).on("click", ".btn-hapus-attribute-additional", function () {
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
            stateAttribute=stateAttribute-1;
            statebutton();
            $(this).closest(".attribute-group-additional").remove();
        }
    });
})
// end attribute


// feature
// add new feature
$("#FormFeature").on("submit", function (e) {
    e.preventDefault();
    let id = $(".id").val();
    let title = $(".title-feature").val();
    let order = $(".order").val();
    let published = $(".published").val();
    let image = $(".image-feature")[0].files[0];

    let data = {
        id: id,
        title: title,
        order: order,
        published: published,
        image: image
    }

    postFeature(data);
});

function postFeature(feature) {
    axios.post('{{ route('menu.content.postfeature') }}', feature, {
        headers: {
            'Content-Type': 'multipart/form-data'
        }
    })
        .then(function (response) {
            Swal.fire({
                title: "Berhasil!",
                text: "Fitur berhasil ditambah!",
                icon: "success"
            });
            $("#ModalFeature").modal('hide');
            $("#FormFeature")[0].reset();
            getfeature();
        })
}

function getfeature() {
    axios.get('./feature')
        .then(function (response) {
            let data = response.data.feature;
            let option = `<option value="">-- Pilih Fitur --</option>`;
            $.each(data, function (key, val) {
                option += `<option value="${val.id}">${val.title}</option>`
            });
            $("#feature_id").html(option);
        })
}
// end feature

// image gallery content
$('#image_gallery').on('change', function () {
    $('#preview-upload-galleries').removeClass('d-none');
    const files = this.files;
    let koleksi = '';

    $.each(files, function (key, val) {
        if (val) {
            let reader = new FileReader();
            reader.onload = function (event) {
                koleksi += `<div class="content-gallery-image position-relative gallery-item mb-3" style="overflow:hidden;float:left">
                        <img class="img-fluid float-left img-thumbnail mx-1" style="width:100px" src="${event.target.result}" alt="">
                    </div>`;
                $("#preview-upload-galleries").removeClass('d-none').html(koleksi);
            };
            reader.readAsDataURL(val);
        }
    });
});
function getContentGallery(contentId) {
    axios.get(`../content/${contentId}/galleries`)
        .then(function (response) {
            let data = response.data.gallery;
            let galeri = '';
            $.each(data, function (key, val) {
                galeri += `<div class="content-gallery-image position-relative gallery-item mb-3" style="overflow:hidden;float:left">
                            <button type="button" class="btn btn-sm btn-danger position-absolute delete-gallery" data-gallery-id="${val.id}" data-content-id="${contentId}" style="top:0;left:0"><i class="bi bi-trash"></i></button>
                            <img class="img-fluid float-left img-thumbnail mx-1" style="width:100px" src="../../gallery/${val.image}" alt="">
                        </div>
                        `;
            });
            $("#koleksi-galeri").removeClass('d-none').html(galeri);
        });
}

$(document).on('click', '.delete-gallery', function () {
    let idGallery = $(this).data('gallery-id');
    let contentId = $(this).data('content-id');
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
            deleteGall(idGallery);
            getContentGallery(contentId);
        }
    });
});

// Checkbox Rating

async function getRating() {
    $('.each-icon').html(`<div class="d-flex justify-content-center align-items-center" style="gap:10px"><i class="fa-solid fa-spinner"></i> Loading...</div>`);
    await axios.get('./rating')
        .then(function (response) {
            let data = response.data.rating;
            // console.log(data);
            let icon = '';
            $.each(data, function (key, val) {
                icon += `
                <div class="col-4 text-center checkbox-wrapper">
                    <label class="chk">
                        <input class="checkbox-image-${val.id} checkbox-image" type="checkbox" value="${val.id}" name="rating_id[]" />
                        <img class="img-fluid img-thumbnail p-2" src="../../rating/${val.icon}" alt="${val.name}" />
                    </label>
                </div>`
            })
            $(".each-icon").html(icon);

        })
    }

    $(".btn-clear-ratings").on("click", function(){
        $(`input.checkbox-image`).removeAttr("checked");
        // console.log("clear bang");

        let idx = $('.content-idx').val();
        // console.log(idx);
        // clearRatings(idx);
    });

function clearRatings(contentid){
    axios.delete(`../content/${contentid}/clear`)
        .then(function(response){
            console.log(response);
        })
}
// end checkbox rating

// post
$("#FormContent").on("submit", function (e) {
    e.preventDefault();
    let formData = new FormData($(this)[0]);
    postContent(formData);
})

function postContent(post) {
    axios.post('{{ route('menu.content.post') }}', post, {
        headers: {
            'Content-Type': 'multipart/form-data'
        }
    })
        .then(function (response) {
            Swal.fire({
                title: "Berhasil!",
                text: "Data yang anda masukan tersimpan dengan baik!",
                icon: "success"
            });
            afterAction();
            reloadDatatable();
        })
        .catch(function (error) {
            console.log("Terjadi kesalahan saat post data ", error);
            Swal.fire({
                icon: "error",
                title: "Terjadi kesalahan!",
                text: "Perhatikan kembali, pastikan untuk mengisi data dengan benar!"
            });
        });
}
// end post

// edit
$(document).on("click", ".btn-edit-content", function () {
    let id = $(this).data('id');
    getContent(id);
    // showAttributeForm('hide');
    getContentGallery(id);
});

function getContent(id) {
    axios.get('./get/' + id)
    .then(async function (response) {
            let data = response.data.content;
            let feature = response.data.content.feature_value;
            let attribute = response.data.content.attribut_value;
            let galleries = response.data.content.galleries;
            let ratings = response.data.content.get_ratings;
            $('.id').val(data.id);
            //$('.category_id').val(data.category_id).trigger('change');
            // $('.subcategory_id').val(data.subcategory_id);
            // getcatid(data.category_id);
            // getsubid(data.subcategory_id);
            //console.log(data);
            $('.title').val(data.title);
            $('#body').summernote('code', data.body);
            $('.meta').val(data.meta);
            $('.qrcode').val(data.qrcode);
            $('.current_image').val(data.image);
            $('.content_order').val(data.order);

            $.each(ratings, function(k,v) {
                $(`input.checkbox-image-${v.rating_id}`).attr('checked', 'checked');
            });

            // await getsubcategory(data.category_id);
            await categoryId(data.category_id);
            await subcategoryId(data.category_id, data.subcategory_id);
            dynamicselect();


            if (data.subcategory_id === null) {
                $('#form-subcategory').show();
            }

            if (feature === null) {
                $('.feature_id').val();
                $('.id_featureValue').val("");
            } else {
                $('.feature_id').val(feature.feature_id).trigger('change');
                $('.id_featureValue').val(feature.id);
            }

            $("#gambar-content").removeClass('d-none');
            $('#gambar-content').find('img').attr('src', `../../content/${data.image}`);

            // console.log(attribute);
            if (attribute.length === 0) {
                // buttonAddAttr('hide');
            } else {
                stateAttribute = attribute.length;
                // console.log(stateAttribute);
                statebutton();
                let attributeData = "";
                $.each(attribute, function (key, val) {
                    let rand = generateRandomString();
                    attributeData += `<div class="attribute-group attribute-group-additional">
                            <div class="form-group mb-3 col-6">
                                <label class="required" for="attribute_id"><strong>Label</strong></label>
                                <select name="attribute_id[]" id="attribute_id" class="form-control custom-select select-${val.attribut_id} get-all-attribute attribute-modal attribute_id" style="width: 100%">
                                    <option value="${val.attribut_id}">${val.attribut.id}</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <input type="hidden" id="attribute_value_id" name="attribute_value_id[]" class="form-control attribute_value_id" value="${val.id}" readonly>
                            </div>
                            <div class="form-group mb-3 col-12">
                                <label class="required" for="description"><strong>Deskripsi Label</strong></label>
                                <textarea name="description[]" id="description" rows="2" class="form-control description" placeholder="Masukan deskripsi attribut">${val.description}</textarea>
                            </div>
                            <div class="form-row col-12">
                                <div class="form-group col-3">
                                    <label for="order"><strong>Order</strong></label>
                                    <input type="number" name="order[]" id="order" class="form-control text-center order" value="${val.order}" onclick="this.select()" required>
                                </div>
                                <div class="col-2">
                                    <button type="button" class="btn btn-sm btn-outline-danger btn-hapus-attribute" style="width: 100%;margin-top:2.5em"><i class="bi bi-trash"></i></button>
                                </div>
                            </div>
                        </div>`;
                    getAllAttribute(val.attribut_id);
                    $("#tambah-attribute").on("click", function(){
                        buttonAddAttr('show');
                    });
                });
                $("#form-group-body").html(attributeData);
            }
            $(".btn-hapus-attribute").on("click", function () {
                let attValId = $(this).closest(".attribute-group-additional").find(".attribute_value_id").val();
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
                        stateAttribute=stateAttribute-1;
                        statebutton();
                        deleteAttVal(attValId);
                        $(this).closest(".attribute-group-additional").remove();
                    }
                });
            });
        });
}
// end edit


// partials delete
// delete attribute value
function deleteAttVal(id) {
    axios.delete('./delAttVal/' + id)
        .then(function (response) {
            Swal.fire({
                title: "Terhapus!",
                text: "Attribut berhasil dihapus!",
                icon: "success"
            });
            reloadDatatable();
        })
}

//delete content gallery
function deleteGall(id) {
    axios.delete('./delGallery/' + id)
        .then(function (response) {
            Swal.fire({
                title: "Terhapus!",
                text: "Galeri Konten berhasil dihapus!",
                icon: "success"
            });
        })
}

// delete all
$(document).on("click", ".btn-delete-content", function () {
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
            deleteContent(id);
        }
    });
});

function deleteContent(id) {
    axios.delete('./delete/' + id)
        .then(function (response) {
            Swal.fire({
                title: "Terhapus!",
                text: "Isi Konten berhasil terhapus!",
                icon: "success"
            });
            reloadDatatable();
        })
}
@endsection
