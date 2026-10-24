<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Export Excel Data Pengguna SIM-Lab</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11pt;
        }

        .header-title {
            font-size: 14pt;
            font-weight: bold;
            text-align: center;
        }

        .header-sub {
            font-size: 11pt;
            text-align: center;
            margin-bottom: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #000000;
            padding: 6px 8px;
            vertical-align: middle;
        }

        th {
            background-color: #0D9488;
            color: #FFFFFF;
            font-weight: bold;
            text-align: center;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .font-bold {
            font-weight: bold;
        }

        .group-header {
            background-color: #E2E8F0;
            color: #0F172A;
            font-weight: bold;
            font-size: 11pt;
        }

        .group-subtotal {
            background-color: #F8FAFC;
            font-style: italic;
            color: #475569;
        }

        .grand-total {
            background-color: #CBD5E1;
            font-weight: bold;
        }
    </style>
</head>

<body>

    <!-- KOP LAPORAN EXCEL -->
    <table>
        <tr>
            <td colspan="5" class="header-title">LAPORAN DATA PENGGUNA SISTEM INFORMASI LABORATORIUM</td>
        </tr>
        <tr>
            <td colspan="5" class="header-title">SMK KESEHATAN SURYA MEDIKA</td>
        </tr>
        <tr>
            <td colspan="5" class="header-sub">Kategori Peran: <strong><?= $filter_role; ?></strong> | Dicetak pada: <?= $tgl_cetak; ?></td>
        </tr>
        <tr>
            <td colspan="5"></td>
        </tr>
    </table>

    <!-- TABEL DATA USER -->
    <table>
        <thead>
            <tr>
                <th width="40">NO</th>
                <th width="180">USERNAME</th>
                <th width="260">NAMA LENGKAP</th>
                <th width="140">ROLE / HAK AKSES</th>
                <th width="150">STATUS AKUN</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($grouped_users)): ?>
                <?php foreach ($grouped_users as $role_title => $list_user): ?>
                    <!-- HEADER BARIS KELOMPOK ROLE -->
                    <tr class="group-header">
                        <td colspan="5">
                            &#9658; KELOMPOK PERAN: <?= $role_title; ?> (<?= count($list_user); ?> Pengguna)
                        </td>
                    </tr>

                    <?php
                    $sub_no = 1;
                    foreach ($list_user as $u):
                        $username = is_object($u) ? $u->username : $u['username'];
                        $nama     = is_object($u) ? $u->nama_lengkap : $u['nama_lengkap'];
                        $role     = is_object($u) ? $u->role : $u['role'];
                    ?>
                        <tr>
                            <td class="text-center"><?= $sub_no++; ?></td>
                            <td class="text-center" style="mso-number-format:'\@';"><?= $username; ?></td>
                            <td><?= $nama; ?></td>
                            <td class="text-center font-bold"><?= strtoupper($role); ?></td>
                            <td class="text-center">Aktif</td>
                        </tr>
                    <?php endforeach; ?>

                    <!-- SUB-TOTAL PER GRUP -->
                    <tr class="group-subtotal">
                        <td colspan="3" class="text-right">Subtotal Pengguna <?= $role_title; ?>:</td>
                        <td class="text-center font-bold"><?= count($list_user); ?></td>
                        <td></td>
                    </tr>
                <?php endforeach; ?>

                <!-- BARIS GRAND TOTAL -->
                <tr class="grand-total">
                    <td colspan="3" class="text-right">TOTAL PENGGUNA TEREXPORT:</td>
                    <td class="text-center"><?= $total_users; ?> User</td>
                    <td></td>
                </tr>
            <?php else: ?>
                <tr>
                    <td colspan="5" class="text-center">Tidak ada data pengguna yang sesuai dengan filter role tersebut.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- FOOTER TANDA TANGAN -->
    <br><br>
    <table>
        <tr>
            <td colspan="3"></td>
            <td colspan="2" class="text-center">
                Buleleng, <?= date('d F Y'); ?><br>
                <strong>Administrator Laboratorium</strong>
                <br><br><br><br>
                <strong><u><?= $this->session->userdata('nama_lengkap'); ?></u></strong><br>
                NIP/NIK. <?= $this->session->userdata('username'); ?>
            </td>
        </tr>
    </table>

</body>

</html>