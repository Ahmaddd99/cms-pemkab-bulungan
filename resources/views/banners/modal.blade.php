<div id="ModalBanner" class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog"
    aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <form id="FormBanner" enctype="multipart/form-data">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" style="margin: 0 auto">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Form Banner</h5>
                    <button type="button" class="close close-banner" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" class="form-control id" id="id" name="id">
                    <div class="form-group mb-3">
                        <label class="required" for="keterangan">Keterangan</label>
                        <input type="text" class="form-control keterangan" id="keterangan" name="keterangan" placeholder="Tulis keterangan disini" required>
                    </div>
                    <div id="gambar-banner"></div>
                    <div class="form-group">
                        <label for="image">Upload Banner</label>
                        <input type="file" class="form-control image-banner" id="image" name="image">
                        <small class="text-danger">*Disarankan resolusi untuk upload banner adalah: 1200x600</small>
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
