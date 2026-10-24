<header class="bg-white border-b border-slate-200 h-16 flex items-center justify-between px-6 z-10">
    <div class="flex items-center gap-4">
        <h1 class="text-lg font-semibold text-slate-700">Pengadaan Alat & BHP</h1>
    </div>
</header>

<main class="flex-1 overflow-y-auto p-6 bg-slate-50 w-full">

    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-slate-800">Form Pengajuan Pengadaan</h2>
            <p class="text-xs text-slate-500">Isi formulir untuk usulan pengadaan instrumen atau bahan habis pakai baru.</p>
        </div>
        <a href="<?= base_url('pengadaan'); ?>" class="text-slate-600 hover:text-slate-800 text-sm flex items-center gap-2 bg-white px-3 py-2 rounded-lg border border-slate-200 shadow-sm transition">
            <i class="fa-solid fa-arrow-left"></i>
            <span>Kembali</span>
        </a>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6 max-w-3xl">
        <form action="<?= base_url('pengadaan/simpan'); ?>" method="POST" class="space-y-5">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <!-- Nama Item -->
                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Nama Item / Barang <span class="text-rose-500">*</span></label>
                    <input type="text" name="nama_item" placeholder="Contoh: Stetoskop Littmann / Spuit 3cc" required class="w-full px-3.5 py-2 text-sm rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>

                <!-- Jumlah -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Jumlah <span class="text-rose-500">*</span></label>
                    <input type="number" name="jumlah" min="1" value="1" required class="w-full px-3.5 py-2 text-sm rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>

                <!-- Satuan -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Satuan <span class="text-rose-500">*</span></label>
                    <input type="text" name="satuan" placeholder="Pcs / Box / Unit" value="Pcs" required class="w-full px-3.5 py-2 text-sm rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>

                <!-- Estimasi Harga Total -->
                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Estimasi Total Harga (Rp)</label>
                    <input type="number" name="estimasi_harga" placeholder="0" class="w-full px-3.5 py-2 text-sm rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>

                <!-- Alasan Pengadaan -->
                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Alasan / Urgensi Pengadaan</label>
                    <textarea name="alasan_pengadaan" rows="3" placeholder="Jelaskan alasa pengadaan barang ini..." class="w-full px-3.5 py-2 text-sm rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-teal-500"></textarea>
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
                <a href="<?= base_url('pengadaan'); ?>" class="px-4 py-2 text-sm rounded-lg border border-slate-300 text-slate-700 font-medium hover:bg-slate-50 transition">Batal</a>
                <button type="submit" class="px-5 py-2 text-sm rounded-lg bg-teal-600 hover:bg-teal-700 text-white font-medium shadow-sm transition flex items-center gap-2">
                    <i class="fa-solid fa-paper-plane"></i>
                    <span>Kirim Pengajuan</span>
                </button>
            </div>

        </form>
    </div>

</main>