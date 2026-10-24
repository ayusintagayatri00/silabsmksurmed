<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_Dashboard extends CI_Model
{

    // Hitung Total Inventaris
    public function count_inventaris()
    {
        return $this->db->count_all('tb_inventaris');
    }

    // Hitung Total Alat Lab
    public function count_alat()
    {
        return $this->db->count_all('alat_lab');
    }

    // Hitung Total BHP (Bahan Habis Pakai)
    public function count_bhp()
    {
        return $this->db->count_all('tb_inventaris');
    }

    // Hitung Peminjaman Aktif / Sedang Dipinjam
    public function count_sedang_dipinjam()
    {
        return $this->db->where('status', 'Dipinjam')
            ->count_all_results('tb_peminjaman');
    }

    // Hitung Total Semua Riwayat Peminjaman
    public function count_peminjaman()
    {
        return $this->db->count_all('tb_peminjaman');
    }

    // Hitung Total Pengadaan
    public function count_pengadaan()
    {
        return $this->db->count_all('tb_pengadaan');
    }

    // Hitung Pengadaan Pending
    public function count_pengadaan_pending()
    {
        return $this->db->where('status_approval', 'Pending')
            ->count_all_results('tb_pengadaan');
    }

    // Hitung Total Users
    public function count_user()
    {
        return $this->db->count_all('tb_users');
    }

    public function count_peminjaman_pending()
    {
        // Sesuaikan nama tabel 'peminjaman' dan status 'pending' dengan database kamu
        $this->db->where('status', 'pending');
        return $this->db->count_all_results('peminjaman');
    }
}
