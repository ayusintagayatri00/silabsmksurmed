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
        // 1. Ambil data detail transaksi
        $peminjaman = $this->db->get_where('tb_peminjaman', ['id_peminjaman' => $id_peminjaman])->row();
        if (!$peminjaman) {
            return false;
        }

        $id_barang     = $peminjaman->id_barang;
        $jumlah_pinjam = (int) ($peminjaman->jumlah_pinjam ?? 1);
        $status_lama   = $peminjaman->status;

        // 2. Logika Pemotongan & Penambahan Stok Inventaris
        if ($status_baru === 'Dipinjam' && $status_lama !== 'Dipinjam') {
            // Cek ketersediaan stok inventaris terlebih dahulu
            $barang = $this->db->get_where('tb_inventaris', ['id_barang' => $id_barang])->row();
            if (!$barang || $barang->stok < $jumlah_pinjam) {
                return 'stok_habis';
            }
            // Kurangi stok alat
            $this->db->set('stok', 'stok - ' . $jumlah_pinjam, FALSE);
            $this->db->where('id_barang', $id_barang);
            $this->db->update('tb_inventaris');
        } elseif ($status_baru === 'Dikembalikan' && $status_lama === 'Dipinjam') {
            // Tambahkan kembali stok saat alat dikembalikan
            $this->db->set('stok', 'stok + ' . $jumlah_pinjam, FALSE);
            $this->db->where('id_barang', $id_barang);
            $this->db->update('tb_inventaris');
        }

        // 3. Update status peminjaman di database
        $update_data = ['status' => $status_baru];
        if ($status_baru === 'Dikembalikan') {
            $update_data['tgl_kembali_realisasi'] = date('Y-m-d H:i:s');
        }

        $this->db->where('id_peminjaman', $id_peminjaman);
        return $this->db->update('tb_peminjaman', $update_data);
    }
    //=================================================================================
}
