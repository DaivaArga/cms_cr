@extends('admin.layout.app')

@section('title')
    Hak Akses - Master
@endsection

@include('admin.master.role-modal')
<div class="p-6">
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Hak Akses</h1>
            <p class="text-gray-600">Kelola hak akses untuk setiap role dalam sistem</p>
        </div>

        <!-- Table Controls -->
        <div class="flex justify-between items-center mb-4">
            <div class="flex gap-2">
                <div class="relative">
                    <input type="text" id="searchInput" placeholder="Cari role..."
                        class="w-64 pl-10 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#c8d44e] focus:border-transparent"
                        onkeyup="handleSearch(event)">
                    <i data-lucide="search" class="absolute right-3 top-1/2 text-gray-400"></i>
                </div>
                <button onclick="refreshData()"
                        class="px-4 py-2 bg-gray-100 hover:bg-gray-200 rounded-md border border-gray-300 transition-colors duration-200">
                    <i data-lucide="refresh-cw" class="w-4 h-4"></i>
                </button>
                <button onclick="openRoleModal()" class="px-4 py-2 bg-[#c8d44e] text-white font-medium rounded-md hover:bg-[#d4c35] transition-colors duration-200 border border-transparent">
                    <i data-lucide="plus" class="w-4 h-4"></i>
                </button>
            </div>
            <div class="flex gap-2">
                <div class="relative">
                    <select id="sortBy" onchange="handleSort()"
                            class="w-48 pl-3 pr-2 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#c8d44e] focus:border-transparent">
                        <option value="nama">Nama</option>
                        <option value="created_at">Tanggal Dibuat</option>
                        <option value="updated_at">Tanggal Diupdate</option>
                    </select>
                </div>
                <div class="relative">
                    <select id="sortDirection" onchange="handleSort()"
                            class="w-40 pl-3 pr-2 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#c8d44e] focus:border-transparent">
                        <option value="asc">A-Z</option>
                        <option value="desc">Z-A</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto border border border-gray-200 rounded-lg">
            <table class="min-w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-12">
                            #
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition-colors"
                            onclick="handleSort('nama')">
                            Nama Role
                            <i data-lucide="chevron-up-down" class="ml-2 w-3 h-3 transition-transform"></i>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition-colors"
                            onclick="handleSort('created_at')">
                            Tanggal Dibuat
                            <i data-lucide="chevron-up-down" class="ml-2 w-3 h-3 transition-transform"></i>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition-colors"
                            onclick="handleSort('updated_at')">
                            Tanggal Diupdate
                            <i data-lucide="chevron-up-down" class="ml-2 w-3 h-3 transition-transform"></i>
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider w-40">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody id="rolesTableBody" class="bg-white divide-y divide-gray-200">
                    <!-- Data akan diisi oleh JavaScript -->
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="flex justify-between items-center mt-4">
            <div class="text-sm text-gray-600">
                Menampilkan <span id="showingFrom">0</span> - <span id="showingTo">0</span> dari <span id="totalRoles">0</span> role
            </div>
            <div class="flex items-center gap-2">
                <button onclick="loadRoles(-1)" id="prevBtn"
                        class="px-4 py-2 bg-white hover:bg-gray-50 border border-gray-300 rounded-md transition-colors duration-200 disabled">
                    <i data-lucide="chevron-left" class="w-4 h-4"></i>
                </button>
                <button onclick="loadRoles(1)" id="nextBtn"
                        class="px-4 py-2 bg-white hover:bg-gray-50 border border-gray-300 rounded-md transition-colors duration-200 disabled">
                    <i data-lucide="chevron-right" class="w-4 h-4"></i>
                </button>
            </div>
        </div>

        <!-- Empty State -->
        <div id="emptyState" class="hidden text-center py-12">
            <div class="text-gray-500 mb-4">
                <i data-lucide="search" class="w-16 h-16 text-gray-300 mb-4"></i>
                <p class="text-lg font-medium">Tidak ada data role</p>
                <button onclick="refreshData()"
                        class="mt-4 px-6 py-2 bg-[#c8d44e] text-white hover:bg-[#d4c35] rounded-md border border-transparent transition-colors duration-200">
                    Refresh Data
                </button>
            </div>
        </div>

        <!-- Loading State -->
        <div id="loadingState" class="hidden text-center py-12">
            <div class="flex justify-center items-center">
                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-[#c8d44e]"></div>
                <span class="ml-3 text-gray-600">Memuat data...</span>
            </div>
        </div>

        <!-- Error State -->
        <div id="errorState" class="hidden text-center py-12">
            <div class="text-gray-500 mb-4">
                <i data-lucide="alert-circle" class="w-16 h-16 text-red-500 mb-4"></i>
                <p id="errorMessage" class="text-lg font-medium text-red-500">Terjadi kesalahan saat memuat data</p>
                <button onclick="refreshData()"
                        class="mt-4 px-6 py-2 bg-[#c8d44e] text-white hover:bg-[#d4c35] rounded-md border border-transparent transition-colors duration-200">
                    Coba Lagi
                </button>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
<script>
    const API_URL = '/api/roles';
    let currentPage = 1;
    let totalPages = 0;
    let currentSortBy = 'created_at';
    let currentSortDirection = 'desc';
    let allRoles = [];
    let totalRolesCount = 0;

    // Load roles on page load
    document.addEventListener('DOMContentLoaded', function() {
        loadRoles();
    });

    function handleSearch(event) {
        if (event.key === 'Enter') {
            loadRoles();
        }
    }

    function handleSort(sortBy = null) {
        if (sortBy) {
            currentSortBy = sortBy;
        }
        const sortDirectionInput = document.getElementById('sortDirection');
        if (sortDirectionInput) {
            currentSortDirection = sortDirectionInput.value;
        }
        refreshData();
    }

    async function loadRoles(page = 1) {
        try {
            showLoading();

            // Build query parameters
            const searchInput = document.getElementById('searchInput').value.trim();
            const params = new URLSearchParams({
                per_page: 15,
                page: page,
                sort_by: currentSortBy,
                sort_direction: currentSortDirection
            });

            if (searchInput) {
                params.append('search', searchInput);
            }

            const response = await fetch(`${API_URL}?${params.toString()}`, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            if (!response.ok) {
                throw new Error('Gagal mengambil data');
            }

            const result = await response.json();

            if (result.success) {
                allRoles = result.data.data;
                totalPages = result.data.meta.last_page;
                currentPage = result.data.meta.current_page;
                totalRolesCount = result.data.meta.total;
                renderTable();
                updatePagination();
            } else {
                showError(result.message || 'Gagal mengambil data');
            }
        } catch (error) {
            showError('Gagal menghubungi server. Silakan coba lagi.');
            console.error('Error:', error);
        }
    }

    function renderTable() {
        const tbody = document.getElementById('rolesTableBody');
        tbody.innerHTML = '';

        // Hide all states first
        document.getElementById('loadingState').classList.add('hidden');
        document.getElementById('emptyState').classList.add('hidden');
        document.getElementById('errorState').classList.add('hidden');

        if (allRoles.length === 0) {
            showEmptyState();
            showTableControls(false);
            return;
        }

        showTableControls(true);

        allRoles.forEach((role, index) => {
            const globalIndex = (currentPage - 1) * 15 + index + 1;

            const row = document.createElement('tr');
            row.className = index % 2 === 0 ? 'bg-white' : 'bg-gray-50';

            row.innerHTML = `
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                    ${globalIndex}.
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">
                    ${role.nama}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                    ${role.created_at}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                    ${role.updated_at}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 text-right">
                    <button onclick="openRoleModal('${role.id}', '${role.nama}')"
                        class="px-3 py-1.5 text-xs bg-[#c8d44e] text-white hover:bg-[#d4c35] rounded border border-transparent transition-colors duration-200">
                        Edit
                    </button>
                    <button onclick="deleteRole('${role.id}')"
                        class="px-3 py-1.5 text-xs bg-red-500 text-white hover:bg-red-600 rounded border border-transparent transition-colors duration-200 ml-2">
                        Hapus
                    </button>
                </td>
            `;

            tbody.appendChild(row);
        });

        showTableControls(true);
    }

    function updatePagination() {
        document.getElementById('showingFrom').textContent = (currentPage - 1) * 15 + 1;
        document.getElementById('showingTo').textContent = Math.min(currentPage * 15, totalRolesCount);
        document.getElementById('totalRoles').textContent = totalRolesCount;
    }

    function showEmptyState() {
        document.getElementById('loadingState').classList.add('hidden');
        document.getElementById('errorState').classList.add('hidden');
        document.getElementById('emptyState').classList.remove('hidden');
        showTableControls(false);
    }

    function showTableControls(show) {
        const prevBtn = document.getElementById('prevBtn');
        const nextBtn = document.getElementById('nextBtn');

        if (show) {
            prevBtn.disabled = currentPage <= 1;
            nextBtn.disabled = currentPage >= totalPages;
        } else {
            prevBtn.disabled = false;
            nextBtn.disabled = false;
        }
    }

    function openRoleModal(roleId = null, roleName = '') {
        const modal = document.getElementById('roleModal');
        const title = document.getElementById('modalTitle');
        const idInput = document.getElementById('roleId');
        const namaInput = document.getElementById('nama');

        if (roleId) {
            title.textContent = 'Edit Role';
            idInput.value = roleId;
            namaInput.value = roleName;
        } else {
            title.textContent = 'Tambah Role Baru';
            idInput.value = '';
            namaInput.value = '';
        }

        modal.classList.remove('hidden');
    }

    function closeRoleModal() {
        const modal = document.getElementById('roleModal');
        modal.classList.add('hidden');
        document.getElementById('roleForm').reset();
        document.getElementById('roleId').value = '';
    }

    async function handleRoleSubmit(event) {
        event.preventDefault();

        const idInput = document.getElementById('roleId').value;
        const namaInput = document.getElementById('nama').value;

        const url = idInput ? `${API_URL}/${idInput}` : API_URL;
        const method = idInput ? 'PUT' : 'POST';

        try {
            const response = await fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ nama: namaInput })
            });

            const result = await response.json();

            if (result.success) {
                closeRoleModal();
                loadRoles(currentPage);
                alert(idInput ? 'Role berhasil diupdate' : 'Role berhasil ditambahkan');
            } else {
                alert(result.message || 'Gagal menyimpan data role');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat menyimpan data role');
        }
    }

    async function deleteRole(roleId) {
        if (!confirm('Apakah Anda yakin ingin menghapus role ini?')) {
            return;
        }

        try {
            const response = await fetch(`${API_URL}/${roleId}`, {
                method: 'DELETE',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });

            const result = await response.json();

            if (result.success) {
                loadRoles(currentPage);
                alert('Role berhasil dihapus');
            } else {
                alert(result.message || 'Gagal menghapus role');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat menghapus role');
        }
    }

    function refreshData() {
        loadRoles(currentPage);
    }

    function showLoading() {
        document.getElementById('loadingState').classList.remove('hidden');
        document.getElementById('emptyState').classList.add('hidden');
        document.getElementById('errorState').classList.add('hidden');
    }

    function showError(message) {
        document.getElementById('loadingState').classList.add('hidden');
        document.getElementById('emptyState').classList.add('hidden');
        document.getElementById('errorState').classList.remove('hidden');
        document.getElementById('errorMessage').textContent = message;
    }
</script>
