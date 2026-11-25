@props([
    'title',
    'subtitle' => null,
    'backUrl' => null,
    'backLabel' => 'Kembali ke Beranda',
])

<section {{ $attributes->merge(['class' => 'tc-page-banner']) }}>
    <div class="container">
        @if($backUrl)
            <a href="{{ $backUrl }}" class="text-white-50 text-decoration-none small fw-semibold d-inline-flex align-items-center gap-1 mb-3">
                <i class="bi bi-arrow-left"></i>{{ $backLabel }}
            </a>
        @endif

        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-end gap-3">
            <div>
                <h1 class="mb-2" style="font-size: clamp(1.7rem, 3vw, 2.35rem);">{{ $title }}</h1>
                @if(isset($subtitleSlot))
                    <p class="mb-0 tc-dark-copy">{{ $subtitleSlot }}</p>
                @elseif($subtitle)
                    <p class="mb-0 tc-dark-copy">{{ $subtitle }}</p>
                @endif
            </div>

            @if(isset($actions))
                {{ $actions }}
            @endif
        </div>
    </div>
</section>
