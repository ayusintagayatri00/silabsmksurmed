<div class="p-6">
    <!-- Header Halaman -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div>
            <h2 class="text-xl font-bold text-slate-800">Pengajuan Pengadaan Alat & BHP</h2>
            <p class="text-xs text-slate-500">Kelola dan pantau pengusulan instrumen medis baru dan bahan habis pakai.</p>
        </div>

        <div class="flex items-center gap-2.5">
            <?php
            $role_user = strtolower($this->session->userdata('role') ?? '');
            if (!in_array($role_user, ['kepsek', 'siswa'])):
            ?>
                <a href="<?= site_url('pengadaan/tambah'); ?>" class="bg-teal-600 hover:bg-teal-700 text-white text-sm font-semibold px-4 py-2.5 rounded-xl transition flex items-center gap-2 shadow-sm">
                    <i class="fa-solid fa-plus"></i> Ajukan Pengadaan
                </a>
            <?php endif; ?>

            <a href="<?= site_url('pengadaan/export_excel'); ?>" class="bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold px-4 py-2.5 rounded-xl transition flex items-center gap-2 shadow-sm">
                <i class="fa-solid fa-file-excel"></i> Export Excel
            </a>
        </div>
    </div>

    <!-- Flashdata Notifikasi -->
    <?php if ($this->session->flashdata('success')): ?>
        <div class="mb-4 p-4 text-sm text-emerald-700 bg-emerald-50 rounded-xl border border-emerald-200 flex items-center gap-2">
            <i class="fa-solid fa-circle-check"></i>
            <span><?= $this->session->flashdata('success'); ?></span>
        </div>
    <?php elseif ($this->session->flashdata('error')): ?>
        <div class="mb-4 p-4 text-sm text-rose-700 bg-rose-50 rounded-xl border border-rose-200 flex items-center gap-2">
            <i class="fa-solid fa-triangle-exclamation"></i>
            <span><?= $this->session->flashdata('error'); ?></span>
        </div>
    <?php endif; ?>

    <!-- Card Tabel & Form Search -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">

        <!-- Input Pencarian (Search Form) -->
        <div class="p-4 border-b border-slate-100 flex flex-col sm:flex-row justify-between items-center gap-4 bg-slate-50/50">
            <form action="<?= site_url('pengadaan'); ?>" method="GET" class="w-full sm:w-80 flex items-center gap-2">
                <?php if (!empty($per_page)): ?>
                    <input type="hidden" name="limit" value="<?= $per_page; ?>">
                <?php endif; ?>

                <div class="relative w-full">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-slate-400">
                        <i class="fa-solid fa-magnifying-glass text-xs"></i>
                    </span>
                    <input type="text" name="q" value="<?= html_escape($keyword ?? ''); ?>"
                        placeholder="Cari nama barang, pengaju..."
                        class="w-full pl-9 pr-8 py-2 text-xs bg-white border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent transition text-slate-700 placeholder-slate-400">

                    <?php if (!empty($keyword)): ?>
                        <a href="<?= site_url('pengadaan'); ?>" class="absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400 hover:text-slate-600" title="Reset Pencarian">
                            <i class="fa-solid fa-xmark text-xs"></i>
                        </a>
                    <?php endif; ?>
                </div>

                <button type="submit" class="bg-teal-600 hover:bg-teal-700 text-white text-xs font-semibold px-3 py-2 rounded-xl transition shadow-sm">
                    Cari
                </button>
            </form>

            <?php if (!empty($keyword)): ?>
                <div class="text-xs text-slate-500">
                    Hasil pencarian untuk: <strong class="text-slate-800">"<?= html_escape($keyword); ?>"</strong>
                </div>
            <?php endif; ?>
        </div>

        <!-- Tabel Data Pengadaan -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse table-auto">
                <thead>
                    <tr class="border-b border-slate-100 text-xs font-bold text-slate-400 uppercase tracking-wider bg-slate-50/70">
                        <th class="py-3.5 px-6 w-36">ID PENGADAAN</th>
                        <th class="py-3.5 px-4">NAMA ITEM / BARANG</th>
                        <th class="py-3.5 px-4 text-center w-28">JUMLAH</th>
                        <th class="py-3.5 px-4 w-44">ESTIMASI HARGA TOTAL</th>
                        <th class="py-3.5 px-4 w-44">PENGAJU</th>
                        <th class="py-3.5 px-4 text-center w-32">STATUS</th>
                        <th class="py-3.5 px-4 text-center w-24">AKSI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm text-slate-700">
                    <?php if (!empty($pengadaan)): ?>
                        <?php
                        foreach ($pengadaan as $item):
                            $id_pengadaan  = is_object($item) ? $item->id_pengadaan : $item['id_pengadaan'];
                            $nama_item     = is_object($item) ? $item->nama_item : $item['nama_item'];
                            $jumlah        = is_object($item) ? $item->jumlah : $item['jumlah'];
                            $satuan        = is_object($item) ? $item->satuan : $item['satuan'];
                            $estimasi_harga = is_object($item) ? ($item->estimasi_harga ?? 0) : ($item['estimasi_harga'] ?? 0);
                            $pengaju       = is_object($item) ? ($item->nama_pengaju ?? '-') : ($item['nama_pengaju'] ?? '-');
                            $status        = is_object($item) ? $item->status_approval : $item['status_approval'];

                            // Format ID Pengadaan menjadi #0001, #0002, dst.
                            $formatted_id  = '#' . str_pad($id_pengadaan, 4, '0', STR_PAD_LEFT);

                            // Badge Class Status Approval
                            $badge_class = 'bg-slate-100 text-slate-600 border-slate-200';
                            if ($status == 'Pending') {
                                $badge_class = 'bg-amber-50 text-amber-600 border-amber-200';
                            } elseif ($status == 'Disetujui') {
                                $badge_class = 'bg-emerald-50 text-emerald-600 border-emerald-200';
                            } elseif ($status == 'Ditolak') {
                                $badge_class = 'bg-rose-50 text-rose-600 border-rose-200';
                            }
                        ?>
                            <tr class="hover:bg-slate-50/60 transition">
                                <td class="py-3.5 px-6 font-bold text-slate-900 whitespace-nowrap">
                                    <?= $formatted_id; ?>
                                </td>
                                <td class="py-3.5 px-4 font-semibold text-slate-800">
                                    <?= $nama_item; ?>
                                </td>
                                <td class="py-3.5 px-4 text-center font-medium text-slate-700 whitespace-nowrap">
                                    <?= $jumlah . ' ' . $satuan; ?>
                                </td>
                                <td class="py-3.5 px-4 text-slate-700 font-medium whitespace-nowrap">
                                    Rp <?= number_format($estimasi_harga, 0, ',', '.'); ?>
                                </td>
                                <td class="py-3.5 px-4 text-slate-600 whitespace-nowrap">
                                    <?= $pengaju; ?>
                                </td>
                                <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                    <span class="inline-block px-3 py-1 text-xs rounded-full font-semibold border <?= $badge_class; ?>">
                                        <?= $status; ?>
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                    <div class="flex items-center justify-center gap-1.5">
                                        <a href="<?= site_url('pengadaan/detail/' . $id_pengadaan); ?>" class="text-teal-600 hover:text-teal-700 p-1.5 transition rounded-lg hover:bg-slate-100" title="Detail">
                                            <i class="fa-solid fa-eye text-base"></i>
                                        </a>

                                        <?php if (in_array(strtolower($this->session->userdata('role')), ['admin', 'laboran', 'kepsek'])): ?>
                                            <a href="<?= site_url('pengadaan/edit/' . $id_pengadaan); ?>" class="text-amber-500 hover:text-amber-600 p-1.5 transition rounded-lg hover:bg-amber-50" title="Edit / Approval">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </a>
                                           <form
                                            action="<?= site_url('pengadaan/hapus/' . (int) $id_pengadaan); ?>"
                                            method="post"
                                            class="inline"
                                            onsubmit="return confirm('Yakin ingin menghapus pengadaan ini?');">
                                            <input
                                                type="hidden"
                                                name="<?= html_escape($this->security->get_csrf_token_name()); ?>"
                                                value="<?= html_escape($this->security->get_csrf_hash()); ?>">
                                            <button
                                                type="submit"
                                                title="Hapus"
                                                class="text-rose-500 hover:text-rose-600 p-1.5 rounded-lg">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </button>
                                        </form>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="py-8 text-center text-slate-400">
                                <?= !empty($keyword) ? 'Tidak ditemukan usulan pengadaan yang cocok dengan kata kunci "' . html_escape($keyword) . '"' : 'Belum ada data pengadaan alat dan BHP.'; ?>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Footer Tabel: Pilihan Limit Baris & Pagination -->
        <div class="flex flex-col sm:flex-row justify-between items-center gap-4 px-6 py-4 border-t border-slate-100 bg-white">
            <div class="flex items-center gap-3 text-xs text-slate-500">
                <span class="flex items-center gap-1.5">
                    Tampilkan
                    <select onchange="changeLimit(this.value)" class="py-1 px-2 text-xs border border-slate-200 rounded-lg bg-white focus:outline-none focus:ring-1 focus:ring-teal-500 font-medium text-slate-700 cursor-pointer">
                        <option value="5" <?= ($per_page == 5) ? 'selected' : ''; ?>>5</option>
                        <option value="10" <?= ($per_page == 10) ? 'selected' : ''; ?>>10</option>
                        <option value="25" <?= ($per_page == 25) ? 'selected' : ''; ?>>25</option>
                        <option value="50" <?= ($per_page == 50) ? 'selected' : ''; ?>>50</option>
                    </select>
                    data
                </span>
                <span class="text-slate-300">|</span>
                <span>
                    Menampilkan <strong class="text-slate-700"><?= ($total_rows > 0) ? ($offset + 1) : 0; ?></strong> - <strong class="text-slate-700"><?= min($offset + $per_page, $total_rows); ?></strong> dari <strong class="text-slate-700"><?= $total_rows; ?></strong> data
                </span>
            </div>

            <div>
                <?= $pagination; ?>
            </div>
        </div>
    </div>
</div>

<script>
    function changeLimit(limit) {
        const url = new URL(window.location.href);
        url.searchParams.set('limit', limit);
        url.searchParams.set('page', 0);
        window.location.href = url.toString();
    }
</script>