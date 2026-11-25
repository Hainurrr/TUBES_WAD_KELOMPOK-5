@props([
    'menuId',
    'qty',
    'itemName' => 'menu',
    'editable' => true,
])

<div class="tc-qty-stepper">
    <form action="{{ route('cart.update') }}" method="POST" class="m-0">
        @csrf
        <input type="hidden" name="id_menu" value="{{ $menuId }}">
        <input type="hidden" name="action" value="decrease">
        <button type="submit" class="btn btn-sm tc-qty-btn tc-qty-btn-minus" aria-label="Kurangi jumlah {{ $itemName }}">
            <i class="bi bi-dash"></i>
        </button>
    </form>

    @if($editable)
        <form action="{{ route('cart.update') }}" method="POST" class="m-0">
            @csrf
            <input type="hidden" name="id_menu" value="{{ $menuId }}">
            <input type="hidden" name="action" value="set">
            <input type="number" name="qty" value="{{ $qty }}" min="1"
                   class="form-control form-control-sm tc-mono tc-qty-input tc-no-spinners"
                   onchange="this.form.submit()" aria-label="Jumlah {{ $itemName }}">
        </form>
    @else
        <span class="fw-bold tc-mono px-2 small">{{ $qty }}</span>
    @endif

    <form action="{{ route('cart.update') }}" method="POST" class="m-0">
        @csrf
        <input type="hidden" name="id_menu" value="{{ $menuId }}">
        <input type="hidden" name="action" value="increase">
        <button type="submit" class="btn btn-sm tc-qty-btn tc-qty-btn-plus" aria-label="Tambah jumlah {{ $itemName }}">
            <i class="bi bi-plus"></i>
        </button>
    </form>
</div>
