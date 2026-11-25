@props([
    'rating' => 0,
    'showValue' => false,
    'sizeClass' => '',
])

<span {{ $attributes->merge(['class' => 'd-inline-flex align-items-center gap-1']) }}
      style="color: var(--tc-amber);"
      aria-label="Rating {{ $rating }} dari 5">
    @for($i = 1; $i <= 5; $i++)
        <i class="bi {{ $i <= $rating ? 'bi-star-fill' : 'bi-star' }} {{ $sizeClass }}" aria-hidden="true"></i>
    @endfor
    @if($showValue)
        <span class="tc-mono small text-dark ms-1">({{ $rating }})</span>
    @endif
</span>
