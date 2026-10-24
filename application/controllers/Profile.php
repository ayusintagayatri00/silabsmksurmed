<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property CI_Session $session
 * @property CI_Input $input
 * @property CI_Form_validation $form_validation
 * @property CI_Upload $upload
 * @property M_Auth $M_Auth
 */
class Profile extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('logged_in')) {
            redirect('auth');
        }
        $this->load->model('M_Auth');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $data['title'] = 'Profil Saya';
        $id_user = $this->session->userdata('id_user');
        $data['user'] = $this->M_Auth->get_user_by_id($id_user);

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('profile/v_index', $data);
        $this->load->view('templates/footer');
    }

    public function update_profile()
    {
        $id_user = $this->session->userdata('id_user');

        $this->form_validation->set_rules('nama_lengkap', 'Nama Lengkap', 'required|trim');
        $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email');

        if ($this->form_validation->run() == FALSE) {
            $this->index();
        } else {
            $update_data = [
                'nama_lengkap' => $this->input->post('nama_lengkap', TRUE),
                'email'        => $this->input->post('email', TRUE)
            ];

            // Cek Jika Ada File Foto yang Diunggah
            if (!empty($_FILES['foto']['name'])) {
                $config['upload_path']   = './uploads/profile/';
                $config['allowed_types'] = 'jpg|jpeg|png';
                $config['max_size']      = 2048; // Max 2MB
                $config['file_name']     = 'user_' . $id_user . '_' . time();

                // Buat folder jika belum ada
                if (!is_dir($config['upload_path'])) {
                    mkdir($config['upload_path'], 0777, true);
                }

                $this->load->library('upload', $config);

                if ($this->upload->do_upload('foto')) {
                    // Hapus foto lama jika ada
                    $user_old = $this->M_Auth->get_user_by_id($id_user);
                    if (!empty($user_old->foto) && file_exists('./uploads/profile/' . $user_old->foto)) {
                        unlink('./uploads/profile/' . $user_old->foto);
                    }

                    $upload_data = $this->upload->data();
                    $update_data['foto'] = $upload_data['file_name'];

                    // Update photo in session
                    $this->session->set_userdata('foto', $upload_data['file_name']);
                } else {
                    $this->session->set_flashdata('error', $this->upload->display_errors('', ''));
                    redirect('profile');
                    return;
                }
            }

            $this->M_Auth->update_user($id_user, $update_data);
            $this->session->set_userdata('nama_lengkap', $update_data['nama_lengkap']);
            $this->session->set_flashdata('success', 'Profil & Foto berhasil diperbarui!');
            redirect('profile');
        }
    }

    public function delete_photo()
    {
        $id_user = $this->session->userdata('id_user');
        $user = $this->M_Auth->get_user_by_id($id_user);

        if (!empty($user->foto) && file_exists('./uploads/profile/' . $user->foto)) {
            unlink('./uploads/profile/' . $user->foto);
        }

        $this->M_Auth->update_user($id_user, ['foto' => NULL]);
        $this->session->unset_userdata('foto');
        $this->session->set_flashdata('success', 'Foto profil berhasil dihapus!');
        redirect('profile');
    }

    public function change_password()
    {
        $id_user = $this->session->userdata('id_user');

        $this->form_validation->set_rules('password_lama', 'Password Lama', 'required');
        $this->form_validation->set_rules('password_baru', 'Password Baru', 'required|min_length[6]');
        $this->form_validation->set_rules('konfirmasi_password', 'Konfirmasi Password', 'required|matches[password_baru]');

        if ($this->form_validation->run() == FALSE) {
            $this->index();
        } else {
            $user = $this->M_Auth->get_user_by_id($id_user);
            $pass_lama = $this->input->post('password_lama');
            $pass_baru = $this->input->post('password_baru');

            if (password_verify($pass_lama, $user->password) || $pass_lama === $user->password) {
                $hash_password = password_hash($pass_baru, PASSWORD_BCRYPT);
                $this->M_Auth->update_user($id_user, ['password' => $hash_password]);

                $this->session->set_flashdata('success', 'Password berhasil diubah!');
            } else {
                $this->session->set_flashdata('error', 'Password lama Anda salah!');
            }
            redirect('profile');
        }
    }
}
