@extends('layouts.admin')

@section('title', 'Danh sách order')

@section('content')

<div class="mb-3 d-flex justify-content-between align-items-center">
    <h3>Danh sách order</h3>
</div>

@php
    $statuses = ['Tất cả', 'Mới', 'Đang xử lý', 'Đang giao hàng', 'Đã nhận hàng', 'Hoàn thành', 'Hủy'];
    $currentStatus = request('status') ?? 'Tất cả';
    $statusColors = [
        'Tất cả' => 'secondary',
        'Mới' => 'success',
        'Đang xử lý' => 'warning',
        'Đang giao hàng' => 'info',
        'Đã nhận hàng' => 'primary',
        'Hoàn thành' => 'success',
        'Hủy' => 'danger',
    ];
@endphp

<div class="mb-3 flex-wrap d-flex gap-2">
    @foreach ($statuses as $status)
        <a href="{{ route('admin.orders.index', ['status' => $status]) }}"
           class="btn btn-sm 
           {{ $currentStatus === $status 
                ? 'btn-' . ($statusColors[$status] ?? 'secondary') 
                : 'btn-outline-' . ($statusColors[$status] ?? 'secondary') }}">
            {{ $status }} ({{ $statusCounts[$status] ?? 0 }})
        </a>
    @endforeach
</div>

<table class="table table-bordered table-striped">
    <thead class="table-dark">
        <tr>
            <th>STT</th>
            <th style="min-width:240px">Thông tin khách hàng</th>
            <th>Trạng thái đơn hàng</th>
            <th>Tổng tiền</th>
            <th>Cập nhật trạng thái</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        @foreach ($orders as $index => $order)
        <tr>
            <td>{{ $index + 1}}</td>
            <td>{{ $order->name }}<br>{{ $order->phone }}<br>{{ $order->address }}</td>
            @php
                $statusMap = [
                    'Mới' => ['class' => 'text-primary', 'icon' => '🆕'],
                    'Đang xử lý' => ['class' => 'text-warning', 'icon' => '🔄'],
                    'Đang giao hàng' => ['class' => 'text-info', 'icon' => '🚚'],
                    'Đã nhận hàng' => ['class' => 'text-success', 'icon' => '📦'],
                    'Hoàn thành' => ['class' => 'text-success', 'icon' => '✅'],
                    'Hủy' => ['class' => 'text-danger', 'icon' => '❌'],
                ];

                $statusInfo = $statusMap[$order->status] ?? ['class' => 'text-secondary', 'icon' => '❓'];
            @endphp

            <td>
                <b class="{{ $statusInfo['class'] }}">
                    {{ $statusInfo['icon'] }} {{ $order->status }}
                </b>

            </td>

            <td>{{ number_format($order->total_price) }}đ</td>
            <td>
                <form method="POST" action="{{ route('admin.orders.updateStatus', $order->id) }}">
                    @csrf
                    @method('PUT')
                    <div class="d-flex align-items-center">
                        <select name="status" class="form-select form-select-sm me-2" onchange="this.form.submit()">
                            @php
                                $statuses = ['Mới', 'Đang xử lý', 'Đang giao hàng', 'Đã nhận hàng', 'Hoàn thành', 'Hủy'];
                            @endphp
                            @foreach($statuses as $status)
                                <option value="{{ $status }}" {{ $order->status === $status ? 'selected' : '' }}>{{ $status }}</option>
                            @endforeach
                        </select>
                        <noscript><button class="btn btn-sm btn-primary">Cập nhật</button></noscript>
                    </div>
                </form>

            </td>
            <td>
                <a href="{{ route('admin.orders.show', $order->id) }}" class="text-secondary text-decoration-none">
                    <i class="bi bi-eye"></i> Xem chi tiết đơn hàng
                </a>
            </td>
        </tr>
        @endforeach
        @if($orders->isEmpty())
            <tr>
                <td colspan='6' class='text-center'>Hiện chưa có order nào</td>
            </tr>
        @endif
    </tbody>
</table>

<div class="pagination">
    {{ $orders->links('vendor.pagination.bootstrap-5') }}
</div>
@endsection
