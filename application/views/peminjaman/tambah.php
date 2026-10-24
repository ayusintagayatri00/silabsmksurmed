<!-- Top Header Navbar -->
<header class="bg-white border-b border-slate-200 h-16 flex items-center justify-between px-6 z-10">
    <div class="flex items-center gap-4">
        <h1 class="text-lg font-semibold text-slate-700">Peminjaman Alat</h1>
    </div>

</header>

<!-- Main Body Content -->
<main class="flex-1 overflow-y-auto p-6 bg-slate-50 w-full">

    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-slate-800">Form Pinjam Alat Baru</h2>
            <p class="text-xs text-slate-500">Catat peminjaman instrumen / alat praktikum laboratorium.</p>
        </div>
        <a href="<?= base_url('peminjaman'); ?>" class="text-slate-600 hover:text-slate-800 text-sm flex items-center gap-2 bg-white px-3 py-2 rounded-lg border border-slate-200 shadow-sm transition">
            <i class="fa-solid fa-arrow-left"></i>
            <span>Kembali</span>
        </a>
    </div>

    <!-- Form Card Container -->
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6 max-w-3xl">
        <form action="<?= base_url('peminjaman/simpan'); ?>" method="POST" class="space-y-5">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <!-- Kode Peminjaman (Otomatis) -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Kode Peminjaman</label>
                    <input type="text" name="kode_peminjaman" value="<?= $kode_peminjaman; ?>" readonly
                        class="w-full px-3.5 py-2 text-sm font-mono font-semibold bg-slate-100 border border-slate-300 rounded-lg text-slate-600 cursor-not-allowed">
                </div>

                <!-- Jumlah Pinjam -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Jumlah Pinjam <span class="text-rose-500">*</span></label>
                    <input type="number" name="jumlah_pinjam" min="1" value="1" required
                        class="w-full px-3.5 py-2 text-sm rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>

                <!-- Pilih Alat -->
                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Pilih Alat Medis / Instrumen <span class="text-rose-500">*</span></label>
                    <select name="id_barang" required class="w-full px-3.5 py-2 text-sm rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-teal-500">
                        <option value="" disabled selected>-- Pilih Alat --</option>
                        <?php foreach ($barang as $b) : ?>
                            <option value="<?= $b['id_barang']; ?>">
                                <?= $b['kode_barcode'] . ' - ' . $b['nama_barang'] . ' (Stok Tersedia: ' . $b['stok'] . ' ' . $b['satuan'] . ')'; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Tanggal Pinjam -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Tanggal Pinjam <span class="text-rose-500">*</span></label>
                    <input type="date" name="tgl_pinjam" value="<?= date('Y-m-d'); ?>" required
                        class="w-full px-3.5 py-2 text-sm rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>

                <!-- Rencana Kembali -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Rencana Tanggal Kembali <span class="text-rose-500">*</span></label>
                    <input type="date" name="tgl_kembali_rencana" value="<?= date('Y-m-d', strtotime('+1 day')); ?>" required
                        class="w-full px-3.5 py-2 text-sm rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>

                <!-- Catatan / Keperluan -->
                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Catatan / Keperluan Praktikum</label>
                    <textarea name="catatan" rows="3" placeholder="Contoh: Praktikum Pemeriksaan Tanda-Tanda Vital Kelas XI"
                        class="w-full px-3.5 py-2 text-sm rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-teal-500"></textarea>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
                <a href="<?= base_url('peminjaman'); ?>" class="px-4 py-2 text-sm rounded-lg border border-slate-300 text-slate-700 font-medium hover:bg-slate-50 transition">Batal</a>
                <button type="submit" class="px-5 py-2 text-sm rounded-lg bg-teal-600 hover:bg-teal-700 text-white font-medium shadow-sm transition flex items-center gap-2">
                    <i class="fa-solid fa-paper-plane"></i>
                    <span>Simpan Peminjaman</span>
                </button>
            </div>

        </form>
    </div>

</main>