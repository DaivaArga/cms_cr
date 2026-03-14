<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Category Management - Inventory360</title>

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
                <h1 class="text-xl font-bold text-gray-800">Category Management</h1>
                <div class="flex items-center gap-4">
                    <button class="p-2 text-gray-500 hover:bg-gray-100 rounded-full">
                        <i data-lucide="bell" class="w-5 h-5"></i>
                    </button>
                    <a href="/dev/menu"
                        class="px-4 py-2 bg-gray-600 text-white text-sm rounded-md hover:bg-gray-700 transition">
                        Back to Menu
                    </a>
                    <button
                        class="px-4 py-2 bg-[#c8d44e] text-black text-sm font-semibold rounded-md hover:bg-opacity-90 transition flex items-center gap-2"
                        onclick="openAddCategoryModal()">
                        <i data-lucide="plus" class="w-4 h-4"></i>
                        New Category
                    </button>
                </div>
            </header>

            <div class="flex-1 overflow-y-auto p-8">
                <div class="max-w-5xl mx-auto space-y-6">

                    <!-- Categories Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($categories as $category)
                            <div
                                class="bg-white rounded-xl border border-gray-100 shadow-sm p-6 hover:shadow-md transition">
                                <div class="flex justify-between items-start mb-4">
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-800">{{ $category['name'] }}</h3>
                                        <p class="text-sm text-gray-600 mt-1">{{ $category['description'] }}</p>
                                    </div>
                                    <div class="flex gap-2">
                                        <button class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition">
                                            <i data-lucide="edit" class="w-4 h-4"></i>
                                        </button>
                                        <button class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    </div>
                                </div>

                                <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                                    <div class="text-center flex-1">
                                        <p class="text-2xl font-bold text-[#c8d44e]">{{ $category['items_count'] }}</p>
                                        <p class="text-xs text-gray-500">Items</p>
                                    </div>
                                    <div class="text-center flex-1 border-l border-gray-200">
                                        <p class="text-xs text-gray-500">Created</p>
                                        <p class="text-sm text-gray-700">{{ $category['created'] }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Add Category Modal Form (simplified inline) -->
                    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-6">Add New Category</h3>

                        <form class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Category Name *</label>
                                <input type="text" placeholder="e.g. Desserts, Beverages"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg text-gray-700 focus:ring-2 focus:ring-[#c8d44e]">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                                <textarea placeholder="Brief description of this category"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg text-gray-700 focus:ring-2 focus:ring-[#c8d44e]"
                                    rows="2"></textarea>
                            </div>

                            <div class="flex gap-3">
                                <button type="submit"
                                    class="px-6 py-2 bg-[#c8d44e] text-black font-semibold rounded-lg hover:bg-opacity-90 transition">
                                    Create Category
                                </button>
                                <button type="button"
                                    class="px-6 py-2 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 transition">
                                    Cancel
                                </button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </main>
    </div>

    <script>
        lucide.createIcons();

        function openAddCategoryModal() {
            // Modal functionality can be enhanced later
            console.log('Open category modal');
        }
    </script>
</body>

</html>