@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
    .dashboard-charts {
        display: flex;
        gap: 20px;
        flex-wrap: wrap;
    }
    .chart-container {
        flex: 1;
        min-width: 300px;
    }
</style>

<div class="dashboard-charts">
    <div class="chart-container">
        <h4>Lượt truy cập theo ngày</h4>
        <canvas id="visitChart" width="400" height="300"></canvas>
    </div>
    <div class="chart-container">
        <h4>Số đơn hàng & Doanh thu theo ngày</h4>
        <canvas id="ordersRevenueChart" width="400" height="300"></canvas>
    </div>
</div>

<script>
fetch('{{ route("api.visits") }}')
.then(res => res.json())
.then(res => {
    const visits = res.visits;
    const orders = res.orders;

    // === Chart 1: Lượt truy cập ===
    const visitLabels = visits.map(item => item.date);
    const visitCounts = visits.map(item => item.count);

    new Chart(document.getElementById('visitChart').getContext('2d'), {
        type: 'bar',
        data: {
            labels: visitLabels,
            datasets: [{
                label: 'Lượt truy cập',
                data: visitCounts,
                backgroundColor: 'rgba(75, 192, 192, 0.6)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0,
                        callback: val => Number.isInteger(val) ? val : ''
                    }
                }
            }
        }
    });

    // === Chart 2: Đơn hàng và doanh thu ===
    const orderLabels = orders.map(item => item.date);
    const totalMoi = orders.map(item => item.total_moi);
    const totalHoanthanh = orders.map(item => item.total_hoanthanh);
    const totalKhac = orders.map(item => item.total_khac);

    new Chart(document.getElementById('ordersRevenueChart').getContext('2d'), {
        type: 'bar',
        data: {
            labels: orderLabels,
            datasets: [
                {
                    label: 'Mới',
                    data: totalMoi,
                    backgroundColor: 'rgba(13, 110, 253, 0.7)',
                    borderColor: 'rgba(13, 110, 253, 1)',
                    borderWidth: 1
                },
                {
                    label: 'Hoàn thành',
                    data: totalHoanthanh,
                    backgroundColor: 'rgba(25, 192, 84, 0.7)',
                    borderColor: 'rgba(25, 192, 84, 1)',
                    borderWidth: 1
                },
                {
                    label: 'Khác',
                    data: totalKhac,
                    backgroundColor: 'rgba(201, 203, 207, 0.7)',
                    borderColor: 'rgba(201, 203, 207, 1)',
                    borderWidth: 1
                }
            ]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return value.toLocaleString('vi-VN');
                        }
                    }
                }
            }
        }
    });

});
</script>
@endsection
