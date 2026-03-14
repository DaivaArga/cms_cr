<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory Management - Inventory360</title>

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

    <div class="min-h-screen bg-[#1a1a1a] text-white p-6">
        <div class="flex">
            <!-- Sidebar -->
            @include('admin.sidebar')

            <!-- Main Content -->
            <div class="flex-1 ml-64">
                <!-- Header -->
                <div class="mb-8">
                    <h1 class="text-4xl font-bold mb-2">Inventory Management</h1>
                    <p class="text-gray-400">Monitor raw materials and finished goods stock levels</p>
                </div>

                <!-- Quick Stats -->
                <div class="grid grid-cols-4 gap-4 mb-8">
                    <div class="bg-[#2a2a2a] rounded-lg p-6">
                        <div class="text-gray-400 text-sm mb-2">Total Raw Materials</div>
                        <div class="text-3xl font-bold">{{ count($rawMaterials) }}</div>
                        <div class="text-green-400 text-xs mt-2">✓ All monitored</div>
                    </div>
                    <div class="bg-[#2a2a2a] rounded-lg p-6">
                        <div class="text-gray-400 text-sm mb-2">Finished Goods</div>
                        <div class="text-3xl font-bold">{{ count($finishedGoods) }}</div>
                        <div class="text-green-400 text-xs mt-2">{{ count($finishedGoods) }} active products</div>
                    </div>
                    <div class="bg-[#2a2a2a] rounded-lg p-6">
                        <div class="text-gray-400 text-sm mb-2">Low Stock Items</div>
                        <div class="text-3xl font-bold text-red-400">
                            {{ count(array_filter($rawMaterials, fn($m) => $m['quantity'] <= $m['min_level'])) }}
                        </div>
                        <div class="text-red-400 text-xs mt-2">Require attention</div>
                    </div>
                    <div class="bg-[#2a2a2a] rounded-lg p-6">
                        <div class="text-gray-400 text-sm mb-2">Total Value</div>
                        <div class="text-3xl font-bold">Rp
                            {{ number_format(array_sum(array_map(fn($m) => $m['quantity'] * $m['unit_cost'], $rawMaterials)), 0, ',', '.') }}
                        </div>
                        <div class="text-blue-400 text-xs mt-2">Raw materials cost</div>
                    </div>
                </div>

                <!-- Navigation Tabs -->
                <div class="flex gap-4 mb-8 border-b border-[#3a3a3a]">
                    <a href="/dev/inventory/raw-materials"
                        class="px-4 py-3 border-b-2 border-blue-500 text-white font-semibold">
                        Raw Materials
                    </a>
                    <a href="/dev/inventory/alerts" class="px-4 py-3 text-gray-400 hover:text-white">
                        Alerts & History
                    </a>
                    <a href="/dev/inventory/suppliers" class="px-4 py-3 text-gray-400 hover:text-white">
                        Suppliers
                    </a>
                    <a href="/dev/inventory/stock-opname" class="px-4 py-3 text-gray-400 hover:text-white">
                        Stock Opname
                    </a>
                    <a href="/dev/inventory/reorder-points" class="px-4 py-3 text-gray-400 hover:text-white">
                        Reorder Analysis
                    </a>
                </div>

                <!-- Raw Materials Section -->
                <div class="bg-[#2a2a2a] rounded-lg p-6 mb-8">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-bold">Raw Materials Summary</h2>
                        <a href="/dev/inventory/raw-materials"
                            class="bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded-lg text-sm font-semibold">
                            View All
                        </a>
                    </div>

                    <div class="space-y-3">
                        @foreach($rawMaterials as $material)
                            @php
                                $percentage = ($material['quantity'] / $material['min_level']) * 100;
                                $status = $material['quantity'] <= $material['min_level'] ? 'danger' : 'ok';
                                $statusColor = $status === 'danger' ? 'bg-red-500' : 'bg-green-500';
                                $statusText = $status === 'danger' ? 'Low Stock' : 'Adequate';
                            @endphp
                            <div class="flex items-center gap-4 p-4 bg-[#1a1a1a] rounded-lg hover:bg-[#252525] transition">
                                <div class="flex-1">
                                    <div class="flex justify-between items-center mb-2">
                                        <h3 class="font-semibold">{{ $material['name'] }}</h3>
                                        <span
                                            class="text-sm {{ $status === 'danger' ? 'text-red-400' : 'text-green-400' }}">
                                            {{ $material['quantity'] }} {{ $material['unit'] }}
                                        </span>
                                    </div>
                                    <div class="flex items-center gap-4 text-sm text-gray-400">
                                        <span>Min: {{ $material['min_level'] }} {{ $material['unit'] }}</span>
                                        <span>|</span>
                                        <span>Supplier: {{ $material['supplier'] }}</span>
                                    </div>
                                    <div class="mt-3 w-full bg-[#3a3a3a] rounded-full h-2">
                                        <div class="h-2 {{ $statusColor }} rounded-full"
                                            style="width: {{ min($percentage, 100) }}%"></div>
                                    </div>
                                </div>
                                <span
                                    class="px-3 py-1 rounded-full text-xs font-semibold {{ $status === 'danger' ? 'bg-red-900 text-red-200' : 'bg-green-900 text-green-200' }}">
                                    {{ $statusText }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Finished Goods Section -->
                <div class="bg-[#2a2a2a] rounded-lg p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-bold">Finished Goods Stock</h2>
                        <a href="/dev/inventory" class="text-gray-400 hover:text-white text-sm">Manage &rarr;</a>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        @foreach($finishedGoods as $product)
                            @php
                                $margin = (($product['price'] - $product['cost']) / $product['price']) * 100;
                            @endphp
                            <div class="bg-[#1a1a1a] rounded-lg p-4 hover:bg-[#252525] transition">
                                <div class="flex justify-between items-start mb-2">
                                    <h3 class="font-semibold">{{ $product['name'] }}</h3>
                                    <span class="text-xs bg-blue-900 text-blue-200 px-2 py-1 rounded">
                                        {{ $product['quantity'] }}/{{ $product['min_level'] }}
                                    </span>
                                </div>
                                <div class="grid grid-cols-3 gap-4 mt-4 text-sm">
                                    <div>
                                        <div class="text-gray-400 text-xs">Price</div>
                                        <div class="font-semibold">Rp {{ number_format($product['price'], 0, ',', '.') }}
                                        </div>
                                    </div>
                                    <div>
                                        <div class="text-gray-400 text-xs">Cost</div>
                                        <div class="font-semibold">Rp {{ number_format($product['cost'], 0, ',', '.') }}
                                        </div>
                                    </div>
                                    <div>
                                        <div class="text-gray-400 text-xs">Margin</div>
                                        <div class="font-semibold text-green-400">{{ number_format($margin, 1) }}%</div>
                                    </div>
                                </div>
                                <div class="mt-4 w-full bg-[#3a3a3a] rounded-full h-2">
                                    <div class="h-2 bg-blue-500 rounded-full"
                                        style="width: {{ ($product['quantity'] / $product['min_level']) * 100 }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Real-time timestamp update
        setInterval(() => {
            const now = new Date().toLocaleString('id-ID', {
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            });
            // Update timestamp if element exists
        }, 1000);

        // Initialize Lucide icons
        lucide.createIcons();
    </script>

</body>

</html>