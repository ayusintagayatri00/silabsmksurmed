<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Export Excel Peminjaman Alat</title>
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
            /* Warna Teal Header */
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

        /* Style Status Badge */
        .status-dipinjam {
            background-color: #FEF3C7;
            color: #92400E;
            font-weight: bold;
        }

        .status-selesai {
            background-color: #D1FAE5;
            color: #065F46;
            font-weight: bold;
        }

        .status-pending {
            background-color: #DBEAFE;
            color: #1E40AF;
            font-weight: bold;
        }

        .status-ditolak {
            background-color: #FEE2E2;
            color: #991B1B;
            font-weight: bold;
        }
    </style>
</head>

<body>

    <!-- KOP LAPORAN EXCEL -->
    <table>
        <tr>
            <td colspan="10" class="header-title">LAPORAN REKAPITULASI PEMINJAMAN ALAT LABORATORIUM</td>
        </tr>
        <tr>
            <td colspan="10" class="header-title">SMK KESEHATAN SURYA MEDIKA</td>
        </tr>
        <tr>
            <td colspan="10" class="header-sub">Sistem Informasi Laboratorium Keperawatan | Dicetak pada: <?= $tgl_cetak; ?></td>
        </tr>
        <tr>
            <td colspan="10"></td> <!-- Baris Kosong -->
        </tr>
    </table>

    <!-- TABEL DATA PEMINJAMAN -->
    <table>
        <thead>
            <tr>
                <th width="35">NO</th>
                <th width="120">KODE PINJAM</th>
                <th width="160">NAMA PEMINJAM</th>
                <th width="80">ROLE</th>
                <th width="200">NAMA ALAT / INSTRUMEN</th>
                <th width="65">QTY</th>
                <th width="115">TGL PINJAM</th>
                <th width="115">RENCANA KEMBALI</th>
                <th width="115">REALISASI KEMBALI</th>
                <th width="90">STATUS</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($peminjaman)): ?>
                <?php $no = 1;
                foreach ($peminjaman as $row):
                    // Konversi Fleksibel Object/Array
                    $kode_pmj   = is_object($row) ? ($row->kode_peminjaman ?? '-')   : ($row['kode_peminjaman'] ?? '-');
                    $peminjam   = is_object($row) ? ($row->peminjam ?? '-')          : ($row['peminjam'] ?? '-');
                    $role       = is_object($row) ? ($row->role ?? '-')              : ($row['role'] ?? '-');
                    $nama_alat  = is_object($row) ? ($row->nama_barang ?? '-')       : ($row['nama_barang'] ?? '-');
                    $qty        = is_object($row) ? ($row->jumlah_pinjam ?? 1)       : ($row['jumlah_pinjam'] ?? 1);
                    $tgl_pinjam = is_object($row) ? ($row->tgl_pinjam ?? '-')        : ($row['tgl_pinjam'] ?? '-');
                    $tgl_rencana = is_object($row) ? ($row->tgl_kembali_rencana ?? '-') : ($row['tgl_kembali_rencana'] ?? '-');
                    $tgl_real   = is_object($row) ? ($row->tgl_kembali_realisasi ?? '-') : ($row['tgl_kembali_realisasi'] ?? '-');
                    $status     = is_object($row) ? ($row->status ?? 'Pending')      : ($row['status'] ?? 'Pending');

                    // Format Tanggal
                    $str_pinjam  = ($tgl_pinjam != '-') ? date('d/m/Y H:i', strtotime($tgl_pinjam)) : '-';
                    $str_rencana = ($tgl_rencana != '-' && !empty($tgl_rencana)) ? date('d/m/Y H:i', strtotime($tgl_rencana)) : '-';
                    $str_real    = ($tgl_real != '-' && !empty($tgl_real)) ? date('d/m/Y H:i', strtotime($tgl_real)) : '-';

                    // Kelas CSS Status
                    $bg_status = '';
                    if ($status == 'Dipinjam') $bg_status = 'status-dipinjam';
                    elseif (in_array($status, ['Selesai', 'Dikembalikan', 'Disetujui'])) $bg_status = 'status-selesai';
                    elseif ($status == 'Pending') $bg_status = 'status-pending';
                    elseif ($status == 'Ditolak') $bg_status = 'status-ditolak';
                ?>
                    <tr>
                        <td class="text-center"><?= $no++; ?></td>
                        <td class="text-center" style="mso-number-format:'\@';"><?= $kode_pmj; ?></td>
                        <td><?= $peminjam; ?></td>
                        <td class="text-center" style="text-transform: uppercase;"><?= $role; ?></td>
                        <td><?= $nama_alat; ?></td>
                        <td class="text-center font-bold"><?= $qty; ?></td>
                        <td class="text-center"><?= $str_pinjam; ?></td>
                        <td class="text-center"><?= $str_rencana; ?></td>
                        <td class="text-center"><?= $str_real; ?></td>
                        <td class="text-center <?= $bg_status; ?>"><?= $status; ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="10" class="text-center">Belum ada data transaksi peminjaman alat.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- FOOTER TANDA TANGAN LABORAN -->
    <br><br>
    <table>
        <tr>
            <td colspan="8"></td>
            <td colspan="2" class="text-center">
                Buleleng, <?= date('d F Y'); ?><br>
                <strong>Kepala / Laboran Laboratorium</strong>
                <br><br><br><br>
                <strong><u><?= $nama_laboran ?? 'Siti Rahma, A.Md.Kep'; ?></u></strong><br>
                NIP/NIK. <?= $nip_laboran ?? '-'; ?>
            </td>
        </tr>
    </table>

</body>

</html>