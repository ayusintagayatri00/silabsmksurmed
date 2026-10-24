<div class="p-6">
    <!-- Header Halaman -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div>
            <h2 class="text-xl font-bold text-slate-800">
                <?= in_array(strtolower($this->session->userdata('role')), ['guru', 'siswa']) ? 'Riwayat Peminjaman Alat Saya' : 'Daftar Transaksi Peminjaman Alat Medis'; ?>
            </h2>
            <p class="text-xs text-slate-500">
                <?= in_array(strtolower($this->session->userdata('role')), ['guru', 'siswa']) ? 'Pantau pengajuan dan status barang praktikum yang Anda pinjam.' : 'Pantau dan kelola pengajuan serta status peminjaman instrumen praktikum.'; ?>
            </p>
        </div>

        <div class="flex items-center gap-2.5">
            <a href="<?= site_url('peminjaman/tambah'); ?>" class="bg-teal-600 hover:bg-teal-700 text-white text-sm font-semibold px-4 py-2.5 rounded-xl transition flex items-center gap-2 shadow-sm">
                <i class="fa-solid fa-plus"></i> Pinjam Alat Baru
            </a>

            <?php if (!in_array(strtolower($this->session->userdata('role')), ['guru', 'siswa'])): ?>
                <a href="<?= site_url('peminjaman/export_excel'); ?>" class="bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold px-4 py-2.5 rounded-xl transition flex items-center gap-2 shadow-sm">
                    <i class="fa-solid fa-file-excel"></i> Export Excel
                </a>
            <?php endif; ?>
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

    <!-- Card Tabel & Search Bar -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">

        <!-- Input Pencarian (Search Form) -->
        <div class="p-4 border-b border-slate-100 flex flex-col sm:flex-row justify-between items-center gap-4 bg-slate-50/50">
            <form action="<?= site_url('peminjaman'); ?>" method="GET" class="w-full sm:w-80 flex items-center gap-2">
                <?php if (!empty($per_page)): ?>
                    <input type="hidden" name="limit" value="<?= $per_page; ?>">
                <?php endif; ?>

                <div class="relative w-full">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-slate-400">
                        <i class="fa-solid fa-magnifying-glass text-xs"></i>
                    </span>
                    <input type="text" name="q" value="<?= html_escape($keyword ?? ''); ?>"
                        placeholder="Cari kode, peminjam, nama alat..."
                        class="w-full pl-9 pr-8 py-2 text-xs bg-white border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent transition text-slate-700 placeholder-slate-400">

                    <?php if (!empty($keyword)): ?>
                        <a href="<?= site_url('peminjaman'); ?>" class="absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400 hover:text-slate-600" title="Reset Pencarian">
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

        <!-- Tabel Data -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse table-auto">
                <thead>
                    <tr class="border-b border-slate-100 text-xs font-bold text-slate-400 uppercase tracking-wider bg-slate-50/70">
                        <th class="py-3.5 px-4 text-center w-12">NO</th>
                        <th class="py-3.5 px-4 w-44">KODE PINJAM</th>
                        <th class="py-3.5 px-4 w-48">PEMINJAM</th>
                        <th class="py-3.5 px-4">NAMA BARANG</th>
                        <th class="py-3.5 px-4 text-center w-36">TANGGAL PINJAM</th>
                        <th class="py-3.5 px-4 text-center w-36">RENCANA KEMBALI</th>
                        <th class="py-3.5 px-4 text-center w-32">STATUS</th>
                        <th class="py-3.5 px-4 text-center w-28">AKSI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm text-slate-700">
                    <?php if (!empty($peminjaman)): ?>
                        <?php
                        $no = $offset + 1;
                        foreach ($peminjaman as $p):
                            $id_pinjam = is_object($p) ? $p->id_peminjaman : $p['id_peminjaman'];
                            $status    = is_object($p) ? $p->status : $p['status'];

                            $badge_class = 'bg-slate-100 text-slate-600 border-slate-200';
                            if ($status == 'Pending') {
                                $badge_class = 'bg-amber-50 text-amber-600 border-amber-200';
                            } elseif ($status == 'Dipinjam') {
                                $badge_class = 'bg-sky-50 text-sky-600 border-sky-200';
                            } elseif ($status == 'Dikembalikan') {
                                $badge_class = 'bg-emerald-50 text-emerald-600 border-emerald-200';
                            } elseif ($status == 'Ditolak') {
                                $badge_class = 'bg-rose-50 text-rose-600 border-rose-200';
                            }
                        ?>
                            <tr class="hover:bg-slate-50/60 transition">
                                <td class="py-3.5 px-4 text-center font-medium text-slate-400">
                                    <?= $no++; ?>
                                </td>
                                <td class="py-3.5 px-4 font-semibold text-slate-800 whitespace-nowrap">
                                    <?= is_object($p) ? $p->kode_peminjaman : $p['kode_peminjaman']; ?>
                                </td>
                                <td class="py-3.5 px-4 font-medium text-slate-800 whitespace-nowrap">
                                    <?= is_object($p) ? ($p->peminjam ?? '-') : ($p['peminjam'] ?? '-'); ?>
                                </td>
                                <td class="py-3.5 px-4 text-slate-700 font-medium">
                                    <?= is_object($p) ? $p->nama_barang : $p['nama_barang']; ?>
                                </td>
                                <td class="py-3.5 px-4 text-center text-slate-600 whitespace-nowrap">
                                    <?= date('d/m/Y', strtotime(is_object($p) ? $p->tgl_pinjam : $p['tgl_pinjam'])); ?>
                                </td>
                                <td class="py-3.5 px-4 text-center text-slate-600 whitespace-nowrap">
                                    <?= date('d/m/Y', strtotime(is_object($p) ? $p->tgl_kembali_rencana : $p['tgl_kembali_rencana'])); ?>
                                </td>
                                <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                    <span class="inline-block px-3 py-1 text-xs rounded-full font-semibold border <?= $badge_class; ?>">
                                        <?= $status; ?>
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                    <div class="flex items-center justify-center gap-1.5">
                                        <a href="<?= site_url('peminjaman/detail/' . $id_pinjam); ?>" class="text-teal-600 hover:text-teal-700 p-1.5 transition rounded-lg hover:bg-slate-100" title="Detail">
                                            <i class="fa-solid fa-eye text-base"></i>
                                        </a>

                                        <?php if (in_array(strtolower($this->session->userdata('role')), ['admin', 'laboran'])): ?>
                                            <?php if ($status == 'Pending'): ?>
                                                <a href="<?= site_url('peminjaman/update_status/' . $id_pinjam . '/Dipinjam'); ?>" onclick="return confirm('Setujui peminjaman ini?')" class="bg-emerald-600 hover:bg-emerald-700 text-white text-xs px-2 py-1 rounded-lg transition font-medium">
                                                    Setujui
                                                </a>
                                                <a href="<?= site_url('peminjaman/update_status/' . $id_pinjam . '/Ditolak'); ?>" onclick="return confirm('Tolak pengajuan ini?')" class="bg-rose-500 hover:bg-rose-600 text-white text-xs px-2 py-1 rounded-lg transition font-medium">
                                                    Tolak
                                                </a>
                                            <?php elseif ($status == 'Dipinjam'): ?>
                                                <a href="<?= site_url('peminjaman/update_status/' . $id_pinjam . '/Dikembalikan'); ?>" onclick="return confirm('Konfirmasi pengembalian alat?')" class="bg-teal-600 hover:bg-teal-700 text-white text-xs px-2 py-1 rounded-lg transition font-medium">
                                                    Kembali
                                                </a>
                                            <?php endif; ?>

                                            <a href="<?= site_url('peminjaman/delete/' . $id_pinjam); ?>" onclick="return confirm('Yakin ingin menghapus data peminjaman ini?')" class="text-rose-500 hover:text-rose-600 p-1.5 transition rounded-lg hover:bg-rose-50" title="Hapus Riwayat">
                                                <i class="fa-solid fa-trash-can text-base"></i>
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="py-8 text-center text-slate-400">
                                <?= !empty($keyword) ? 'Tidak ditemukan transaksi yang cocok dengan kata kunci "' . html_escape($keyword) . '"' : (in_array(strtolower($this->session->userdata('role')), ['guru', 'siswa']) ? 'Anda belum memiliki riwayat peminjaman.' : 'Belum ada transaksi peminjaman.'); ?>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Footer Tabel (Pagination & Limit) -->
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
                    Menampilkan <strong class="text-slate-700"><?= ($total_rows > 0) ? ($offset + 1) : 0; ?></strong> - <strong class="text-slate-700"><?= min($offset + $per_page, $total_rows); ?></strong> dari <strong class="text-slate-700"><?= $total_rows; ?></strong> transaksi
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