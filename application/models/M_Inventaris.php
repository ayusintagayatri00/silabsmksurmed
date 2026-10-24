<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_Inventaris extends CI_Model
{

    // Ambil seluruh data inventaris
    public function get_all()
    {
        $this->db->order_by('id_barang', 'DESC');
        return $this->db->get('tb_inventaris')->result_array();
    }

    // Ambil data inventaris berdasarkan ID
    public function get_by_id($id)
    {
        return $this->db->get_where('tb_inventaris', ['id_barang' => $id])->row_array();
    }

    // Tambah data inventaris baru
    public function insert($data)
    {
        return $this->db->insert('tb_inventaris', $data);
    }

    // Update data inventaris
    public function update($id, $data)
    {
        $this->db->where('id_barang', $id);
        return $this->db->update('tb_inventaris', $data);
    }

    // Hapus data inventaris
    public function delete($id)
    {
        $this->db->where('id_barang', $id);
        return $this->db->delete('tb_inventaris');
    }

    // 1. Ambil semua data inventaris
    public function get_new_all()
    {
        return $this->db->get('tb_inventaris')->result();
    }

    // 2. Hitung total seluruh item inventaris (Method yang hilang)
    public function get_total_items()
    {
        return $this->db->count_all_results('tb_inventaris');
    }

    // 3. Hitung jumlah item berdasarkan kategori (misal: 'Bahan Habis Pakai' atau 'Alat Medis / Instrumen')
    public function count_by_kategori($kategori)
    {
        $this->db->where('kategori', $kategori);
        return $this->db->count_all_results('tb_inventaris');
    }
}
