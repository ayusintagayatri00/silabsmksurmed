<div class="p-6">
    <!-- Header Halaman -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div>
            <p class="text-sm text-gray-500">Kelola data pengguna sistem laboratorium</p>
        </div>
        <div class="flex items-center gap-3 w-full sm:w-auto">
            <!-- Input Pencarian -->
            <div class="relative w-full sm:w-64">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </span>
                <input type="text" id="searchInput" onkeyup="filterUserTable()" placeholder="Cari user / nama..." class="w-full pl-9 pr-4 py-2 border border-gray-300 rounded-lg text-sm bg-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors">
            </div>

            <button type="button" onclick="openModal('modalTambah')" class="inline-flex items-center px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-medium text-sm rounded-lg shadow-sm transition-colors whitespace-nowrap">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Tambah User
            </button>

        </div>
    </div>

    <!-- Alert Flashdata -->
    <?php if ($this->session->flashdata('success')): ?>
        <div class="mb-4 p-4 text-sm text-emerald-800 bg-emerald-100 rounded-lg border border-emerald-200 flex justify-between items-center">
            <span><?= $this->session->flashdata('success'); ?></span>
            <button onclick="this.parentElement.remove()" class="text-emerald-800 hover:text-emerald-900 font-bold">&times;</button>
        </div>
    <?php endif; ?>

    <?php if ($this->session->flashdata('error')): ?>
        <div class="mb-4 p-4 text-sm text-red-800 bg-red-100 rounded-lg border border-red-200 flex justify-between items-center">
            <span><?= $this->session->flashdata('error'); ?></span>
            <button onclick="this.parentElement.remove()" class="text-red-800 hover:text-red-900 font-bold">&times;</button>
        </div>
    <?php endif; ?>

    <!-- Tabel Data User -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse" id="userTable">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200 text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        <th class="py-3 px-4 text-center w-12">No</th>
                        <th class="py-3 px-4">Username</th>
                        <th class="py-3 px-4">Nama Lengkap</th>
                        <th class="py-3 px-4 text-center">Role</th>
                        <th class="py-3 px-4 text-center w-40">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 text-sm text-gray-700" id="userTableBody">
                    <?php $no = 1;
                    foreach ($users as $u): ?>
                        <tr class="user-row hover:bg-gray-50 transition-colors">
                            <td class="py-3 px-4 text-center font-medium text-gray-500 row-number"><?= $no++; ?></td>
                            <td class="py-3 px-4 font-semibold text-gray-900 col-username"><?= htmlspecialchars($u['username']); ?></td>
                            <td class="py-3 px-4 col-nama"><?= htmlspecialchars($u['nama_lengkap'] ?? $u['nama'] ?? '-'); ?></td>
                            <td class="py-3 px-4 text-center col-role">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-teal-100 text-teal-800 uppercase">
                                    <?= htmlspecialchars($u['role'] ?? 'User'); ?>
                                </span>
                            </td>
                            <td class="py-3 px-4 text-center">
                                <div class="flex items-center justify-center space-x-2">
                                    <button type="button" onclick="openModal('modalEdit<?= $u['id_user']; ?>')" class="inline-flex items-center px-2.5 py-1.5 bg-amber-500 hover:bg-amber-600 text-white text-xs font-medium rounded transition-colors">
                                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                        Edit
                                    </button>
                                    <a href="<?= site_url('user/hapus/' . $u['id_user']); ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus user ini?')" class="inline-flex items-center px-2.5 py-1.5 bg-rose-600 hover:bg-rose-700 text-white text-xs font-medium rounded transition-colors">
                                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                        Hapus
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <tr id="emptySearchRow" class="hidden">
                        <td colspan="5" class="py-6 text-center text-sm text-gray-500 italic">Data user tidak ditemukan.</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- MODAL TAMBAH USER -->
<div id="modalTambah" class="fixed inset-0 bg-gray-900 bg-opacity-50 hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-md overflow-hidden transform transition-all">
        <div class="px-6 py-4 bg-emerald-600 flex justify-between items-center text-white">
            <h3 class="font-bold text-lg">Tambah User Baru</h3>
            <button type="button" onclick="closeModal('modalTambah')" class="text-white hover:text-gray-200 text-xl font-bold">&times;</button>
        </div>
        <form action="<?= site_url('user/tambah'); ?>" method="post" class="p-6 space-y-4">
            <!-- penambahan CSRF Token -->
            <input
                type="hidden"
                name="<?= html_escape($this->security->get_csrf_token_name()); ?>"
                value="<?= html_escape($this->security->get_csrf_hash()); ?>">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                <input type="text" name="username" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-sm outline-none" placeholder="Masukkan username" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                <input type="text" name="nama_lengkap" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-sm outline-none" placeholder="Masukkan nama lengkap" required>
            </div>
            <!-- Tambahkan input untuk NIP / NIK / NIS -->
            <div>
                <label class="block text-sm font-medium mb-1">NIP / NIK / NIS</label>
                <input
                    type="text"
                    name="nip_nik_user"
                    maxlength="100"
                    required
                    class="w-full px-3 py-2 border rounded-lg text-sm"
                >
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Email</label>
                <input
                    type="email"
                    name="email"
                    maxlength="100"
                    required
                    class="w-full px-3 py-2 border rounded-lg text-sm"
                >
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <input type="password" name="password" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-sm outline-none" placeholder="Masukkan password" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                <select name="role" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-sm outline-none" required>
                    <option value="">-- Pilih Role --</option>
                    <option value="admin">Admin</option>
                    <option value="laboran">Laboran</option>
                    <option value="guru">Guru</option>
                    <option value="siswa">Siswa</option>
                    <option value="kepsek">Kepsek</option>
                </select>
            </div>
            <div class="flex justify-end space-x-3 pt-4 border-t border-gray-100">
                <button type="button" onclick="closeModal('modalTambah')" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-colors">Batal</button>
                <button type="submit" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium rounded-lg transition-colors">Simpan User</button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL EDIT USER -->
