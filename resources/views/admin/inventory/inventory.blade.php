<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Inventori - Inventory360</title>

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
                <h1 class="text-xl font-bold text-gray-800">Manajemen Inventori</h1>
                <div class="flex items-center gap-4">
                    <button class="p-2 text-gray-500 hover:bg-gray-100 rounded-full">
                        <i data-lucide="bell" class="w-5 h-5"></i>
                    </button>
                    <button class="px-4 py-2 bg-black text-white text-sm rounded-md hover:bg-gray-800 transition">
                        Ekspor Data
                    </button>
                </div>
            </header>

            <div class="flex-1 overflow-y-auto p-8">
                <div class="max-w-7xl mx-auto">

                    <!-- Tabs Navigation -->
                    <div class="mb-8 border-b border-gray-200">
                        <div class="flex gap-8">
                            <a href="?tab=overview"
                                class="py-4 px-4 text-sm font-medium border-b-2 {{ $tab === 'overview' ? 'border-black text-black' : 'border-transparent text-gray-600 hover:text-black' }} cursor-pointer">
                                Ringkasan
                            </a>
                            <a href="?tab=raw-materials"
                                class="py-4 px-4 text-sm font-medium border-b-2 {{ $tab === 'raw-materials' ? 'border-black text-black' : 'border-transparent text-gray-600 hover:text-black' }} cursor-pointer">
                                Bahan Baku
                            </a>
                            <a href="?tab=alerts"
                                class="py-4 px-4 text-sm font-medium border-b-2 {{ $tab === 'alerts' ? 'border-black text-black' : 'border-transparent text-gray-600 hover:text-black' }} cursor-pointer">
                                Notifikasi & Riwayat
                            </a>
                            <a href="?tab=suppliers"
                                class="py-4 px-4 text-sm font-medium border-b-2 {{ $tab === 'suppliers' ? 'border-black text-black' : 'border-transparent text-gray-600 hover:text-black' }} cursor-pointer">
                                Pemasok
                            </a>
                            <a href="?tab=opname"
                                class="py-4 px-4 text-sm font-medium border-b-2 {{ $tab === 'opname' ? 'border-black text-black' : 'border-transparent text-gray-600 hover:text-black' }} cursor-pointer">
                                Stock Opname
                            </a>
                            <a href="?tab=reorder"
                                class="py-4 px-4 text-sm font-medium border-b-2 {{ $tab === 'reorder' ? 'border-black text-black' : 'border-transparent text-gray-600 hover:text-black' }} cursor-pointer">
                                Analisis Reorder
                            </a>
                        </div>
                    </div>

                    <!-- TAB: Overview -->
                    @if($tab === 'overview' || $tab === '')
                        <div class="space-y-6">
                            <div class="grid grid-cols-4 gap-6">
                                <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm">
                                    <div class="text-sm text-gray-600 mb-2">Total Bahan Baku</div>
                                    <div class="text-3xl font-bold text-gray-900">{{ count($rawMaterials) }}</div>
                                    <div class="text-xs text-green-600 mt-2">Semua dipantau</div>
                                </div>
                                <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm">
                                    <div class="text-sm text-gray-600 mb-2">Barang Jadi</div>
                                    <div class="text-3xl font-bold text-gray-900">{{ count($finishedGoods) }}</div>
                                    <div class="text-xs text-green-600 mt-2">{{ count($finishedGoods) }} aktif</div>
                                </div>
                                <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm">
                                    <div class="text-sm text-gray-600 mb-2">Item Stok Rendah</div>
                                    <div class="text-3xl font-bold text-red-600">
                                        {{ count(array_filter($rawMaterials, fn($m) => $m['status'] === 'danger')) }}</div>
                                    <div class="text-xs text-red-600 mt-2">Memerlukan perhatian</div>
                                </div>
                                <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm">
                                    <div class="text-sm text-gray-600 mb-2">Nilai Total</div>
                                    <div class="text-3xl font-bold text-gray-900">Rp
                                        {{ number_format(array_sum(array_map(fn($m) => $m['quantity'] * $m['unit_cost'], $rawMaterials)), 0, ',', '.') }}
                                    </div>
                                    <div class="text-xs text-gray-500 mt-2">Bahan baku</div>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-6">
                                <!-- Ringkasan Bahan Baku -->
                                <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm">
                                    <h3 class="text-lg font-bold text-gray-900 mb-4">Stok Bahan Baku</h3>
                                    <div class="space-y-3">
                                        @foreach($rawMaterials as $material)
                                            @php
                                                $percentage = min(($material['quantity'] / $material['min_level']) * 100, 100);
                                                $statusBg = match ($material['status']) {
                                                    'ok' => 'bg-green-500',
                                                    'warning' => 'bg-yellow-500',
                                                    'danger' => 'bg-red-500',
                                                    default => 'bg-gray-500'
                                                };
                                            @endphp
                                            <div
                                                class="flex items-center justify-between pb-3 border-b border-gray-100 last:border-0">
                                                <div>
                                                    <div class="font-semibold text-sm text-gray-900">{{ $material['name'] }}
                                                    </div>
                                                    <div class="text-xs text-gray-500">{{ $material['quantity'] }} /
                                                        {{ $material['min_level'] }} {{ $material['unit'] }}</div>
                                                </div>
                                                <div class="w-20 h-2 bg-gray-200 rounded-full overflow-hidden">
                                                    <div class="{{ $statusBg }} h-full rounded-full"
                                                        style="width: {{ $percentage }}%"></div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <!-- Ringkasan Barang Jadi -->
                                <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm">
                                    <h3 class="text-lg font-bold text-gray-900 mb-4\">Barang Jadi</h3>
                                    <div class="space-y-3">
                                        @foreach($finishedGoods as $product)
                                            @php
                                                $margin = (($product['price'] - $product['cost']) / $product['price']) * 100;
                                            @endphp
                                            <div
                                                class="flex items-center justify-between pb-3 border-b border-gray-100 last:border-0">
                                                <div>
                                                    <div class="font-semibold text-sm text-gray-900">{{ $product['name'] }}
                                                    </div>
                                                    <div class="text-xs text-gray-500">Rp
                                                        {{ number_format($product['price'], 0, ',', '.') }} | Margin
                                                        {{ number_format($margin, 1) }}%</div>
                                                </div>
                                                <div class="text-right">
                                                    <div class="font-semibold text-sm text-gray-900">{{ $product['quantity'] }}
                                                    </div>
                                                    <div class="text-xs text-gray-500">{{ $product['unit'] }}</div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- TAB: Raw Materials -->
                    @if($tab === 'raw-materials')
                        <div class="space-y-6">
                            <div
                                class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm flex justify-between items-center">
                                <h2 class="text-lg font-bold text-gray-900">CRUD Bahan Baku</h2>
                                <button onclick="alert('Formulir tambah bahan baku')"
                                    class="px-4 py-2 bg-black text-white text-sm rounded-md hover:bg-gray-800 transition flex items-center gap-2">
                                    <i data-lucide="plus" class="w-4 h-4"></i> Tambah Bahan
                                </button>
                            </div>

                            <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                                <table class="w-full">
                                    <thead>
                                        <tr class="border-b border-gray-200 bg-gray-50">
                                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600">Bahan</th>
                                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600">Unit</th>
                                            <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600">Saat Ini
                                            </th>
                                            <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600">Min Level
                                            </th>
                                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600">Pemasok</th>
                                            <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600">Harga Unit
                                            </th>
                                            <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600">Status
                                            </th>
                                            <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200">
                                        @foreach($rawMaterials as $material)
                                            @php
                                                $statusBg = match ($material['status']) {
                                                    'ok' => 'bg-green-100 text-green-800',
                                                    'warning' => 'bg-yellow-100 text-yellow-800',
                                                    'danger' => 'bg-red-100 text-red-800',
                                                    default => 'bg-gray-100 text-gray-800'
                                                };
                                                $statusLabel = match ($material['status']) {
                                                    'ok' => 'Cukup',
                                                    'warning' => 'Peringatan',
                                                    'danger' => 'Stok Rendah',
                                                    default => 'Tidak Diketahui'
                                                };
                                            @endphp
                                            <tr class="hover:bg-gray-50 transition">
                                                <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $material['name'] }}
                                                </td>
                                                <td class="px-6 py-4 text-sm text-gray-600">{{ $material['unit'] }}</td>
                                                <td class="px-6 py-4 text-sm text-center font-semibold text-gray-900">
                                                    {{ $material['quantity'] }}</td>
                                                <td class="px-6 py-4 text-sm text-center text-gray-600">
                                                    {{ $material['min_level'] }}</td>
                                                <td class="px-6 py-4 text-sm text-gray-600">{{ $material['supplier'] }}</td>
                                                <td class="px-6 py-4 text-sm text-right text-gray-900">Rp
                                                    {{ number_format($material['unit_cost'], 0, ',', '.') }}</td>
                                                <td class="px-6 py-4 text-center">
                                                    <span
                                                        class="px-2 py-1 text-xs font-semibold rounded-full {{ $statusBg }}">{{ $statusLabel }}</span>
                                                </td>
                                                <td class="px-6 py-4 text-right">
                                                    <button
                                                        class="text-blue-600 hover:text-blue-800 text-sm font-semibold">Edit</button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif

                    <!-- TAB: Notifikasi & Riwayat -->
                    @if($tab === 'alerts')
                        <div class="space-y-6">
                            <!-- Bagian Notifikasi -->
                            <div>
                                <h2 class="text-lg font-bold text-gray-900 mb-4">Notifikasi Aktif</h2>
                                <div class="space-y-3">
                                    @foreach($alerts as $alert)
                                                                @php
                                                                    $severityBg = match ($alert['severity']) {
                                                                        'danger' => 'bg-red-50 border-red-200',
                                                                        'warning' => 'bg-yellow-50 border-yellow-200',
                                                                        'info' => 'bg-blue-50 border-blue-200',
                                                                        default => 'bg-gray-50 border-gray-200'
                                                                    };
                                                                    $severityText = match ($alert['severity']) {
                                                                        'danger' => 'text-red-800',
                                                                        'warning' => 'text-yellow-800',
                                                                        'info' => 'text-blue-800',
                                                                        default => 'text-gray-800'
                                                                    };
                                                                @endphp
                                                                <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm {{ $severityBg }}">
                                                                    <div class="flex justify-between items-start">
                                                                        <div>
                                                                            <h3 class="font-semibold text-gray-900 mb-1">{{ $alert['item'] }}</h3>
                                                                            <p class="text-sm text-gray-600">
                                                                                @if($alert['type'] === 'low_stock')
                                                                                    Saat Ini: {{ $alert['current'] }} (Min: {{ $alert['min_level'] }})
                                                                                @elseif($alert['type'] === 'expiring_soon')
                                                                                    Kadaluarsa: {{ $alert['expires'] }}
                                                                                @endif
                                                                            </p>
                                                                            <p class="text-xs text-gray-500 mt-1">
                                                                                {{ date('d M Y H:i', strtotime($alert['created'])) }}</p>
                                                                        </div>
                                                                        <span class="px-3 py-1 text-xs font-semibold rounded-full {{ match ($alert['severity']) {
                                            'danger' => 'bg-red-200 text-red-800',
                                            'warning' => 'bg-yellow-200 text-yellow-800',
                                            'info' => 'bg-blue-200 text-blue-800',
                                            default => 'bg-gray-200 text-gray-800'
                                        } }}">
                                                                            {{ match ($alert['severity']) { 'danger' => 'Berbahaya', 'warning' => 'Peringatan', 'info' => 'Informasi', default => 'Lainnya'} }}
                                                                        </span>
                                                                    </div>
                                                                </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Bagian Riwayat Pergerakan Stok -->
                            <div>
                                <h2 class="text-lg font-bold text-gray-900 mb-4">Riwayat Pergerakan Stok</h2>
                                <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                                    <table class="w-full">
                                        <thead>
                                            <tr class="border-b border-gray-200 bg-gray-50">
                                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600">Tanggal
                                                    & Waktu</th>
                                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600">Item
                                                </th>
                                                <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600">Tipe
                                                </th>
                                                <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600">Jumlah
                                                </th>
                                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600">Pengguna
                                                </th>
                                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600">Alasan
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-200">
                                            @foreach($history as $entry)
                                                @php
                                                    $typeColor = match ($entry['type']) {
                                                        'out' => 'text-red-600 bg-red-50',
                                                        'in' => 'text-green-600 bg-green-50',
                                                        default => 'text-gray-600 bg-gray-50'
                                                    };
                                                @endphp
                                                <tr class="hover:bg-gray-50 transition">
                                                    <td class="px-6 py-4 text-sm text-gray-600">
                                                        {{ date('d M Y H:i', strtotime($entry['date'])) }}</td>
                                                    <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $entry['item'] }}
                                                    </td>
                                                    <td class="px-6 py-4 text-center">
                                                        <span class="px-2 py-1 text-xs font-semibold rounded {{ $typeColor }}">
                                                            {{ $entry['type'] === 'out' ? '↓ Keluar' : '↑ Masuk' }}
                                                        </span>
                                                    </td>
                                                    <td class="px-6 py-4 text-sm text-center font-semibold text-gray-900">
                                                        {{ $entry['quantity'] }}</td>
                                                    <td class="px-6 py-4 text-sm text-gray-600">{{ $entry['user'] }}</td>
                                                    <td class="px-6 py-4 text-sm text-gray-600">{{ $entry['reason'] }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- TAB: Pemasok -->
                    @if($tab === 'suppliers')
                        <div class="space-y-6">
                            <div
                                class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm flex justify-between items-center">
                                <h2 class="text-lg font-bold text-gray-900">Manajemen Pemasok</h2>
                                <button onclick="alert('Form tambah pemasok baru')"
                                    class="px-4 py-2 bg-black text-white text-sm rounded-md hover:bg-gray-800 transition flex items-center gap-2">
                                    <i data-lucide="plus" class="w-4 h-4"></i> Tambah Pemasok
                                </button>
                            </div>

                            <div class="grid grid-cols-2 gap-6">
                                @foreach($suppliers as $supplier)
                                    @php
                                        $perfPercent = ($supplier['avg_delivery_rating'] / 5) * 100;
                                    @endphp
                                    <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm">
                                        <div class="flex justify-between items-start mb-4">
                                            <div>
                                                <h3 class="font-bold text-gray-900">{{ $supplier['name'] }}</h3>
                                                <p class="text-xs text-gray-500">{{ $supplier['items'] }}</p>
                                            </div>
                                            <div class="text-right">
                                                <div class="text-2xl font-bold text-gray-900">
                                                    {{ $supplier['avg_delivery_rating'] }}</div>
                                                <div class="text-xs text-gray-500">Rating</div>
                                            </div>
                                        </div>

                                        <div class="space-y-2 mb-4 text-sm">
                                            <div class="flex gap-2"><span class="text-gray-600">✉️</span><span
                                                    class="text-gray-900">{{ $supplier['contact'] }}</span></div>
                                            <div class="flex gap-2"><span class="text-gray-600">📞</span><span
                                                    class="text-gray-900">{{ $supplier['phone'] }}</span></div>
                                            <div class="flex gap-2"><span class="text-gray-600">📍</span><span
                                                    class="text-gray-900">{{ $supplier['address'] }}</span></div>
                                        </div>

                                        <div class="grid grid-cols-3 gap-2 mb-4 text-center">
                                            <div class="bg-gray-50 p-3 rounded">
                                                <div class="font-bold text-gray-900">{{ $supplier['lead_time_days'] }}h</div>
                                                <div class="text-xs text-gray-500">Waktu Lead</div>
                                            </div>
                                            <div class="bg-gray-50 p-3 rounded">
                                                <div class="font-bold text-gray-900">{{ $supplier['total_orders'] }}</div>
                                                <div class="text-xs text-gray-500">Pesanan</div>
                                            </div>
                                            <div class="bg-gray-50 p-3 rounded">
                                                <div class="font-bold text-gray-900">{{ round($perfPercent) }}%</div>
                                                <div class="text-xs text-gray-500">Reliabilitas</div>
                                            </div>
                                        </div>

                                        <div class="w-full bg-gray-200 rounded-full h-2">
                                            <div class="bg-black h-2 rounded-full" style="width: {{ $perfPercent }}%"></div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- TAB: Stock Opname -->
                    @if($tab === 'opname')
                        <div class="space-y-6">
                            <div class="grid grid-cols-4 gap-6">
                                <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm">
                                    <div class="text-sm text-gray-600 mb-2">Total Item</div>
                                    <div class="text-3xl font-bold text-gray-900">{{ count($opnameItems) }}</div>
                                </div>
                                <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm">
                                    <div class="text-sm text-gray-600 mb-2">Dihitung</div>
                                    <div class="text-3xl font-bold text-green-600">
                                        {{ count(array_filter($opnameItems, fn($i) => $i['physical_qty'] !== null)) }}</div>
                                </div>
                                <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm">
                                    <div class="text-sm text-gray-600 mb-2">Sisa</div>
                                    <div class="text-3xl font-bold text-yellow-600">
                                        {{ count(array_filter($opnameItems, fn($i) => $i['physical_qty'] === null)) }}</div>
                                </div>
                                <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm">
                                    <div class="text-sm text-gray-600 mb-2">Diskrepansi</div>
                                    <div class="text-3xl font-bold text-red-600">
                                        {{ count(array_filter($opnameItems, fn($i) => $i['variance'] !== null && $i['variance'] !== 0)) }}
                                    </div>
                                </div>
                            </div>

                            <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                                <table class="w-full">
                                    <thead>
                                        <tr class="border-b border-gray-200 bg-gray-50">
                                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600">Item</th>
                                            <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600">Unit</th>
                                            <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600">Qty Sistem
                                            </th>
                                            <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600">
                                                Perhitungan Fisik</th>
                                            <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600">Varians
                                            </th>
                                            <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600">Status
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200">
                                        @foreach($opnameItems as $item)
                                            @php
                                                $isCounted = $item['physical_qty'] !== null;
                                                $hasVariance = $isCounted && $item['variance'] !== 0;
                                                $variancePercent = $isCounted ? round(($item['variance'] / $item['system_qty']) * 100) : null;
                                                $statusColor = !$isCounted ? 'bg-gray-100 text-gray-800' : ($hasVariance ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800');
                                                $statusText = !$isCounted ? 'Tertunda' : ($hasVariance ? 'Diskrepansi' : 'OK');
                                            @endphp
                                            <tr class="hover:bg-gray-50 transition">
                                                <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $item['name'] }}</td>
                                                <td class="px-6 py-4 text-center text-sm text-gray-600">{{ $item['unit'] }}</td>
                                                <td class="px-6 py-4 text-center text-sm font-semibold text-gray-900">
                                                    {{ $item['system_qty'] }}</td>
                                                <td class="px-6 py-4 text-center text-sm">
                                                    @if($isCounted)
                                                        <span class="font-semibold text-gray-900">{{ $item['physical_qty'] }}</span>
                                                    @else
                                                        <input type="number" placeholder="0"
                                                            class="w-16 px-2 py-1 border border-gray-300 rounded text-center">
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 text-center text-sm">
                                                    @if($isCounted)
                                                        <span
                                                            class="font-semibold {{ $item['variance'] > 0 ? 'text-green-600' : 'text-red-600' }}">
                                                            {{ $item['variance'] > 0 ? '+' : '' }}{{ $item['variance'] }}
                                                            ({{ $variancePercent }}%)
                                                        </span>
                                                    @else
                                                        <span class="text-gray-400">-</span>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 text-center">
                                                    <span
                                                        class="px-2 py-1 text-xs font-semibold rounded-full {{ $statusColor }}">{{ $statusText }}</span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif

                    <!-- TAB: Analisis Reorder -->
                    @if($tab === 'reorder')
                        <div class="space-y-6">
                            <div class="bg-blue-50 border border-blue-200 p-6 rounded-xl">
                                <h3 class="font-semibold text-blue-900 mb-2">Cara Menghitung Titik Reorder</h3>
                                <p class="text-sm text-blue-800">Titik Reorder = (Penggunaan Harian Rata-rata × Waktu Lead
                                    Pemasok) + Stok Keamanan</p>
                            </div>

                            <div class="grid grid-cols-2 gap-6">
                                <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm">
                                    <h3 class="font-bold text-gray-900 mb-4">Efisiensi Inventori</h3>
                                    @php
                                        $highEfficiency = count(array_filter($reorderAnalysis, fn($r) => $r['efficiency'] === 'high'));
                                        $mediumEfficiency = count(array_filter($reorderAnalysis, fn($r) => $r['efficiency'] === 'medium'));
                                        $lowEfficiency = count(array_filter($reorderAnalysis, fn($r) => $r['efficiency'] === 'low'));
                                    @endphp
                                    <div class="space-y-3">
                                        <div>
                                            <div class="flex justify-between mb-1">
                                                <span class="text-sm font-semibold text-gray-900">Efisiensi Tinggi</span>
                                                <span class="text-sm text-green-600">{{ $highEfficiency }} item</span>
                                            </div>
                                            <div class="h-2 bg-gray-200 rounded-full">
                                                <div class="h-2 bg-green-500 rounded-full"
                                                    style="width: {{ ($highEfficiency / count($reorderAnalysis)) * 100 }}%">
                                                </div>
                                            </div>
                                        </div>
                                        <div>
                                            <div class="flex justify-between mb-1">
                                                <span class="text-sm font-semibold text-gray-900">Efisiensi Sedang</span>
                                                <span class="text-sm text-yellow-600">{{ $mediumEfficiency }} item</span>
                                            </div>
                                            <div class="h-2 bg-gray-200 rounded-full">
                                                <div class="h-2 bg-yellow-500 rounded-full"
                                                    style="width: {{ ($mediumEfficiency / count($reorderAnalysis)) * 100 }}%">
                                                </div>
                                            </div>
                                        </div>
                                        <div>
                                            <div class="flex justify-between mb-1">
                                                <span class="text-sm font-semibold text-gray-900">Efisiensi Rendah</span>
                                                <span class="text-sm text-red-600">{{ $lowEfficiency }} item</span>
                                            </div>
                                            <div class="h-2 bg-gray-200 rounded-full">
                                                <div class="h-2 bg-red-500 rounded-full"
                                                    style="width: {{ ($lowEfficiency / count($reorderAnalysis)) * 100 }}%">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm">
                                    <h3 class="font-bold text-gray-900 mb-4">Dampak Optimasi</h3>
                                    @php
                                        $currentTotal = array_sum(array_map(fn($r) => $r['current_setting'], $reorderAnalysis));
                                        $recommendedTotal = array_sum(array_map(fn($r) => $r['calculated_reorder'], $reorderAnalysis));
                                        $savingPercent = round((($currentTotal - $recommendedTotal) / $currentTotal) * 100);
                                    @endphp
                                    <div class="space-y-3">
                                        <div class="bg-gray-50 p-4 rounded">
                                            <div class="text-xs text-gray-600 mb-1">Total Level Reorder Saat Ini</div>
                                            <div class="text-2xl font-bold text-gray-900">
                                                {{ number_format($currentTotal, 0) }} unit</div>
                                        </div>
                                        <div class="bg-green-50 p-4 rounded">
                                            <div class="text-xs text-green-600 mb-1">Total Level Reorder Rekomendasi</div>
                                            <div class="text-2xl font-bold text-green-600">
                                                {{ number_format($recommendedTotal, 0) }} unit</div>
                                        </div>
                                        <div class="bg-green-100 border border-green-300 p-4 rounded">
                                            <div class="text-xs text-green-800 mb-1 font-semibold">Penghematan Potensial
                                            </div>
                                            <div class="text-2xl font-bold text-green-700">{{ $savingPercent }}%</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                                <table class="w-full">
                                    <thead>
                                        <tr class="border-b border-gray-200 bg-gray-50">
                                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600">Item</th>
                                            <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600">Penggunaan
                                                Harian</th>
                                            <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600">Waktu Lead
                                            </th>
                                            <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600">Dihitung
                                            </th>
                                            <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600">Saat Ini
                                            </th>
                                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600">Rekomendasi
                                            </th>
                                            <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600">Efisiensi
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200">
                                        @foreach($reorderAnalysis as $item)
                                            @php
                                                $efficiencyBg = match ($item['efficiency']) {
                                                    'high' => 'bg-green-100 text-green-800',
                                                    'medium' => 'bg-yellow-100 text-yellow-800',
                                                    'low' => 'bg-red-100 text-red-800',
                                                    default => 'bg-gray-100 text-gray-800'
                                                };
                                                $efficiencyText = match ($item['efficiency']) {
                                                    'high' => 'Tinggi',
                                                    'medium' => 'Sedang',
                                                    'low' => 'Rendah',
                                                    default => 'Lainnya'
                                                };
                                            @endphp
                                            <tr class="hover:bg-gray-50 transition">
                                                <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $item['item'] }}</td>
                                                <td class="px-6 py-4 text-center text-sm text-gray-600">
                                                    {{ $item['avg_daily_usage'] }} unit</td>
                                                <td class="px-6 py-4 text-center text-sm text-gray-600">
                                                    {{ $item['supplier_lead_time'] }} hari</td>
                                                <td class="px-6 py-4 text-center text-sm font-semibold text-blue-600">
                                                    {{ $item['calculated_reorder'] }}</td>
                                                <td class="px-6 py-4 text-center text-sm font-semibold text-gray-900">
                                                    {{ $item['current_setting'] }}</td>
                                                <td class="px-6 py-4 text-sm text-gray-600">{{ $item['recommendation'] }}</td>
                                                <td class="px-6 py-4 text-center">
                                                    <span
                                                        class="px-2 py-1 text-xs font-semibold rounded-full {{ $efficiencyBg }}">{{ $efficiencyText }}</span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif

                </div>
            </div>

        </main>
    </div>

    <script>
        lucide.createIcons();
    </script>

</body>

</html>