<div id="roleModal" class="fixed inset-0 z-50 hidden" role="dialog" aria-modal="true">
    <div class="bg-black bg-opacity-50 flex items-center justify-center min-h-screen">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-md p-6">
            <div class="flex justify-between items-center mb-6">
                <h3 id="modalTitle" class="text-xl font-bold text-gray-800">Tambah Role Baru</h3>
                <button onclick="closeRoleModal()" class="text-gray-400 hover:text-gray-600">
                    <i data-lucide="x" class="w-6 h-6"></i>
                </button>
            </div>

            <form id="roleForm" onsubmit="handleRoleSubmit(event)">
                @csrf
                <input type="hidden" id="roleId" name="id" value="">

                <div class="mb-6">
                    <label for="nama" class="block text-sm font-medium text-gray-700 mb-2">Nama Role</label>
                    <input type="text" id="nama" name="nama" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#c8d44e] focus:border-transparent"
                        placeholder="Contoh: Super Admin">
                </div>

                <div class="flex justify-end gap-3">
                    <button type="submit" class="px-6 py-2 bg-[#c8d44e] text-white font-medium rounded-md hover:bg-[#d4c35] transition-colors duration-200">
                        Simpan
                    </button>
                    <button type="button" onclick="closeRoleModal()"
                        class="px-6 py-2 bg-gray-200 text-gray-800 font-medium rounded-md hover:bg-gray-300 transition-colors duration-200">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
