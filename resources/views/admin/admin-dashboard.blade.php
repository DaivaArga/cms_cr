<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dasbor Admin - CritaSena</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        ::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }
    </style>
</head>

<body class="bg-gray-50 font-sans antialiased overflow-hidden">

    <div class="flex h-screen w-full">

        @include('admin.sidebar')

        <main class="flex-1 flex flex-col h-screen overflow-hidden bg-gray-50">

            <header class="bg-white border-b border-gray-200 px-8 py-4 flex justify-between items-center shrink-0">
                <h1 class="text-xl font-bold text-gray-800">Ringkasan Dasbor</h1>
                <div class="flex items-center gap-4">
                    <button class="p-2 text-gray-500 hover:bg-gray-100 rounded-full">
                        <i data-lucide="bell" class="w-5 h-5"></i>
                    </button>
                    <button class="px-4 py-2 bg-black text-white text-sm rounded-md hover:bg-gray-800 transition">
                        Unduh Laporan
                    </button>
                </div>
            </header>

            <div class="flex-1 overflow-y-auto p-8">
                <div class="max-w-7xl mx-auto space-y-6">

                    <!-- FR-D04: Period Filter -->
                    <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm">
                        <div class="flex items-center gap-4">
                            <label for="periodFilter" class="text-sm font-medium text-gray-700">Pilih Periode:</label>
                            <select id="periodFilter" onchange="updateDashboard(this.value)"
                                class="text-sm border border-gray-300 rounded-md text-gray-700 bg-white px-3 py-2 outline-none focus:ring-2 focus:ring-[#c8d44e]">
                                @foreach($periods as $period)
                                    <option value="{{ $period }}" {{ $period === $selectedPeriod ? 'selected' : '' }}>
                                        {{ ucfirst($period) }}</option>
                                @endforeach
                            </select>
                            <span class="text-xs text-gray-500 ml-auto">Terakhir diperbarui: <span id="lastUpdate">baru
                                    saja</span></span>
                        </div>
                    </div>

                    <!-- FR-D01: Dashboard Overview Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm">
                            <div class="flex items-start justify-between">
                                <div>
                                    <span class="text-gray-500 text-sm">Total Pendapatan</span>
                                    <div class="text-2xl font-bold mt-2 text-gray-800">
                                        ${{ number_format($totalRevenue, 0) }}</div>
                                </div>
                                <i data-lucide="dollar-sign" class="w-6 h-6 text-[#c8d44e]"></i>
                            </div>
                            <p class="text-xs text-green-600 mt-2">+12,5% dari periode lalu</p>
                        </div>

                        <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm">
                            <div class="flex items-start justify-between">
                                <div>
                                    <span class="text-gray-500 text-sm">Total Transaksi</span>
                                    <div class="text-2xl font-bold mt-2 text-gray-800">
                                        {{ number_format($totalTransactions, 0) }}</div>
                                </div>
                                <i data-lucide="shopping-cart" class="w-6 h-6 text-blue-500"></i>
                            </div>
                            <p class="text-xs text-green-600 mt-2">+8,3% dari periode lalu</p>
                        </div>

                        <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm">
                            <div class="flex items-start justify-between">
                                <div>
                                    <span class="text-gray-500 text-sm">Produk Terjual</span>
                                    <div class="text-2xl font-bold mt-2 text-gray-800">
                                        {{ number_format($totalProductsSold, 0) }}</div>
                                </div>
                                <i data-lucide="package" class="w-6 h-6 text-purple-500"></i>
                            </div>
                            <p class="text-xs text-green-600 mt-2">+15,2% dari periode lalu</p>
                        </div>

                        <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm">
                            <div class="flex items-start justify-between">
                                <div>
                                    <span class="text-gray-500 text-sm">Rata-rata Transaksi</span>
                                    <div class="text-2xl font-bold mt-2 text-gray-800">
                                        ${{ number_format($avgTransactionValue, 2) }}</div>
                                </div>
                                <i data-lucide="trending-up" class="w-6 h-6 text-orange-500"></i>
                            </div>
                            <p class="text-xs text-green-600 mt-2">+5,8% dari periode lalu</p>
                        </div>
                    </div>

                    <!-- FR-D02: Sales Charts -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm">
                            <div class="flex justify-between items-center mb-6">
                                <h3 class="text-base font-semibold text-gray-800">Ringkasan Penjualan</h3>
                                <span class="text-xs text-gray-500">{{ $selectedPeriod }}</span>
                            </div>
                            <div id="salesOverviewChart" style="min-height: 300px;"></div>
                        </div>

                        <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm">
                            <div class="flex justify-between items-center mb-6">
                                <h3 class="text-base font-semibold text-gray-800">Kinerja Bulanan</h3>
                                <button class="text-xs text-[#c8d44e] font-semibold hover:underline">Lihat
                                    Detail</button>
                            </div>
                            <div id="monthlySalesChart" style="min-height: 300px;"></div>
                        </div>
                    </div>

                    <!-- FR-D03: Top Products -->
                    <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-base font-semibold text-gray-800">Produk Terlaris</h3>
                            <a href="#" class="text-xs text-[#c8d44e] font-semibold hover:underline">Lihat Semua</a>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead>
                                    <tr class="border-b border-gray-200">
                                        <th class="text-left py-3 px-4 text-gray-700 font-semibold">Nama Produk</th>
                                        <th class="text-right py-3 px-4 text-gray-700 font-semibold">Jumlah Terjual</th>
                                        <th class="text-right py-3 px-4 text-gray-700 font-semibold">Pendapatan</th>
                                        <th class="text-right py-3 px-4 text-gray-700 font-semibold">% Total</th>
                                        <th class="text-center py-3 px-4 text-gray-700 font-semibold">Kemajuan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($topProducts as $product)
                                        <tr class="border-b border-gray-100 hover:bg-gray-50">
                                            <td class="py-4 px-4 text-gray-800 font-medium">{{ $product['name'] }}</td>
                                            <td class="py-4 px-4 text-right text-gray-700">
                                                {{ number_format($product['quantity']) }}</td>
                                            <td class="py-4 px-4 text-right text-gray-800 font-semibold">
                                                ${{ number_format($product['revenue']) }}</td>
                                            <td class="py-4 px-4 text-right">
                                                <span
                                                    class="inline-block bg-[#c8d44e] bg-opacity-20 text-[#c8d44e] px-3 py-1 rounded-full text-xs font-semibold">
                                                    {{ $product['contribution'] }}%
                                                </span>
                                            </td>
                                            <td class="py-4 px-4 text-center">
                                                <div class="w-full bg-gray-200 rounded-full h-2">
                                                    <div class="bg-[#c8d44e] h-2 rounded-full"
                                                        style="width: {{ $product['contribution'] * 4 }}%"></div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </main>
    </div>

    <script>
        lucide.createIcons();

        const salesDataRaw = @json($salesOverviewData);
        const monthlyDataRaw = @json($monthlySalesData);

        const getCategories = (data) => data.map(item => item.date);
        const getSeriesData = (data) => data.map(item => item.value);

        function getCommonOptions(dates, dataValues, height) {
            return {
                chart: {
                    type: 'area',
                    height: height,
                    fontFamily: 'inherit',
                    toolbar: { show: false },
                    zoom: { enabled: false }
                },
                series: [{
                    name: 'Sales ($)',
                    data: dataValues
                }],
                colors: ['#c8d44e'],
                fill: {
                    type: 'gradient',
                    gradient: {
                        shadeIntensity: 1,
                        opacityFrom: 0.4,
                        opacityTo: 0.05,
                        stops: [0, 90, 100]
                    }
                },
                dataLabels: { enabled: false },
                stroke: {
                    curve: 'smooth',
                    width: 2
                },
                xaxis: {
                    categories: dates,
                    axisBorder: { show: false },
                    axisTicks: { show: false },
                    labels: { style: { colors: '#9ca3af', fontSize: '12px' } }
                },
                yaxis: {
                    labels: {
                        style: { colors: '#9ca3af', fontSize: '12px' },
                        formatter: (val) => val >= 1000 ? '$' + (val / 1000).toFixed(1) + 'k' : '$' + val
                    }
                },
                grid: {
                    borderColor: '#f3f4f6',
                    strokeDashArray: 4,
                    yaxis: { lines: { show: true } },
                    xaxis: { lines: { show: false } },
                    padding: { top: 0, right: 0, bottom: 0, left: 10 }
                },
                tooltip: {
                    theme: 'light',
                    y: { formatter: (val) => "$" + val.toLocaleString() }
                }
            };
        }

        function renderCharts() {
            const dates1 = getCategories(salesDataRaw);
            const values1 = getSeriesData(salesDataRaw);

            const chart1 = new ApexCharts(
                document.querySelector("#salesOverviewChart"),
                getCommonOptions(dates1, values1, 300)
            );
            chart1.render();

            const dates2 = getCategories(monthlyDataRaw);
            const values2 = getSeriesData(monthlyDataRaw);

            const chart2 = new ApexCharts(
                document.querySelector("#monthlySalesChart"),
                getCommonOptions(dates2, values2, 300)
            );
            chart2.render();
        }

        renderCharts();

        // FR-D04: Period Filter Handler
        function updateDashboard(period) {
            window.location.href = '/dev/admin?period=' + encodeURIComponent(period);
        }

        // FR-D05: Real-time Update (simulated)
        function updateLastUpdateTime() {
            const now = new Date();
            const seconds = Math.floor((Date.now() - Date.parse('2026-03-04')) / 1000);

            let timeStr = '';
            if (seconds < 60) {
                timeStr = 'just now';
            } else if (seconds < 3600) {
                timeStr = Math.floor(seconds / 60) + ' min ago';
            } else if (seconds < 86400) {
                timeStr = Math.floor(seconds / 3600) + ' hour ago';
            } else {
                timeStr = Math.floor(seconds / 86400) + ' days ago';
            }

            document.getElementById('lastUpdate').textContent = timeStr;
        }

        // Update timestamp every minute
        updateLastUpdateTime();
        setInterval(updateLastUpdateTime, 60000);

        // Optional: Auto-refresh dashboard every 5 minutes (uncomment to enable)
        // setInterval(() => {
        //     fetch('/dev/admin?period=' + new URLSearchParams(window.location.search).get('period') || '7 days')
        //         .then(response => response.text())
        //         .then(html => {
        //             location.reload();
        //         });
        // }, 5 * 60 * 1000);

    </script>
</body>

</html>