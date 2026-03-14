<aside
    class="w-[220px] min-w-[220px] bg-[#1a1a1a] h-full flex flex-col text-white flex-shrink-0 transition-all duration-300">
    <div class="flex items-center justify-between px-5 py-6">
        <span class="text-white tracking-wider text-sm font-bold">CritaSena</span>
        <i data-lucide="menu" class="text-gray-400 cursor-pointer w-5 h-5 hover:text-white"></i>
    </div>

    <nav class="flex-1 mt-2 overflow-y-auto">
        @php
            $currentPath = request()->path();
            $navItems = [
                ['icon' => 'layout-grid', 'label' => 'Beranda', 'href' => '/dev/admin', 'pattern' => 'dev/admin'],
                ['icon' => 'bar-chart-2', 'label' => 'Laporan', 'pattern' => 'dev/reports', 'hasDropdown' => true, 'dropdownId' => 'laporan'],
                ['icon' => 'package', 'label' => 'Kelola Produk', 'pattern' => 'dev/kelola-produk', 'hasDropdown' => true, 'dropdownId' => 'kelola-produk'],
                ['icon' => 'settings', 'label' => 'Master', 'pattern' => 'dev/master', 'hasDropdown' => true, 'dropdownId' => 'master'],
                ['icon' => 'utensils', 'label' => 'Menu', 'href' => '/dev/menu', 'pattern' => 'dev/menu'],
                ['icon' => 'box', 'label' => 'Inventori', 'href' => '/dev/inventory', 'pattern' => 'dev/inventory'],
            ];

            $laporanSubmenus = [
                ['label' => 'Ringkasan', 'href' => '/dev/reports/ringkasan'],
                ['label' => 'Penjualan Produk', 'href' => '/dev/reports/penjualan-produk'],
                ['label' => 'Diskon Shift & Kas', 'href' => '/dev/reports/diskon-shift-kas'],
                ['label' => 'Tipe Pesanan', 'href' => '/dev/reports/tipe-pesanan'],
                ['label' => 'Pembayaran', 'href' => '/dev/reports/pembayaran'],
                ['label' => 'Pencairan Dana', 'href' => '/dev/reports/pencairan-dana'],
                ['label' => 'Karyawan', 'href' => '/dev/reports/karyawan'],
            ];

            $kelolaProdukSubmenus = [
                ['label' => 'Produk', 'href' => '/dev/kelola-produk/produk'],
                ['label' => 'Inventaris', 'href' => '/dev/kelola-produk/inventaris'],
                ['label' => 'Diskon', 'href' => '/dev/kelola-produk/diskon'],
                ['label' => 'Tipe Pesanan', 'href' => '/dev/kelola-produk/tipe-pesanan'],
                ['label' => 'Bahan Baku', 'href' => '/dev/kelola-produk/bahan-baku'],
            ];

            $masterSubmenus = [
                ['label' => 'User', 'href' => '/dev/master/user'],
                ['label' => 'Hak Akses', 'href' => '/dev/master/hak-akses'],
                ['label' => 'Shift', 'href' => '/dev/master/shift'],
                ['label' => 'Kode Akses Kasir', 'href' => '/dev/master/kode-akses-kasir'],
                ['label' => 'Struk', 'href' => '/dev/master/struk'],
            ];
        @endphp

        @foreach($navItems as $item)
            @php
                // Cek apakah path saat ini cocok dengan pattern
                $isActive = false;
                if ($item['pattern'] === 'dev/admin') {
                    $isActive = $currentPath === 'dev/admin';
                } elseif ($item['pattern'] === 'dev/reports') {
                    $isActive = strpos($currentPath, 'dev/reports') === 0;
                } elseif ($item['pattern'] === 'dev/kelola-produk') {
                    $isActive = strpos($currentPath, 'dev/kelola-produk') === 0;
                } elseif ($item['pattern'] === 'dev/master') {
                    $isActive = strpos($currentPath, 'dev/master') === 0;
                } elseif ($item['pattern'] === 'dev/menu') {
                    $isActive = strpos($currentPath, 'dev/menu') === 0;
                } elseif ($item['pattern'] === 'dev/inventory') {
                    $isActive = strpos($currentPath, 'dev/inventory') === 0;
                }

                // Cek apakah submenu aktif
                $isSubmenuActive = false;
                if (isset($item['hasDropdown']) && $item['hasDropdown']) {
                    if (isset($item['dropdownId']) && $item['dropdownId'] === 'laporan') {
                        foreach ($laporanSubmenus as $submenu) {
                            if ($currentPath === $submenu['href']) {
                                $isSubmenuActive = true;
                                $isActive = true;
                                break;
                            }
                        }
                    } elseif (isset($item['dropdownId']) && $item['dropdownId'] === 'kelola-produk') {
                        foreach ($kelolaProdukSubmenus as $submenu) {
                            if ($currentPath === $submenu['href']) {
                                $isSubmenuActive = true;
                                $isActive = true;
                                break;
                            }
                        }
                    } elseif (isset($item['dropdownId']) && $item['dropdownId'] === 'master') {
                        foreach ($masterSubmenus as $submenu) {
                            if ($currentPath === $submenu['href']) {
                                $isSubmenuActive = true;
                                $isActive = true;
                                break;
                            }
                        }
                    }
                }
            @endphp

            {{-- Main menu item --}}
            <div class="nav-item">
                @if(isset($item['hasDropdown']) && $item['hasDropdown'])
                    {{-- Dropdown menu item --}}
                    @php
                        $dropdownId = isset($item['dropdownId']) ? $item['dropdownId'] : 'dropdown';
                        $chevronId = $dropdownId . '-chevron';
                        $dropdownContainerId = $dropdownId . '-dropdown';
                        if (isset($item['dropdownId']) && $item['dropdownId'] === 'laporan') {
                            $currentSubmenus = $laporanSubmenus;
                        } elseif (isset($item['dropdownId']) && $item['dropdownId'] === 'kelola-produk') {
                            $currentSubmenus = $kelolaProdukSubmenus;
                        } elseif (isset($item['dropdownId']) && $item['dropdownId'] === 'master') {
                            $currentSubmenus = $masterSubmenus;
                        } else {
                            $currentSubmenus = [];
                        }
                    @endphp
                    <div onclick="toggleDropdown('{{ $dropdownId }}')"
                        class="flex items-center gap-3 px-5 py-3 cursor-pointer group relative transition-colors duration-200
                            {{ $isActive ? 'bg-[#2a2a2a] border-l-2 border-[#c8d44e]' : 'hover:bg-[#252525] border-l-2 border-transparent' }}">

                        <i data-lucide="{{ $item['icon'] }}"
                            class="w-[18px] h-[18px] {{ $isActive ? 'text-[#c8d44e]' : 'text-gray-400 group-hover:text-gray-200' }}"></i>

                        <span
                            class="text-sm {{ $isActive ? 'text-[#c8d44e] font-semibold' : 'text-gray-400 group-hover:text-gray-200' }}">
                            {{ $item['label'] }}
                        </span>

                        <i id="{{ $chevronId }}" data-lucide="chevron-down"
                            class="ml-auto w-4 h-4 {{ $isActive ? 'text-[#c8d44e]' : 'text-gray-400' }} transition-transform duration-200"></i>
                    </div>

                    {{-- Submenu items --}}
                    <div id="{{ $dropdownContainerId }}" class="hidden">
                        @foreach($currentSubmenus as $submenu)
                            @php
                                $isSubItemActive = $currentPath === $submenu['href'];
                            @endphp
                            <a href="{{ $submenu['href'] }}"
                                class="flex items-center gap-3 px-5 py-2.5 pl-12 cursor-pointer group relative transition-colors duration-200
                                    {{ $isSubItemActive ? 'bg-[#2a2a2a] border-l-2 border-[#c8d44e]' : 'hover:bg-[#252525] border-l-2 border-transparent' }}">

                                <span
                                    class="text-sm {{ $isSubItemActive ? 'text-[#c8d44e] font-semibold' : 'text-gray-400 group-hover:text-gray-200' }}">
                                    {{ $submenu['label'] }}
                                </span>
                            </a>
                        @endforeach
                    </div>
                @else
                    {{-- Regular menu item --}}
                    <a href="{{ $item['href'] }}"
                        class="flex items-center gap-3 px-5 py-3 cursor-pointer group relative transition-colors duration-200
                            {{ $isActive ? 'bg-[#2a2a2a] border-l-2 border-[#c8d44e]' : 'hover:bg-[#252525] border-l-2 border-transparent' }}">

                        <i data-lucide="{{ $item['icon'] }}"
                            class="w-[18px] h-[18px] {{ $isActive ? 'text-[#c8d44e]' : 'text-gray-400 group-hover:text-gray-200' }}"></i>

                        <span
                            class="text-sm {{ $isActive ? 'text-[#c8d44e] font-semibold' : 'text-gray-400 group-hover:text-gray-200' }}">
                            {{ $item['label'] }}
                        </span>

                        @if(isset($item['badge']) && $item['badge'])
                            <span
                                class="ml-auto {{ $isActive ? 'bg-[#c8d44e] text-black' : 'bg-gray-700 text-gray-200' }} text-xs rounded-full w-5 h-5 flex items-center justify-center font-bold">
                                {{ $item['badge'] }}
                            </span>
                        @endif
                    </a>
                @endif
            </div>
        @endforeach
    </nav>

    <script>
        const dropdownStates = {};

        function toggleDropdown(dropdownId) {
            const dropdown = document.getElementById(dropdownId + '-dropdown');
            const chevron = document.getElementById(dropdownId + '-chevron');

            // Initialize state if not exists
            if (!(dropdownId in dropdownStates)) {
                dropdownStates[dropdownId] = false;
            }

            dropdownStates[dropdownId] = !dropdownStates[dropdownId];

            if (dropdownStates[dropdownId]) {
                dropdown.classList.remove('hidden');
                chevron.style.transform = 'rotate(180deg)';
            } else {
                dropdown.classList.add('hidden');
                chevron.style.transform = 'rotate(0deg)';
            }
        }

        // Initialize dropdown state
        document.addEventListener('DOMContentLoaded', function() {
            @php
                $activeDropdown = null;
                foreach ($navItems as $item) {
                    if (isset($item['hasDropdown']) && $item['hasDropdown']) {
                        if (isset($item['dropdownId']) && $item['dropdownId'] === 'laporan') {
                            $currentSubmenus = $laporanSubmenus;
                        } elseif (isset($item['dropdownId']) && $item['dropdownId'] === 'kelola-produk') {
                            $currentSubmenus = $kelolaProdukSubmenus;
                        } elseif (isset($item['dropdownId']) && $item['dropdownId'] === 'master') {
                            $currentSubmenus = $masterSubmenus;
                        } else {
                            $currentSubmenus = [];
                        }
                        foreach ($currentSubmenus as $submenu) {
                            if ($currentPath === $submenu['href']) {
                                $activeDropdown = $item['dropdownId'];
                                break 2;
                            }
                        }
                    }
                }
            @endphp

            @if($activeDropdown)
                const activeDropdownId = '{{ $activeDropdown }}';
                dropdownStates[activeDropdownId] = true;
                const dropdown = document.getElementById(activeDropdownId + '-dropdown');
                const chevron = document.getElementById(activeDropdownId + '-chevron');
                if (dropdown) dropdown.classList.remove('hidden');
                if (chevron) chevron.style.transform = 'rotate(180deg)';
            @endif
        });
    </script>

    <div class="flex items-center gap-3 px-5 py-4 border-t border-[#2a2a2a] mt-auto">
        <div
            class="w-8 h-8 rounded-full bg-[#c8d44e] flex items-center justify-center text-black text-sm font-bold shadow-sm">
            S
        </div>
        <div class="flex flex-col">
            <span class="text-sm text-gray-200 font-medium">CritaSena</span>
            <span class="text-xs text-gray-500">Admin</span>
        </div>
        <i data-lucide="chevron-up" class="text-gray-400 ml-auto w-4 h-4 cursor-pointer hover:text-white"></i>
    </div>
</aside>