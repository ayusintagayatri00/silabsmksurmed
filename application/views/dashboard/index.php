<?php
$role = $this->session->userdata('role');
$nama = $this->session->userdata('nama_lengkap');
?>

<!-- ==================== 1. CARD STATISTIK UTAMA (DI ATAS) ==================== -->
<?php if (in_array($role, ['admin', 'laboran', 'kepsek'])): ?>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-6">
        <!-- Total Items -->
        <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-lg bg-teal-100 text-teal-600 flex items-center justify-center text-xl font-bold">
                    <i class="fa-solid fa-boxes-stacked"></i>
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Items Lab</p>
                    <h3 class="text-2xl font-bold text-slate-800"><?= $total_items ?? 0; ?></h3>
                </div>
            </div>
            <div class="mt-3 pt-3 border-t border-slate-100 flex justify-between text-xs text-slate-500 font-medium">
                <span><strong><?= $count_alat ?? 0; ?></strong> Alat Medis</span>
                <span>•</span>
                <span><strong><?= $count_bhp ?? 0; ?></strong> BHP</span>
            </div>
        </div>

        <!-- Sedang Dipinjam -->
        <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-lg bg-amber-100 text-amber-600 flex items-center justify-center text-xl font-bold">
                    <i class="fa-solid fa-hand-holding-medical"></i>
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Sedang Dipinjam</p>
                    <h3 class="text-2xl font-bold text-slate-800"><?= $count_dipinjam ?? 0; ?></h3>
                </div>
            </div>
            <div class="mt-3 pt-3 border-t border-slate-100 text-xs text-slate-500">
                <?= ($count_dipinjam > 0) ? 'Item berada di luar ruangan' : 'Tidak ada alat luar ruangan'; ?>
            </div>
        </div>

        <!-- Butuh Persetujuan -->
        <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center text-xl font-bold">
                    <i class="fa-solid fa-clock-rotate-left"></i>
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Butuh Persetujuan</p>
                    <h3 class="text-2xl font-bold text-slate-800"><?= $count_pending ?? 0; ?></h3>
                </div>
            </div>
            <div class="mt-3 pt-3 border-t border-slate-100 text-xs text-slate-500">
                <?= ($count_pending > 0) ? 'Menunggu verifikasi' : 'Tidak ada antrean approval'; ?>
            </div>
        </div>

        <!-- Akses Sistem -->
        <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-lg bg-purple-100 text-purple-600 flex items-center justify-center text-xl font-bold">
                    <i class="fa-solid fa-shield-halved"></i>
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Akses Sistem</p>
                    <h3 class="text-lg font-bold text-slate-800 uppercase"><?= $role; ?></h3>
                </div>
            </div>
            <div class="mt-3 pt-3 border-t border-slate-100 text-xs text-slate-500">
                Akses: <strong><?= $role == 'admin' ? 'Penuh (Master Admin)' : ($role == 'laboran' ? 'Kelola Stok & Peminjaman' : 'Monitoring & Approval'); ?></strong>
            </div>
        </div>
    </div>

<?php else: ?>

    <!-- Tampilan Card untuk Siswa / Guru -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-6">
        <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-lg bg-amber-100 text-amber-600 flex items-center justify-center text-xl font-bold">
                    <i class="fa-solid fa-briefcase-medical"></i>
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Alat Saya Pinjam</p>
                    <h3 class="text-2xl font-bold text-slate-800"><?= $my_loans_active ?? 0; ?></h3>
                </div>
            </div>
            <div class="mt-3 pt-3 border-t border-slate-100 text-xs text-slate-500">
                Item sedang Anda bawa/gunakan
            </div>
        </div>

        <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center text-xl font-bold">
                    <i class="fa-solid fa-hourglass-half"></i>
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Pengajuan Pending</p>
                    <h3 class="text-2xl font-bold text-slate-800"><?= $my_loans_pending ?? 0; ?></h3>
                </div>
            </div>
            <div class="mt-3 pt-3 border-t border-slate-100 text-xs text-slate-500">
                Menunggu persetujuan laboran
            </div>
        </div>

        <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-lg bg-emerald-100 text-emerald-600 flex items-center justify-center text-xl font-bold">
                    <i class="fa-solid fa-history"></i>
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Riwayat Pinjam</p>
                    <h3 class="text-2xl font-bold text-slate-800"><?= $my_loans_total ?? 0; ?></h3>
                </div>
            </div>
            <div class="mt-3 pt-3 border-t border-slate-100 text-xs text-slate-500">
                Total peminjaman selama ini
            </div>
        </div>

        <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-lg bg-teal-100 text-teal-600 flex items-center justify-center text-xl font-bold">
                    <i class="fa-solid fa-boxes-stacked"></i>
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Katalog Lab</p>
                    <h3 class="text-2xl font-bold text-slate-800"><?= $total_items ?? 0; ?></h3>
                </div>
            </div>
            <div class="mt-3 pt-3 border-t border-slate-100 text-xs text-slate-500">
                Alat & Bahan siap dipinjam
            </div>
        </div>
    </div>

<?php endif; ?>


<!-- ==================== 2. BANNER WELCOME (DI DIBAWAH) ==================== -->
<div class="bg-teal-800 rounded-2xl p-6 text-white shadow-md">
    <h2 class="text-xl font-bold mb-2">Selamat Datang, <?= $nama ?? 'Administrator Lab'; ?>!</h2>
    <p class="text-teal-100 text-sm max-w-2xl">
        Anda masuk sebagai <strong class="uppercase"><?= $role; ?></strong> di Sistem Informasi Laboratorium Keperawatan SMK Kesehatan Surya Medika. Gunakan navigasi di sebelah kiri untuk mengelola instrumen medis, peminjaman alat, dan pengajuan BHP praktikum.
    </p>
</div>