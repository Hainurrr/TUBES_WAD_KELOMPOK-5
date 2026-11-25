@props(['showValidation' => false])

@if(session('success'))
    <div class="alert alert-success border-0 rounded-4 d-flex align-items-center justify-content-between mb-4 px-4 py-3" role="alert">
        <span class="fw-semibold"><i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}</span>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger border-0 rounded-4 d-flex align-items-center justify-content-between mb-4 px-4 py-3" role="alert">
        <span class="fw-semibold"><i class="bi bi-exclamation-circle-fill me-2"></i>{{ session('error') }}</span>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
    </div>
@endif

@if($showValidation && $errors->any())
    <div class="alert alert-danger border-0 rounded-4 small mb-4" role="alert">
        <ul class="mb-0 ps-3">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
