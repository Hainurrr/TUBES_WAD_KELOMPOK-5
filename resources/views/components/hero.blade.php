<div class="position-relative w-100 d-flex align-items-center justify-content-center bg-light overflow-hidden" style="min-height: 500px; height: 60vh;">
    <div class="position-absolute top-0 start-0 w-100 h-100 z-0">
        <img src="https://images.unsplash.com/photo-1638569099509-2f46eb4bb94e?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&q=80&w=1080" 
             alt="University Canteen" 
             class="w-100 h-100 object-fit-cover zoom-effect">
        <div class="position-absolute top-0 start-0 w-100 h-100 bg-dark opacity-50"></div>
    </div>

    <div class="position-relative z-1 w-100 px-3 py-5" style="max-width: 800px;" data-aos="fade-up" data-aos-duration="1000">
        <div class="bg-white rounded-4 shadow-lg p-4 p-md-5 text-center transition-hover mt-5 mt-md-0">
            <h1 class="fw-bold text-dark mb-4 display-6">
                Mau Makan di Mana Hari Ini?
            </h1>

            <form action="{{ route('search') }}" method="GET">
                <div class="d-flex flex-col flex-md-row gap-2 justify-content-center mx-auto p-1 bg-light rounded-pill border" style="max-width: 600px;">
                    <div class="flex-grow-1 text-start">
                        <div class="input-group input-group-lg border-0">
                            <span class="input-group-text bg-transparent border-0 ps-4">
                                <i class="bi bi-geo-alt-fill text-maroon"></i>
                            </span>
                            <select name="category" class="form-select border-0 bg-transparent shadow-none ps-2" style="height: 50px; cursor: pointer;">
                                <option value="" selected>Semua Lokasi / Kategori</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-maroon fw-bold px-5 rounded-pill shadow-sm" style="background-color: #800000; height: 50px;">
                        Cari
                    </button>
                </div>
            </form>
            
            <p class="mt-4 text-secondary small fw-medium">
                <i class="bi bi-shop me-1"></i> Temukan berbagai pilihan kuliner terbaik di lingkungan kampus
            </p>
        </div>
    </div>
</div>