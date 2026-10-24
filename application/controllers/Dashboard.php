<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property CI_Session $session
 * @property M_Inventaris $M_Inventaris
 * @property M_Peminjaman $M_Peminjaman
 * @property M_Peminjaman $M_Dashboard
 */
class Dashboard extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('logged_in')) {
            redirect('auth');
        }
        $this->load->model('M_Inventaris');
        $this->load->model('M_Peminjaman');
        $this->load->model('M_Dashboard');
    }

    public function index()
    {
        $data['title']   = 'Dashboard SIM-Lab Keperawatan';
        $role            = $this->session->userdata('role');
        $id_user         = $this->session->userdata('id_user');

        // Stats Umum (Diakses oleh Admin, Laboran, & Kepsek)
        $data['total_items']    = $this->M_Inventaris->get_total_items();
        $data['count_alat']     = $this->M_Inventaris->count_by_kategori('Alat Medis / Instrumen');
        $data['count_bhp']      = $this->M_Inventaris->count_by_kategori('Bahan Habis Pakai');
        $data['count_dipinjam'] = $this->M_Peminjaman->get_active_loans();
        $data['count_pending']  = $this->M_Peminjaman->get_pending_loans();

        // Stats Spesifik Per Role
        if ($role === 'siswa' || $role === 'guru') {
            // Khusus Siswa & Guru: Hitung peminjaman milik dirinya sendiri
            $data['my_loans_active']  = $this->M_Peminjaman->count_user_loans($id_user, 'Dipinjam');
            $data['my_loans_pending'] = $this->M_Peminjaman->count_user_loans($id_user, 'Pending');
            $data['my_loans_total']   = $this->M_Peminjaman->count_user_loans_total($id_user);
        }

        // Load Tampilan Dashboard
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('dashboard/index', $data);
        $this->load->view('templates/footer');
    }
}