<?php foreach ($users as $u): ?>
    <div id="modalEdit<?= $u['id_user']; ?>" class="fixed inset-0 bg-gray-900 bg-opacity-50 hidden items-center justify-center z-50 p-4">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-md overflow-hidden transform transition-all">
            <div class="px-6 py-4 bg-amber-500 flex justify-between items-center text-white">
                <h3 class="font-bold text-lg">Edit Data User</h3>
                <button type="button" onclick="closeModal('modalEdit<?= $u['id_user']; ?>')" class="text-white hover:text-gray-200 text-xl font-bold">&times;</button>
            </div>
            <form action="<?= site_url('user/edit'); ?>" method="post" class="p-6 space-y-4">
                <!-- penambahan CSRF Token -->
                <input
                    type="hidden"
                    name="<?= html_escape($this->security->get_csrf_token_name()); ?>"
                    value="<?= html_escape($this->security->get_csrf_hash()); ?>">
                <input type="hidden" name="id_user" value="<?= $u['id_user']; ?>">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                    <input type="text" name="username" value="<?= htmlspecialchars($u['username']); ?>" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 text-sm outline-none" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                    <input type="text" name="nama_lengkap" value="<?= htmlspecialchars($u['nama_lengkap'] ?? $u['nama'] ?? ''); ?>" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 text-sm outline-none" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Password Baru <span class="text-xs text-gray-400 font-normal">(Kosongkan jika tidak diubah)</span></label>
                    <input type="password" name="password" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 text-sm outline-none" placeholder="••••••••">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                    <select name="role" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 text-sm outline-none" required>
                        <option value="admin" <?= strtolower($u['role']) == 'admin' ? 'selected' : ''; ?>>Admin</option>
                        <option value="laboran" <?= strtolower($u['role']) == 'laboran' ? 'selected' : ''; ?>>Laboran</option>
                        <option value="guru" <?= strtolower($u['role']) == 'guru' ? 'selected' : ''; ?>>Guru</option>
                        <option value="siswa" <?= strtolower($u['role']) == 'siswa' ? 'selected' : ''; ?>>Siswa</option>
                        <option value="kepsek" <?= strtolower($u['role']) == 'kepsek' ? 'selected' : ''; ?>>Kepsek</option>
                    </select>
                </div>
                <div class="flex justify-end space-x-3 pt-4 border-t border-gray-100">
                    <button type="button" onclick="closeModal('modalEdit<?= $u['id_user']; ?>')" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-colors">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white text-sm font-medium rounded-lg transition-colors">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
<?php endforeach; ?>

<script>
    function openModal(id) {
        const modal = document.getElementById(id);
        if (modal) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }
    }

    function closeModal(id) {
        const modal = document.getElementById(id);
        if (modal) {
            modal.classList.remove('flex');
            modal.classList.add('hidden');
        }
    }

    function filterUserTable() {
        const input = document.getElementById('searchInput');
        const filter = input.value.toLowerCase().trim();
        const rows = document.querySelectorAll('#userTableBody tr.user-row');
        const emptyRow = document.getElementById('emptySearchRow');
        let visibleCount = 0;

        rows.forEach(row => {
            const username = row.querySelector('.col-username')?.textContent.toLowerCase() || '';
            const nama = row.querySelector('.col-nama')?.textContent.toLowerCase() || '';
            const role = row.querySelector('.col-role')?.textContent.toLowerCase() || '';

            if (username.includes(filter) || nama.includes(filter) || role.includes(filter)) {
                row.style.display = '';
                visibleCount++;
                const numberCell = row.querySelector('.row-number');
                if (numberCell) {
                    numberCell.textContent = visibleCount;
                }
            } else {
                row.style.display = 'none';
            }
        });

        if (visibleCount === 0) {
            emptyRow.classList.remove('hidden');
        } else {
            emptyRow.classList.add('hidden');
        }
    }
</script>