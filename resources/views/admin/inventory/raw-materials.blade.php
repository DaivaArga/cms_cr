<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Raw Materials - Inventory360</title>

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
                <div class="mb-8 flex justify-between items-center">
                    <div>
                        <h1 class="text-4xl font-bold mb-2">Raw Materials</h1>
                        <p class="text-gray-400">Manage ingredient inventory and suppliers</p>
                    </div>
                    <button onclick="document.getElementById('addMaterialModal').classList.remove('hidden')"
                        class="bg-blue-600 hover:bg-blue-700 px-6 py-3 rounded-lg font-semibold flex items-center gap-2">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <line x1="12" y1="5" x2="12" y2="19"></line>
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                        Add Material
                    </button>
                </div>

                <!-- Filters -->
                <div class="bg-[#2a2a2a] rounded-lg p-4 mb-8">
                    <div class="grid grid-cols-3 gap-4">
                        <input type="text" placeholder="Search materials..."
                            class="bg-[#1a1a1a] border border-[#3a3a3a] rounded px-4 py-2 text-white placeholder-gray-500 focus:outline-none focus:border-blue-500">
                        <select
                            class="bg-[#1a1a1a] border border-[#3a3a3a] rounded px-4 py-2 text-white focus:outline-none focus:border-blue-500">
                            <option>All Status</option>
                            <option>Adequate</option>
                            <option>Low Stock</option>
                            <option>Critical</option>
                        </select>
                        <select
                            class="bg-[#1a1a1a] border border-[#3a3a3a] rounded px-4 py-2 text-white focus:outline-none focus:border-blue-500">
                            <option>All Suppliers</option>
                            <option>Farm Fresh Co.</option>
                            <option>Tropical Supplies</option>
                            <option>Spice Master</option>
                        </select>
                    </div>
                </div>

                <!-- Materials Table -->
                <div class="bg-[#2a2a2a] rounded-lg overflow-hidden">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-[#3a3a3a] bg-[#1a1a1a]">
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-400">Material</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-400">Unit</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-400">Current Qty</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-400">Min Level</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-400">Supplier</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-400">Unit Cost</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-400">Total Value</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-400">Status</th>
                                <th class="px-6 py-4 text-right text-sm font-semibold text-gray-400">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#3a3a3a]">
                            @foreach($rawMaterials as $material)
                                @php
                                    $totalValue = $material['quantity'] * $material['unit_cost'];
                                    $percentage = ($material['quantity'] / $material['min_level']) * 100;
                                    if ($material['quantity'] <= $material['min_level']) {
                                        $statusColor = 'bg-red-900 text-red-200';
                                        $statusText = 'Low Stock';
                                        $statusIcon = '⚠️';
                                    } else {
                                        $statusColor = 'bg-green-900 text-green-200';
                                        $statusText = 'Adequate';
                                        $statusIcon = '✓';
                                    }
                                @endphp
                                <tr class="hover:bg-[#333] transition">
                                    <td class="px-6 py-4">
                                        <div class="font-semibold">{{ $material['name'] }}</div>
                                        <div class="text-xs text-gray-400 mt-1">Last received: {{ $material['received'] }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm">{{ $material['unit'] }}</td>
                                    <td class="px-6 py-4 text-sm font-semibold">{{ $material['quantity'] }}</td>
                                    <td class="px-6 py-4 text-sm">{{ $material['min_level'] }}</td>
                                    <td class="px-6 py-4 text-sm">{{ $material['supplier'] }}</td>
                                    <td class="px-6 py-4 text-sm">Rp
                                        {{ number_format($material['unit_cost'], 0, ',', '.') }}</td>
                                    <td class="px-6 py-4 text-sm font-semibold">Rp
                                        {{ number_format($totalValue, 0, ',', '.') }}</td>
                                    <td class="px-6 py-4">
                                        <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $statusColor }}">
                                            {{ $statusIcon }} {{ $statusText }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex justify-end gap-2">
                                            <button
                                                class="text-blue-400 hover:text-blue-300 text-sm font-semibold">Edit</button>
                                            <button
                                                class="text-red-400 hover:text-red-300 text-sm font-semibold">Delete</button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Low Stock Alert Summary -->
                <div class="mt-8 bg-red-900 bg-opacity-20 border border-red-500 rounded-lg p-6">
                    <div class="flex gap-4">
                        <div class="text-red-500 text-3xl">⚠️</div>
                        <div>
                            <h3 class="font-bold text-red-400 mb-2">Low Stock Alert</h3>
                            <p class="text-gray-300">
                                {{ count(array_filter($rawMaterials, fn($m) => $m['quantity'] <= $m['min_level'])) }}
                                materials are below minimum levels. Consider ordering from suppliers.</p>
                            <button class="mt-3 text-blue-400 hover:text-blue-300 text-sm font-semibold">View Supplier
                                Orders →</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Material Modal -->
    <div id="addMaterialModal"
        class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-[#2a2a2a] rounded-lg p-8 max-w-2xl w-full mx-4">
            <h2 class="text-2xl font-bold mb-6">Add New Raw Material</h2>
            <form class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold mb-2">Material Name</label>
                        <input type="text" placeholder="e.g., Rice (Long Grain)"
                            class="w-full bg-[#1a1a1a] border border-[#3a3a3a] rounded px-4 py-2 text-white placeholder-gray-500 focus:outline-none focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold mb-2">Unit</label>
                        <select
                            class="w-full bg-[#1a1a1a] border border-[#3a3a3a] rounded px-4 py-2 text-white focus:outline-none focus:border-blue-500">
                            <option>kg</option>
                            <option>liter</option>
                            <option>piece</option>
                            <option>gram</option>
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold mb-2">Current Quantity</label>
                        <input type="number" placeholder="0"
                            class="w-full bg-[#1a1a1a] border border-[#3a3a3a] rounded px-4 py-2 text-white placeholder-gray-500 focus:outline-none focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold mb-2">Minimum Level</label>
                        <input type="number" placeholder="0"
                            class="w-full bg-[#1a1a1a] border border-[#3a3a3a] rounded px-4 py-2 text-white placeholder-gray-500 focus:outline-none focus:border-blue-500">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold mb-2">Supplier</label>
                        <select
                            class="w-full bg-[#1a1a1a] border border-[#3a3a3a] rounded px-4 py-2 text-white focus:outline-none focus:border-blue-500">
                            <option>Farm Fresh Co.</option>
                            <option>Tropical Supplies</option>
                            <option>Spice Master</option>
                            <option>Premium Poultry</option>
                            <option>Premium Meats</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold mb-2">Unit Cost</label>
                        <input type="number" placeholder="0"
                            class="w-full bg-[#1a1a1a] border border-[#3a3a3a] rounded px-4 py-2 text-white placeholder-gray-500 focus:outline-none focus:border-blue-500">
                    </div>
                </div>
                <div class="flex gap-3 mt-6">
                    <button type="button" onclick="document.getElementById('addMaterialModal').classList.add('hidden')"
                        class="flex-1 px-4 py-2 border border-gray-500 rounded-lg text-gray-300 hover:text-white transition">Cancel</button>
                    <button type="submit"
                        class="flex-1 px-4 py-2 bg-blue-600 hover:bg-blue-700 rounded-lg font-semibold">Add
                        Material</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        lucide.createIcons();
    </script>

</body>

</html>