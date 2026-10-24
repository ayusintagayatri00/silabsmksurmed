<div class="p-6">
    <?php $role = strtolower($this->session->userdata('role') ?? ''); ?>

    <!-- Header & Action Buttons -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div>
            <h2 class="text-xl font-bold text-slate-800"><?= $title; ?></h2>
            <p class="text-xs text-slate-500">Daftar kelola stok ketersediaan <?= strtolower($title); ?>.</p>
        </div>

        <div class="flex items-center gap-2.5">
            <?php if (in_array($role, ['admin', 'laboran'])): ?>
                <a href="<?= site_url('inventaris/tambah/' . $jenis); ?>" class="bg-teal-600 hover:bg-teal-700 text-white text-sm font-semibold px-4 py-2.5 rounded-xl transition flex items-center gap-2 shadow-sm">
                    <i class="fa-solid fa-plus"></i> Tambah <?= $jenis; ?> Baru
                </a>
            <?php endif; ?>

            <?php if (in_array($role, ['admin', 'laboran', 'kepsek'])): ?>
                <a href="<?= site_url('inventaris/export_excel/' . $jenis); ?>" class="bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold px-4 py-2.5 rounded-xl transition flex items-center gap-2 shadow-sm">
                    <i class="fa-solid fa-file-excel"></i> Export Excel
                </a>
            <?php endif; ?>
        </div>
    </div>

    <!-- Flashdata Notification -->
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

        <div class="p-4 border-b border-slate-100 flex flex-col sm:flex-row justify-between items-center gap-4 bg-slate-50/50">
            <form action="<?= site_url('inventaris/' . strtolower($jenis)); ?>" method="GET" class="w-full sm:w-80 flex items-center gap-2">
                <?php if (!empty($per_page)): ?>
                    <input type="hidden" name="limit" value="<?= $per_page; ?>">
                <?php endif; ?>

                <div class="relative w-full">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-slate-400">
                        <i class="fa-solid fa-magnifying-glass text-xs"></i>
                    </span>
                    <input type="text" name="q" value="<?= html_escape($keyword ?? ''); ?>"
                        placeholder="Cari <?= strtolower($jenis); ?>..."
                        class="w-full pl-9 pr-8 py-2 text-xs bg-white border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500 font-medium text-slate-700">
                </div>

                <button type="submit" class="bg-teal-600 hover:bg-teal-700 text-white text-xs font-semibold px-3 py-2 rounded-xl transition shadow-sm">
                    Cari
                </button>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse table-auto">
                <thead>
                    <tr class="border-b border-slate-100 text-xs font-bold text-slate-400 uppercase tracking-wider bg-slate-50/70">
                        <th class="py-3.5 px-4 text-center w-12">NO</th>
                        <th class="py-3.5 px-4 w-36">KODE BARCODE</th>
                        <th class="py-3.5 px-4">NAMA BARANG</th>
                        <th class="py-3.5 px-4 w-44">KATEGORI</th>
                        <th class="py-3.5 px-4 text-center w-32">STOK TERSEDIA</th>
                        <th class="py-3.5 px-4 text-center w-28">KONDISI</th>
                        <th class="py-3.5 px-4 w-40">LOKASI</th>

                        <?php if (in_array($role, ['admin', 'laboran'])): ?>
                            <th class="py-3.5 px-4 text-center w-24">AKSI</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm text-slate-700">
                    <?php if (!empty($inventaris)): ?>
                        <?php
                        $no = $offset + 1;
                        foreach ($inventaris as $item):
                            $id_barang = is_object($item) ? $item->id_barang : $item['id_barang'];
                            $barcode   = is_object($item) ? $item->kode_barcode : $item['kode_barcode'];
                            $nama      = is_object($item) ? $item->nama_barang : $item['nama_barang'];
                            $kategori  = is_object($item) ? ($item->kategori ?? '') : ($item['kategori'] ?? '');
                            $stok      = is_object($item) ? $item->stok : $item['stok'];
                            $satuan    = is_object($item) ? $item->satuan : $item['satuan'];
                            $kondisi   = is_object($item) ? $item->kondisi : $item['kondisi'];
                            $lokasi    = is_object($item) ? $item->lokasi_penyimpanan : $item['lokasi_penyimpanan'];
                        ?>
                            <tr class="hover:bg-slate-50/60 transition">
                                <td class="py-3.5 px-4 text-center font-medium text-slate-400"><?= $no++; ?></td>
                                <td class="py-3.5 px-4 font-semibold text-slate-800 whitespace-nowrap"><?= $barcode; ?></td>
                                <td class="py-3.5 px-4 font-medium text-slate-800"><?= $nama; ?></td>
                                <td class="py-3.5 px-4">
                                    <?php if (!empty(trim($kategori))): ?>
                                        <span class="px-2.5 py-1 text-xs rounded-full bg-slate-100 text-slate-600 font-medium inline-block">
                                            <?= html_escape($kategori); ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="text-xs text-slate-400 font-medium">-</span>
                                    <?php endif; ?>
                                </td>
                                <td class="py-3.5 px-4 text-center font-bold text-teal-600 whitespace-nowrap">
                                    <?= $stok . ' ' . $satuan; ?>
                                </td>
                                <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                    <span class="px-2.5 py-1 text-xs rounded-full bg-emerald-50 text-emerald-600 font-semibold">
                                        <?= $kondisi; ?>
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 text-slate-600"><?= $lokasi; ?></td>

                                <?php if (in_array($role, ['admin', 'laboran'])): ?>
                                    <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                        <div class="flex items-center justify-center gap-1.5">
                                            <a href="<?= site_url('inventaris/edit/' . $id_barang); ?>" class="text-amber-500 hover:text-amber-600 p-1.5 transition rounded-lg hover:bg-amber-50" title="Edit">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </a>
                                            <a href="<?= site_url('inventaris/delete/' . $id_barang); ?>" onclick="return confirm('Yakin ingin menghapus item ini?')" class="text-rose-500 hover:text-rose-600 p-1.5 transition rounded-lg hover:bg-rose-50" title="Hapus">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </a>
                                        </div>
                                    </td>
                                <?php endif; ?>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="<?= in_array($role, ['admin', 'laboran']) ? '8' : '7'; ?>" class="py-8 text-center text-slate-400">
                                Belum ada data <?= strtolower($jenis); ?>.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="flex flex-col sm:flex-row justify-between items-center gap-4 px-6 py-4 border-t border-slate-100 bg-white">
            <div class="flex items-center gap-3 text-xs text-slate-500">
                <span>
                    Menampilkan <strong class="text-slate-700"><?= ($total_rows > 0) ? ($offset + 1) : 0; ?></strong> - <strong class="text-slate-700"><?= min($offset + $per_page, $total_rows); ?></strong> dari <strong class="text-slate-700"><?= $total_rows; ?></strong> item
                </span>
            </div>
            <div>
                <?= $pagination; ?>
            </div>
        </div>
    </div>
</div>