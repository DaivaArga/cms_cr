@php
    // Kita definisikan data navigasi di sini (atau bisa dikirim dari Controller/ViewComposer)
    // Nama ikon disesuaikan dengan format 'kebab-case' untuk library ikon Blade
    $navItems = [
        ['icon' => 'layout-grid', 'label' => 'Home', 'active' => true],
        ['icon' => 'dollar-sign', 'label' => 'Sell', 'active' => false],
        ['icon' => 'bar-chart-2', 'label' => 'Reporting', 'active' => false, 'badge' => 2],
        ['icon' => 'shopping-cart', 'label' => 'Catalog', 'active' => false],
        ['icon' => 'package', 'label' => 'Inventory', 'active' => false],
        ['icon' => 'users', 'label' => 'Customers', 'active' => false],
        ['icon' => 'settings', 'label' => 'Setup', 'active' => false],
    ];
@endphp

<div class="w-[220px] min-w-[220px] bg-[#1a1a1a] h-full flex flex-col text-white">

    {{-- Logo Header --}}
    <div class="flex items-center justify-between px-5 py-5">
        <span class="text-white tracking-wider text-sm font-semibold">INVENTORY360</span>
        {{-- Ikon Menu --}}
        <x-lucide-menu class="text-gray-400 cursor-pointer w-[18px] h-[18px]" />
    </div>

    {{-- Navigation List --}}
    <nav class="flex-1 mt-2">
        @foreach($navItems as $item)
            @php
                // Menentukan kelas aktif vs tidak aktif
                $containerClasses = $item['active']
                    ? 'bg-[#2a2a2a] border-l-2 border-white'
                    : 'hover:bg-[#252525] border-l-2 border-transparent';

                $textClasses = $item['active'] ? 'text-white' : 'text-gray-400';
            @endphp

            <div class="flex items-center gap-3 px-5 py-3 cursor-pointer relative {{ $containerClasses }}">

                {{-- Dynamic Icon: Membutuhkan package blade-lucide-icons --}}
                <x-dynamic-component :component="'lucide-' . $item['icon']" class="w-[18px] h-[18px] {{ $textClasses }}" />

                <span class="text-sm {{ $textClasses }}">
                    {{ $item['label'] }}
                </span>

                {{-- Badge (Jika ada) --}}
                @if(isset($item['badge']) && $item['badge'])
                    <span
                        class="ml-auto bg-[#c8d44e] text-black text-xs rounded-full w-5 h-5 flex items-center justify-center font-semibold">
                        {{ $item['badge'] }}
                    </span>
                @endif
            </div>
        @endforeach
    </nav>

    {{-- User Footer --}}
    <div class="flex items-center gap-3 px-5 py-4 border-t border-[#2a2a2a]">
        <div
            class="w-8 h-8 rounded-full bg-[#c8d44e] flex items-center justify-center text-black text-sm font-semibold">
            S
        </div>
        <span class="text-sm text-gray-300">Sasha Merkel</span>
        <x-lucide-chevron-up class="text-gray-400 ml-auto w-[14px] h-[14px]" />
    </div>

</div>