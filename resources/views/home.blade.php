@extends('layouts.app')

@section('content')
    <h2>Chào mừng đến với quán chay Đại Bi</h2>
    <p>Chuyên các món ăn chay thanh tịnh, ngon miệng, giao tận nơi.</p>

    <script>
        const customerId = localStorage.getItem('customer_id');
        fetch('/api/track-visit', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Customer-ID': customerId,
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({})
        });
    </script>
@endsection
