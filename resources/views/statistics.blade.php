<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistik Penjualan - Movie Ticket</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Statistik Penjualan Tiket</h1>
            <a href="{{ route('dashboard') }}" class="text-blue-600 hover:underline">← Kembali ke Dashboard</a>
        </div>

        <!-- Revenue Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-sm font-medium text-gray-500 mb-2">Hari Ini</h3>
                <p class="text-2xl font-bold text-green-600">Rp {{ number_format($dailyRevenue, 0, ',', '.') }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-sm font-medium text-gray-500 mb-2">Bulan Ini</h3>
                <p class="text-2xl font-bold text-blue-600">Rp {{ number_format($monthlyRevenue, 0, ',', '.') }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-sm font-medium text-gray-500 mb-2">Tahun Ini</h3>
                <p class="text-2xl font-bold text-purple-600">Rp {{ number_format($yearlyRevenue, 0, ',', '.') }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-sm font-medium text-gray-500 mb-2">Total Tiket Terjual</h3>
                <p class="text-2xl font-bold text-orange-600">{{ number_format($totalTickets) }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Monthly Revenue Chart -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold mb-4">Pendapatan Bulanan</h3>
                <canvas id="monthlyChart" width="400" height="200"></canvas>
            </div>

            <!-- Top Movies -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold mb-4">Film Terlaris</h3>
                <div class="space-y-4">
                    @foreach($topMovies as $movie)
                    <div class="flex justify-between items-center p-3 bg-gray-50 rounded">
                        <div>
                            <h4 class="font-medium">{{ $movie->title }}</h4>
                            <p class="text-sm text-gray-600">{{ $movie->tickets_sold }} tiket terjual</p>
                        </div>
                        <div class="text-right">
                            <p class="font-semibold text-green-600">Rp {{ number_format($movie->revenue, 0, ',', '.') }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <script>
        // Monthly Revenue Chart
        const ctx = document.getElementById('monthlyChart').getContext('2d');
        const monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        
        const monthlyData = @json($monthlyData);
        const chartData = Array(12).fill(0);
        
        monthlyData.forEach(item => {
            chartData[item.month - 1] = item.revenue;
        });

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: monthNames,
                datasets: [{
                    label: 'Pendapatan (Rp)',
                    data: chartData,
                    borderColor: 'rgb(59, 130, 246)',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    tension: 0.1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + value.toLocaleString('id-ID');
                            }
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>