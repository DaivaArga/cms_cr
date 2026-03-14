<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supplier Management - Inventory360</title>

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
                        <h1 class="text-4xl font-bold mb-2">Supplier Management</h1>
                        <p class="text-gray-400">Manage supplier information and track performance</p>
                    </div>
                    <button onclick="document.getElementById('addSupplierModal').classList.remove('hidden')"
                        class="bg-blue-600 hover:bg-blue-700 px-6 py-3 rounded-lg font-semibold flex items-center gap-2">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <line x1="12" y1="5" x2="12" y2="19"></line>
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                        Add Supplier
                    </button>
                </div>

                <!-- Supplier Cards Grid -->
                <div class="grid grid-cols-2 gap-6">
                    @foreach($suppliers as $supplier)
                        @php
                            $ratingColor = match (true) {
                                $supplier['avg_delivery_rating'] >= 4.7 => 'text-green-400',
                                $supplier['avg_delivery_rating'] >= 4.0 => 'text-yellow-400',
                                default => 'text-red-400'
                            };
                            $ratingBg = match (true) {
                                $supplier['avg_delivery_rating'] >= 4.7 => 'bg-green-900',
                                $supplier['avg_delivery_rating'] >= 4.0 => 'bg-yellow-900',
                                default => 'bg-red-900'
                            };
                        @endphp
                        <div class="bg-[#2a2a2a] rounded-lg p-6 hover:bg-[#333] transition">
                            <!-- Header -->
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <h3 class="text-xl font-bold mb-1">{{ $supplier['name'] }}</h3>
                                    <p class="text-sm text-gray-400">{{ $supplier['items'] }}</p>
                                </div>
                                <div class="text-right">
                                    <div class="flex items-center gap-1 justify-end mb-1">
                                        <span class="text-lg">⭐</span>
                                        <span
                                            class="font-bold {{ $ratingColor }}">{{ $supplier['avg_delivery_rating'] }}</span>
                                    </div>
                                    <span class="text-xs text-gray-400">Rating</span>
                                </div>
                            </div>

                            <!-- Contact Info -->
                            <div class="space-y-2 mb-4 text-sm">
                                <div class="flex items-start gap-3">
                                    <span class="text-blue-400">📧</span>
                                    <div>
                                        <div class="text-gray-400 text-xs">Email</div>
                                        <div class="font-semibold">{{ $supplier['contact'] }}</div>
                                    </div>
                                </div>
                                <div class="flex items-start gap-3">
                                    <span class="text-green-400">📞</span>
                                    <div>
                                        <div class="text-gray-400 text-xs">Phone</div>
                                        <div class="font-semibold">{{ $supplier['phone'] }}</div>
                                    </div>
                                </div>
                                <div class="flex items-start gap-3">
                                    <span class="text-purple-400">📍</span>
                                    <div>
                                        <div class="text-gray-400 text-xs">Address</div>
                                        <div class="font-semibold text-sm">{{ $supplier['address'] }}</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Stats -->
                            <div class="grid grid-cols-3 gap-3 mb-4 p-4 bg-[#1a1a1a] rounded-lg">
                                <div class="text-center">
                                    <div class="text-2xl font-bold">{{ $supplier['lead_time_days'] }}d</div>
                                    <div class="text-xs text-gray-400">Lead Time</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-2xl font-bold">{{ $supplier['total_orders'] }}</div>
                                    <div class="text-xs text-gray-400">Total Orders</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-2xl font-bold {{ $ratingColor }}">
                                        {{ round(($supplier['avg_delivery_rating'] / 5) * 100) }}%</div>
                                    <div class="text-xs text-gray-400">Reliability</div>
                                </div>
                            </div>

                            <!-- Performance Badge -->
                            <div class="flex items-center gap-2 mb-4 p-3 {{ $ratingBg }} rounded-lg">
                                <span>{{ $supplier['avg_delivery_rating'] >= 4.7 ? '✓' : ($supplier['avg_delivery_rating'] >= 4.0 ? '⚠' : '❌') }}</span>
                                <span class="text-sm font-semibold">
                                    @if($supplier['avg_delivery_rating'] >= 4.7)
                                        Excellent Performance
                                    @elseif($supplier['avg_delivery_rating'] >= 4.0)
                                        Good Performance
                                    @else
                                        Needs Improvement
                                    @endif
                                </span>
                            </div>

                            <!-- Actions -->
                            <div class="grid grid-cols-2 gap-2">
                                <button
                                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 rounded text-sm font-semibold flex items-center justify-center gap-2">
                                    <span>📋</span> View History
                                </button>
                                <button
                                    class="px-4 py-2 bg-[#3a3a3a] hover:bg-[#4a4a4a] rounded text-sm font-semibold flex items-center justify-center gap-2">
                                    <span>✏️</span> Edit
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Supplier Performance Metrics -->
                <div class="mt-12 bg-[#2a2a2a] rounded-lg p-6">
                    <h2 class="text-2xl font-bold mb-6">Performance Summary</h2>
                    <div class="space-y-4">
                        @foreach($suppliers as $supplier)
                            @php
                                $perfPercent = ($supplier['avg_delivery_rating'] / 5) * 100;
                            @endphp
                            <div>
                                <div class="flex justify-between mb-2">
                                    <span class="font-semibold">{{ $supplier['name'] }}</span>
                                    <span class="text-sm text-gray-400">{{ $supplier['avg_delivery_rating'] }}/5.0</span>
                                </div>
                                <div class="w-full bg-[#1a1a1a] rounded-full h-2">
                                    <div class="h-2 rounded-full transition"
                                        style="width: {{ $perfPercent }}%; background: linear-gradient(90deg, #3b82f6, #10b981)">
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Supplier Modal -->
    <div id="addSupplierModal"
        class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-[#2a2a2a] rounded-lg p-8 max-w-2xl w-full mx-4">
            <h2 class="text-2xl font-bold mb-6">Add New Supplier</h2>
            <form class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold mb-2">Supplier Name</label>
                        <input type="text" placeholder="e.g., Farm Fresh Co."
                            class="w-full bg-[#1a1a1a] border border-[#3a3a3a] rounded px-4 py-2 text-white placeholder-gray-500 focus:outline-none focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold mb-2">Contact Email</label>
                        <input type="email" placeholder="email@supplier.com"
                            class="w-full bg-[#1a1a1a] border border-[#3a3a3a] rounded px-4 py-2 text-white placeholder-gray-500 focus:outline-none focus:border-blue-500">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold mb-2">Phone</label>
                        <input type="tel" placeholder="+62-812-345-6789"
                            class="w-full bg-[#1a1a1a] border border-[#3a3a3a] rounded px-4 py-2 text-white placeholder-gray-500 focus:outline-none focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold mb-2">Lead Time (Days)</label>
                        <input type="number" placeholder="2"
                            class="w-full bg-[#1a1a1a] border border-[#3a3a3a] rounded px-4 py-2 text-white placeholder-gray-500 focus:outline-none focus:border-blue-500">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-2">Address</label>
                    <textarea placeholder="Full address" rows="3"
                        class="w-full bg-[#1a1a1a] border border-[#3a3a3a] rounded px-4 py-2 text-white placeholder-gray-500 focus:outline-none focus:border-blue-500"></textarea>
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-2">Products/Items Supplied</label>
                    <input type="text" placeholder="e.g., Rice, Grains, Vegetables"
                        class="w-full bg-[#1a1a1a] border border-[#3a3a3a] rounded px-4 py-2 text-white placeholder-gray-500 focus:outline-none focus:border-blue-500">
                </div>
                <div class="flex gap-3 mt-6">
                    <button type="button" onclick="document.getElementById('addSupplierModal').classList.add('hidden')"
                        class="flex-1 px-4 py-2 border border-gray-500 rounded-lg text-gray-300 hover:text-white transition">Cancel</button>
                    <button type="submit"
                        class="flex-1 px-4 py-2 bg-blue-600 hover:bg-blue-700 rounded-lg font-semibold">Add
                        Supplier</button>
                </div>
            </form>
        </div>
    </div>
    </div>

    <script>
        lucide.createIcons();
    </script>

</body>

</html>