<div id="ModalContent" class="modal fade bd-example-modal-xl" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <form id="FormContent" enctype="multipart/form-data">
        <div class="modal-dialog modal-xl">
            <div class="modal-content" style="margin: 0 auto">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Form konten</h5>
                    <button type="button" class="close close-content" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-row">
                        <div class="col-8">
                            <input type="hidden" class="form-control id" id="id" name="id" value="">
                            <div class="form-row">
                                <div class="form-group col-6">
                                    <label class="required" for="category_id">Kategori</label>
                                    <select name="category_id" id="category_id" class="form-control custom-select category_id content" style="width: 100%" required></select>
                                </div>
                                <div class="form-group col-6">
                                    <label for="subcategory_id">Subkategori</label>
                                    <select name="subcategory_id" id="subcategory_id" class="form-control custom-select subcategory_id content" style="width: 100%"></select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="required" for="title">Judul</label>
                                <input type="text" name="title" id="title" class="form-control title" placeholder="Tulis judul konten disini" required value="">
                            </div>
                            <div class="form-group">
                                <label class="required" for="meta">Meta</label>
                                <input type="text" name="meta" id="meta" class="form-control meta" placeholder="Tulis alamat/lokasi disini" required value="">
                            </div>
                            <div id="gambar-content"></div>
                            <div class="form-group">
                                <label for="image">Upload Gambar</label>
                                <input type="file" name="image" id="image" class="form-control image-content" accept="image/*">
                            </div>
                            <div class="form-group">
                                <label for="body">Konten</label>
                                <textarea name="body" id="body" cols="3" class="form-control body"></textarea>
                            </div>
                            <hr>
                            <button type="button" class="btn btn-secondary btn-sm btn-add-attribute" data-toggle="modal" data-target="#ModalAttribute">Tambah Atribut</button>
                            <div id="form-group-body">
                                <div class="attribute-group">
                                    <div class="form-group mb-3">
                                        <label class="required" for="attribute_id">Attribute</label>
                                        <select name="attribute_id[]" id="attribute_id" class="form-control custom-select get-attribute attribute_id" style="width: 100%" required></select>
                                    </div>
                                    <input type="text" id="attribute_value_id[]" name="attribute_value_id" class="form-control attribute_value_id" readonly>
                                    <div class="form-group">
                                        <label class="required" for="description">Deskripsi Attribut</label>
                                        <textarea name="description[]" id="description" cols="3" class="form-control description" required placeholder="Masukan deskripsi attribut"></textarea>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-6">
                                            <label class="required" for="order">Order</label>
                                            <input type="number" name="order[]" id="order" class="form-control order" placeholder="cth:0" required>
                                        </div>
                                        <div class="col-6">
                                            <button type="button" class="btn btn-sm btn-secondary btn-hapus-attribute" style="width: 100%;margin-top:2.5em">Hapus</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button type="button" id="tambah-attribute" class="btn btn-sm btn-secondary tambah-attribute" style="width:100%">Tambah Attribut</button>
                        </div>
                        <div class="col-4">
                            <input type="hidden" name="id_featureValue" id="id_featureValue" class="form-control id_featureValue" value="">
                            <div class="form-group">
                                <label for="feature_id">Pilih Penempatan untuk ditampilkan</label>
                                <select name="feature_id" id="feature_id" class="form-control custom-select feature_id content" style="width: 100%"></select>
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
