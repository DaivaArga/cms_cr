<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu Item Form - Inventory360</title>

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
                <h1 class="text-xl font-bold text-gray-800">{{ $isEdit ? 'Edit Menu Item' : 'Create Menu Item' }}</h1>
                <div class="flex items-center gap-4">
                    <a href="/dev/menu"
                        class="px-4 py-2 bg-gray-600 text-white text-sm rounded-md hover:bg-gray-700 transition">
                        Cancel
                    </a>
                    <button
                        class="px-4 py-2 bg-[#c8d44e] text-black text-sm font-semibold rounded-md hover:bg-opacity-90 transition">
                        {{ $isEdit ? 'Update Item' : 'Create Item' }}
                    </button>
                </div>
            </header>

            <div class="flex-1 overflow-y-auto p-8">
                <div class="max-w-4xl mx-auto space-y-6">

                    <!-- FR-M01: Basic Item Information -->
                    <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm">
                        <h3 class="text-lg font-semibold text-gray-800 mb-6">Item Information</h3>

                        <form class="space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Item Name *</label>
                                    <input type="text" value="{{ $menuItem['name'] ?? '' }}"
                                        placeholder="e.g. Nasi Kuning Spesial"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg text-gray-700 focus:ring-2 focus:ring-[#c8d44e]">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Category *</label>
                                    <select
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg text-gray-700 focus:ring-2 focus:ring-[#c8d44e]">
                                        @foreach($categories as $cat)
                                            <option value="{{ $cat }}" {{ ($menuItem['category'] ?? '') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                                <textarea placeholder="Describe your menu item..."
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg text-gray-700 focus:ring-2 focus:ring-[#c8d44e]"
                                    rows="3">{{ $menuItem['description'] ?? '' }}</textarea>
                            </div>

                            <!-- FR-M03: Pricing and Availability -->
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Selling Price ($)
                                        *</label>
                                    <input type="number" step="0.01" value="{{ $menuItem['price'] ?? '' }}"
                                        placeholder="0.00"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg text-gray-700 focus:ring-2 focus:ring-[#c8d44e]">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Cost Price ($)</label>
                                    <input type="number" step="0.01" value="{{ $menuItem['cost'] ?? '' }}"
                                        placeholder="0.00"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg text-gray-700 focus:ring-2 focus:ring-[#c8d44e]">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Initial Stock</label>
                                    <input type="number" value="{{ $menuItem['stock'] ?? '' }}" placeholder="0"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg text-gray-700 focus:ring-2 focus:ring-[#c8d44e]">
                                </div>
                            </div>

                            <div>
                                <label class="flex items-center gap-3 cursor-pointer">
                                    <input type="checkbox" class="w-5 h-5 text-[#c8d44e] rounded" {{ ($menuItem['available'] ?? true) ? 'checked' : '' }}>
                                    <span class="text-sm font-medium text-gray-700">Available for Sale</span>
                                </label>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Image</label>
                                <div
                                    class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center cursor-pointer hover:border-[#c8d44e] transition">
                                    <i data-lucide="upload" class="w-8 h-8 text-gray-400 mx-auto mb-2"></i>
                                    <p class="text-sm text-gray-600">Drag and drop your image or click to upload</p>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- FR-M04: Recipe Management (Ingredients) -->
                    <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-lg font-semibold text-gray-800">Recipe & Ingredients</h3>
                            <button
                                class="px-4 py-2 bg-[#c8d44e] text-black text-sm font-semibold rounded-lg hover:bg-opacity-90 transition flex items-center gap-2">
                                <i data-lucide="plus" class="w-4 h-4"></i>
                                Add Ingredient
                            </button>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead>
                                    <tr class="border-b border-gray-200">
                                        <th class="text-left py-3 px-4 text-gray-700 font-semibold">Ingredient</th>
                                        <th class="text-center py-3 px-4 text-gray-700 font-semibold">Quantity</th>
                                        <th class="text-center py-3 px-4 text-gray-700 font-semibold">Unit</th>
                                        <th class="text-right py-3 px-4 text-gray-700 font-semibold">Unit Cost</th>
                                        <th class="text-right py-3 px-4 text-gray-700 font-semibold">Total Cost</th>
                                        <th class="text-center py-3 px-4 text-gray-700 font-semibold">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $totalRecipeCost = 0; @endphp
                                    @foreach($recipes as $recipe)
                                        @php $recipeCost = $recipe['quantity'] * ($recipe['cost'] / 1);
                                        $totalRecipeCost += $recipeCost; @endphp
                                        <tr class="border-b border-gray-100 hover:bg-gray-50">
                                            <td class="py-4 px-4 text-gray-800">{{ $recipe['ingredient'] }}</td>
                                            <td class="py-4 px-4 text-center">
                                                <input type="number" value="{{ $recipe['quantity'] }}"
                                                    class="w-20 px-2 py-1 border border-gray-300 rounded text-center"
                                                    step="0.01">
                                            </td>
                                            <td class="py-4 px-4 text-center text-gray-700">{{ $recipe['unit'] }}</td>
                                            <td class="py-4 px-4 text-right text-gray-700">
                                                ${{ number_format($recipe['cost'], 2) }}</td>
                                            <td class="py-4 px-4 text-right text-gray-800 font-semibold">
                                                ${{ number_format($recipeCost, 2) }}</td>
                                            <td class="py-4 px-4 text-center">
                                                <button class="text-red-600 hover:text-red-800">
                                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                    <tr class="bg-gray-50 border-t-2 border-gray-200">
                                        <td colspan="4" class="py-3 px-4 text-right font-semibold text-gray-800">Total
                                            Recipe Cost:</td>
                                        <td class="py-3 px-4 text-right font-bold text-gray-800">
                                            ${{ number_format($totalRecipeCost, 2) }}</td>
                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                            <p class="text-sm text-blue-700"><strong>Note:</strong> Recipe ingredients are used for
                                inventory calculation and cost analysis.</p>
                        </div>
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