<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_Peminjaman extends CI_Model
{

    public function get_all_with_relations()
    {
        $this->db->select('p.*, u.nama_lengkap as peminjam, u.role, b.nama_barang, b.kode_barcode');
        $this->db->from('tb_peminjaman p');
        $this->db->join('tb_users u', 'u.id_user = p.id_user');
        $this->db->join('tb_inventaris b', 'b.id_barang = p.id_barang');
        $this->db->order_by('p.id_peminjaman', 'DESC');
        return $this->db->get()->result();
    }

    public function get_active_loans()
    {
        $this->db->select('p.*, u.nama_lengkap, b.nama_barang');
        $this->db->from('tb_peminjaman p');
        $this->db->join('tb_users u', 'u.id_user = p.id_user');
        $this->db->join('tb_inventaris b', 'b.id_barang = p.id_barang');

        // Gunakan 'p.status' agar tidak bentrok dengan kolom status di tabel lain
        $this->db->where('p.status', 'Dipinjam');

        return $this->db->count_all_results();
    }

    public function get_pending_loans()
    {
        $this->db->where('status', 'Pending');
        return $this->db->count_all_results('tb_peminjaman');
    }

    public function get_by_id($id_peminjaman)
    {
        return $this->db->get_where('tb_peminjaman', ['id_peminjaman' => $id_peminjaman])->row();
    }

    public function insert($data)
    {
        return $this->db->insert('tb_peminjaman', $data);
    }

    public function update_status($id_peminjaman, $status, $tgl_kembali = NULL)
    {
        $this->db->where('id_peminjaman', $id_peminjaman);
        return $this->db->update('tb_peminjaman', [
            'status' => $status,
            'tgl_kembali' => $tgl_kembali
        ]);
    }

    // Hitung peminjaman milik user tertentu berdasarkan status
    public function count_user_loans($id_user, $status)
    {
        $this->db->where('id_user', $id_user);
        $this->db->where('status', $status);
        return $this->db->count_all_results('tb_peminjaman');
    }

    // Hitung total seluruh riwayat peminjaman user tertentu
    public function count_user_loans_total($id_user)
    {
        $this->db->where('id_user', $id_user);
        return $this->db->count_all_results('tb_peminjaman');
    }


    //==========================================================================
    // 1. Ambil semua data peminjaman beserta relasi user & barang
    public function get_all_with_relation()
    {
        $this->db->select('p.*, u.nama_lengkap as peminjam, u.role, b.nama_barang, b.kode_barcode');
        $this->db->from('tb_peminjaman p');
        $this->db->join('tb_users u', 'u.id_user = p.id_user', 'left');
        $this->db->join('tb_inventaris b', 'b.id_barang = p.id_barang', 'left');
        $this->db->order_by('p.id_peminjaman', 'DESC');
        return $this->db->get()->result();
    }

    // 2. Hitung jumlah peminjaman aktif (status Dipinjam)
    public function get_active_loan()
    {
        $this->db->where('status', 'Dipinjam');
        return $this->db->count_all_results('tb_peminjaman');
    }

    // 3. Hitung jumlah peminjaman pending
    public function get_pending_loan()
    {
        $this->db->where('status', 'Pending');
        return $this->db->count_all_results('tb_peminjaman');
    }

    // 4. Ambil data peminjaman berdasarkan ID
    public function get_by_id_peminjaman($id_peminjaman)
    {
        return $this->db->get_where('tb_peminjaman', ['id_peminjaman' => $id_peminjaman])->row();
    }

    // 5. Tambah transaksi peminjaman baru
    public function inserts($data)
    {
        return $this->db->insert('tb_peminjaman', $data);
    }

    // 6. Update status peminjaman (Approval / Pengembalian)
    public function update_status_peminjaman($id_peminjaman, $status, $tgl_kembali = NULL)
    {
        $this->db->where('id_peminjaman', $id_peminjaman);
        return $this->db->update('tb_peminjaman', [
            'status' => $status,
            'tgl_kembali_realisasi' => $tgl_kembali
        ]);
    }

    // 7. Hitung peminjaman spesifik per user berdasarkan status
    public function count_user_loans_status($id_user, $status)
    {
        $this->db->where('id_user', $id_user);
        $this->db->where('status', $status);
        return $this->db->count_all_results('tb_peminjaman');
    }

    // 8. Hitung total seluruh riwayat peminjaman user tertentu
    public function count_user_loans_total_peminjaman($id_user)
    {
        $this->db->where('id_user', $id_user);
        return $this->db->count_all_results('tb_peminjaman');
    }

    //================ STATUS APPROVAL PEMINJAMAN SISWA ========================

    // Update status transaksi sekaligus sinkronisasi stok inventaris
    public function proses_approval($id_peminjaman, $status_baru)
    {
        $status_valid = ['Dipinjam', 'Ditolak', 'Dikembalikan'];

        if (!in_array($status_baru, $status_valid, true)) {
            return 'transisi_tidak_valid';
        }

        $peminjaman = $this->db->get_where(
            'tb_peminjaman',
            ['id_peminjaman' => $id_peminjaman]
        )->row_array();

        if (!$peminjaman || (int) $peminjaman['jumlah_pinjam'] <= 0) {
            return 'data_tidak_valid';
        }

        $status_lama = $peminjaman['status'];
        $id_barang = (int) $peminjaman['id_barang'];
        $jumlah = (int) $peminjaman['jumlah_pinjam'];

        if ($status_baru === $status_lama) {
            return 'tidak_berubah';
        }

        $transisi = [
            'Pending' => ['Dipinjam', 'Ditolak'],
            'Disetujui' => ['Dipinjam', 'Ditolak'],
            'Dipinjam' => ['Dikembalikan'],
            'Ditolak' => [],
            'Dikembalikan' => []
        ];

        if (!in_array($status_baru, $transisi[$status_lama] ?? [], true)) {
            return 'transisi_tidak_valid';
        }

        $data_update = [
            'status' => $status_baru,
            'updated_at' => date('Y-m-d H:i:s')
        ];

        if ($status_baru === 'Dikembalikan') {
            $data_update['tgl_kembali_realisasi'] = date('Y-m-d H:i:s');
        } else {
            $data_update['tgl_kembali_realisasi'] = null;
        }

        if (!$this->db->trans_begin()) {
            return false;
        }

        // Cegah pemrosesan ulang jika transaksi berubah sejak dibaca.
        $tersimpan = $this->db
            ->where('id_peminjaman', $id_peminjaman)
            ->where('status', $status_lama)
            ->where('id_barang', $id_barang)
            ->where('jumlah_pinjam', $jumlah)
            ->update('tb_peminjaman', $data_update);

        if (!$tersimpan || $this->db->affected_rows() !== 1) {
            $this->db->trans_rollback();
            return 'data_berubah';
        }

        $stok_ok = true;

        if ($status_baru === 'Dipinjam') {
            $stok_ok = $this->db
                ->set('stok', 'stok - ' . $jumlah, false)
                ->where('id_barang', $id_barang)
                ->where('kondisi', 'Baik')
                ->where('stok >=', $jumlah)
                ->update('tb_inventaris');

            $stok_ok = $stok_ok && $this->db->affected_rows() === 1;

            if (!$stok_ok) {
                $this->db->trans_rollback();
                return 'stok_atau_kondisi';
            }
        } elseif ($status_baru === 'Dikembalikan') {
            $stok_ok = $this->db
                ->set('stok', 'stok + ' . $jumlah, false)
                ->where('id_barang', $id_barang)
                ->update('tb_inventaris');

            $stok_ok = $stok_ok && $this->db->affected_rows() === 1;
        }

        if (!$stok_ok || $this->db->trans_status() === false) {
            $this->db->trans_rollback();
            return false;
        }

        if (!$this->db->trans_commit()) {
            $this->db->trans_rollback();
            return false;
        }

        return true;
    }
    //=================================================================================
}
