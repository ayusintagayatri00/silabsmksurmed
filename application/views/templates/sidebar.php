<?php
$role = $this->session->userdata('role');
$current_page = $this->uri->segment(1);
?>

<!-- Hapus -translate-x-full dan fixed, ganti dengan w-64 min-h-screen -->
<aside id="main-sidebar" class="w-64 bg-slate-900 text-slate-300 flex flex-col flex-shrink-0 min-h-screen">
    <!-- Brand Logo -->
    <div class="h-16 flex items-center px-6 bg-slate-950 border-b border-slate-800 gap-3">
        <i class="fa-solid fa-user-nurse text-teal-400 text-2xl"></i>
        <span class="font-bold text-white text-base tracking-wide">SILAB KEPERAWATAN</span>
    </div>

    <!-- Nav Links -->
    <nav class="flex-1 px-4 py-6 space-y-1.5 overflow-y-auto">
        <a href="<?= base_url('dashboard'); ?>" class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium transition <?= ($current_page == 'dashboard' || $current_page == '') ? 'bg-teal-600 text-white' : 'hover:bg-slate-800 hover:text-white'; ?>">
            <i class="fa-solid fa-chart-line w-5"></i>
            <span>Dashboard</span>
        </a>

        <?php if (in_array($role, ['admin', 'laboran', 'guru', 'siswa', 'kepsek'])): ?>
            <!-- Menu Stok Alat -->
            <a href="<?= site_url('inventaris/alat'); ?>"
                class="flex items-center gap-3 px-4 py-3 rounded-xl transition <?= ($this->uri->segment(2) == 'alat') ? 'bg-teal-600 text-white font-semibold' : 'text-slate-400 hover:text-white hover:bg-slate-800'; ?>">
                <i class="fa-solid fa-stethoscope"></i>
                <span>Stok Alat</span>
            </a>

            <!-- Menu Stok Bahan Habis Pakai (BHP) -->
            <a href="<?= site_url('inventaris/bahan'); ?>"
                class="flex items-center gap-3 px-4 py-3 rounded-xl transition <?= ($this->uri->segment(2) == 'bahan') ? 'bg-teal-600 text-white font-semibold' : 'text-slate-400 hover:text-white hover:bg-slate-800'; ?>">
                <i class="fa-solid fa-box-tissue"></i>
                <span>Stok Bahan (BHP)</span>
            </a>
        <?php endif; ?>

        <?php if (in_array($role, ['admin', 'laboran', 'guru', 'siswa', 'kepsek'])): ?>
            <a href="<?= base_url('peminjaman'); ?>" class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium transition <?= ($current_page == 'peminjaman') ? 'bg-teal-600 text-white' : 'hover:bg-slate-800 hover:text-white'; ?>">
                <i class="fa-solid fa-hand-holding-medical w-5"></i>
                <span>Peminjaman Alat</span>
            </a>
        <?php endif; ?>

        <?php if (in_array($role, ['admin', 'laboran', 'kepsek'])): ?>
            <a href="<?= base_url('pengadaan'); ?>" class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium transition <?= ($current_page == 'pengadaan') ? 'bg-teal-600 text-white' : 'hover:bg-slate-800 hover:text-white'; ?>">
                <i class="fa-solid fa-file-signature w-5"></i>
                <span>Pengadaan Alat/BHP</span>
            </a>
        <?php endif; ?>

        <?php if ($role === 'admin'): ?>
            <div class="pt-4 pb-1 px-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Pengaturan System</div>
            <a href="<?= base_url('user'); ?>" class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium transition <?= ($current_page == 'user') ? 'bg-teal-600 text-white' : 'hover:bg-slate-800 hover:text-white'; ?>">
                <i class="fa-solid fa-users-gear w-5"></i>
                <span>Manajemen User</span>
            </a>
        <?php endif; ?>
    </nav>

    <!-- Logout -->
    <div class="p-4 border-t border-slate-800">
        <a href="<?= base_url('auth/logout'); ?>" class="flex items-center gap-3 px-4 py-2.5 rounded-md text-red-400 hover:bg-red-500/10 transition font-medium text-sm">
            <i class="fa-solid fa-right-from-bracket"></i>
            <span>Keluar / Logout</span>
        </a>
    </div>
</aside>

<!-- Area Kanan (Navbar + Content + Footer) -->
<div class="flex-1 flex flex-col min-h-screen min-w-0">

    <!-- Topbar Header -->
    <header class="bg-slate-100 border-b border-slate-200/80 px-8 py-5 flex justify-between items-center shrink-0">

        <div>
            <h1 class="text-2xl font-bold text-slate-800 tracking-tight"><?= $title ?? 'Dashboard'; ?></h1>
        </div>

        <div class="flex items-center gap-3">
            <?php
            $id_user_login = $this->session->userdata('id_user');
            $user_login    = $this->db->get_where('tb_users', ['id_user' => $id_user_login])->row();
            ?>
            <!-- Profil User Topbar (Bisa Diklik ke Halaman Profile) -->
            <a href="<?= base_url('profile'); ?>" class="flex items-center gap-3 hover:opacity-80 transition group cursor-pointer" title="Lihat Profil Saya">
                <div class="text-right hidden sm:block">
                    <p class="text-sm font-bold text-slate-800 leading-none mb-1 group-hover:text-teal-600 transition">
                        <?= $user_login->nama_lengkap ?? 'User'; ?>
                    </p>
                    <span class="inline-block bg-teal-100 text-teal-700 text-[10px] font-extrabold uppercase px-2 py-0.5 rounded-full tracking-wider">
                        <?= $user_login->role ?? 'GUEST'; ?>
                    </span>
                </div>

                <?php if (!empty($user_login->foto) && file_exists('./uploads/profile/' . $user_login->foto)): ?>
                    <img src="<?= base_url('uploads/profile/' . $user_login->foto); ?>" alt="Avatar" class="w-10 h-10 rounded-full object-cover shadow-sm ring-2 ring-white group-hover:scale-105 transition-transform">
                <?php else: ?>
                    <div class="w-10 h-10 rounded-full bg-teal-600 text-white font-bold flex items-center justify-center text-base shadow-sm ring-2 ring-white group-hover:scale-105 transition-transform">
                        <?= strtoupper(substr($user_login->nama_lengkap ?? 'U', 0, 1)); ?>
                    </div>
                <?php endif; ?>
            </a>
        </div>
    </header>

    <!-- Main Content Area (flex-1 mendorong footer ke bawah) -->
    <main class="p-8 flex-1">