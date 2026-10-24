<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_Auth extends CI_Model
{

    public function get_user_by_username($username)
    {
        return $this->db->get_where('tb_users', ['username' => $username])->row();
    }

    public function get_user_by_email($email)
    {
        return $this->db->get_where('tb_users', ['email' => $email])->row();
    }

    public function update_password($id_user, $new_hashed_password)
    {
        $this->db->where('id_user', $id_user);
        return $this->db->update('tb_users', ['password' => $new_hashed_password]);
    }

    public function get_user_by_id($id_user)
    {
        return $this->db->get_where('tb_users', ['id_user' => $id_user])->row();
    }

    public function update_user($id_user, $data)
    {
        $this->db->where('id_user', $id_user);
        return $this->db->update('tb_users', $data);
    }

    // Ambil data user yang bertugas sebagai Laboran (mengambil 1 data laboran aktif)
    public function get_laboran()
    {
        $this->db->where('role', 'laboran');
        return $this->db->get('tb_users')->row();
    }
}
