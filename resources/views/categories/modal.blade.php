<div id="ModalCategory" class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog"
    aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <form id="FormCategory" enctype="multipart/form-data">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" style="margin: 0 auto">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Form Category</h5>
                    <button type="button" class="close close-category" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" class="form-control id" id="id" name="id">
                    <div class="form-row">
                        <div class="form-group col-6">
                            <label class="required" for="name">Nama Kategori</label>
                            <input type="text" class="form-control name" id="name" name="name" placeholder="Tulis kategori disini" required>
                        </div>
                        <div class="form-group col-6">
                            <label class="required" for="published">Publish</label>
                            <select name="published" id="published" class="custom-select published" required style="width: 100%">
                                <option disabled selected>Pilih status</option>
                                <option value="0">Tidak Dipublish</option>
                                <option value="1">Publish</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <input type="text" class="form-control current_image_category" name="current_image_category" readonly value="">
                        <input type="text" class="form-control current_placeholder_category" name="current_placeholder_category" readonly value="">
                    </div>
                    <div class="form-row">
                        <div class="col-6">
                            <div id="gambar-category" class="d-none my-3 text-center">
                                <img alt="" src="" class="img-fluid img-thumbnail" style="width: 50%" />
                            </div>
                            <div class="form-group">
                                <label for="image">Upload Ikon Kategori</label>
                                <input type="file" class="form-control image-category" name="image" accept="image/*">
                            </div>
                        </div>
                        <div class="col-6">
                            <div id="gambar-placeholder-category" class="my-3 text-center">
                                <img alt="" src="{{asset('placeholder/no_image.png')}}" class="img-fluid img-thumbnail" style="width: 50%" />
                            </div>
                            <div class="form-group">
                                <label for="image_placeholder">Upload Placeholder</label>
                                <input type="file" class="form-control image-placeholder-category" name="image_placeholder" accept="image/*">
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
