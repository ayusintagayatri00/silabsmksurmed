<!-- Top Header Navbar -->
<header class="bg-white border-b border-slate-200 h-16 flex items-center justify-between px-6 z-10">
    <div class="flex items-center gap-4">
        <h1 class="text-lg font-semibold text-slate-700">Peminjaman Alat</h1>
    </div>

    <!-- User Profile Corner -->
    <div class="flex items-center gap-4">
        <div class="text-right hidden sm:block">
            <p class="text-sm font-semibold text-slate-800"><?= $this->session->userdata('nama_lengkap') ?? 'Administrator Lab'; ?></p>
            <span class="text-xs px-2 py-0.5 bg-teal-100 text-teal-800 rounded-full font-medium uppercase">
                <?= $this->session->userdata('role') ?? 'ADMIN'; ?>
            </span>
        </div>
        <div class="w-10 h-10 rounded-full bg-teal-600 text-white flex items-center justify-center font-bold">
            <?= strtoupper(substr($this->session->userdata('nama_lengkap') ?? 'A', 0, 1)); ?>
        </div>
    </div>
</header>

<main class="flex-1 overflow-y-auto p-6 bg-slate-50 w-full">

    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-slate-800">Edit Data Peminjaman</h2>
            <p class="text-xs text-slate-500">Perbarui status atau detail transaksi peminjaman alat.</p>
        </div>
        <a href="<?= base_url('peminjaman'); ?>" class="text-slate-600 hover:text-slate-800 text-sm flex items-center gap-2 bg-white px-3 py-2 rounded-lg border border-slate-200 shadow-sm transition">
            <i class="fa-solid fa-arrow-left"></i>
            <span>Kembali</span>
        </a>
    </div>

    <!-- Form Container -->
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6 max-w-3xl">
        <form action="<?= base_url('peminjaman/update'); ?>" method="POST" class="space-y-5">
            <!-- hapus pembacaan manual, sekarang ambil dari database -->
            <input type="hidden" name="id_peminjaman" value="<?= $peminjaman['id_peminjaman']; ?>">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <!-- Kode Peminjaman -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Kode Peminjaman</label>
                    <input type="text" value="<?= $peminjaman['kode_peminjaman']; ?>" readonly
                        class="w-full px-3.5 py-2 text-sm font-mono font-semibold bg-slate-100 border border-slate-300 rounded-lg text-slate-600 cursor-not-allowed">
                </div>

                <!-- Status Peminjaman -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Status Peminjaman <span class="text-rose-500">*</span></label>
                    <select name="status" required class="w-full px-3.5 py-2 text-sm rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-teal-500">
                       <!-- fix perbaiki jalur Edit → Simpan Perubahan. Tombol approval/pengembalian pada daftar menggunakan M_Peminjaman::proses_approval() yang terpisah; -->
                       <?php 
                       $pilihan_status = ['Pending','Disetujui','Ditolak','Dipinjam','Dikembalikan']; 
                       ?>
                        <?php foreach ($pilihan_status as $status): ?>
                            <option value="<?= html_escape($status); ?>"
                                <?= $peminjaman['status'] === $status ? 'selected' : ''; ?>>
                                <?= html_escape($status); ?>
                            </option>
                        <?php endforeach; ?> 
                    </select>
                </div>
                <!-- Pilih Alat -->
                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Alat Medis / Instrumen <span class="text-rose-500">*</span></label>
                    <select name="id_barang" required class="w-full px-3.5 py-2 text-sm rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-teal-500">
                        <?php foreach ($barang as $b) : ?>
                            <option value="<?= $b['id_barang']; ?>" <?= ($b['id_barang'] == $peminjaman['id_barang']) ? 'selected' : ''; ?>>
                                <?= $b['kode_barcode'] . ' - ' . $b['nama_barang'] . ' (Stok: ' . $b['stok'] . ' ' . $b['satuan'] . ')'; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Jumlah Pinjam -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Jumlah Pinjam <span class="text-rose-500">*</span></label>
                    <input type="number" name="jumlah_pinjam" min="1" value="<?= $peminjaman['jumlah_pinjam']; ?>" required
                        class="w-full px-3.5 py-2 text-sm rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>

                <!-- Tanggal Pinjam -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Tanggal Pinjam <span class="text-rose-500">*</span></label>
                    <input type="date" name="tgl_pinjam" value="<?= date('Y-m-d', strtotime($peminjaman['tgl_pinjam'])); ?>" required
                        class="w-full px-3.5 py-2 text-sm rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>

                <!-- Rencana Kembali -->
                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Rencana Tanggal Kembali <span class="text-rose-500">*</span></label>
                    <input type="date" name="tgl_kembali_rencana" value="<?= date('Y-m-d', strtotime($peminjaman['tgl_kembali_rencana'])); ?>" required
                        class="w-full px-3.5 py-2 text-sm rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>

                <!-- Catatan -->
                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Catatan / Keperluan Praktikum</label>
                    <textarea name="catatan" rows="3" class="w-full px-3.5 py-2 text-sm rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-teal-500"><?= $peminjaman['catatan']; ?></textarea>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
                <a href="<?= base_url('peminjaman'); ?>" class="px-4 py-2 text-sm rounded-lg border border-slate-300 text-slate-700 font-medium hover:bg-slate-50 transition">Batal</a>
                <button type="submit" class="px-5 py-2 text-sm rounded-lg bg-teal-600 hover:bg-teal-700 text-white font-medium shadow-sm transition flex items-center gap-2">
                    <i class="fa-solid fa-floppy-disk"></i>
                    <span>Simpan Perubahan</span>
                </button>
            </div>

        </form>
    </div>

</main>