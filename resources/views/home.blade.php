@extends('layouts.dashboard')
<style>
    body {
        color: #000;
    }

    .card {
        background: #fff;
        color: #333;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        border: none;
    }

    .stat-icon {
        font-size: 3rem;
        color: #6a11cb;
    }

    .chart-container {
        width: 100%;
        height: 400px;
    }

    .card-header {
        background: linear-gradient(135deg, #2575fc, #6a11cb);
        color: #fff;
    }
</style>

@section('content')
    <div class="container py-5">
        <h1 class="text-center mb-5">لوحة التحكم</h1>

        <!-- الإحصائيات -->
        <div class="row text-center">
            <div class="col-md-3">
                <div class="card p-4">
                    <i class="fa-solid fa-users stat-icon mb-3"></i>
                    <h4>المستخدمون</h4>
                    <p class="fs-5"><strong>{{ $usersCount }}</strong></p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card p-4">
                    <i class="fa-solid fa-layer-group stat-icon mb-3"></i>
                    <h4>الأقسام</h4>
                    <p class="fs-5"><strong>{{ $categoriesCount }}</strong></p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card p-4">
                    <i class="fa-solid fa-book stat-icon mb-3"></i>
                    <h4>الكتب</h4>
                    <p class="fs-5"><strong>{{ $booksCount }}</strong></p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card p-4">
                    <i class="fa-solid fa-film stat-icon mb-3"></i>
                    <h4>الوسائط</h4>
                    <p class="fs-5"><strong>{{ $mediaCount }}</strong></p>
                </div>
            </div>
        </div>

        <!-- الرسوم البيانية -->
        <div class="mt-5">
            <h3 class="mb-4">إحصائيات الأنشطة</h3>
            <div class="chart-container">
                <canvas id="activityChart"></canvas>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // بيانات الديناميكية للأنشطة
        const months = {!! json_encode($months) !!};
        const mediaData = {!! json_encode($mediaData) !!};
        const bookData = {!! json_encode($bookData) !!};

        const ctx = document.getElementById('activityChart').getContext('2d');
        const activityChart = new Chart(ctx, {
            type: 'bar', // يمكن تغييره إلى 'line' أو 'pie'
            data: {
                labels: months,
                datasets: [{
                    label: 'نشاط الوسائط',
                    data: mediaData,
                    backgroundColor: 'rgba(106, 17, 203, 0.5)',
                    borderColor: 'rgba(106, 17, 203, 1)',
                    borderWidth: 1
                }, {
                    label: 'نشاط الكتب',
                    data: bookData,
                    backgroundColor: 'rgba(37, 117, 252, 0.5)',
                    borderColor: 'rgba(37, 117, 252, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
@stop
