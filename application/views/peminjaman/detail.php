<!-- Top Header Navbar -->
<header class="bg-white border-b border-slate-200 h-16 flex items-center justify-between px-6 z-10">
    <div class="flex items-center gap-4">
        <h1 class="text-lg font-semibold text-slate-700">Peminjaman Alat</h1>
    </div>


</header>

<main class="flex-1 overflow-y-auto p-6 bg-slate-50 w-full">

    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-slate-800">Detail Transaksi Peminjaman</h2>
            <p class="text-xs text-slate-500">Rincian informasi peminjaman alat praktikum laboratorium.</p>
        </div>
        <a href="<?= base_url('peminjaman'); ?>" class="text-slate-600 hover:text-slate-800 text-sm flex items-center gap-2 bg-white px-3 py-2 rounded-lg border border-slate-200 shadow-sm transition">
            <i class="fa-solid fa-arrow-left"></i>
            <span>Kembali</span>
        </a>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden max-w-3xl">
        <div class="p-6 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
            <div>
                <span class="text-xs text-slate-400 font-semibold uppercase">Kode Peminjaman</span>
                <h3 class="text-lg font-mono font-bold text-slate-800"><?= $peminjaman['kode_peminjaman']; ?></h3>
            </div>
            <div>
                <?php if ($peminjaman['status'] == 'Dipinjam') : ?>
                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-amber-100 text-amber-800">Dipinjam</span>
                <?php elseif ($peminjaman['status'] == 'Dikembalikan') : ?>
                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-emerald-100 text-emerald-800">Dikembalikan</span>
                <?php else : ?>
                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-rose-100 text-rose-800"><?= $peminjaman['status']; ?></span>
                <?php endif; ?>
            </div>
        </div>

        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6 text-sm">
            <div>
                <p class="text-xs text-slate-400 font-medium">Informasi Barang</p>
                <p class="font-semibold text-slate-800 text-base mt-1"><?= $peminjaman['nama_barang'] ?? '-'; ?></p>
                <p class="text-xs text-slate-500 mt-0.5">Kode Barcode: <span class="font-mono text-slate-700"><?= $peminjaman['kode_barcode'] ?? '-'; ?></span></p>
                <p class="text-xs text-slate-500 mt-0.5">Kategori: <?= $peminjaman['kategori'] ?? '-'; ?></p>
                <p class="text-xs text-slate-500 mt-0.5">Lokasi Simpan: <?= $peminjaman['lokasi_penyimpanan'] ?? '-'; ?></p>
            </div>

            <div>
                <p class="text-xs text-slate-400 font-medium">Jumlah Dipinjam</p>
                <p class="font-bold text-teal-600 text-xl mt-1"><?= $peminjaman['jumlah_pinjam'] . ' ' . ($peminjaman['satuan'] ?? 'Unit'); ?></p>
            </div>

            <hr class="md:col-span-2 border-slate-100">

            <div>
                <p class="text-xs text-slate-400 font-medium">Tanggal Pinjam</p>
                <p class="font-medium text-slate-700 mt-1"><?= date('d F Y', strtotime($peminjaman['tgl_pinjam'])); ?></p>
            </div>

            <div>
                <p class="text-xs text-slate-400 font-medium">Rencana Tanggal Kembali</p>
                <p class="font-medium text-slate-700 mt-1"><?= date('d F Y', strtotime($peminjaman['tgl_kembali_rencana'])); ?></p>
            </div>

            <div>
                <p class="text-xs text-slate-400 font-medium">Realisasi Pengembalian</p>
                <p class="font-medium text-slate-700 mt-1">
                    <?= !empty($peminjaman['tgl_kembali_realisasi']) ? date('d F Y H:i', strtotime($peminjaman['tgl_kembali_realisasi'])) : '-'; ?>
                </p>
            </div>

            <div>
                <p class="text-xs text-slate-400 font-medium">Catatan / Keperluan</p>
                <p class="text-slate-700 mt-1"><?= !empty($peminjaman['catatan']) ? $peminjaman['catatan'] : '-'; ?></p>
            </div>
        </div>

        <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex justify-end gap-3">
            <a href="<?= base_url('peminjaman/edit/' . $peminjaman['id_peminjaman']); ?>" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium transition flex items-center gap-2">
                <i class="fa-solid fa-pen-to-square"></i>
                <span>Edit Data</span>
            </a>


        </div>
    </div>

</main>