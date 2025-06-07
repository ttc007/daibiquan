<div class="card h-100">
    <div class="product-image">
        <a href="{{route('product.show', $product->id)}}">
            <img src="{{ asset($product->image ?? 'storage/default.png') }}" alt="{{ $product->name }}">
        </a>
    </div>
    <div class="card-body">
        <h5 class="card-title text-truncate" title="{{ $product->name }}">{{ $product->name }}</h5>
        <p class="card-text text-truncate">{{ $product->description }}</p>

        <form action="{{ route('cart.add', $product->id) }}" method="POST">
            @csrf
            <input type="hidden" name="redirect_to_checkout" value="1">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <strong>{{ number_format($product->price, 0, ',', '.') }}đ</strong>
                <div class="input-group input-group-sm" style="width: 82px;">
                    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="changeQuantity(this, -1)">−</button>
                    <input type="text" name="quantity" value="1" min="1" class="form-control text-center quantity-input"/>
                    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="changeQuantity(this, 1)">+</button>
                </div>
            </div>
            <button type="submit" class="btn btn-success w-100 product-add-cart">Đặt ngay</button>
        </form>
    </div>
</div>
