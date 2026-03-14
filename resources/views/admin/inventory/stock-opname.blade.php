<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stock Opname - Inventory360</title>

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
                    <h1 class="text-4xl font-bold mb-2">Stock Opname (Physical Count)</h1>
                    <p class="text-gray-400">Reconcile physical inventory with system records</p>
                </div>

                <!-- Progress Stats -->
                <div class="grid grid-cols-4 gap-4 mb-8">
                    <div class="bg-[#2a2a2a] rounded-lg p-6">
                        <div class="text-gray-400 text-sm mb-2">Total Items</div>
                        <div class="text-3xl font-bold">{{ count($items) }}</div>
                    </div>
                    <div class="bg-[#2a2a2a] rounded-lg p-6">
                        <div class="text-gray-400 text-sm mb-2">Counted</div>
                        <div class="text-3xl font-bold text-green-400">
                            {{ count(array_filter($items, fn($i) => $i['physical_qty'] !== null)) }}</div>
                    </div>
                    <div class="bg-[#2a2a2a] rounded-lg p-6">
                        <div class="text-gray-400 text-sm mb-2">Remaining</div>
                        <div class="text-3xl font-bold text-yellow-400">
                            {{ count(array_filter($items, fn($i) => $i['physical_qty'] === null)) }}</div>
                    </div>
                    <div class="bg-[#2a2a2a] rounded-lg p-6">
                        <div class="text-gray-400 text-sm mb-2">Discrepancies</div>
                        <div class="text-3xl font-bold text-red-400">
                            {{ count(array_filter($items, fn($i) => $i['variance'] !== null && $i['variance'] !== 0)) }}
                        </div>
                    </div>
                </div>

                <!-- Opname Form -->
                <div class="bg-[#2a2a2a] rounded-lg p-8">
                    <h2 class="text-2xl font-bold mb-6">Physical Count Form</h2>
                    <form method="POST" class="space-y-6">
                        @csrf
                        <table class="w-full">
                            <thead>
                                <tr class="border-b border-[#3a3a3a]">
                                    <th class="px-4 py-4 text-left text-sm font-semibold text-gray-400">Item</th>
                                    <th class="px-4 py-4 text-left text-sm font-semibold text-gray-400">Unit</th>
                                    <th class="px-4 py-4 text-center text-sm font-semibold text-gray-400">System Qty
                                    </th>
                                    <th class="px-4 py-4 text-center text-sm font-semibold text-gray-400">Physical Count
                                    </th>
                                    <th class="px-4 py-4 text-center text-sm font-semibold text-gray-400">Variance</th>
                                    <th class="px-4 py-4 text-center text-sm font-semibold text-gray-400">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-[#3a3a3a]">
                                @foreach($items as $item)
                                    @php
                                        $isCounted = $item['physical_qty'] !== null;
                                        $hasVariance = $isCounted && $item['variance'] !== 0;
                                        $variancePercent = $isCounted ? round(($item['variance'] / $item['system_qty']) * 100) : null;
                                        $statusColor = !$isCounted ? 'bg-gray-800 text-gray-200' : ($hasVariance ? 'bg-red-900 text-red-200' : 'bg-green-900 text-green-200');
                                        $statusText = !$isCounted ? 'Pending' : ($hasVariance ? 'Discrepancy' : 'OK');
                                    @endphp
                                    <tr class="hover:bg-[#333] transition">
                                        <td class="px-4 py-4 font-semibold">{{ $item['name'] }}</td>
                                        <td class="px-4 py-4 text-sm text-gray-400">{{ $item['unit'] }}</td>
                                        <td class="px-4 py-4 text-center font-semibold">{{ $item['system_qty'] }}</td>
                                        <td class="px-4 py-4 text-center">
                                            @if($isCounted)
                                                <span class="font-semibold">{{ $item['physical_qty'] }}</span>
                                            @else
                                                <input type="number" placeholder="0" name="physical_qty[{{ $item['id'] }}]"
                                                    class="w-20 bg-[#1a1a1a] border border-[#3a3a3a] rounded px-3 py-2 text-white text-center focus:outline-none focus:border-blue-500">
                                            @endif
                                        </td>
                                        <td class="px-4 py-4 text-center">
                                            @if($isCounted)
                                                <span
                                                    class="font-semibold {{ $item['variance'] > 0 ? 'text-green-400' : 'text-red-400' }}">
                                                    {{ $item['variance'] > 0 ? '+' : '' }}{{ $item['variance'] }}
                                                    ({{ $variancePercent }}%)
                                                </span>
                                            @else
                                                <span class="text-gray-500">-</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-4 text-center">
                                            <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $statusColor }}">
                                                {{ $statusText }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <!-- Action Buttons -->
                        <div class="flex gap-3 mt-8 pt-6 border-t border-[#3a3a3a]">
                            <button type="button"
                                class="flex-1 px-6 py-3 border border-gray-500 rounded-lg text-gray-300 hover:text-white font-semibold transition">
                                Save Draft
                            </button>
                            <button type="submit"
                                class="flex-1 px-6 py-3 bg-green-600 hover:bg-green-700 rounded-lg font-semibold">
                                Complete Opname
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Variance Report -->
                <div class="mt-8 bg-[#2a2a2a] rounded-lg p-8">
                    <h2 class="text-2xl font-bold mb-6">Variance Report</h2>

                    @php
                        $variances = array_filter($items, fn($i) => $i['variance'] !== null && $i['variance'] !== 0);
                    @endphp

                    @if(count($variances) > 0)
                        <div class="space-y-4">
                            @foreach($variances as $item)
                                @php
                                    $variancePercent = round(($item['variance'] / $item['system_qty']) * 100);
                                    $varianceType = $item['variance'] > 0 ? 'Surplus' : 'Shortage';
                                    $varianceColor = $item['variance'] > 0 ? 'border-green-700 bg-green-900 bg-opacity-20' : 'border-red-700 bg-red-900 bg-opacity-20';
                                    $textColor = $item['variance'] > 0 ? 'text-green-400' : 'text-red-400';
                                @endphp
                                <div class="border {{ $varianceColor }} rounded-lg p-4 flex justify-between items-center">
                                    <div>
                                        <h3 class="font-semibold mb-1">{{ $item['name'] }}</h3>
                                        <p class="text-sm text-gray-400">System: {{ $item['system_qty'] }} | Physical:
                                            {{ $item['physical_qty'] }} | Variance: {{ $varianceType }}</p>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-2xl font-bold {{ $textColor }}">
                                            {{ $item['variance'] > 0 ? '+' : '' }}{{ $item['variance'] }}
                                        </div>
                                        <div class="text-sm text-gray-400">{{ $variancePercent }}% difference</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12 text-gray-400">
                            <div class="text-4xl mb-4">✓</div>
                            <p>No variances found - all items match system records</p>
                        </div>
                    @endif

                    <!-- Variance Summary -->
                    <div class="mt-8 grid grid-cols-3 gap-4">
                        <div class="bg-[#1a1a1a] rounded-lg p-4">
                            <div class="text-gray-400 text-sm mb-2">Total Shortage</div>
                            <div class="text-2xl font-bold text-red-400">
                                {{ abs(array_sum(array_map(fn($i) => $i['variance'] < 0 ? $i['variance'] : 0, $items))) }}
                            </div>
                        </div>
                        <div class="bg-[#1a1a1a] rounded-lg p-4">
                            <div class="text-gray-400 text-sm mb-2">Total Surplus</div>
                            <div class="text-2xl font-bold text-green-400">
                                {{ abs(array_sum(array_map(fn($i) => $i['variance'] > 0 ? $i['variance'] : 0, $items))) }}
                            </div>
                        </div>
                        <div class="bg-[#1a1a1a] rounded-lg p-4">
                            <div class="text-gray-400 text-sm mb-2">Net Variance</div>
                            <div class="text-2xl font-bold">
                                {{ abs(array_sum(array_map(fn($i) => $i['variance'] ?? 0, $items))) }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>

    <script>
        lucide.createIcons();
    </script>

</body>

</html>