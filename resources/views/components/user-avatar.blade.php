@props([
    'user',
    'size' => 38,
    'rounded' => 'rounded-circle',
    'alt' => null,
    'id' => null,
])

@php
    $userName = $user?->name ?? 'Pengguna';
    $avatarPath = $user?->avatar;
    $avatarUrl = ($avatarPath && $avatarPath !== 'default_avatar.png')
        ? (\Illuminate\Support\Str::startsWith($avatarPath, 'http') ? $avatarPath : '/storage/' . $avatarPath)
        : 'https://ui-avatars.com/api/?name=' . urlencode($userName) . '&background=B9112E&color=fff&size=' . max(128, (int) $size * 2);
@endphp

<img src="{{ $avatarUrl }}"
     alt="{{ $alt ?? 'Foto profil ' . $userName }}"
     width="{{ $size }}"
     height="{{ $size }}"
     @if($id) id="{{ $id }}" @endif
     {{ $attributes->merge(['class' => $rounded . ' object-fit-cover flex-shrink-0']) }}>
