<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reorder Analysis - Inventory360</title>

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
                    <h1 class="text-4xl font-bold mb-2">Reorder Point Analysis</h1>
                    <p class="text-gray-400">Optimize inventory levels based on usage patterns</p>
                </div>

                <!-- Info Card -->
                <div class="bg-blue-900 bg-opacity-30 border border-blue-700 rounded-lg p-6 mb-8">
                    <div class="flex gap-4">
                        <div class="text-blue-400 text-2xl">ℹ️</div>
                        <div>
                            <h3 class="font-bold text-blue-300 mb-2">How Reorder Points are Calculated</h3>
                            <p class="text-sm text-blue-200">
                                Reorder Point = (Average Daily Usage × Supplier Lead Time) + Safety Stock
                            </p>
                            <p class="text-xs text-blue-200 mt-2 opacity-75">
                                This ensures you never run out of stock while minimizing excess inventory holding costs.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Current vs Recommended -->
                <div class="grid grid-cols-2 gap-6 mb-8">
                    <div class="bg-[#2a2a2a] rounded-lg p-6">
                        <h3 class="text-xl font-bold mb-6">Inventory Efficiency Status</h3>
                        <div class="space-y-4">
                            @php
                                $highEfficiency = count(array_filter($reorderAnalysis, fn($r) => $r['efficiency'] === 'high'));
                                $mediumEfficiency = count(array_filter($reorderAnalysis, fn($r) => $r['efficiency'] === 'medium'));
                                $lowEfficiency = count(array_filter($reorderAnalysis, fn($r) => $r['efficiency'] === 'low'));
                            @endphp
                            <div class="flex items-center gap-4">
                                <div class="flex-1">
                                    <div class="flex justify-between mb-2">
                                        <span class="font-semibold">High Efficiency</span>
                                        <span class="text-green-400">{{ $highEfficiency }} items</span>
                                    </div>
                                    <div class="w-full bg-[#1a1a1a] rounded-full h-2">
                                        <div class="h-2 bg-green-500 rounded-full"
                                            style="width: {{ ($highEfficiency / count($reorderAnalysis)) * 100 }}%">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center gap-4">
                                <div class="flex-1">
                                    <div class="flex justify-between mb-2">
                                        <span class="font-semibold">Medium Efficiency</span>
                                        <span class="text-yellow-400">{{ $mediumEfficiency }} items</span>
                                    </div>
                                    <div class="w-full bg-[#1a1a1a] rounded-full h-2">
                                        <div class="h-2 bg-yellow-500 rounded-full"
                                            style="width: {{ ($mediumEfficiency / count($reorderAnalysis)) * 100 }}%">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center gap-4">
                                <div class="flex-1">
                                    <div class="flex justify-between mb-2">
                                        <span class="font-semibold">Low Efficiency</span>
                                        <span class="text-red-400">{{ $lowEfficiency }} items</span>
                                    </div>
                                    <div class="w-full bg-[#1a1a1a] rounded-full h-2">
                                        <div class="h-2 bg-red-500 rounded-full"
                                            style="width: {{ ($lowEfficiency / count($reorderAnalysis)) * 100 }}%">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-[#2a2a2a] rounded-lg p-6">
                        <h3 class="text-xl font-bold mb-6">Optimization Impact</h3>
                        @php
                            $currentTotal = array_sum(array_map(fn($r) => $r['current_setting'], $reorderAnalysis));
                            $recommendedTotal = array_sum(array_map(fn($r) => $r['calculated_reorder'], $reorderAnalysis));
                            $savingPercent = round((($currentTotal - $recommendedTotal) / $currentTotal) * 100);
                            $savingValue = $currentTotal - $recommendedTotal;
                        @endphp
                        <div class="space-y-4">
                            <div class="bg-[#1a1a1a] rounded-lg p-4">
                                <div class="text-gray-400 text-sm mb-1">Current Total Reorder Level</div>
                                <div class="text-3xl font-bold">{{ number_format($currentTotal, 0) }} units</div>
                            </div>
                            <div class="bg-[#1a1a1a] rounded-lg p-4">
                                <div class="text-gray-400 text-sm mb-1">Recommended Total</div>
                                <div class="text-3xl font-bold text-green-400">{{ number_format($recommendedTotal, 0) }}
                                    units</div>
                            </div>
                            <div class="bg-green-900 bg-opacity-30 border border-green-700 rounded-lg p-4">
                                <div class="text-green-400 font-bold mb-1">Potential Savings</div>
                                <div class="text-2xl font-bold text-green-300">{{ $savingPercent }}% reduction</div>
                                <div class="text-sm text-green-200 mt-1">{{ $savingValue }} units of tied-up capital
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Analysis Table -->
                <div class="bg-[#2a2a2a] rounded-lg overflow-hidden">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-[#3a3a3a] bg-[#1a1a1a]">
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-400">Item</th>
                                <th class="px-6 py-4 text-center text-sm font-semibold text-gray-400">Avg Daily Usage
                                </th>
                                <th class="px-6 py-4 text-center text-sm font-semibold text-gray-400">Lead Time</th>
                                <th class="px-6 py-4 text-center text-sm font-semibold text-gray-400">Calculated Point
                                </th>
                                <th class="px-6 py-4 text-center text-sm font-semibold text-gray-400">Current Setting
                                </th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-400">Recommendation</th>
                                <th class="px-6 py-4 text-center text-sm font-semibold text-gray-400">Efficiency</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#3a3a3a]">
                            @foreach($reorderAnalysis as $item)
                                @php
                                    $recommendation = $item['recommendation'];
                                    $efficiency = $item['efficiency'];
                                    $efficiencyColor = match ($efficiency) {
                                        'high' => 'text-green-400',
                                        'medium' => 'text-yellow-400',
                                        'low' => 'text-red-400',
                                        default => 'text-gray-400'
                                    };
                                    $efficiencyBg = match ($efficiency) {
                                        'high' => 'bg-green-900',
                                        'medium' => 'bg-yellow-900',
                                        'low' => 'bg-red-900',
                                        default => 'bg-gray-900'
                                    };
                                    $variance = $item['current_setting'] - $item['calculated_reorder'];
                                    $variancePercent = round(($variance / $item['current_setting']) * 100);
                                @endphp
                                <tr class="hover:bg-[#333] transition">
                                    <td class="px-6 py-4 font-semibold">{{ $item['item'] }}</td>
                                    <td class="px-6 py-4 text-center">{{ $item['avg_daily_usage'] }} units/day</td>
                                    <td class="px-6 py-4 text-center">{{ $item['supplier_lead_time'] }} days</td>
                                    <td class="px-6 py-4 text-center font-semibold text-blue-400">
                                        {{ $item['calculated_reorder'] }} units</td>
                                    <td class="px-6 py-4 text-center font-semibold">{{ $item['current_setting'] }} units
                                    </td>
                                    <td class="px-6 py-4">
                                        @if(strpos($recommendation, 'No change') !== false)
                                            <span class="text-green-400 text-sm">{{ $recommendation }}</span>
                                        @else
                                            <span class="text-yellow-400 text-sm font-semibold">{{ $recommendation }}</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span
                                            class="px-3 py-1 rounded-full text-xs font-semibold {{ $efficiencyBg }} {{ $efficiencyColor }}">
                                            {{ ucfirst($efficiency) }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Detailed Calculation Example -->
                <div class="mt-8 bg-[#2a2a2a] rounded-lg p-8">
                    <h2 class="text-2xl font-bold mb-6">Calculation Example: Rice (Long Grain)</h2>
                    <div class="space-y-6">
                        <div class="grid grid-cols-2 gap-8">
                            <div>
                                <h3 class="font-semibold mb-4">Calculation Formula</h3>
                                <div class="bg-[#1a1a1a] rounded-lg p-4 space-y-3 font-mono text-sm">
                                    <div class="text-gray-400">
                                        Average Daily Usage<br>
                                        <span class="text-white font-bold">7.5</span> units/day
                                    </div>
                                    <div class="text-center text-gray-500">×</div>
                                    <div class="text-gray-400">
                                        Supplier Lead Time<br>
                                        <span class="text-white font-bold">2</span> days
                                    </div>
                                    <div class="text-center text-gray-500">=</div>
                                    <div class="text-green-400 font-bold">
                                        15 units (Minimum Reorder Point)
                                    </div>
                                </div>
                            </div>
                            <div>
                                <h3 class="font-semibold mb-4">Current vs Recommended</h3>
                                <div class="space-y-3">
                                    <div class="bg-red-900 bg-opacity-20 border border-red-700 rounded-lg p-4">
                                        <div class="text-red-400 text-sm mb-1">Current Setting</div>
                                        <div class="text-2xl font-bold">50 units</div>
                                    </div>
                                    <div class="bg-green-900 bg-opacity-20 border border-green-700 rounded-lg p-4">
                                        <div class="text-green-400 text-sm mb-1">Recommended Setting</div>
                                        <div class="text-2xl font-bold">15 units</div>
                                    </div>
                                    <div class="bg-yellow-900 bg-opacity-20 border border-yellow-700 rounded-lg p-4">
                                        <div class="text-yellow-400 text-sm mb-1">Excess Stock Reduction</div>
                                        <div class="text-2xl font-bold">35 units (70% reduction)</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="border-t border-[#3a3a3a] pt-6">
                            <h4 class="font-semibold mb-3">Key Insights</h4>
                            <ul class="space-y-2 text-sm text-gray-300">
                                <li>✓ Current setting is <strong>3.3x higher</strong> than necessary</li>
                                <li>✓ Recommended level protects against stockouts while waiting for delivery</li>
                                <li>✓ Excess inventory ties up capital that could be used elsewhere</li>
                                <li>✓ Reducing to 15 units frees up storage space and reduces holding costs</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="mt-8 flex gap-4">
                    <button class="flex-1 bg-blue-600 hover:bg-blue-700 px-6 py-3 rounded-lg font-semibold">
                        Apply All Recommendations
                    </button>
                    <button class="flex-1 bg-[#3a3a3a] hover:bg-[#4a4a4a] px-6 py-3 rounded-lg font-semibold">
                        Export Report (PDF)
                    </button>
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