<div class="max-w-4xl mx-auto space-y-6">

    <!-- Notifikasi Flashdata -->
    <?php if ($this->session->flashdata('success')): ?>
        <div class="p-4 bg-emerald-100 border border-emerald-300 text-emerald-800 rounded-xl text-sm font-semibold flex items-center gap-2">
            <i class="fa-solid fa-circle-check"></i> <?= $this->session->flashdata('success'); ?>
        </div>
    <?php endif; ?>

    <?php if ($this->session->flashdata('error')): ?>
        <div class="p-4 bg-rose-100 border border-rose-300 text-rose-800 rounded-xl text-sm font-semibold flex items-center gap-2">
            <i class="fa-solid fa-circle-exclamation"></i> <?= $this->session->flashdata('error'); ?>
        </div>
    <?php endif; ?>

    <!-- Card Header Profil -->
    <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col md:flex-row items-center gap-6">
        <div class="relative group shrink-0">
            <?php if (!empty($user->foto) && file_exists('./uploads/profile/' . $user->foto)): ?>
                <img src="<?= base_url('uploads/profile/' . $user->foto); ?>" alt="Foto Profil" class="w-24 h-24 rounded-full object-cover shadow-md ring-4 ring-teal-50">
            <?php else: ?>
                <div class="w-24 h-24 rounded-full bg-teal-600 text-white font-bold flex items-center justify-center text-4xl shadow-md ring-4 ring-teal-50">
                    <?= strtoupper(substr($user->nama_lengkap ?? 'U', 0, 1)); ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="text-center md:text-left space-y-1">
            <h2 class="text-2xl font-bold text-slate-800"><?= $user->nama_lengkap; ?></h2>
            <p class="text-slate-500 text-sm"><i class="fa-solid fa-envelope mr-1"></i> <?= $user->email; ?></p>
            <div class="pt-2 flex items-center justify-center md:justify-start gap-2">
                <span class="bg-teal-100 text-teal-800 text-xs font-bold uppercase px-3 py-1 rounded-full">
                    <?= $user->role; ?>
                </span>
                <?php if (!empty($user->foto)): ?>
                    <a href="<?= base_url('profile/delete_photo'); ?>" onclick="return confirm('Hapus foto profil ini?')" class="text-xs text-rose-600 hover:underline">
                        <i class="fa-solid fa-trash-can mr-1"></i> Hapus Foto
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        <!-- Form Edit Informasi Profil & Upload Foto -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm">
            <h3 class="text-lg font-bold text-slate-800 mb-4 pb-2 border-b border-slate-100 flex items-center gap-2">
                <i class="fa-solid fa-user-pen text-teal-600"></i> Informasi & Foto Akun
            </h3>

            <?= form_open_multipart('profile/update_profile', ['class' => 'space-y-4']); ?>
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Username / NIP / NISN</label>
                <input type="text" value="<?= $user->username; ?>" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-100 text-slate-500 text-sm font-semibold cursor-not-allowed" disabled>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1">Nama Lengkap</label>
                <input type="text" name="nama_lengkap" value="<?= set_value('nama_lengkap', $user->nama_lengkap); ?>" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-teal-500 text-sm" required>
                <?= form_error('nama_lengkap', '<p class="text-rose-500 text-xs mt-1">', '</p>'); ?>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1">Email</label>
                <input type="email" name="email" value="<?= set_value('email', $user->email); ?>" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-teal-500 text-sm" required>
                <?= form_error('email', '<p class="text-rose-500 text-xs mt-1">', '</p>'); ?>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1">Upload Foto Profil Baru</label>
                <input type="file" name="foto" accept="image/png, image/jpeg, image/jpg" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-teal-50 file:text-teal-700 hover:file:bg-teal-100 cursor-pointer">
                <p class="text-[11px] text-slate-400 mt-1">Format: JPG, JPEG, PNG (Maksimal 2MB)</p>
            </div>

            <button type="submit" class="w-full bg-teal-600 hover:bg-teal-700 text-white font-semibold py-2.5 rounded-xl transition text-sm shadow-sm">
                Simpan Perubahan
            </button>
            <?= form_close(); ?>
        </div>

        <!-- Form Ganti Password -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm">
            <h3 class="text-lg font-bold text-slate-800 mb-4 pb-2 border-b border-slate-100 flex items-center gap-2">
                <i class="fa-solid fa-key text-teal-600"></i> Ganti Password
            </h3>
            <form action="<?= base_url('profile/change_password'); ?>" method="POST" class="space-y-4">
                <!-- penambahan CSRF Token -->
                <input
                    type="hidden"
                    name="<?= html_escape($this->security->get_csrf_token_name()); ?>"
                    value="<?= html_escape($this->security->get_csrf_hash()); ?>">
                <input type="hidden" name="id_user" value="<?= $u['id_user']; ?>">
                <div>
                    <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1">Password Saat Ini</label>
                    <input type="password" name="password_lama" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-teal-500 text-sm" required>
                    <?= form_error('password_lama', '<p class="text-rose-500 text-xs mt-1">', '</p>'); ?>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1">Password Baru (Min. 6 Karakter)</label>
                    <input type="password" name="password_baru" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-teal-500 text-sm" required>
                    <?= form_error('password_baru', '<p class="text-rose-500 text-xs mt-1">', '</p>'); ?>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1">Konfirmasi Password Baru</label>
                    <input type="password" name="konfirmasi_password" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-teal-500 text-sm" required>
                    <?= form_error('konfirmasi_password', '<p class="text-rose-500 text-xs mt-1">', '</p>'); ?>
                </div>

                <button type="submit" class="w-full bg-slate-800 hover:bg-slate-900 text-white font-semibold py-2.5 rounded-xl transition text-sm shadow-sm">
                    Ubah Password
                </button>
            </form>
        </div>

    </div>
</div>