<div class="order-table-wrapper">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Ảnh</th>
                <th>Tên</th>
                <th>Giá</th>
                <th style="width:350px">Số lượng</th>
                <th>Tổng</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @php $total = 0; @endphp
            @foreach ($cart as $id => $item)
                @php $subtotal = $item['price'] * $item['quantity']; $total += $subtotal; @endphp
                <tr>
                    <td width="80">
                        @if($item['image'])
                            <div class="product-image">
                                <a href="{{route('product.show', $id)}}"><img src="{{ asset($item['image']) }}"></a>
                            </div>
                        @else
                            <div class="product-image">
                              <img src="{{ asset('storage/default.png') }}">
                            </div>
                        @endif
                    </td>
                    <td>{{ $item['name'] }}</td>
                    <td>{{ number_format($item['price'], 0, ',', '.') }}đ</td>
                    <td>
                    <div class="d-flex align-items-center">
                        <div class="input-group input-group-sm" style="width: 82px;">
                            <a type="button" class="btn btn-outline-secondary btn-sm"  href="{{ route('cart.decrease', $id) }}">−</a>
                            <input type="text" name="quantity" value="{{$item['quantity']}}" min="1" class="form-control text-center quantity-input" data="{{$id}}"/>
                            <a type="button" class="btn btn-outline-secondary btn-sm" href="{{ route('cart.increase', $id) }}">+</a>
                        </div>
                        
                        <form action="{{ route('cart.update', $id) }}" method="POST" style="display:inline;" id="form{{$id}}">
                            @csrf
                            <input type="hidden" name="quantity" value="{{ $item['quantity'] }}" min="1" style="width:60px; text-align:center;" id="input{{$id}}"/>
                            
                        </form>
                        
                    </div>
                </td>
                    <td>{{ number_format($subtotal, 0, ',', '.') }}đ</td>
                    <td>
                        <form action="{{ route('cart.remove', $id) }}" method="POST">
                            @csrf
                            <button class="btn btn-danger btn-sm">Xoá</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            <tr>
                <td colspan="4" class="text-end"><strong>Tổng cộng:</strong></td>
                <td colspan="2"><strong>{{ number_format($total, 0, ',', '.') }}đ</strong></td>
            </tr>
        </tbody>
    </table>
</div>