<?php
// Mencegah error caching pada browser/excel
header("Content-Type: application/vnd.ms-excel; charset=utf-8");
header("Content-Disposition: attachment; filename=\"Laporan_Stok_" . $jenis . "_" . date('Ymd_His') . ".xls\"");
header("Pragma: no-cache");
header("Expires: 0");
?>
<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <!--[if gte mso 9]>
    <xml>
        <x:ExcelWorkbook>
            <x:ExcelWorksheets>
                <x:ExcelWorksheet>
                    <x:Name><?= $jenis == 'Bahan' ? 'Stok BHP' : 'Stok Alat'; ?></x:Name>
                    <x:WorksheetOptions>
                        <x:DisplayGridlines/>
                    </x:WorksheetOptions>
                </x:ExcelWorksheet>
            </x:ExcelWorksheets>
        </x:ExcelWorkbook>
    </xml>
    <![endif]-->
    <style>
        .str {
            mso-number-format: "\@";
        }

        .num {
            mso-number-format: "\#\,\#\#0";
        }
    </style>
</head>

<body>

    <!-- KOP LAPORAN (Merge 8 Kolom) -->
    <table border="0" style="width:100%;">
        <tr>
            <td colspan="8" align="center" style="font-size: 14pt; font-weight: bold; height: 30px; vertical-align: middle;">
                SMK KESEHATAN SURYA MEDIKA
            </td>
        </tr>
        <tr>
            <td colspan="8" align="center" style="font-size: 12pt; font-weight: bold; height: 25px; vertical-align: middle;">
                <?= strtoupper($title); ?>
            </td>
        </tr>
        <tr>
            <td colspan="8" align="center" style="font-size: 9pt; color: #555555; height: 20px; vertical-align: middle;">
                Tanggal Cetak: <?= date('d/m/Y H:i:s'); ?> | Dicetak Oleh: <?= html_escape($this->session->userdata('nama_lengkap') ?? 'Administrator'); ?>
            </td>
        </tr>
        <tr>
            <td colspan="8" style="height: 15px;"></td>
        </tr>
    </table>

    <!-- TABEL DATA INVENTARIS -->
    <table border="1" style="border-collapse: collapse; width: 100%;">
        <thead>
            <tr style="background-color: #0D9488; color: #FFFFFF; font-weight: bold; text-align: center; height: 35px;">
                <th style="width: 50px; background-color: #0D9488; color: #FFFFFF; border: 1px solid #000000; vertical-align: middle;">NO</th>
                <th style="width: 160px; background-color: #0D9488; color: #FFFFFF; border: 1px solid #000000; vertical-align: middle;">KODE BARCODE</th>
                <th style="width: 250px; background-color: #0D9488; color: #FFFFFF; border: 1px solid #000000; vertical-align: middle;">NAMA BARANG</th>
                <th style="width: 180px; background-color: #0D9488; color: #FFFFFF; border: 1px solid #000000; vertical-align: middle;">KATEGORI</th>
                <th style="width: 90px; background-color: #0D9488; color: #FFFFFF; border: 1px solid #000000; vertical-align: middle;">STOK</th>
                <th style="width: 90px; background-color: #0D9488; color: #FFFFFF; border: 1px solid #000000; vertical-align: middle;">SATUAN</th>
                <th style="width: 110px; background-color: #0D9488; color: #FFFFFF; border: 1px solid #000000; vertical-align: middle;">KONDISI</th>
                <th style="width: 220px; background-color: #0D9488; color: #FFFFFF; border: 1px solid #000000; vertical-align: middle;">LOKASI PENYIMPANAN</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($inventaris)): ?>
                <?php
                $no = 1;
                foreach ($inventaris as $item):
                    $barcode  = is_object($item) ? $item->kode_barcode : $item['kode_barcode'];
                    $nama     = is_object($item) ? $item->nama_barang : $item['nama_barang'];
                    $kategori = is_object($item) ? ($item->kategori ?? '-') : ($item['kategori'] ?? '-');
                    $stok     = is_object($item) ? $item->stok : $item['stok'];
                    $satuan   = is_object($item) ? $item->satuan : $item['satuan'];
                    $kondisi  = is_object($item) ? $item->kondisi : $item['kondisi'];
                    $lokasi   = is_object($item) ? $item->lokasi_penyimpanan : $item['lokasi_penyimpanan'];
                ?>
                    <tr style="height: 25px;">
                        <td align="center" style="border: 1px solid #000000; vertical-align: middle;"><?= $no++; ?></td>
                        <td align="center" class="str" style="border: 1px solid #000000; vertical-align: middle;"><?= $barcode; ?></td>
                        <td style="border: 1px solid #000000; vertical-align: middle; padding-left: 5px;"><?= html_escape($nama); ?></td>
                        <td style="border: 1px solid #000000; vertical-align: middle; padding-left: 5px;"><?= html_escape($kategori); ?></td>
                        <td align="center" class="num" style="border: 1px solid #000000; font-weight: bold; vertical-align: middle;"><?= $stok; ?></td>
                        <td align="center" style="border: 1px solid #000000; vertical-align: middle;"><?= html_escape($satuan); ?></td>
                        <td align="center" style="border: 1px solid #000000; vertical-align: middle;"><?= html_escape($kondisi); ?></td>
                        <td style="border: 1px solid #000000; vertical-align: middle; padding-left: 5px;"><?= html_escape($lokasi); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="8" align="center" style="border: 1px solid #000000; height: 30px; vertical-align: middle;">
                        Belum ada data stok <?= strtolower($jenis); ?>.
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

</body>

</html>