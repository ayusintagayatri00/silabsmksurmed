<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
		//fix hak akses user bocor
        if (!$this->session->userdata('logged_in')) {
            redirect('auth');
        }
        if ($this->session->userdata('role') !== 'admin') {
            show_error('Akses ditolak!', 403, 'Forbidden');
        }
        $this->load->model('M_User');
        $this->load->library('form_validation');
    }

    // Halaman utama daftar user
    public function index()
    {
        $data['title'] = 'Manajemen Data User';
        $data['users'] = $this->M_User->get_all_users();

        // Sesuaikan nama struktur template jika ada folder/file khusus (misal: templates/header & templates/sidebar)
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data); // Tambahkan jika sidebar terpisah
        $this->load->view('user/index', $data);
        $this->load->view('templates/footer');
    }

    // Proses Tambah User
    public function tambah()
    {
        $this->form_validation->set_rules('username', 'Username', 'required|trim|is_unique[tb_users.username]');
        $this->form_validation->set_rules('nama_lengkap', 'Nama Lengkap', 'required|trim');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[5]');
        $this->form_validation->set_rules('role', 'Role', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('user');
        } else {
            $data = [
                'username'     => $this->input->post('username', true),
                'nama_lengkap' => $this->input->post('nama_lengkap', true),
                'password'     => password_hash($this->input->post('password'), PASSWORD_DEFAULT), // Enkripsi password
                'role'         => $this->input->post('role', true),
                'created_at'   => date('Y-m-d H:i:s')
            ];

            $this->M_User->insert_user($data);
            $this->session->set_flashdata('success', 'Data user berhasil ditambahkan!');
            redirect('user');
        }
    }

    // Proses Edit User
    public function edit()
    {
        $id_user = $this->input->post('id_user');

        $data = [
            'username'     => $this->input->post('username', true),
            'nama_lengkap' => $this->input->post('nama_lengkap', true),
            'role'         => $this->input->post('role', true),
            'updated_at'   => date('Y-m-d H:i:s')
        ];

        // Ubah password hanya jika field password diisi
        if (!empty($this->input->post('password'))) {
            $data['password'] = password_hash($this->input->post('password'), PASSWORD_DEFAULT);
        }

        $this->M_User->update_user($id_user, $data);
        $this->session->set_flashdata('success', 'Data user berhasil diperbarui!');
        redirect('user');
    }

    // Proses Hapus User
    public function hapus($id)
    {
        if ($id) {
            $this->M_User->delete_user($id);
            $this->session->set_flashdata('success', 'Data user berhasil dihapus!');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus data.');
        }
        redirect('user');
    }

    public function export_excel()
    {
        if ($this->session->userdata('role') !== 'admin') {
            $this->session->set_flashdata('error', 'Akses ditolak!');
            redirect('user');
        }

        $this->load->model('M_Auth');

        $filter_role = $this->input->get('role', TRUE);

        // Ambil data user
        if (!empty($filter_role)) {
            $this->db->where('LOWER(role)', strtolower(trim($filter_role)));
        }

        $this->db->order_by('role', 'ASC');
        $this->db->order_by('nama_lengkap', 'ASC');
        $raw_users = $this->db->get('tb_users')->result();

        // Kelompokkan (Group By) data
        $grouped_users = [];
        foreach ($raw_users as $u) {
            $role_key = strtoupper($u->role);
            $grouped_users[$role_key][] = $u;
        }

        $data['grouped_users'] = $grouped_users;
        $data['total_users']   = count($raw_users);
        $data['filter_role']   = !empty($filter_role) ? strtoupper($filter_role) : 'SEMUA PERAN';
        $data['tgl_cetak']     = date('d-m-Y H:i:s');

        $role_suffix = !empty($filter_role) ? '_' . strtoupper($filter_role) : '_SEMUA';
        $filename    = "Laporan_Data_User" . $role_suffix . "_SIMLAB_" . date('Ymd_His') . ".xls";

        header("Content-Type: application/vnd.ms-excel; charset=utf-8");
        header("Content-Disposition: attachment; filename=\"$filename\"");
        header("Cache-Control: max-age=0");
        header("Pragma: no-cache");

        $this->load->view('user/v_export_excel', $data);
    }
}
