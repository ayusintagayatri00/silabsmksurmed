<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_Pengadaan extends CI_Model
{
    // Ambil seluruh data pengadaan beserta relasi user pengaju
    public function get_all_with_relations()
    {
        $this->db->select('p.*, u.nama_lengkap as pengaju, u.role');
        $this->db->from('tb_pengadaan p');
        $this->db->join('tb_users u', 'u.id_user = p.id_pengaju', 'left');
        $this->db->order_by('p.id_pengadaan', 'DESC');
        return $this->db->get()->result();
    }
}
