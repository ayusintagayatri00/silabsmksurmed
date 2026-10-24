<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-900">

<head>
    <meta charset="UTF-8">
    <title>Login - SIM Lab Keperawatan SMK Kesehatan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="h-full flex items-center justify-center p-4">
    <div class="w-full max-w-md bg-white rounded-2xl shadow-2xl overflow-hidden">
        <div class="bg-teal-700 p-6 text-center text-white">
            <i class="fa-solid fa-user-nurse text-4xl mb-2"></i>
            <h1 class="text-xl font-bold">SIM-LAB KEPERAWATAN</h1>
            <p class="text-xs text-teal-100">SMK Kesehatan Surya Medika</p>
        </div>

        <form action="<?= base_url('auth/login_process') ?>" method="POST" class="p-6 space-y-4">
            <?php if ($this->session->flashdata('error')): ?>
                <div class="p-3 bg-red-100 border border-red-300 text-red-700 rounded-lg text-xs">
                    <?= $this->session->flashdata('error') ?>
                </div>
            <?php endif; ?>

            <div>
                <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Username</label>
                <input type="text" name="username" required class="w-full px-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-teal-500">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Password</label>
                <input type="password" name="password" required class="w-full px-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-teal-500">
            </div>

            <div class="p-3 bg-slate-50 border rounded-lg flex items-center justify-between">
                <label class="flex items-center space-x-2 text-xs text-slate-700 cursor-pointer">
                    <input type="checkbox" name="recaptcha_check" value="1" required class="w-4 h-4 text-teal-600">
                    <span>Saya bukan robot</span>
                </label>
                <span class="text-[10px] text-slate-400">reCAPTCHA v2</span>
            </div>

            <button type="submit" class="w-full py-2.5 bg-teal-600 hover:bg-teal-700 text-white font-semibold rounded-lg text-sm">Masuk Sistem</button>
        </form>
    </div>
</body>

</html>