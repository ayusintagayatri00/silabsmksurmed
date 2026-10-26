<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pengadaan extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('logged_in')) {
            redirect('auth');
        }

        // PASTI KAN KEDUA MODEL INI DI-LOAD DI SINI
        $this->load->model('M_Pengadaan');
        $this->load->model('M_Auth');
    }
    // Tampilan Utama / Daftar Pengadaan
    public function index()
    {
        $data['title'] = 'Pengadaan Alat & BHP';

        // 1. Load Library Pagination
        $this->load->library('pagination');

        $role_user = strtolower($this->session->userdata('role') ?? '');
        $id_user   = $this->session->userdata('id_user');

        // 2. Limit & Keyword Search
        $per_page = (int) $this->input->get('limit', TRUE);
        if (!in_array($per_page, [5, 10, 25, 50])) {
            $per_page = 10;
        }

        $keyword = trim($this->input->get('q', TRUE) ?? '');

        // 3. Hitung Total Data dengan JOIN ke tb_users via id_pengaju
        $this->db->from('tb_pengadaan p');
        $this->db->join('tb_users u', 'u.id_user = p.id_pengaju', 'left');

        if ($role_user === 'siswa') {
            $this->db->where('p.id_pengaju', $id_user);
        }

        if (!empty($keyword)) {
            $this->db->group_start();
            $this->db->like('p.nama_item', $keyword);
            $this->db->or_like('u.nama_lengkap', $keyword);
            $this->db->or_like('p.status_approval', $keyword);
            $this->db->group_end();
        }

        $total_rows = $this->db->count_all_results();

        // 4. Konfigurasi Pagination CI3
        $config['base_url']             = base_url('pengadaan/index');
        $config['total_rows']           = $total_rows;
        $config['per_page']             = $per_page;
        $config['page_query_string']    = TRUE;
        $config['query_string_segment'] = 'page';
        $config['reuse_query_string']   = TRUE;

        // Styling Pagination Tailwind CSS
        $config['full_tag_open']   = '<ul class="inline-flex items-center gap-1 text-xs">';
        $config['full_tag_close']  = '</ul>';

        $config['first_link']      = '&laquo;';
        $config['first_tag_open']  = '<li>';
        $config['first_tag_close'] = '</li>';

        $config['last_link']       = '&raquo;';
        $config['last_tag_open']   = '<li>';
        $config['last_tag_close']  = '</li>';

        $config['next_link']       = '<i class="fa-solid fa-chevron-right text-[10px]"></i>';
        $config['next_tag_open']   = '<li>';
        $config['next_tag_close']  = '</li>';

        $config['prev_link']       = '<i class="fa-solid fa-chevron-left text-[10px]"></i>';
        $config['prev_tag_open']   = '<li>';
        $config['prev_tag_close']  = '</li>';

        $config['cur_tag_open']    = '<li><span class="flex items-center justify-center px-3 h-8 text-white bg-teal-600 rounded-lg font-semibold shadow-sm">';
        $config['cur_tag_close']   = '</span></li>';

        $config['num_tag_open']    = '<li>';
        $config['num_tag_close']   = '</li>';

        $config['attributes']      = ['class' => 'flex items-center justify-center px-3 h-8 text-slate-600 bg-white border border-slate-200 rounded-lg hover:bg-slate-50 transition'];

        $this->pagination->initialize($config);

        // 5. Query Data Utama dengan Limit & Offset
        $page   = (int) $this->input->get('page');
        $offset = ($page > 0) ? $page : 0;

        $this->db->select('p.*, u.nama_lengkap AS nama_pengaju');
        $this->db->from('tb_pengadaan p');
        $this->db->join('tb_users u', 'u.id_user = p.id_pengaju', 'left');

        if ($role_user === 'siswa') {
            $this->db->where('p.id_pengaju', $id_user);
        }

        if (!empty($keyword)) {
            $this->db->group_start();
            $this->db->like('p.nama_item', $keyword);
            $this->db->or_like('u.nama_lengkap', $keyword);
            $this->db->or_like('p.status_approval', $keyword);
            $this->db->group_end();
        }

        $this->db->order_by('p.id_pengadaan', 'DESC');
        $this->db->limit($per_page, $offset);
        $data['pengadaan'] = $this->db->get()->result();

        // 6. Data Parsing ke View
        $data['pagination'] = $this->pagination->create_links();
        $data['total_rows'] = $total_rows;
        $data['offset']     = $offset;
        $data['per_page']   = $per_page;
        $data['keyword']    = $keyword;

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('pengadaan/index', $data);
        $this->load->view('templates/footer');
    }

    // Form Tambah Pengajuan
    public function tambah()
    {
        // Cek session login terlebih dahulu
        if (!$this->session->userdata('id_user')) {
            redirect('auth');
        }

        $role_user = strtolower($this->session->userdata('role') ?? '');

        // Blokir akses jika role Kepsek atau Siswa
        if (in_array($role_user, ['kepsek', 'siswa'])) {
            $this->session->set_flashdata('error', 'Akses ditolak! Role Anda tidak memiliki wewenang untuk mengajukan pengadaan.');
            redirect('pengadaan');
            return;
        }

        $data['title'] = 'Form Pengajuan Pengadaan Alat / BHP';


        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('pengadaan/tambah', $data);
        $this->load->view('templates/footer');
    }

    // Proses Simpan
    public function simpan()
    {
        $data_insert = [
            'nama_item'        => $this->input->post('nama_item', true),
            'jumlah'           => (int)$this->input->post('jumlah', true),
            'satuan'           => $this->input->post('satuan', true),
            'estimasi_harga'   => (float)$this->input->post('estimasi_harga', true),
            'alasan_pengadaan' => $this->input->post('alasan_pengadaan', true),
            'id_pengaju'       => $this->session->userdata('id_user') ?? 1,
            'status_approval'  => 'Pending',
            'created_at'       => date('Y-m-d H:i:s')
        ];

        $this->db->insert('tb_pengadaan', $data_insert);
        $this->session->set_flashdata('success', 'Usulan pengadaan berhasil diajukan!');
        redirect('pengadaan');
    }

    // Tampilan Detail
    public function detail($id)
    {
        $data['title'] = 'Detail Pengadaan Alat & BHP';
        // fix query untuk menampilkan detail pengadaan beserta nama pengaju dari tabel tb_users
        $this->db->select('tb_pengadaan.*, tb_users.nama_lengkap as nama_pengaju');
        $this->db->from('tb_pengadaan');
        $this->db->join('tb_users', 'tb_users.id_user = tb_pengadaan.id_pengaju', 'left');
        $this->db->where('tb_pengadaan.id_pengadaan', $id);
        $data['pengadaan'] = $this->db->get('tb_pengadaan')->row_array();

        if (!$data['pengadaan']) {
            $this->session->set_flashdata('error', 'Data pengadaan tidak ditemukan!');
            redirect('pengadaan');
        }

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('pengadaan/detail', $data);
        $this->load->view('templates/footer');
    }

    // Update Status Approval
    public function update_status()
    {
        // Keamanan: Cek apakah role yang login berhak melakukan approval
        $role_user = $this->session->userdata('role');
        if (!in_array($role_user, ['admin', 'laboran', 'kepsek'])) {
            $this->session->set_flashdata('error', 'Anda tidak memiliki hak akses untuk menyetujui pengajuan ini!');
            redirect(site_url('pengadaan'));
            return;
        }

        $id_pengadaan    = $this->input->post('id_pengadaan', true);
        $status_approval = $this->input->post('status_approval', true);

        if ($id_pengadaan) {
            $this->db->where('id_pengadaan', $id_pengadaan);
            $this->db->update('tb_pengadaan', [
                'status_approval' => $status_approval
            ]);

            $this->session->set_flashdata('success', 'Status persetujuan berhasil diperbarui!');
        }

        redirect(site_url('pengadaan/detail/' . $id_pengadaan));
    }

    // Hapus
    public function hapus($id)
    {
        $this->db->where('id_pengadaan', $id);
        $this->db->delete('tb_pengadaan');

        $this->session->set_flashdata('success', 'Data pengadaan berhasil dihapus!');
        redirect('pengadaan');
    }

    // =======================================================
    // METHOD EXPORT DATA PENGADAAN ALAT/BHP KE EXCEL
    // =======================================================
    public function export_excel()
    {
        // Proteksi Hak Akses: Guru dan Siswa dilarang Export Excel
        $role_akses = $this->session->userdata('role');
        if (in_array($role_akses, ['guru', 'siswa'])) {
            $this->session->set_flashdata('error', 'Akses ditolak! Anda tidak memiliki hak untuk mengekspor data laporan.');
            redirect('pengadaan');
        }

        $data['pengadaan'] = $this->M_Pengadaan->get_all_with_relations();
        $data['tgl_cetak'] = date('d-m-Y H:i:s');

        $laboran = $this->M_Auth->get_laboran();
        if ($laboran) {
            $data['nama_laboran'] = $laboran->nama_lengkap;
            $data['nip_laboran']  = $laboran->nip_nik_user;
        } else {
            $data['nama_laboran'] = $this->session->userdata('nama_lengkap');
            $data['nip_laboran']  = $this->session->userdata('nip_nik_user');
        }

        $filename = "Laporan_Pengadaan_Alat_BHP_SIMLAB_" . date('Ymd_His') . ".xls";

        header("Content-Type: application/vnd.ms-excel; charset=utf-8");
        header("Content-Disposition: attachment; filename=\"$filename\"");
        header("Cache-Control: max-age=0");
        header("Pragma: no-cache");

        $this->load->view('pengadaan/v_export_excel', $data);
    }
}
