<div id="ModalSub" class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog"
    aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <form id="FormSub" enctype="multipart/form-data">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" style="margin: 0 auto">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Form Subkategori</h5>
                    <button type="button" class="close close-subcategory" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" class="form-control id" id="id" name="id">
                    <div class="form-group mb-3">
                        <label class="required" for="category_id"><strong>Kategori</strong></label>
                        <select name="category_id" id="category_id" class="form-control custom-select category_id" style="width: 100%" required></select>
                    </div>
                    <div class="form-row mb-3">
                        <div class="form-group col-6">
                            <label class="required" for="name"><strong>Nama subkategori</strong></label>
                            <input type="text" class="form-control name" id="name" name="name" placeholder="Tulis subkategori disini" required>
                        </div>
                        <div class="form-group col-6">
                            <label class="required" for="published"><strong>Publish</strong></label>
                            <select name="published" id="published" class="form-control custom-select published" required style="width: 100%">
                                <option selected disabled>Pilih Status</option>
                                <option value="0">Tidak Dipublish</option>
                                <option value="1">Publish</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <input type="hidden" class="form-control current_image_subcategory" name="current_image_subcategory" value="" readonly>
                        <input type="hidden" class="form-control current_placeholder_subcategory" name="current_placeholder_subcategory" value="" readonly>
                    </div>
                    <div class="form-row">
                        <div class="col-6">
                            <div id="gambar-subcategory" class="d-none my-3 text-center">
                                <img src="" alt="" class="img-fluid img-thumbnail" style="width: 50%">
                            </div>
                            <div class="form-group">
                                <label for="image"><strong>Upload Ikon Subkategori</strong></label>
                                <input type="file" class="form-control image-sub" id="image-subcategory" name="image" accept="image/*">
                                <small class="text-primary">*Disarankan resolusi untuk upload ikon subkategori adalah: 512x512</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div id="gambar-placeholder-subcategory" class="my-3 text-center">
                                <img src="{{{asset('placeholder/no_image.png')}}}" alt="" class="img-fluid img-thumbnail" style="width: 50%">
                            </div>
                            <div class="form-group">
                                <label for="image_placeholder"><strong>Upload placeholder</strong></label>
                                <input type="file" class="form-control image-placeholder-sub" id="image-placeholder-sub" name="image_placeholder" accept="image/*">
                                <small class="text-primary">*Disarankan resolusi untuk upload placeholder subkategori adalah: 512x512</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Kembali</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </div>
    </form>
</div>
