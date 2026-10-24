<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_User extends CI_Model
{

    // Ambil semua data user
    public function get_all_users()
    {
        return $this->db->get('tb_users')->result_array();
    }

    // Ambil data user berdasarkan ID
    public function get_user_by_id($id)
    {
        return $this->db->get_where('tb_users', ['id_user' => $id])->row_array();
    }

    // Tambah data user baru
    public function insert_user($data)
    {
        return $this->db->insert('tb_users', $data);
    }

    // Update data user
    public function update_user($id, $data)
    {
        $this->db->where('id_user', $id);
        return $this->db->update('tb_users', $data);
    }

    // Hapus data user
    public function delete_user($id)
    {
        $this->db->where('id_user', $id);
        return $this->db->delete('tb_users');
    }
}
