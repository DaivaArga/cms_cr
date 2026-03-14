<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Penjualan - Inventory360</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        ::-webkit-scrollbar { width: 8px; height: 8px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: #c1c1c1; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #a8a8a8; }
    </style>
</head>
<body class="bg-gray-50 font-sans antialiased overflow-hidden">

    <div class="flex h-screen w-full">
        
        @include('admin.sidebar')

        <main class="flex-1 flex flex-col h-screen overflow-hidden bg-gray-50">
            
            <header class="bg-white border-b border-gray-200 px-8 py-4 flex justify-between items-center shrink-0">
                <h1 class="text-xl font-bold text-gray-800">Laporan Penjualan</h1>
                <div class="flex items-center gap-4">
                    <button class="p-2 text-gray-500 hover:bg-gray-100 rounded-full">
                        <i data-lucide="bell" class="w-5 h-5"></i>
                    </button>
                    <div class="flex gap-2">
                        <a href="/dev/reports/export/excel?type={{ $reportType }}" class="px-4 py-2 bg-green-600 text-white text-sm rounded-md hover:bg-green-700 transition flex items-center gap-2">
                            <i data-lucide="file-text" class="w-4 h-4"></i>
                            Excel
                        </a>
                        <a href="/dev/reports/export/pdf?type={{ $reportType }}" class="px-4 py-2 bg-red-600 text-white text-sm rounded-md hover:bg-red-700 transition flex items-center gap-2">
                            <i data-lucide="file-pdf" class="w-4 h-4"></i>
                            PDF
                        </a>
                        <a href="/dev/reports/scheduled" class="px-4 py-2 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700 transition flex items-center gap-2">
                            <i data-lucide="clock" class="w-4 h-4"></i>
                            Terjadwal
                        </a>
                    </div>
                </div>
            </header>

            <div class="flex-1 overflow-y-auto p-8">
                <div class="max-w-7xl mx-auto space-y-6">

                    <!-- Report Type Selector -->
                    <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm">
                        <div class="flex flex-wrap gap-4 items-center">
                            <label class="text-sm font-medium text-gray-700">Tipe Laporan:</label>
                            <div class="flex gap-2">
                                <a href="/dev/reports?type=daily{{ isset($selectedCategory) ? '&category=' . $selectedCategory : '' }}" 
                                   class="px-4 py-2 rounded-lg text-sm font-medium transition {{ $reportType === 'daily' ? 'bg-[#c8d44e] text-black' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                                    Harian
                                </a>
                                <a href="/dev/reports?type=weekly{{ isset($selectedCategory) ? '&category=' . $selectedCategory : '' }}" 
                                   class="px-4 py-2 rounded-lg text-sm font-medium transition {{ $reportType === 'weekly' ? 'bg-[#c8d44e] text-black' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                                    Mingguan
                                </a>
                                <a href="/dev/reports?type=monthly{{ isset($selectedCategory) ? '&category=' . $selectedCategory : '' }}" 
                                   class="px-4 py-2 rounded-lg text-sm font-medium transition {{ $reportType === 'monthly' ? 'bg-[#c8d44e] text-black' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                                    Bulanan
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- FR-L04: Filters -->
                    <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm">
                        <h3 class="text-sm font-semibold text-gray-800 mb-4">Filter</h3>
                        <form method="GET" action="/dev/reports" class="grid grid-cols-1 md:grid-cols-5 gap-4">
                            <input type="hidden" name="type" value="{{ $reportType }}">
                            
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Kategori</label>
                                <select name="category" onchange="this.form.submit()" class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 text-gray-700 focus:ring-2 focus:ring-[#c8d44e]">
                                    @foreach($categories as $key => $label)
                                        <option value="{{ $key }}" {{ $selectedCategory === $key ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Metode Pembayaran</label>
                                <select name="payment" onchange="this.form.submit()" class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 text-gray-700 focus:ring-2 focus:ring-[#c8d44e]">
                                    @foreach($paymentMethods as $key => $label)
                                        <option value="{{ $key }}" {{ $selectedPayment === $key ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Dari Tanggal</label>
                                <input type="date" name="date_from" value="{{ $dateFrom }}" class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 text-gray-700 focus:ring-2 focus:ring-[#c8d44e]">
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Hingga Tanggal</label>
                                <input type="date" name="date_to" value="{{ $dateTo }}" class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 text-gray-700 focus:ring-2 focus:ring-[#c8d44e]">
                            </div>

                            <div class="flex items-end">
                                <button type="submit" class="w-full px-4 py-2 bg-[#c8d44e] text-black text-sm font-semibold rounded-lg hover:bg-opacity-90 transition">
                                    Terapkan Filter
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Daily Report -->
                    @if($reportType === 'daily')
                        <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm">
                            <h3 class="text-lg font-semibold text-gray-800 mb-6">Laporan Penjualan Harian - Rincian Jam-jaman</h3>
                            
                            <div class="overflow-x-auto">
                                <table class="w-full text-sm">
                                    <thead>
                                        <tr class="border-b border-gray-200">
                                            <th class="text-left py-3 px-4 text-gray-700 font-semibold">Periode Waktu</th>
                                            <th class="text-right py-3 px-4 text-gray-700 font-semibold">Transaksi</th>
                                            <th class="text-right py-3 px-4 text-gray-700 font-semibold">Pendapatan</th>
                                            <th class="text-right py-3 px-4 text-gray-700 font-semibold">Rata-rata Transaksi</th>
                                            <th class="text-center py-3 px-4 text-gray-700 font-semibold\">Jam Puncak</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $totalDailyRevenue = 0;
                                            $totalDailyTransactions = 0;
                                        @endphp
                                        @foreach($dailyTransactions as $item)
                                            @php
                                                $totalDailyRevenue += $item['revenue'];
                                                $totalDailyTransactions += $item['transactions'];
                                                $isPeakHour = $item['transactions'] >= 30;
                                            @endphp
                                            <tr class="border-b border-gray-100 hover:bg-gray-50">
                                                <td class="py-4 px-4 text-gray-800 font-medium">{{ $item['hour'] }}</td>
                                                <td class="py-4 px-4 text-right text-gray-700">{{ $item['transactions'] }}</td>
                                                <td class="py-4 px-4 text-right text-gray-800 font-semibold">${{ number_format($item['revenue'], 2) }}</td>
                                                <td class="py-4 px-4 text-right text-gray-700">${{ number_format($item['avg_value'], 2) }}</td>
                                                <td class="py-4 px-4 text-center">
                                                    @if($isPeakHour)
                                                        <span class="inline-block bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs font-semibold">Puncak</span>
                                                    @else
                                                        <span class="inline-block bg-gray-100 text-gray-600 px-3 py-1 rounded-full text-xs font-semibold">Normal</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                        <tr class="bg-gray-50 border-t-2 border-gray-200">
                                            <td class="py-4 px-4 text-gray-800 font-bold">Total</td>
                                            <td class="py-4 px-4 text-right text-gray-800 font-bold">{{ $totalDailyTransactions }}</td>
                                            <td class="py-4 px-4 text-right text-gray-800 font-bold">${{ number_format($totalDailyRevenue, 2) }}</td>
                                            <td class="py-4 px-4 text-right text-gray-800 font-bold">${{ number_format($totalDailyRevenue / $totalDailyTransactions, 2) }}</td>
                                            <td class="py-4 px-4"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif

                    <!-- Weekly Report -->
                    @if($reportType === 'weekly')
                        <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm">
                            <h3 class="text-lg font-semibold text-gray-800 mb-6">Weekly Sales Report - Trend Comparison</h3>
                            
                            <div class="overflow-x-auto">
                                <table class="w-full text-sm">
                                    <thead>
                                        <tr class="border-b border-gray-200">
                                            <th class="text-left py-3 px-4 text-gray-700 font-semibold">Week</th>
                                            <th class="text-right py-3 px-4 text-gray-700 font-semibold">Revenue</th>
                                            <th class="text-right py-3 px-4 text-gray-700 font-semibold">Transactions</th>
                                            <th class="text-right py-3 px-4 text-gray-700 font-semibold">Previous Week</th>
                                            <th class="text-right py-3 px-4 text-gray-700 font-semibold">Growth %</th>
                                            <th class="text-center py-3 px-4 text-gray-700 font-semibold">Trend</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($weeklyData as $week)
                                            @php
                                                $isPositiveGrowth = $week['growth'] >= 0;
                                            @endphp
                                            <tr class="border-b border-gray-100 hover:bg-gray-50">
                                                <td class="py-4 px-4 text-gray-800 font-medium">{{ $week['week'] }}</td>
                                                <td class="py-4 px-4 text-right text-gray-800 font-semibold">${{ number_format($week['revenue']) }}</td>
                                                <td class="py-4 px-4 text-right text-gray-700">{{ $week['transactions'] }}</td>
                                                <td class="py-4 px-4 text-right text-gray-700">${{ number_format($week['prev_revenue']) }}</td>
                                                <td class="py-4 px-4 text-right">
                                                    <span class="inline-block {{ $isPositiveGrowth ? 'text-green-600' : 'text-red-600' }} font-semibold">
                                                        {{ $isPositiveGrowth ? '+' : '' }}{{ number_format($week['growth'], 1) }}%
                                                    </span>
                                                </td>
                                                <td class="py-4 px-4 text-center">
                                                    @if($isPositiveGrowth)
                                                        <i data-lucide="trending-up" class="w-5 h-5 text-green-600 inline"></i>
                                                    @else
                                                        <i data-lucide="trending-down" class="w-5 h-5 text-red-600 inline"></i>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif

                    <!-- Monthly Report -->
                    @if($reportType === 'monthly')
                        <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm">
                            <h3 class="text-lg font-semibold text-gray-800 mb-6">Monthly Sales Report - Performance Summary</h3>
                            
                            <div class="overflow-x-auto">
                                <table class="w-full text-sm">
                                    <thead>
                                        <tr class="border-b border-gray-200">
                                            <th class="text-left py-3 px-4 text-gray-700 font-semibold">Month</th>
                                            <th class="text-right py-3 px-4 text-gray-700 font-semibold">Total Revenue</th>
                                            <th class="text-right py-3 px-4 text-gray-700 font-semibold">Total Transactions</th>
                                            <th class="text-right py-3 px-4 text-gray-700 font-semibold">Avg Value</th>
                                            <th class="text-left py-3 px-4 text-gray-700 font-semibold">Top Product</th>
                                            <th class="text-right py-3 px-4 text-gray-700 font-semibold">Units Sold</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($monthlyData as $month)
                                            <tr class="border-b border-gray-100 hover:bg-gray-50">
                                                <td class="py-4 px-4 text-gray-800 font-medium">{{ $month['month'] }}</td>
                                                <td class="py-4 px-4 text-right text-gray-800 font-semibold">${{ number_format($month['revenue']) }}</td>
                                                <td class="py-4 px-4 text-right text-gray-700">{{ $month['transactions'] }}</td>
                                                <td class="py-4 px-4 text-right text-gray-700">${{ number_format($month['revenue'] / $month['transactions'], 2) }}</td>
                                                <td class="py-4 px-4 text-gray-800 font-medium">{{ $month['top_product'] }}</td>
                                                <td class="py-4 px-4 text-right">
                                                    <span class="inline-block bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-xs font-semibold">
                                                        {{ $month['top_product_qty'] }} units
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif

                    <!-- Filtered Transactions List -->
                    <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm">
                        <h3 class="text-lg font-semibold text-gray-800 mb-6">Detailed Transactions ({{ count($filteredTransactions) }})</h3>
                        
                        @if(count($filteredTransactions) > 0)
                            <div class="overflow-x-auto">
                                <table class="w-full text-sm">
                                    <thead>
                                        <tr class="border-b border-gray-200">
                                            <th class="text-left py-3 px-4 text-gray-700 font-semibold">Date & Time</th>
                                            <th class="text-left py-3 px-4 text-gray-700 font-semibold">Product</th>
                                            <th class="text-left py-3 px-4 text-gray-700 font-semibold">Category</th>
                                            <th class="text-right py-3 px-4 text-gray-700 font-semibold">Quantity</th>
                                            <th class="text-right py-3 px-4 text-gray-700 font-semibold">Amount</th>
                                            <th class="text-left py-3 px-4 text-gray-700 font-semibold">Payment Method</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($filteredTransactions as $transaction)
                                            <tr class="border-b border-gray-100 hover:bg-gray-50">
                                                <td class="py-4 px-4 text-gray-700">{{ $transaction['date'] }} <span class="text-gray-500">{{ $transaction['time'] }}</span></td>
                                                <td class="py-4 px-4 text-gray-800 font-medium">{{ $transaction['product'] }}</td>
                                                <td class="py-4 px-4 text-gray-700">{{ ucfirst(str_replace('_', ' ', $transaction['category'])) }}</td>
                                                <td class="py-4 px-4 text-right text-gray-700">{{ $transaction['quantity'] }}</td>
                                                <td class="py-4 px-4 text-right text-gray-800 font-semibold">${{ number_format($transaction['amount'], 2) }}</td>
                                                <td class="py-4 px-4">
                                                    <span class="inline-block bg-purple-100 text-purple-700 px-3 py-1 rounded-full text-xs font-semibold">
                                                        {{ ucfirst(str_replace('_', ' ', $transaction['payment'])) }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-8 text-gray-500">
                                <i data-lucide="inbox" class="w-10 h-10 mx-auto mb-2 opacity-50"></i>
                                <p>No transactions found for selected filters.</p>
                            </div>
                        @endif
                    </div>

                </div>
            </div>
        </main>
    </div>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>
