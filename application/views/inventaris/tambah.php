<div class="p-6">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-xl font-bold text-slate-800"><?= $title; ?></h2>
            <p class="text-xs text-slate-500">Isi formulir berikut untuk menambahkan item data <?= strtolower($jenis); ?> laboratorium baru.</p>
        </div>
        <a href="<?= site_url('inventaris/' . strtolower($jenis)); ?>" class="bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-semibold px-4 py-2.5 rounded-xl transition flex items-center gap-2">
            <i class="fa-solid fa-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 max-w-3xl">

        <?php if (validation_errors()): ?>
            <div class="mb-6 p-4 text-xs text-rose-700 bg-rose-50 rounded-xl border border-rose-200">
                <div class="font-bold mb-1 flex items-center gap-1.5">
                    <i class="fa-solid fa-circle-exclamation"></i> Terjadi kesalahan pengisian form:
                </div>
                <div class="list-disc pl-5">
                    <?= validation_errors(); ?>
                </div>
            </div>
        <?php endif; ?>

        <form action="<?= site_url('inventaris/simpan'); ?>" method="POST" class="space-y-5">
            <!-- penambahan CSRF Token -->
            <input
                type="hidden"
                name="<?= html_escape($this->security->get_csrf_token_name()); ?>"
                value="<?= html_escape($this->security->get_csrf_hash()); ?>">
            <input type="hidden" name="jenis" value="<?= $jenis; ?>">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-2">
                        KODE BARCODE / ID <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="kode_barcode" value="<?= set_value('kode_barcode', $auto_barcode); ?>"
                        required class="w-full px-3.5 py-2.5 text-xs bg-slate-50/50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500 font-semibold text-slate-800">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-2">
                        NAMA <?= strtoupper($jenis); ?> <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="nama_barang" value="<?= set_value('nama_barang'); ?>"
                        placeholder="Contoh: <?= ($jenis === 'Bahan') ? 'Spuit 3cc / Tissue Tessa' : 'Stetoskop / Pantum Bayi'; ?>" required
                        class="w-full px-3.5 py-2.5 text-xs bg-white border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500 transition text-slate-800 placeholder-slate-400">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-2">
                        KATEGORI <span class="text-rose-500">*</span>
                    </label>
                    <select name="kategori" required class="w-full px-3.5 py-2.5 text-xs bg-white border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500 transition text-slate-700 cursor-pointer">
                        <option value="">-- Pilih Kategori --</option>
                        <?php if ($jenis === 'Bahan'): ?>
                            <option value="Bahan Habis Pakai Medis" <?= set_select('kategori', 'Bahan Habis Pakai Medis'); ?>>Bahan Habis Pakai Medis</option>
                            <option value="Cairan / Desinfektan" <?= set_select('kategori', 'Cairan / Desinfektan'); ?>>Cairan / Desinfektan</option>
                            <option value="Obat & Praktikum" <?= set_select('kategori', 'Obat & Praktikum'); ?>>Obat & Praktikum</option>
                            <option value="Perlengkapan Lab" <?= set_select('kategori', 'Perlengkapan Lab'); ?>>Perlengkapan Lab</option>
                        <?php else: ?>
                            <option value="Alat Medis / Instrumen" <?= set_select('kategori', 'Alat Medis / Instrumen'); ?>>Alat Medis / Instrumen</option>
                            <option value="Manekin & Simulator" <?= set_select('kategori', 'Manekin & Simulator'); ?>>Manekin & Simulator</option>
                            <option value="Perlengkapan Lab" <?= set_select('kategori', 'Perlengkapan Lab'); ?>>Perlengkapan Lab</option>
                        <?php endif; ?>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-2">
                        KONDISI <span class="text-rose-500">*</span>
                    </label>
                    <select name="kondisi" required class="w-full px-3.5 py-2.5 text-xs bg-white border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500 transition text-slate-700 cursor-pointer">
                        <option value="Baik" <?= set_select('kondisi', 'Baik', TRUE); ?>>Baik</option>
                        <option value="Rusak Ringan" <?= set_select('kondisi', 'Rusak Ringan'); ?>>Rusak Ringan</option>
                        <option value="Rusak Berat" <?= set_select('kondisi', 'Rusak Berat'); ?>>Rusak Berat</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-2">
                        JUMLAH STOK <span class="text-rose-500">*</span>
                    </label>
                    <!-- Input type number with minimum value of 0 and step of 1 for stock quantity -->
                    <input type="number" min="0" step="1" name="stok" value="<?= set_value('stok', 1); ?>" required
                        class="w-full px-3.5 py-2.5 text-xs bg-white border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500 transition text-slate-800">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-2">
                        SATUAN <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="satuan" value="<?= set_value('satuan', ($jenis === 'Bahan' ? 'Box' : 'Unit')); ?>"
                        placeholder="Contoh: Unit, Buah, Box, Botol, Pcs" required
                        class="w-full px-3.5 py-2.5 text-xs bg-white border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500 transition text-slate-800">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-2">
                    LOKASI PENYIMPANAN / RAK <span class="text-rose-500">*</span>
                </label>
                <input type="text" name="lokasi_penyimpanan" value="<?= set_value('lokasi_penyimpanan'); ?>"
                    placeholder="Contoh: Ruang Penyimpanan Keperawatan - Lemari 2" required
                    class="w-full px-3.5 py-2.5 text-xs bg-white border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500 transition text-slate-800">
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                <a href="<?= site_url('inventaris/' . strtolower($jenis)); ?>" class="px-5 py-2.5 text-xs font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl transition">
                    Batal
                </a>
                <button type="submit" class="px-5 py-2.5 text-xs font-semibold text-white bg-teal-600 hover:bg-teal-700 rounded-xl transition shadow-sm flex items-center gap-2">
                    <i class="fa-solid fa-floppy-disk"></i> Simpan Data <?= $jenis; ?>
                </button>
            </div>
        </form>
    </div>
</div>