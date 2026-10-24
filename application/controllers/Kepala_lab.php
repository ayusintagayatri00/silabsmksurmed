<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Kepala_lab extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        // Keamanan: Cek apakah user sudah login dan ber-role Kepala Lab / Admin
        $role = $this->session->userdata('role');
        if (!in_array($role, ['laboran', 'admin', 'kepsek'])) {
            $this->session->set_flashdata('error', 'Akses terbatas! Halaman ini khusus Kepala Lab.');
            redirect(site_url('pengadaan'));
        }
    }

    // Dashboard Pengetujuan Kepala Lab
    public function index()
    {
        $data['title'] = 'Persetujuan Pengadaan - Kepala Lab';

        // Hitung Ringkasan
        $data['total_pengajuan'] = $this->db->count_all('tb_pengadaan');
        $data['total_pending']   = $this->db->where('status_approval', 'Pending')->count_all_results('tb_pengadaan');
        $data['total_disetujui'] = $this->db->where('status_approval', 'Disetujui')->count_all_results('tb_pengadaan');
        $data['total_ditolak']   = $this->db->where('status_approval', 'Ditolak')->count_all_results('tb_pengadaan');

        // Total Estimasi Biaya yang Disetujui
        $this->db->select_sum('estimasi_harga');
        $this->db->where('status_approval', 'Disetujui');
        $query_biaya = $this->db->get('tb_pengadaan')->row();
        $data['total_biaya_disetujui'] = $query_biaya->estimasi_harga ?? 0;

        // Ambil Semua Daftar Pengadaan
        $this->db->from('tb_pengadaan');
        $this->db->order_by('id_pengadaan', 'DESC');
        $data['pengadaan'] = $this->db->get()->result_array();

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('kepala_lab/index', $data);
        $this->load->view('templates/footer');
    }

    // Aksi Cepat Persetujuan (Approve / Reject dari Tabel)
    public function persetujuan_cepat($id, $status)
    {
        $status_valid = ['Disetujui', 'Ditolak', 'Pending'];

        if (in_array($status, $status_valid)) {
            $this->db->where('id_pengadaan', $id);
            $this->db->update('tb_pengadaan', [
                'status_approval' => $status
            ]);
            $this->session->set_flashdata('success', 'Status pengadaan berhasil diubah menjadi: ' . $status);
        } else {
            $this->session->set_flashdata('error', 'Status tidak valid!');
        }

        redirect(site_url('kepala_lab'));
    }
}
