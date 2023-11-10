<div id="ModalFeature" class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog"
    aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <form id="FormFeature" enctype="multipart/form-data">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" style="margin: 0 auto">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Form Feature</h5>
                    <button type="button" class="close close-feature" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" class="form-control id" id="id" name="id" value="">
                    <div class="form-group">
                        <label class="required" for="title"><strong>Judul</strong></label>
                        <input type="text" class="form-control title-feature" id="title" name="title" placeholder="Tulis judul disini" value="" required>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-2">
                            <label for="order"><strong>Order</strong></label>
                            <input type="number" name="order" id="order" class="form-control text-center number order" value="0" onclick="this.select()" required>
                        </div>
                        <div class="form-group col-4">
                            <label class="required" class="required" for="published"><strong>Status</strong></label>
                            <select name="published" id="published" class="form-control custom-select published" style="width: 100%" required>
                                <option value="" selected disabled>-- Pilih status publish --</option>
                                <option value="0">Tidak dipublish</option>
                                <option value="1">Publish</option>
                            </select>
                        </div>
                    </div>
                    <div id="preview-feature" class="d-none text-center my-3">
                        <img src="" alt="" class="img-fluid img-thumbnail">
                    </div>
                    <div>
                        <input type="hidden" class="form-control current_image_feature" name="current_image_feature" value="" readonly>
                    </div>
                    <div class="form-group">
                        <label for="image"><strong>Upload Gambar</strong></label>
                        <input type="file" class="form-control image-feature" id="image" name="image" accept="image/*">
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
