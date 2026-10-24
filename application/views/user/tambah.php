<header class="bg-white border-b border-slate-200 h-16 flex items-center justify-between px-6 z-10">
    <h1 class="text-lg font-semibold text-slate-700">Tambah Pengguna Baru</h1>
</header>

<main class="flex-1 overflow-y-auto p-6 bg-slate-50 w-full">

    <div class="mb-6 flex items-center justify-between max-w-2xl">
        <div>
            <h2 class="text-xl font-bold text-slate-800">Form Pengguna Baru</h2>
            <p class="text-xs text-slate-500">Lengkapi data untuk menambahkan pengguna ke dalam sistem.</p>
        </div>
        <a href="<?= site_url('user'); ?>" class="text-slate-600 hover:text-slate-800 text-sm flex items-center gap-2 bg-white px-3 py-2 rounded-lg border border-slate-200 shadow-sm transition">
            <i class="fa-solid fa-arrow-left"></i>
            <span>Kembali</span>
        </a>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6 max-w-2xl">
        <form action="<?= site_url('user/simpan'); ?>" method="POST" class="space-y-4">
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Username / NIP <span class="text-rose-500">*</span></label>
                <input type="text" name="username" required class="w-full px-3.5 py-2 text-sm rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-teal-500" placeholder="Masukkan username / NIP">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Nama Lengkap <span class="text-rose-500">*</span></label>
                <input type="text" name="nama_lengkap" required class="w-full px-3.5 py-2 text-sm rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-teal-500" placeholder="Masukkan nama lengkap">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Password <span class="text-rose-500">*</span></label>
                <input type="password" name="password" required class="w-full px-3.5 py-2 text-sm rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-teal-500" placeholder="Kata sandi akun">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Role / Peran <span class="text-rose-500">*</span></label>
                    <select name="role" required class="w-full px-3.5 py-2 text-sm rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-teal-500">
                        <option value="ADMIN">ADMIN (Administrator)</option>
                        <option value="KALA">KALA (Kepala Lab)</option>
                        <option value="STAF">STAF (Pengelola / Guru)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Status Akun</label>
                    <select name="status" class="w-full px-3.5 py-2 text-sm rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-teal-500">
                        <option value="Aktif">Aktif</option>
                        <option value="Nonaktif">Nonaktif</option>
                    </select>
                </div>
            </div>

            <div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-100">
                <a href="<?= site_url('user'); ?>" class="px-4 py-2 text-sm font-medium text-slate-600 hover:text-slate-800 transition">Batal</a>
                <button type="submit" class="px-4 py-2 bg-teal-600 hover:bg-teal-700 text-white font-medium text-sm rounded-lg shadow-sm transition">Simpan Pengguna</button>
            </div>
        </form>
    </div>

</main>