<header class="bg-white border-b border-slate-200 h-16 flex items-center justify-between px-6 z-10">
    <div class="flex items-center gap-4">
        <h1 class="text-lg font-semibold text-slate-700">Pengadaan Alat & BHP</h1>
    </div>
</header>

<main class="flex-1 overflow-y-auto p-6 bg-slate-50 w-full">

    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-slate-800">Rincian Pengajuan Pengadaan</h2>
            <p class="text-xs text-slate-500">Detail usulan dan verifikasi status persetujuan pengadaan.</p>
        </div>
        <a href="<?= site_url('pengadaan'); ?>" class="text-slate-600 hover:text-slate-800 text-sm flex items-center gap-2 bg-white px-3 py-2 rounded-lg border border-slate-200 shadow-sm transition">
            <i class="fa-solid fa-arrow-left"></i>
            <span>Kembali</span>
        </a>
    </div>

    <!-- Grid Container Utama -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 max-w-5xl">

        <!-- Sisi Kiri: Rincian Barang (Selalu Tampil) -->
        <div class="lg:col-span-2 bg-white rounded-xl border border-slate-200 shadow-sm p-6 space-y-4">
            <div>
                <p class="text-xs text-slate-400 font-medium">Nama Item / Barang</p>
                <p class="font-bold text-slate-800 text-lg mt-0.5"><?= $pengadaan['nama_item']; ?></p>
            </div>

            <div class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <p class="text-xs text-slate-400 font-medium">Jumlah Kebutuhan</p>
                    <p class="font-bold text-teal-600 text-base mt-1"><?= $pengadaan['jumlah'] . ' ' . $pengadaan['satuan']; ?></p>
                </div>
                <div>
                    <p class="text-xs text-slate-400 font-medium">Estimasi Harga Total</p>
                    <p class="font-bold text-slate-800 text-base mt-1">Rp <?= number_format($pengadaan['estimasi_harga'], 0, ',', '.'); ?></p>
                </div>
                <div>
                    <p class="text-xs text-slate-400 font-medium">Pengaju</p>
                    <!-- Tampilkan nama pengaju atau pesan jika tidak ditemukan -->
                    <p class="font-medium text-slate-700 mt-1"><?= html_escape($pengadaan['nama_pengaju'] ?? 'Pengaju tidak ditemukan'); ?></p> 
                </div>
                <div>
                    <p class="text-xs text-slate-400 font-medium">Tanggal Dibuat</p>
                    <p class="font-medium text-slate-700 mt-1"><?= date('d F Y', strtotime($pengadaan['created_at'])); ?></p>
                </div>
            </div>

            <div>
                <p class="text-xs text-slate-400 font-medium">Alasan Pengadaan</p>
                <p class="text-slate-700 mt-1 bg-slate-50 p-3 rounded-lg border border-slate-100"><?= !empty($pengadaan['alasan_pengadaan']) ? nl2br($pengadaan['alasan_pengadaan']) : '-'; ?></p>
            </div>
        </div>

        <!-- Sisi Kanan: Panel Form Approval / Pesan Terbatas -->
        <?php if (in_array($this->session->userdata('role'), ['admin', 'laboran', 'kepsek'])) : ?>
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6 h-fit">
                <h3 class="font-bold text-slate-800 mb-4 border-b border-slate-100 pb-3">Update Status Approval</h3>
                <form action="<?= site_url('pengadaan/update_status'); ?>" method="POST" class="space-y-4">
                    <input type="hidden" name="id_pengadaan" value="<?= $pengadaan['id_pengadaan']; ?>">

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Status Persetujuan</label>
                        <select name="status_approval" class="w-full px-3.5 py-2 text-sm rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-teal-500">
                            <option value="Pending" <?= ($pengadaan['status_approval'] == 'Pending') ? 'selected' : ''; ?>>Pending</option>
                            <option value="Disetujui" <?= ($pengadaan['status_approval'] == 'Disetujui') ? 'selected' : ''; ?>>Disetujui</option>
                            <option value="Ditolak" <?= ($pengadaan['status_approval'] == 'Ditolak') ? 'selected' : ''; ?>>Ditolak</option>
                        </select>
                    </div>

                    <button type="submit" class="w-full py-2 bg-teal-600 hover:bg-teal-700 text-white font-medium text-sm rounded-lg shadow-sm transition">
                        Simpan Perubahan
                    </button>
                </form>
            </div>
        <?php else : ?>
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6 h-fit text-center">
                <div class="text-slate-400 mb-2">
                    <i class="fa-solid fa-lock text-3xl"></i>
                </div>
                <h4 class="font-bold text-slate-700 text-sm">Akses Terbatas</h4>
                <p class="text-xs text-slate-500 mt-1">Persetujuan pengadaan hanya dapat dilakukan oleh Kepala Lab atau Admin.</p>
            </div>
        <?php endif; ?>

    </div>

</main>
