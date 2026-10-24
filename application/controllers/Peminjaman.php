<?php
defined('BASEPATH') or exit('No direct script access allowed');
/**
 * @property CI_Session $session
 * @property M_Inventaris $M_Inventaris
 * @property M_Peminjaman $M_Peminjaman
 * @property CI_Session $input
 * @property CI_Session $db
 */
class Peminjaman extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('logged_in')) {
            redirect('auth');
        }
        $this->load->model('M_Peminjaman');
        $this->load->model('M_Inventaris');
        $this->load->model('M_Auth');
    }

    public function index()
    {
        $data['title'] = 'Peminjaman Alat';

        // 1. Load Library Pagination
        $this->load->library('pagination');

        $role_user = strtolower($this->session->userdata('role') ?? '');
        $id_user   = $this->session->userdata('id_user');

        // 2. Ambil parameter limit baris (default 10) & keyword pencarian
        $per_page = (int)$this->input->get('limit', TRUE);
        if (!in_array($per_page, [5, 10, 25, 50])) {
            $per_page = 10;
        }

        $keyword = trim($this->input->get('q', TRUE) ?? '');

        // 3. Hitung total data berdasarkan filter role & pencarian
        $this->db->from('tb_peminjaman p');
        $this->db->join('tb_users u', 'u.id_user = p.id_user', 'left');
        $this->db->join('tb_inventaris b', 'b.id_barang = p.id_barang', 'left');

        if (in_array($role_user, ['guru', 'siswa'])) {
            $this->db->where('p.id_user', $id_user);
        }

        if (!empty($keyword)) {
            $this->db->group_start();
            $this->db->like('p.kode_peminjaman', $keyword);
            $this->db->or_like('u.nama_lengkap', $keyword);
            $this->db->or_like('b.nama_barang', $keyword);
            $this->db->or_like('p.status', $keyword);
            $this->db->group_end();
        }

        $total_rows = $this->db->count_all_results();

        // 4. Konfigurasi Pagination
        $config['base_url']             = base_url('peminjaman/index');
        $config['total_rows']           = $total_rows;
        $config['per_page']             = $per_page;
        $config['page_query_string']    = TRUE;
        $config['query_string_segment'] = 'page';
        $config['reuse_query_string']   = TRUE; // Menjaga parameter 'q' dan 'limit' di URL

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

        // 5. Ambil Data Peminjaman dengan Offset & Pencarian
        $page   = (int) $this->input->get('page');
        $offset = ($page > 0) ? $page : 0;

        $this->db->select('p.*, u.nama_lengkap AS peminjam, u.role, b.nama_barang, b.kode_barcode');
        $this->db->from('tb_peminjaman p');
        $this->db->join('tb_users u', 'u.id_user = p.id_user', 'left');
        $this->db->join('tb_inventaris b', 'b.id_barang = p.id_barang', 'left');

        if (in_array($role_user, ['guru', 'siswa'])) {
            $this->db->where('p.id_user', $id_user);
        }

        if (!empty($keyword)) {
            $this->db->group_start();
            $this->db->like('p.kode_peminjaman', $keyword);
            $this->db->or_like('u.nama_lengkap', $keyword);
            $this->db->or_like('b.nama_barang', $keyword);
            $this->db->or_like('p.status', $keyword);
            $this->db->group_end();
        }

        $this->db->order_by('p.id_peminjaman', 'DESC');
        $this->db->limit($per_page, $offset);
        $data['peminjaman'] = $this->db->get()->result_array();

        // 6. Data pendukung ke View
        $data['pagination'] = $this->pagination->create_links();
        $data['total_rows'] = $total_rows;
        $data['offset']     = $offset;
        $data['per_page']   = $per_page;
        $data['keyword']    = $keyword;

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('peminjaman/index', $data);
        $this->load->view('templates/footer');
    }

    public function process_loan()
    {
        $id_barang = $this->input->post('id_barang', TRUE);
        $qty = $this->input->post('qty', TRUE);

        $data_peminjaman = array(
            'kode_transaksi' => 'TRX-' . rand(100, 999),
            'id_user'        => $this->session->userdata('id_user'),
            'id_barang'      => $id_barang,
            'qty'            => $qty,
            'tgl_pinjam'     => date('Y-m-d H:i:s'),
            'status'         => 'DIPINJAM'
        );

        $this->db->trans_start();
        $this->M_Peminjaman->insert($data_peminjaman);
        $this->M_Inventaris->update_stok($id_barang, $qty, 'reduce');
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            $this->session->set_flashdata('error', 'Gagal mencatat peminjaman.');
        } else {
            $this->session->set_flashdata('success', 'Transaksi peminjaman berhasil dicatat!');
        }
        redirect('peminjaman');
    }

    public function return_item($id_peminjaman)
    {
        $loan = $this->M_Peminjaman->get_by_id($id_peminjaman);

        if ($loan && $loan->status === 'DIPINJAM') {
            $this->db->trans_start();
            $this->M_Peminjaman->update_status($id_peminjaman, 'DIKEMBALIKAN', date('Y-m-d H:i:s'));
            $this->M_Inventaris->update_stok($loan->id_barang, $loan->qty, 'add');
            $this->db->trans_complete();

            $this->session->set_flashdata('success', 'Alat berhasil dikembalikan!');
        }
        redirect('peminjaman');
    }


    // Menampilkan Form Tambah Peminjaman
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

        $data['title'] = 'Pinjam Alat Baru';

        // Generate Kode Peminjaman Otomatis (Format: PJM-YYYYMMDD-001)
        $today = date('Ymd');
        $this->db->like('kode_peminjaman', 'PJM-' . $today, 'after');
        $last_code = $this->db->get('tb_peminjaman')->num_rows();
        $next_no = sprintf('%03d', $last_code + 1);
        $data['kode_peminjaman'] = 'PJM-' . $today . '-' . $next_no;

        // Ambil data barang yang stoknya > 0
        $this->db->where('stok >', 0);
        $data['barang'] = $this->db->get('tb_inventaris')->result_array();

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('peminjaman/tambah', $data);
        $this->load->view('templates/footer');
    }

    // Proses Simpan Peminjaman
    public function simpan()
    {
        // Ambil data dari form input
        $data = [
            'kode_peminjaman'       => $this->input->post('kode_peminjaman'),
            'id_user'               => $this->session->userdata('id_user'), // ID akun yang login
            'id_barang'             => $this->input->post('id_barang'),
            'jumlah_pinjam'         => $this->input->post('jumlah_pinjam') ?? 1,
            'tgl_pinjam'            => $this->input->post('tgl_pinjam') ?: date('Y-m-d H:i:s'),
            'tgl_kembali_rencana'   => $this->input->post('tgl_kembali_rencana'),

            // PASTIKAN NILAI INI ADALAH 'Pending':
            'status'                => 'Pending',

            'catatan'               => $this->input->post('catatan'),
            'created_at'            => date('Y-m-d H:i:s')
        ];

        $this->db->insert('tb_peminjaman', $data);

        $this->session->set_flashdata('success', 'Pengajuan peminjaman berhasil dibuat, menunggu persetujuan Laboran.');
        redirect('peminjaman');
    }

    // Menampilkan Detail Peminjaman
    public function detail($id)
    {

        $data['title'] = 'Detail Peminjaman Alat';

        $this->db->select('tb_peminjaman.*, tb_inventaris.nama_barang, tb_inventaris.kode_barcode, tb_inventaris.satuan, tb_inventaris.kategori, tb_inventaris.lokasi_penyimpanan');
        $this->db->from('tb_peminjaman');
        $this->db->join('tb_inventaris', 'tb_inventaris.id_barang = tb_peminjaman.id_barang', 'left');
        $this->db->where('tb_peminjaman.id_peminjaman', $id);
        $data['peminjaman'] = $this->db->get()->row_array();

        if (!$data['peminjaman']) {
            $this->session->set_flashdata('error', 'Data peminjaman tidak ditemukan!');
            redirect('peminjaman');
        }

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('peminjaman/detail', $data);
        $this->load->view('templates/footer');
    }

    // Menampilkan Form Edit Peminjaman
    public function edit($id)
    {
        $data['title'] = 'Edit Peminjaman Alat';

        // Ambil data peminjaman berdasarkan ID
        $data['peminjaman'] = $this->db->get_where('tb_peminjaman', ['id_peminjaman' => $id])->row_array();

        if (!$data['peminjaman']) {
            $this->session->set_flashdata('error', 'Data peminjaman tidak ditemukan!');
            redirect('peminjaman');
        }

        // Ambil semua daftar barang
        $data['barang'] = $this->db->get('tb_inventaris')->result_array();

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('peminjaman/edit', $data);
        $this->load->view('templates/footer');
    }

    // Proses Simpan Update Peminjaman
    public function update()
    {
        $id_peminjaman = $this->input->post('id_peminjaman', true);
        $id_barang_lama = $this->input->post('id_barang_lama', true);
        $jumlah_lama = (int)$this->input->post('jumlah_lama', true);

        $id_barang_baru = $this->input->post('id_barang', true);
        $jumlah_baru = (int)$this->input->post('jumlah_pinjam', true);
        $status_baru = $this->input->post('status', true);

        // Kembalikan dulu stok barang lama
        $barang_lama = $this->db->get_where('tb_inventaris', ['id_barang' => $id_barang_lama])->row_array();
        if ($barang_lama) {
            $this->db->where('id_barang', $id_barang_lama);
            $this->db->update('tb_inventaris', ['stok' => $barang_lama['stok'] + $jumlah_lama]);
        }

        // Ambil data barang baru & potong stok jika status masih 'Dipinjam'
        $barang_baru = $this->db->get_where('tb_inventaris', ['id_barang' => $id_barang_baru])->row_array();

        if ($status_baru == 'Dipinjam') {
            if ($barang_baru && $barang_baru['stok'] >= $jumlah_baru) {
                $this->db->where('id_barang', $id_barang_baru);
                $this->db->update('tb_inventaris', ['stok' => $barang_baru['stok'] - $jumlah_baru]);
            } else {
                $this->session->set_flashdata('error', 'Gagal update! Stok barang tidak mencukupi.');
                redirect('peminjaman/edit/' . $id_peminjaman);
                return;
            }
        }

        // Update data transaksi
        $data_update = [
            'id_barang'             => $id_barang_baru,
            'jumlah_pinjam'         => $jumlah_baru,
            'tgl_pinjam'            => $this->input->post('tgl_pinjam', true),
            'tgl_kembali_rencana'   => $this->input->post('tgl_kembali_rencana', true),
            'tgl_kembali_realisasi' => ($status_baru == 'Dikembalikan') ? date('Y-m-d H:i:s') : null,
            'status'                => $status_baru,
            'catatan'               => $this->input->post('catatan', true),
            'updated_at'            => date('Y-m-d H:i:s')
        ];

        $this->db->where('id_peminjaman', $id_peminjaman);
        $this->db->update('tb_peminjaman', $data_update);

        $this->session->set_flashdata('success', 'Data peminjaman berhasil diperbarui!');
        redirect('peminjaman');
    }

    // =======================================================
    // METHOD BARU: EXPORT DATA PEMINJAMAN ALAT KE EXCEL
    // =======================================================
    public function export_excel()
    {
        // Proteksi Hak Akses: Guru dan Siswa dilarang Export Excel
        $role_akses = $this->session->userdata('role');
        if (in_array($role_akses, ['guru', 'siswa'])) {
            $this->session->set_flashdata('error', 'Akses ditolak! Anda tidak memiliki hak untuk mengekspor data laporan.');
            redirect('peminjaman');
        }

        $data['peminjaman'] = $this->M_Peminjaman->get_all_with_relation();
        $data['tgl_cetak']  = date('d-m-Y H:i:s');

        $laboran = $this->M_Auth->get_laboran();
        if ($laboran) {
            $data['nama_laboran'] = $laboran->nama_lengkap;
            $data['nip_laboran']  = $laboran->nip_nik_user;
        } else {
            $data['nama_laboran'] = $this->session->userdata('nama_lengkap');
            $data['nip_laboran']  = $this->session->userdata('nip_nik_user');
        }

        $filename = "Laporan_Peminjaman_Alat_SIMLAB_" . date('Ymd_His') . ".xls";

        header("Content-Type: application/vnd.ms-excel; charset=utf-8");
        header("Content-Disposition: attachment; filename=\"$filename\"");
        header("Cache-Control: max-age=0");
        header("Pragma: no-cache");

        $this->load->view('peminjaman/v_export_excel', $data);
    }

    // =========================== APPROVAL PEMINJAMAN ====================================
    // Method untuk menangani aksi approval dari Laboran/Admin
    public function update_status($id_peminjaman, $status_tujuan)
    {
        // Proteksi: Hanya Admin dan Laboran yang berhak melakukan approval
        $role = strtolower($this->session->userdata('role'));
        if (!in_array($role, ['admin', 'laboran'])) {
            $this->session->set_flashdata('error', 'Akses ditolak! Anda tidak memiliki wewenang approval.');
            redirect('peminjaman');
        }

        // Validasi status yang diizinkan
        $status_valid = ['Dipinjam', 'Ditolak', 'Dikembalikan'];
        $status_decode = urldecode($status_tujuan);

        if (!in_array($status_decode, $status_valid)) {
            $this->session->set_flashdata('error', 'Status approval tidak valid.');
            redirect('peminjaman');
        }

        // Eksekusi perubahan status dan stok
        $hasil = $this->M_Peminjaman->proses_approval($id_peminjaman, $status_decode);

        if ($hasil === 'stok_habis') {
            $this->session->set_flashdata('error', 'Gagal menyetujui! Stok alat medis tidak mencukupi.');
        } elseif ($hasil) {
            $this->session->set_flashdata('success', 'Status peminjaman berhasil diperbarui menjadi ' . $status_decode . '.');
        } else {
            $this->session->set_flashdata('error', 'Terjadi kesalahan sistem saat memperbarui status.');
        }

        redirect('peminjaman');
    }
    //==================================================================================

    // =======================================================
    // METHOD HAPUS DATA PEMINJAMAN ALAT
    // =======================================================
    public function delete($id_peminjaman)
    {
        // 1. Proteksi Hak Akses: Hanya Admin dan Laboran yang boleh menghapus riwayat
        $role = strtolower($this->session->userdata('role'));
        if (!in_array($role, ['admin', 'laboran'])) {
            $this->session->set_flashdata('error', 'Akses ditolak! Anda tidak memiliki izin untuk menghapus data peminjaman.');
            redirect('peminjaman');
        }

        // 2. Ambil data peminjaman yang ingin dihapus
        $peminjaman = $this->db->get_where('tb_peminjaman', ['id_peminjaman' => $id_peminjaman])->row();
        if (!$peminjaman) {
            $this->session->set_flashdata('error', 'Data peminjaman tidak ditemukan.');
            redirect('peminjaman');
        }

        // 3. Sinkronisasi Stok: Jika statusnya masih 'Dipinjam', kembalikan stok alat ke inventaris
        if ($peminjaman->status === 'Dipinjam') {
            $jumlah = (int) ($peminjaman->jumlah_pinjam ?? 1);
            $this->db->set('stok', 'stok + ' . $jumlah, FALSE);
            $this->db->where('id_barang', $peminjaman->id_barang);
            $this->db->update('tb_inventaris');
        }

        // 4. Eksekusi Hapus dari tabel tb_peminjaman
        $this->db->where('id_peminjaman', $id_peminjaman);
        $hapus = $this->db->delete('tb_peminjaman');

        if ($hapus) {
            $this->session->set_flashdata('success', 'Data transaksi peminjaman berhasil dihapus.');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus data transaksi peminjaman.');
        }

        redirect('peminjaman');
    }
}
