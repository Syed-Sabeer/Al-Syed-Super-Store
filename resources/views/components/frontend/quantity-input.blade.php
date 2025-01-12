@props([
    'isDigital' => false,
    'quantity' => 1,
])

<div {{ $attributes->merge(['class' => 'product-quantity-input d-flex quantity']) }}>
    <button class="qtyButton minus" @disabled($isDigital || $quantity == 1)><i class="la la-minus"></i></button>
    <input type="number" type="number" min="1" name="quantity" step="1" value="{{ $quantity }}" @readonly($isDigital) autocomplete="off">
    <button type="button" class="qtyButton plus" @disabled($isDigital)><i class="la la-plus"></i></button>
</div>
