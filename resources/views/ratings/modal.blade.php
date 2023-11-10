<div id="ModalRating" class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog"
    aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <form id="FormRating" enctype="multipart/form-data">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" style="margin: 0 auto">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Form Rating</h5>
                    <button type="button" class="close close-rating" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" class="form-control id" id="id" name="id" value="">
                    <div class="form-group">
                        <label class="required" for="name"><strong>Nama</strong></label>
                        <input type="text" class="form-control name" id="name" name="name" placeholder="Tulis judul disini" value="" required>
                    </div>
                    <div id="preview-icon" class="d-none text-center my-3">
                        <img src="" alt="" class="img-fluid img-thumbnail">
                    </div>
                    <div>
                        <input type="hidden" class="form-control current_icon" name="current_icon" value="" readonly>
                    </div>
                    <div class="form-group">
                        <label for="icon"><strong>Upload Gambar</strong></label>
                        <input type="file" class="form-control icon-feature" id="icon" name="icon" accept="image/*">
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
