<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alerts & History - Inventory360</title>

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
                    <h1 class="text-4xl font-bold mb-2">Alerts & Stock History</h1>
                    <p class="text-gray-400">Monitor stock levels and track all inventory movements</p>
                </div>

                <!-- Tabs -->
                <div class="flex gap-4 mb-8 border-b border-[#3a3a3a]">
                    <button
                        onclick="document.getElementById('alertsTab').classList.remove('hidden'); document.getElementById('historyTab').classList.add('hidden')"
                        class="px-4 py-3 border-b-2 border-blue-500 text-white font-semibold">
                        Active Alerts
                    </button>
                    <button
                        onclick="document.getElementById('historyTab').classList.remove('hidden'); document.getElementById('alertsTab').classList.add('hidden')"
                        class="px-4 py-3 text-gray-400 hover:text-white">
                        Stock History
                    </button>
                </div>

                <!-- Alerts Section -->
                <div id="alertsTab" class="space-y-4 mb-8">
                    @php
                        $alertIcons = [
                            'low_stock' => '📦',
                            'expiring_soon' => '⏰',
                            'stock_adjustment' => '🔄',
                        ];
                    @endphp
                    @foreach($alerts as $alert)
                        @php
                            $icon = $alertIcons[$alert['type']] ?? '⚠️';
                            $severityBg = match ($alert['severity']) {
                                'danger' => 'bg-red-900 border-red-700',
                                'warning' => 'bg-yellow-900 border-yellow-700',
                                'info' => 'bg-blue-900 border-blue-700',
                                default => 'bg-gray-900 border-gray-700'
                            };
                            $severityText = match ($alert['severity']) {
                                'danger' => 'text-red-200',
                                'warning' => 'text-yellow-200',
                                'info' => 'text-blue-200',
                                default => 'text-gray-200'
                            };
                            $alertType = match ($alert['type']) {
                                'low_stock' => 'Low Stock Alert',
                                'expiring_soon' => 'Expiring Soon',
                                'stock_adjustment' => 'Stock Adjustment',
                                default => 'Stock Alert'
                            };
                        @endphp
                        <div class="border {{ $severityBg }} rounded-lg p-6 flex gap-4 {{ $severityText }}">
                            <div class="text-3xl">{{ $icon }}</div>
                            <div class="flex-1">
                                <div class="font-bold mb-2">{{ $alertType }}: {{ $alert['item'] }}</div>
                                @if($alert['type'] === 'low_stock')
                                    <p class="text-sm mb-3">Current stock: {{ $alert['current'] }} (Min:
                                        {{ $alert['min_level'] }})</p>
                                @elseif($alert['type'] === 'expiring_soon')
                                    <p class="text-sm mb-3">Expiration date: {{ $alert['expires'] }}</p>
                                @elseif($alert['type'] === 'stock_adjustment')
                                    <p class="text-sm mb-3">Adjustment: {{ $alert['adjustment'] }}</p>
                                @endif
                                <div class="text-xs opacity-75">{{ date('M d, Y H:i', strtotime($alert['created'])) }}</div>
                            </div>
                            <div class="flex gap-2">
                                <button class="px-3 py-1 bg-gray-700 hover:bg-gray-600 rounded text-xs font-semibold">Mark
                                    as Read</button>
                                <button
                                    class="px-3 py-1 bg-gray-700 hover:bg-gray-600 rounded text-xs font-semibold">Dismiss</button>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Stock History Section -->
                <div id="historyTab" class="hidden">
                    <!-- Filters -->
                    <div class="bg-[#2a2a2a] rounded-lg p-4 mb-8">
                        <div class="grid grid-cols-4 gap-4">
                            <input type="text" placeholder="Search items..."
                                class="bg-[#1a1a1a] border border-[#3a3a3a] rounded px-4 py-2 text-white placeholder-gray-500 focus:outline-none focus:border-blue-500">
                            <select
                                class="bg-[#1a1a1a] border border-[#3a3a3a] rounded px-4 py-2 text-white focus:outline-none focus:border-blue-500">
                                <option>All Types</option>
                                <option>Inbound</option>
                                <option>Outbound</option>
                                <option>Adjustment</option>
                            </select>
                            <input type="date"
                                class="bg-[#1a1a1a] border border-[#3a3a3a] rounded px-4 py-2 text-white focus:outline-none focus:border-blue-500">
                            <button class="bg-blue-600 hover:bg-blue-700 rounded px-4 py-2 font-semibold">Export
                                CSV</button>
                        </div>
                    </div>

                    <!-- History Table -->
                    <div class="bg-[#2a2a2a] rounded-lg overflow-hidden">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b border-[#3a3a3a] bg-[#1a1a1a]">
                                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-400">Date & Time</th>
                                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-400">Item</th>
                                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-400">Type</th>
                                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-400">Quantity</th>
                                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-400">User</th>
                                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-400">Reason/Reference
                                    </th>
                                    <th class="px-6 py-4 text-right text-sm font-semibold text-gray-400">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-[#3a3a3a]">
                                @foreach($history as $entry)
                                    @php
                                        $typeColor = match ($entry['type']) {
                                            'out' => 'text-red-400',
                                            'in' => 'text-green-400',
                                            'adj' => 'text-yellow-400',
                                            default => 'text-gray-400'
                                        };
                                        $typeText = match ($entry['type']) {
                                            'out' => '↓ Outbound',
                                            'in' => '↑ Inbound',
                                            'adj' => '↔ Adjustment',
                                            default => 'Unknown'
                                        };
                                        $qtySign = match ($entry['type']) {
                                            'out' => '-',
                                            'in' => '+',
                                            'adj' => '',
                                            default => ''
                                        };
                                    @endphp
                                    <tr class="hover:bg-[#333] transition">
                                        <td class="px-6 py-4 text-sm">{{ date('M d, Y H:i', strtotime($entry['date'])) }}
                                        </td>
                                        <td class="px-6 py-4 text-sm font-semibold">{{ $entry['item'] }}</td>
                                        <td class="px-6 py-4">
                                            <span class="text-sm font-semibold {{ $typeColor }}">{{ $typeText }}</span>
                                        </td>
                                        <td class="px-6 py-4 text-sm font-semibold {{ $typeColor }}">
                                            {{ $qtySign }}{{ $entry['quantity'] }}</td>
                                        <td class="px-6 py-4 text-sm">{{ $entry['user'] }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-400">{{ $entry['reason'] }}</td>
                                        <td class="px-6 py-4 text-right">
                                            <button class="text-gray-400 hover:text-white text-sm">View Details</button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="flex justify-between items-center mt-6 text-gray-400">
                        <div class="text-sm">Showing 1 to 5 of 47 entries</div>
                        <div class="flex gap-2">
                            <button class="px-3 py-1 bg-[#3a3a3a] rounded text-sm disabled opacity-50">←
                                Previous</button>
                            <button class="px-3 py-1 bg-blue-600 rounded text-sm">1</button>
                            <button class="px-3 py-1 bg-[#3a3a3a] rounded text-sm hover:bg-[#4a4a4a]">2</button>
                            <button class="px-3 py-1 bg-[#3a3a3a] rounded text-sm hover:bg-[#4a4a4a]">3</button>
                            <button class="px-3 py-1 bg-[#3a3a3a] rounded text-sm hover:bg-[#4a4a4a]">Next →</button>
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