<div class="modal fade" id="reviewModal" tabindex="-1" aria-labelledby="reviewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 p-2" style="border-radius: 24px;">
            <div class="modal-header border-0 pb-0">
                <h5 class="tc-display fw-bold mb-0" id="reviewModalLabel">Beri Ulasan Menu</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <form action="{{ route('reviews.store') }}" method="POST" id="reviewForm">
                @csrf
                <input type="hidden" name="id_order" id="modalOrderId">

                <div class="modal-body">
                    <div class="mb-3">
                        <label for="modalMenuSelect" class="form-label">Pilih Menu</label>
                        <select class="form-select" name="id_menu" id="modalMenuSelect" required></select>
                    </div>

                    <div class="mb-3 text-center">
                        <div class="small fw-semibold text-secondary mb-2">Pilih Penilaian</div>
                        <div class="rating-stars fs-2 tc-rating-input">
                            @for($s = 1; $s <= 5; $s++)
                                <i class="bi bi-star star-input" data-value="{{ $s }}"></i>
                            @endfor
                        </div>
                        <input type="hidden" name="rating" id="ratingInput" required>
                        <div id="ratingError" class="text-danger small mt-1 d-none">Silakan pilih jumlah bintang terlebih dahulu.</div>
                    </div>

                    <div class="mb-2">
                        <label for="comment" class="form-label">Komentar</label>
                        <textarea class="form-control" id="comment" name="comment" rows="3" placeholder="Bagikan pengalaman rasa atau kecepatan layanan..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-tc-ghost px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-tc-primary px-4">Kirim Ulasan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="editReviewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 p-2" style="border-radius: 24px;">
            <div class="modal-header border-0 pb-0">
                <h5 class="tc-display fw-bold mb-0">Ubah Ulasan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <form action="" method="POST" id="editReviewForm">
                @csrf
                @method('PUT')

                <div class="modal-body">
                    <div class="mb-3">
                        <label for="editMenuName" class="form-label">Menu</label>
                        <input type="text" class="form-control" id="editMenuName" disabled>
                    </div>

                    <div class="mb-3 text-center">
                        <div class="small fw-semibold text-secondary mb-2">Ubah Penilaian</div>
                        <div class="rating-stars fs-2 tc-rating-input">
                            @for($s = 1; $s <= 5; $s++)
                                <i class="bi bi-star star-input" data-value="{{ $s }}"></i>
                            @endfor
                        </div>
                        <input type="hidden" name="rating" id="editRatingInput" required>
                    </div>

                    <div class="mb-2">
                        <label for="editComment" class="form-label">Komentar</label>
                        <textarea class="form-control" id="editComment" name="comment" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-tc-ghost px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-tc-primary px-4">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
