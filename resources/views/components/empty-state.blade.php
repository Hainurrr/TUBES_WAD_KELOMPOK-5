@props([
    'icon' => 'bi-inbox',
    'title',
    'description' => null,
    'actionUrl' => null,
    'actionLabel' => null,
    'headingTag' => 'h3',
    'headingClass' => 'h5',
])

<div {{ $attributes->merge(['class' => 'tc-card p-5 text-center']) }}>
    <i class="bi {{ $icon }} fs-1 text-muted d-block mb-2" aria-hidden="true"></i>
    <{{ $headingTag }} class="tc-display {{ $headingClass }} fw-bold mb-1">{{ $title }}</{{ $headingTag }}>
    @if($description)
        <p class="text-secondary small {{ $actionUrl ? 'mb-3' : 'mb-0' }}">{{ $description }}</p>
    @endif
    @if($actionUrl && $actionLabel)
        <a href="{{ $actionUrl }}" class="btn btn-tc-primary btn-sm px-4 text-decoration-none">{{ $actionLabel }}</a>
    @endif
</div>
