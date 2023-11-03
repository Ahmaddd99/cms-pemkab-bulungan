<div id="ModalContent" class="modal fade bd-example-modal-xl" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <form id="FormContent" enctype="multipart/form-data">
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content" style="margin: 0 auto">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Form konten</h5>
                    <button type="button" class="close close-content" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-row">
                        <div class="col-4">
                            <input type="hidden" class="form-control id" id="id" name="id" value="">
                            <div class="form-row">
                                <div class="form-group col-6">
                                    <label class="required" for="category_id">Kategori</label>
                                    <select name="category_id" id="category_id" class="form-control custom-select category_id content" style="width: 100%" required></select>
                                </div>
                                <div id="form-subcategory" class="form-group col-6">
                                    <label for="subcategory_id">Subkategori</label>
                                    <select name="subcategory_id" id="subcategory_id" class="form-control custom-select subcategory_id content" style="width: 100%"></select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="required" for="title">Judul</label>
                                <input type="text" name="title" id="title" class="form-control title" placeholder="Tulis judul konten disini" required value="">
                            </div>
                            <div class="form-group">
                                <label class="required" for="meta">Deskripsi Singkat</label>
                                <input type="text" name="meta" id="meta" class="form-control meta" placeholder="Tulis alamat/lokasi disini" required value="">
                            </div>
                            <!-- preview upload image content -->
                            <div id="gambar-content" class="d-none my-3 text-center">
                                <img src="" alt="" class="img-fluid img-thumbnail" />
                            </div>
                            <!-- link asset gambar sebelumnya -->
                            <div>
                                <input type="hidden" name="current_image" class="form-control current_image" readonly value="" />
                            </div>
                            <div class="form-group">
                                <label for="image">Upload Gambar</label>
                                <input type="file" name="image" id="image" class="form-control image-content" accept="image/*">
                            </div>
                            <div class="form-group">
                                <label for="body">Deskripsi</label>
                                <textarea name="body" id="body" cols="3" class="form-control body" style="height: 10em" placeholder="Masukan deskripsi konten"></textarea>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="col-12">
                                <button type="button" class="btn btn-success btn-sm btn-add-attribute mb-3" data-toggle="modal" data-target="#ModalAttribute">Tambah Label Baru</button>
                            </div>
                            <div id="form-group-body">
                                <div class="attribute-group">
                                    <div class="form-group mb-3 col-6">
                                        <label class="required" for="attribute_id">Label</label>
                                        <select name="attribute_id[]" id="attribute_id" class="form-control custom-select get-all-attribute attribute-modal attribute_id" style="width: 100%" required>
                                            <option class="option-default" selected disabled>-- Pilih Label --</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <input type="hidden" id="attribute_value_id" name="attribute_value_id[]" class="form-control attribute_value_id" readonly>
                                    </div>
                                    <div class="form-group mb-3 col-12">
                                        <label class="required" for="description">Deskripsi Label</label>
                                        <textarea name="description[]" id="description" rows="2" class="form-control description" placeholder="Masukan deskripsi attribut"></textarea>
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
                                </div>
                            </div>
                            <div class="col-12">
                                <button type="button" id="tambah-attribute" class="btn btn-sm btn-outline-primary tambah-attribute" style="width:100%">Tambah Attribut</button>
                            </div>
                        </div>
                        <div class="col-4">
                            <button type="button" class="btn btn-sm btn-success btn-add-fitur mb-3" data-toggle="modal" data-target="#ModalFeature">Tambah Fitur Baru</button>
                            <div class="form-row">
                                <input type="hidden" name="id_featureValue" id="id_featureValue" class="form-control id_featureValue" value="">
                                <div class="form-group">
                                    <label for="feature_id">Pilih Penempatan untuk ditampilkan</label>
                                    <select name="feature_id" id="feature_id" class="form-control custom-select feature_id content" style="width: 100%"></select>
                                </div>
                            </div>
                            <hr>
                            <div class="form-row">
                                {{-- <input type="hidden" name="id_gallery[]" class="form-control id_gallery" readonly> --}}
                                <div class="form-group">
                                    <label for="">Upload gambar konten galeri</label>
                                    <input type="file" id="image_gallery" name="image_gallery[]" class="form-control image_gallery" accept="image/*" multiple>
                                </div>
                            </div>
                            <div class="form-row">
                                <!-- preview upload galleries -->
                                <div id="preview-upload-galleries" class="d-none text-center my-3">

                                </div>
                            </div>
                            <hr>
                            <!-- isi galeri kontent -->
                            <div id="koleksi-galeri" class="d-none my-3 text-center">

                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Kembali</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </div>
    </form>
</div>
