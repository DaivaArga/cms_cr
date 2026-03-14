<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bulk Update - Inventory360</title>

    <script src="https://cdn.tailwindcss.com"></script>
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
                <h1 class="text-xl font-bold text-gray-800">Bulk Update</h1>
                <div class="flex items-center gap-4">
                    <button class="p-2 text-gray-500 hover:bg-gray-100 rounded-full">
                        <i data-lucide="bell" class="w-5 h-5"></i>
                    </button>
                    <a href="/dev/menu"
                        class="px-4 py-2 bg-gray-600 text-white text-sm rounded-md hover:bg-gray-700 transition">
                        Back to Menu
                    </a>
                </div>
            </header>

            <div class="flex-1 overflow-y-auto p-8">
                <div class="max-w-5xl mx-auto space-y-6">

                    <!-- Bulk Update Options -->
                    <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm">
                        <h3 class="text-lg font-semibold text-gray-800 mb-6">Update Options</h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Filter by Category</label>
                                <select
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg text-gray-700 focus:ring-2 focus:ring-[#c8d44e]">
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat }}">{{ $cat }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Update Type</label>
                                <select
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg text-gray-700 focus:ring-2 focus:ring-[#c8d44e]"
                                    onchange="updateUpdateType(this.value)">
                                    <option value="">Select update type...</option>
                                    <option value="price">Update Prices</option>
                                    <option value="availability">Update Availability</option>
                                    <option value="both">Update Both</option>
                                </select>
                            </div>
                        </div>

                        <!-- Price Update Options (hidden by default) -->
                        <div id="priceOptions" class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg hidden">
                            <h4 class="font-semibold text-gray-800 mb-4">Price Update Options</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="flex items-center gap-3 cursor-pointer">
                                        <input type="radio" name="priceUpdateType" value="fixed" checked
                                            class="w-4 h-4">
                                        <span class="text-sm text-gray-700">Set Fixed Price</span>
                                    </label>
                                    <input type="number" step="0.01" placeholder="New price"
                                        class="mt-2 w-full px-4 py-2 border border-gray-300 rounded-lg text-gray-700 focus:ring-2 focus:ring-[#c8d44e]">
                                </div>
                                <div>
                                    <label class="flex items-center gap-3 cursor-pointer">
                                        <input type="radio" name="priceUpdateType" value="percentage" class="w-4 h-4">
                                        <span class="text-sm text-gray-700">Apply Percentage Change</span>
                                    </label>
                                    <input type="number" step="0.01" placeholder="Percentage (+/- value)"
                                        class="mt-2 w-full px-4 py-2 border border-gray-300 rounded-lg text-gray-700 focus:ring-2 focus:ring-[#c8d44e]">
                                </div>
                            </div>
                        </div>

                        <!-- Availability Update Options (hidden by default) -->
                        <div id="availabilityOptions"
                            class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg hidden">
                            <h4 class="font-semibold text-gray-800 mb-4">Availability Update</h4>
                            <div class="flex gap-4">
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="availability" value="available" class="w-4 h-4">
                                    <span class="text-sm text-gray-700">Mark as Available</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="availability" value="unavailable" checked class="w-4 h-4">
                                    <span class="text-sm text-gray-700">Mark as Unavailable</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Items to Update -->
                    <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-lg font-semibold text-gray-800">Items to Update</h3>
                            <div class="flex gap-2">
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" id="selectAll" class="w-4 h-4 text-[#c8d44e] rounded"
                                        onchange="toggleSelectAll(this)">
                                    <span class="text-sm text-gray-700">Select All</span>
                                </label>
                            </div>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead>
                                    <tr class="border-b border-gray-200">
                                        <th class="text-left py-3 px-4 text-gray-700 font-semibold w-12">
                                            <input type="checkbox" class="w-4 h-4 text-[#c8d44e] rounded">
                                        </th>
                                        <th class="text-left py-3 px-4 text-gray-700 font-semibold">Item Name</th>
                                        <th class="text-left py-3 px-4 text-gray-700 font-semibold">Category</th>
                                        <th class="text-right py-3 px-4 text-gray-700 font-semibold">Current Price</th>
                                        <th class="text-center py-3 px-4 text-gray-700 font-semibold">Status</th>
                                        <th class="text-right py-3 px-4 text-gray-700 font-semibold">New Value</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($menuItems as $item)
                                        <tr class="border-b border-gray-100 hover:bg-gray-50">
                                            <td class="py-4 px-4">
                                                <input type="checkbox" class="w-4 h-4 text-[#c8d44e] rounded">
                                            </td>
                                            <td class="py-4 px-4 text-gray-800 font-medium">{{ $item['name'] }}</td>
                                            <td class="py-4 px-4 text-gray-700">{{ $item['category'] }}</td>
                                            <td class="py-4 px-4 text-right text-gray-800 font-semibold">
                                                ${{ number_format($item['price'], 2) }}</td>
                                            <td class="py-4 px-4 text-center">
                                                <span
                                                    class="inline-block {{ $item['available'] ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }} px-3 py-1 rounded-full text-xs font-semibold">
                                                    {{ $item['available'] ? 'Available' : 'Unavailable' }}
                                                </span>
                                            </td>
                                            <td class="py-4 px-4 text-right text-gray-700">
                                                <input type="text" value="${{ number_format($item['price'], 2) }}"
                                                    class="w-24 px-2 py-1 border border-gray-300 rounded text-right text-sm"
                                                    placeholder="New value">
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Update Summary & Action -->
                    <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                            <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                                <p class="text-sm text-gray-600 mb-1">Selected Items</p>
                                <p class="text-2xl font-bold text-blue-600">0</p>
                            </div>
                            <div class="bg-orange-50 p-4 rounded-lg border border-orange-200">
                                <p class="text-sm text-gray-600 mb-1">Update Type</p>
                                <p class="text-lg font-bold text-orange-600">Not selected</p>
                            </div>
                            <div class="bg-purple-50 p-4 rounded-lg border border-purple-200">
                                <p class="text-sm text-gray-600 mb-1">Affected Items</p>
                                <p class="text-2xl font-bold text-purple-600">{{ count($menuItems) }}</p>
                            </div>
                        </div>

                        <div class="flex gap-3">
                            <button
                                class="px-6 py-3 bg-[#c8d44e] text-black font-semibold rounded-lg hover:bg-opacity-90 transition flex items-center gap-2">
                                <i data-lucide="check" class="w-5 h-5"></i>
                                Apply Updates
                            </button>
                            <button
                                class="px-6 py-3 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 transition">
                                Cancel
                            </button>
                        </div>

                        <p class="text-xs text-gray-500 mt-4">Note: This action cannot be undone. Make sure to review
                            all changes before applying.</p>
                    </div>

                </div>
            </div>
        </main>
    </div>

    <script>
        lucide.createIcons();

        function updateUpdateType(value) {
            const priceOpts = document.getElementById('priceOptions');
            const availOpts = document.getElementById('availabilityOptions');

            if (value === 'price') {
                priceOpts.classList.remove('hidden');
                availOpts.classList.add('hidden');
            } else if (value === 'availability') {
                priceOpts.classList.add('hidden');
                availOpts.classList.remove('hidden');
            } else if (value === 'both') {
                priceOpts.classList.remove('hidden');
                availOpts.classList.remove('hidden');
            } else {
                priceOpts.classList.add('hidden');
                availOpts.classList.add('hidden');
            }
        }

        function toggleSelectAll(checkbox) {
            const checkboxes = document.querySelectorAll('input[type="checkbox"]');
            checkboxes.forEach(cb => {
                if (cb !== checkbox) cb.checked = checkbox.checked;
            });
        }
    </script>
</body>

</html>