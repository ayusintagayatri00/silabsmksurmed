<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Export Excel Pengadaan Alat & BHP</title>
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

        .font-bold {
            font-weight: bold;
        }

        /* Style Status Approval */
        .status-disetujui {
            background-color: #D1FAE5;
            color: #065F46;
            font-weight: bold;
        }

        .status-pending {
            background-color: #FEF3C7;
            color: #92400E;
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
            <td colspan="10" class="header-title">LAPORAN REKAPITULASI PENGAJUAN PENGADAAN ALAT &amp; BHP</td>
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

    <!-- TABEL DATA PENGADAAN -->
    <table>
        <thead>
            <tr>
                <th width="35">NO</th>
                <th width="200">NAMA ITEM / BARANG</th>
                <th width="60">QTY</th>
                <th width="80">SATUAN</th>
                <th width="120">ESTIMASI HARGA (SATUAN)</th>
                <th width="130">TOTAL ESTIMASI</th>
                <th width="220">ALASAN PENGADAAN</th>
                <th width="150">PENGAJU</th>
                <th width="110">TGL PENGAJUAN</th>
                <th width="100">STATUS</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $grand_total = 0;
            if (!empty($pengadaan)):
                $no = 1;
                foreach ($pengadaan as $row):
                    // Konversi Fleksibel Object/Array
                    $nama_item   = is_object($row) ? ($row->nama_item ?? '-')        : ($row['nama_item'] ?? '-');
                    $jumlah      = is_object($row) ? ($row->jumlah ?? 0)             : ($row['jumlah'] ?? 0);
                    $satuan      = is_object($row) ? ($row->satuan ?? '-')          : ($row['satuan'] ?? '-');
                    $harga       = is_object($row) ? ($row->estimasi_harga ?? 0)     : ($row['estimasi_harga'] ?? 0);
                    $alasan      = is_object($row) ? ($row->alasan_pengadaan ?? '-') : ($row['alasan_pengadaan'] ?? '-');
                    $pengaju     = is_object($row) ? ($row->pengaju ?? '-')         : ($row['pengaju'] ?? '-');
                    $status      = is_object($row) ? ($row->status_approval ?? 'Pending') : ($row['status_approval'] ?? 'Pending');
                    $created_at  = is_object($row) ? ($row->created_at ?? '-')      : ($row['created_at'] ?? '-');

                    // Kalkulasi Total Per Sub-Item
                    $subtotal    = $jumlah * $harga;
                    $grand_total += $subtotal;

                    // Format Tanggal Pengajuan
                    $str_tgl = ($created_at != '-') ? date('d/m/Y H:i', strtotime($created_at)) : '-';

                    // Kelas CSS Warna Status Approval
                    $bg_status = '';
                    if ($status == 'Disetujui') $bg_status = 'status-disetujui';
                    elseif ($status == 'Pending') $bg_status = 'status-pending';
                    elseif ($status == 'Ditolak') $bg_status = 'status-ditolak';
            ?>
                    <tr>
                        <td class="text-center"><?= $no++; ?></td>
                        <td><?= $nama_item; ?></td>
                        <td class="text-center font-bold"><?= $jumlah; ?></td>
                        <td class="text-center"><?= $satuan; ?></td>
                        <td class="text-right" style="mso-number-format:'\#\,\#\#0';"><?= $harga; ?></td>
                        <td class="text-right font-bold" style="mso-number-format:'\#\,\#\#0';"><?= $subtotal; ?></td>
                        <td><?= $alasan; ?></td>
                        <td><?= $pengaju; ?></td>
                        <td class="text-center"><?= $str_tgl; ?></td>
                        <td class="text-center <?= $bg_status; ?>"><?= $status; ?></td>
                    </tr>
                <?php endforeach; ?>
                <!-- BARIS TOTAL ESTIMASI BIAYA -->
                <tr>
                    <td colspan="5" class="text-right font-bold" style="background-color: #F3F4F6;">TOTAL ESTIMASI BIAYA PENGADAAN</td>
                    <td class="text-right font-bold" style="background-color: #F3F4F6; mso-number-format:'\#\,\#\#0';"><?= $grand_total; ?></td>
                    <td colspan="4" style="background-color: #F3F4F6;"></td>
                </tr>
            <?php else: ?>
                <tr>
                    <td colspan="10" class="text-center">Belum ada data pengajuan pengadaan alat / BHP.</td>
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