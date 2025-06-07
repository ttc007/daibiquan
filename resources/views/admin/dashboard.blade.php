@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
    /*.dashboard-charts {
        display: flex;
        gap: 20px;
        flex-wrap: wrap;
    }
    .chart-container {
        flex: 1;
        min-width: 300px;
    }*/
</style>

<div class="dashboard-charts">
    <h3 class="text-center">Thống kê theo</h3>
    <div class="mb-5 mt-2 text-center">
        <button
           class="filter-btn btn btn-outline-success btn-sm active px-2"  data-type="day">
            Ngày
        </button>
        <button
           class="filter-btn btn btn-outline-success btn-sm" data-type="week">
            Tuần
        </button>
        <button
           class="filter-btn btn btn-outline-success btn-sm" data-type="month">
            Tháng
        </button>
    </div>
    <div class="row">
        <div class="col-md-6">
            <h4>Lượt truy cập</h4>
            <canvas id="visitChart" width="400" height="300"></canvas>
        </div>
        <div class="col-md-6">
            <h4>Doanh thu</h4>
            <canvas id="ordersRevenueChart" width="400" height="300"></canvas>
        </div>
    </div>
    
</div>

<script>
    function loadDashboardData(type = 'day') {
        fetch(`{{ route("api.visits") }}?type=${type}`)
        .then(res => res.json())
        .then(data => {
            renderVisitChart(data.visits);
            renderOrderChart(data.orders);
        });
    }

    document.querySelectorAll('.filter-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const type = this.dataset.type;

            // Xóa class 'active' khỏi tất cả nút
            document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));

            // Thêm class 'active' cho nút được click
            this.classList.add('active');

            // Gọi hàm load dữ liệu
            loadDashboardData(type);
        });
    });


    // Tải mặc định loại 'day'
    loadDashboardData();

    let visitChartInstance, orderChartInstance;

    function renderVisitChart(visits) {
        const labels = visits.map(item => item.date);
        const counts = visits.map(item => item.count);

        if (visitChartInstance) visitChartInstance.destroy();

        visitChartInstance = new Chart(document.getElementById('visitChart').getContext('2d'), {
            type: 'bar',
            data: {
                labels,
                datasets: [{
                    label: 'Lượt truy cập',
                    data: counts,
                    backgroundColor: 'rgba(75, 192, 192, 0.8)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1,
                            precision: 0,
                            callback: value => Number.isInteger(value) ? value : ''
                        }
                    }
                }
            }
        });
    }

    function renderOrderChart(orders) {
        const labels = orders.map(o => o.date);
        const totalHT = orders.map(o => o.total_hoanthanh);
        const totalOther = orders.map(o => o.total_khac);
        const totalCancel = orders.map(o => o.total_huy);

        if (orderChartInstance) orderChartInstance.destroy();

        orderChartInstance = new Chart(document.getElementById('ordersRevenueChart').getContext('2d'), {
            type: 'bar',
            data: {
                labels,
                datasets: [
                    {
                        label: 'Hoàn thành',
                        data: totalHT,
                        backgroundColor: 'rgba(25, 135, 84, 0.8)',
                        borderColor: 'rgba(25, 135, 84, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Chưa hoàn thành',
                        data: totalOther,
                        backgroundColor: 'rgba(255, 193, 7, 0.7)',
                        borderColor: 'rgba(255, 193, 7, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Hủy',
                        data: totalCancel,
                        backgroundColor: 'rgba(175, 150, 156, 0.7)',
                        borderColor: 'rgba(175, 150, 156, 1)',
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
                            precision: 0,
                            callback: value => value.toLocaleString()
                        }
                    }
                }
            }
        });
    }

</script>
@endsection
