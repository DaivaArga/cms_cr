<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Menu - Inventory360</title>

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
                <h1 class="text-xl font-bold text-gray-800">Manajemen Menu</h1>
                <div class="flex items-center gap-4">
                    <button class="p-2 text-gray-500 hover:bg-gray-100 rounded-full">
                        <i data-lucide="bell" class="w-5 h-5"></i>
                    </button>
                    <div class="flex gap-2">
                        <a href="/dev/menu"
                            class="px-4 py-2 bg-[#c8d44e] text-black text-sm font-semibold rounded-md hover:bg-opacity-90 transition flex items-center gap-2">
                            <i data-lucide="plus" class="w-4 h-4"></i>
                            Item Baru
                        </a>
                        <a href="/dev/menu/categories/manage"
                            class="px-4 py-2 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700 transition flex items-center gap-2">
                            <i data-lucide="folder" class="w-4 h-4"></i>
                            Kategori
                        </a>
                        <a href="/dev/menu/bulk-update"
                            class="px-4 py-2 bg-orange-600 text-white text-sm rounded-md hover:bg-orange-700 transition flex items-center gap-2">
                            <i data-lucide="zap" class="w-4 h-4"></i>
                            Update Massal
                        </a>
                    </div>
                </div>
            </header>

            <div class="flex-1 overflow-y-auto p-8">
                <div class="max-w-7xl mx-auto space-y-6">

                    <!-- Search and Filter -->
                    <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <input type="text" placeholder="Cari item menu..."
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg text-gray-700 focus:ring-2 focus:ring-[#c8d44e]">
                            </div>
                            <div>
                                <select
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg text-gray-700 focus:ring-2 focus:ring-[#c8d44e]">
                                    <option value="">Semua Kategori</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat['id'] }}">{{ $cat['name'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <select
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg text-gray-700 focus:ring-2 focus:ring-[#c8d44e]">
                                    <option value="">Semua Status</option>
                                    <option value="available">Tersedia</option>
                                    <option value="unavailable">Tidak Tersedia</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Menu Items Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($menuItems as $item)
                            <div
                                class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden hover:shadow-md transition">
                                <!-- Item Image -->
                                <div
                                    class="bg-gray-200 h-40 flex items-center justify-center relative {{ !$item['available'] ? 'opacity-60' : '' }}">
                                    <i data-lucide="image" class="w-12 h-12 text-gray-400"></i>
                                    @if(!$item['available'])
                                        <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center">
                                            <span class="text-white font-bold text-lg">TIDAK TERSEDIA</span>
                                        </div>
                                    @endif
                                </div>

                                <!-- Item Details -->
                                <div class="p-4">
                                    <div class="flex justify-between items-start mb-2">
                                        <h3 class="font-semibold text-gray-800 text-sm">{{ $item['name'] }}</h3>
                                        <span
                                            class="text-xs bg-blue-100 text-blue-700 px-2 py-1 rounded">{{ $item['category'] }}</span>
                                    </div>

                                    <p class="text-xs text-gray-600 mb-3">{{ Str::limit($item['description'], 60) }}</p>

                                    <div class="grid grid-cols-2 gap-2 mb-4">
                                        <div>
                                            <p class="text-xs text-gray-500">Harga</p>
                                            <p class="text-lg font-bold text-gray-800">
                                                ${{ number_format($item['price'], 2) }}</p>
                                        </div>
                                        <div>
                                            <p class="text-xs text-gray-500">Stok</p>
                                            <p class="text-lg font-bold text-gray-800">{{ $item['stock'] }}</p>
                                        </div>
                                    </div>

                                    <div class="flex gap-2">
                                        <a href="/dev/menu/{{ $item['id'] }}"
                                            class="flex-1 px-3 py-2 bg-blue-600 text-white text-xs font-semibold rounded-lg hover:bg-blue-700 transition flex items-center justify-center gap-1">
                                            <i data-lucide="edit" class="w-4 h-4"></i>
                                            Ubah
                                        </a>
                                        <button
                                            class="px-3 py-2 bg-red-600 text-white text-xs font-semibold rounded-lg hover:bg-red-700 transition">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
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