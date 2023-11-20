<div id="ModalGallery" class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog"
    aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <form id="FormGallery" enctype="multipart/form-data">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" style="margin: 0 auto">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Form Galeri</h5>
                    <button type="button" class="close close-gallery" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="content_id" id="content_id" class="form-control content_id" value="" readonly>
                    <div class="form-group">
                        <label for="image"><strong>Upload Gambar</strong></label>
                        <input type="file" name="image_gallery[]" id="image" class="form-control image" accept="image/*" multiple>
                        <small class="text-primary">*Disarankan resolusi untuk upload galeri konten adalah: 800x800</small>
                    </div>
                    <div id="koleksi-galeri" class="d-none my-3 text-center">

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
