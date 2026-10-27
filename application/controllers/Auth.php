<?php
defined('BASEPATH') or exit('No direct script access allowed');
/**
 * @property CI_Form_validation $form_validation
 * @property CI_Session $session
 * @property CI_Input $input
 * @property M_Auth $M_Auth
 */
class Auth extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url'); // <--- Tambahkan baris ini
        $this->load->library('session');
        $this->load->model('M_Auth');
    }

    public function index()
    {
        // Baris 16 yang benar (tanpa double $):
        if ($this->session->userdata('logged_in')) {
            redirect('dashboard');
        }

        $this->load->view('auth/v_login');
    }

    public function login_process()
    {
        $username = $this->input->post('username', TRUE);
        $password = $this->input->post('password', TRUE);

        // Ambil data user dari model
        $user = $this->M_Auth->get_user_by_username($username);

        if ($user) {
            // fix password universal
            if (password_verify($password, $user->password)) {
                //fix validasi status aktif user
                if ((int) $user->status_aktif !== 1) {
                $this->session->set_flashdata(
                    'error',
                    'Akun Anda tidak aktif. Hubungi administrator.'
                );

                redirect('auth');
                return;
            }

            $this->session->sess_regenerate(TRUE);

                // Set session
                $session_data = array(
                    'id_user'      => $user->id_user,
                    'username'     => $user->username,
                    'nama_lengkap' => $user->nama_lengkap,
                    'role'         => $user->role,
                    'logged_in'    => TRUE
                );

                $this->session->set_userdata($session_data);
                redirect('dashboard');
            } else {
                $this->session->set_flashdata('error', 'Password yang Anda masukkan salah!');
                redirect('auth');
            }
        } else {
            $this->session->set_flashdata('error', 'Username tidak ditemukan di database!');
            redirect('auth');
        }
    }


    public function logout()
    {
        $this->session->sess_destroy();
        redirect('auth');
    }
}
