<main class="flex-1 overflow-y-auto p-6 bg-slate-50 w-full">

    <!-- Flash Message -->
    <?php if ($this->session->flashdata('success')) : ?>
        <div class="mb-4 p-4 text-sm text-emerald-800 bg-emerald-100 rounded-lg border border-emerald-200 flex items-center gap-2" role="alert">
            <i class="fa-solid fa-circle-check"></i>
            <span><?= $this->session->flashdata('success'); ?></span>
        </div>
    <?php endif; ?>

    <?php if ($this->session->flashdata('error')) : ?>
        <div class="mb-4 p-4 text-sm text-rose-800 bg-rose-100 rounded-lg border border-rose-200 flex items-center gap-2" role="alert">
            <i class="fa-solid fa-circle-exclamation"></i>
            <span><?= $this->session->flashdata('error'); ?></span>
        </div>
    <?php endif; ?>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <!-- Card 1: Menunggu Approval -->
        <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs text-slate-500 font-medium uppercase tracking-wider">Perlu Persetujuan</p>
                <h3 class="text-2xl font-bold text-amber-600 mt-1"><?= $total_pending; ?></h3>
                <p class="text-xs text-slate-400 mt-1">Pengajuan pending</p>
            </div>
            <div class="w-12 h-12 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center text-xl">
                <i class="fa-solid fa-clock-rotate-left"></i>
            </div>
        </div>

        <!-- Card 2: Total Disetujui -->
        <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs text-slate-500 font-medium uppercase tracking-wider">Disetujui</p>
                <h3 class="text-2xl font-bold text-emerald-600 mt-1"><?= $total_disetujui; ?></h3>
                <p class="text-xs text-slate-400 mt-1">Item disetujui</p>
            </div>
            <div class="w-12 h-12 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl">
                <i class="fa-solid fa-circle-check"></i>
            </div>
        </div>

        <!-- Card 3: Total Ditolak -->
        <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs text-slate-500 font-medium uppercase tracking-wider">Ditolak</p>
                <h3 class="text-2xl font-bold text-rose-600 mt-1"><?= $total_ditolak; ?></h3>
                <p class="text-xs text-slate-400 mt-1">Item ditolak</p>
            </div>
            <div class="w-12 h-12 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center text-xl">
                <i class="fa-solid fa-circle-xmark"></i>
            </div>
        </div>

        <!-- Card 4: Estimasi Anggaran Disetujui -->
        <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs text-slate-500 font-medium uppercase tracking-wider">Total Anggaran Disetujui</p>
                <h3 class="text-xl font-bold text-slate-800 mt-1">Rp <?= number_format($total_biaya_disetujui, 0, ',', '.'); ?></h3>
                <p class="text-xs text-slate-400 mt-1">Estimasi biaya</p>
            </div>
            <div class="w-12 h-12 rounded-lg bg-teal-50 text-teal-600 flex items-center justify-center text-xl">
                <i class="fa-solid fa-wallet"></i>
            </div>
        </div>
    </div>

    <!-- Table Container -->
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="font-bold text-slate-800">Daftar Pengajuan Pengadaan Alat & BHP</h3>
                <p class="text-xs text-slate-500">Tinjau dan verifikasi usulan pengadaan dari staf atau laboratorium.</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-xs font-semibold text-slate-500 uppercase tracking-wider">
                        <th class="px-4 py-3.5">ID</th>
                        <th class="px-4 py-3.5">Nama Barang / Item</th>
                        <th class="px-4 py-3.5">Jumlah</th>
                        <th class="px-4 py-3.5">Estimasi Harga</th>
                        <th class="px-4 py-3.5">Status</th>
                        <th class="px-4 py-3.5 text-center">Keputusan Cepat</th>
                        <th class="px-4 py-3.5 text-center">Rincian</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    <?php if (!empty($pengadaan)) : ?>
                        <?php foreach ($pengadaan as $p) : ?>
                            <tr class="hover:bg-slate-50 transition">
                                <td class="px-4 py-3.5 font-mono font-semibold text-slate-800">
                                    #<?= str_pad($p['id_pengadaan'], 4, '0', STR_PAD_LEFT); ?>
                                </td>
                                <td class="px-4 py-3.5 font-medium text-slate-800">
                                    <?= $p['nama_item']; ?>
                                </td>
                                <td class="px-4 py-3.5 text-slate-700 font-medium">
                                    <?= $p['jumlah'] . ' ' . $p['satuan']; ?>
                                </td>
                                <td class="px-4 py-3.5 text-slate-700 font-medium">
                                    Rp <?= number_format($p['estimasi_harga'], 0, ',', '.'); ?>
                                </td>
                                <td class="px-4 py-3.5">
                                    <?php if ($p['status_approval'] == 'Pending') : ?>
                                        <span class="px-2.5 py-0.5 text-xs font-medium rounded-full bg-amber-100 text-amber-800">Pending</span>
                                    <?php elseif ($p['status_approval'] == 'Disetujui') : ?>
                                        <span class="px-2.5 py-0.5 text-xs font-medium rounded-full bg-emerald-100 text-emerald-800">Disetujui</span>
                                    <?php else : ?>
                                        <span class="px-2.5 py-0.5 text-xs font-medium rounded-full bg-rose-100 text-rose-800">Ditolak</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-4 py-3.5 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <!-- Tombol Approve Cepat -->
                                        <a href="<?= site_url('kepala_lab/persetujuan_cepat/' . $p['id_pengadaan'] . '/Disetujui'); ?>"
                                            onclick="return confirm('Setujui pengajuan barang ini?');"
                                            class="px-2.5 py-1 text-xs font-medium bg-emerald-600 hover:bg-emerald-700 text-white rounded transition shadow-sm flex items-center gap-1" title="Setujui">
                                            <i class="fa-solid fa-check"></i> Setujui
                                        </a>

                                        <!-- Tombol Reject Cepat -->
                                        <a href="<?= site_url('kepala_lab/persetujuan_cepat/' . $p['id_pengadaan'] . '/Ditolak'); ?>"
                                            onclick="return confirm('Tolak pengajuan barang ini?');"
                                            class="px-2.5 py-1 text-xs font-medium bg-rose-600 hover:bg-rose-700 text-white rounded transition shadow-sm flex items-center gap-1" title="Tolak">
                                            <i class="fa-solid fa-xmark"></i> Tolak
                                        </a>
                                    </div>
                                </td>
                                <td class="px-4 py-3.5 text-center">
                                    <a href="<?= site_url('pengadaan/detail/' . $p['id_pengadaan']); ?>" class="text-teal-600 hover:text-teal-800 font-medium text-xs underline">
                                        Lihat Detail
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="7" class="text-center py-12 text-sm text-slate-400">
                                Belum ada daftar usulan pengadaan.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</main>