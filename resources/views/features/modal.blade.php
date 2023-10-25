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
                    <input type="hidden" class="form-control id" id="id" name="id">
                    <div class="form-group">
                        <label class="required" for="title">Judul</label>
                        <input type="text" class="form-control title" id="title" name="title" placeholder="Tulis judul disini" required>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-6">
                            <label class="required" for="order">Order</label>
                            <input type="number" name="order" id="order" class="form-control number order" placeholder="0" required>
                        </div>
                        <div class="form-group col-6">
                            <label class="required" class="required" for="published">Status</label>
                            <select name="published" id="published" class="form-control custom-select published" required>
                                <option selected disabled>Pilih status publish</option>
                                <option value="0">Tidak dipublish</option>
                                <option value="1">Publish</option>
                            </select>
                        </div>
                    </div>
                    <div id="gambar-feature"></div>
                    <div class="form-group">
                        <label for="image">Upload Gambar</label>
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
