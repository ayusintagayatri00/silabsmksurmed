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

        //old  Generate Kode Peminjaman Otomatis (Format: PJM-YYYYMMDD-001) dihapus karena bug: kode unik per hari tidak dijamin, dan nomor urut tidak reset setiap hari
        // fix bug: pastikan kode unik per hari, dan reset nomor urut setiap hari
        $prefix = 'PJM-' . date('Ymd') . '-';

        $kode_hari_ini = $this->db
            ->select('kode_peminjaman')
            ->like('kode_peminjaman', $prefix, 'after')
            ->get('tb_peminjaman')
            ->result_array();

        $nomor_terakhir = 0;

        foreach ($kode_hari_ini as $baris) {
            $nomor = substr($baris['kode_peminjaman'], strlen($prefix));

            if (ctype_digit($nomor)) {
                $nomor_terakhir = max($nomor_terakhir, (int) $nomor);
            }
        }

        $data['kode_peminjaman'] = $prefix
            . sprintf('%03d', $nomor_terakhir + 1);

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
        //fix proteksi role agar hanya admin, laboran, guru yang bisa mengajukan peminjaman
        $this->load->library('form_validation');
        $this->form_validation->set_rules(
            'kode_peminjaman',
            'Kode Peminjaman',
            'required|trim|max_length[30]|is_unique[tb_peminjaman.kode_peminjaman]',
            [
                'is_unique' => 'Kode peminjaman sudah digunakan. Muat ulang formulir.'
            ]
        );

        $this->form_validation->set_rules(
            'id_barang',
            'Barang',
            'required|integer|greater_than[0]'
        );

        $this->form_validation->set_rules(
            'jumlah_pinjam',
            'Jumlah Pinjam',
            'required|integer|greater_than[0]',
            [
                'integer' => 'Jumlah pinjam harus berupa bilangan bulat.',
                'greater_than' => 'Jumlah pinjam minimal 1.'
            ]
        );

        $this->form_validation->set_rules(
            'tgl_pinjam',
            'Tanggal Pinjam',
            'required'
        );

        $this->form_validation->set_rules(
            'tgl_kembali_rencana',
            'Rencana Kembali',
            'required'
        );

        if (!$this->form_validation->run()) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('peminjaman');
            return;
        }

        $barang = $this->db->get_where(
            'tb_inventaris',
            ['id_barang' => (int) $this->input->post('id_barang')]
        )->row_array();

        if (!$barang) {
            $this->session->set_flashdata('error', 'Barang tidak ditemukan.');
            redirect('peminjaman');
            return;
        }
        // Ambil data dari form input
        $data = [
            'kode_peminjaman'       => trim((string)$this->input->post('kode_peminjaman')), //fix casting ke string
            'id_user'               => $this->session->userdata('id_user'), // ID akun yang login
            'id_barang'             => (int)$this->input->post('id_barang'), //fix casting ke integer
            'jumlah_pinjam'         => (int)$this->input->post('jumlah_pinjam') ?? 1, // fix casting ke integer, default 1 jika null
            'tgl_pinjam'            => $this->input->post('tgl_pinjam') ?: date('Y-m-d H:i:s'),
            'tgl_kembali_rencana'   => $this->input->post('tgl_kembali_rencana'),

            // PASTIKAN NILAI INI ADALAH 'Pending':
            'status'                => 'Pending',

            'catatan'               => $this->input->post('catatan'),
            'created_at'            => date('Y-m-d H:i:s')
        ];

        // insert data dan Cek apakah insert berhasil
        $db_debug_lama = $this->db->db_debug;
        $this->db->db_debug = false;

        try {
            $tersimpan = $this->db->insert('tb_peminjaman', $data);
            $error_db = $this->db->error();
        } finally {
            $this->db->db_debug = $db_debug_lama;
        }

        if (!$tersimpan) {
            $pesan = (int) $error_db['code'] === 1062
                ? 'Kode peminjaman sudah digunakan. Muat ulang formulir.'
                : 'Pengajuan gagal disimpan. Silakan coba lagi.';

            $this->session->set_flashdata('error', $pesan);
            redirect('peminjaman');
            return;
        }

        $this->session->set_flashdata(
            'success',
            'Pengajuan berhasil dibuat, menunggu persetujuan Laboran.'
        );
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

    // fix perhitungan stok pada edit Peminjaman
    public function update()
{
    if (!in_array($this->session->userdata('role'), ['admin', 'laboran'], true)) {
        show_error('Akses ditolak!', 403, 'Forbidden');
    }

    $this->load->library('form_validation');
    $this->form_validation->set_rules(
        'id_peminjaman', 'ID Peminjaman',
        'required|integer|greater_than[0]'
    );
    $this->form_validation->set_rules(
        'id_barang', 'Barang',
        'required|integer|greater_than[0]'
    );
    $this->form_validation->set_rules(
        'jumlah_pinjam', 'Jumlah Pinjam',
        'required|integer|greater_than[0]'
    );
    $this->form_validation->set_rules(
        'status', 'Status',
        'required|in_list[Pending,Disetujui,Ditolak,Dipinjam,Dikembalikan]'
    );
    $this->form_validation->set_rules(
        'tgl_pinjam', 'Tanggal Pinjam', 'required'
    );
    $this->form_validation->set_rules(
        'tgl_kembali_rencana', 'Rencana Kembali', 'required'
    );

    if (!$this->form_validation->run()) {
        $this->session->set_flashdata(
            'error', strip_tags(validation_errors())
        );
        redirect('peminjaman');
    }

    $id = (int) $this->input->post('id_peminjaman');
    $id_barang_baru = (int) $this->input->post('id_barang');
    $jumlah_baru = (int) $this->input->post('jumlah_pinjam');
    $status_baru = $this->input->post('status');

    $lama = $this->db->get_where(
        'tb_peminjaman', ['id_peminjaman' => $id]
    )->row_array();

    if (!$lama || (int) $lama['jumlah_pinjam'] <= 0) {
        $this->session->set_flashdata(
            'error', 'Data peminjaman tidak ditemukan atau tidak valid.'
        );
        redirect('peminjaman');
    }

    $transisi = [
        'Pending' => ['Pending', 'Disetujui', 'Ditolak', 'Dipinjam'],
        'Disetujui' => ['Disetujui', 'Ditolak', 'Dipinjam'],
        'Dipinjam' => ['Dipinjam', 'Dikembalikan'],
        'Ditolak' => ['Ditolak'],
        'Dikembalikan' => ['Dikembalikan']
    ];

    $status_lama = $lama['status'];
    $id_barang_lama = (int) $lama['id_barang'];
    $jumlah_lama = (int) $lama['jumlah_pinjam'];

    if (!in_array($status_baru, $transisi[$status_lama] ?? [], true)) {
        $this->session->set_flashdata(
            'error', 'Perubahan status tidak diperbolehkan.'
        );
        redirect('peminjaman');
    }

    $barang_atau_jumlah_berubah =
        $id_barang_baru !== $id_barang_lama ||
        $jumlah_baru !== $jumlah_lama;

    if (
        $barang_atau_jumlah_berubah &&
        (
            in_array($status_lama, ['Ditolak', 'Dikembalikan'], true) ||
            $status_baru === 'Dikembalikan'
        )
    ) {
        $this->session->set_flashdata(
            'error',
            'Barang dan jumlah tidak boleh diubah saat pengembalian '
            . 'atau pada transaksi yang sudah selesai.'
        );
        redirect('peminjaman');
    }

    $data_update = [
        'id_barang' => $id_barang_baru,
        'jumlah_pinjam' => $jumlah_baru,
        'tgl_pinjam' => $this->input->post('tgl_pinjam', true),
        'tgl_kembali_rencana' =>
            $this->input->post('tgl_kembali_rencana', true),
        'tgl_kembali_realisasi' =>
            $status_baru === 'Dikembalikan'
                ? ($lama['tgl_kembali_realisasi'] ?: date('Y-m-d H:i:s'))
                : null,
        'status' => $status_baru,
        'catatan' => $this->input->post('catatan', true),
        'updated_at' => date('Y-m-d H:i:s')
    ];

    $this->db->trans_begin();

    // Tolak penyimpanan jika keadaan stok transaksi sudah berubah.
    $tersimpan = $this->db
        ->where('id_peminjaman', $id)
        ->where('status', $status_lama)
        ->where('id_barang', $id_barang_lama)
        ->where('jumlah_pinjam', $jumlah_lama)
        ->update('tb_peminjaman', $data_update);

    if (!$tersimpan || $this->db->affected_rows() !== 1) {
        $this->db->trans_rollback();
        $this->session->set_flashdata(
            'error',
            'Tidak ada perubahan tersimpan atau transaksi sudah berubah. '
            . 'Muat ulang data sebelum mengedit.'
        );
        redirect('peminjaman');
    }

    $stok_ok = true;

    if ($status_lama === 'Dipinjam') {
        $stok_ok = $this->db
            ->set('stok', 'stok + ' . $jumlah_lama, false)
            ->where('id_barang', $id_barang_lama)
            ->update('tb_inventaris');

        $stok_ok = $stok_ok && $this->db->affected_rows() === 1;
    }

    if ($stok_ok && $status_baru === 'Dipinjam') {
        $this->db
            ->set('stok', 'stok - ' . $jumlah_baru, false)
            ->where('id_barang', $id_barang_baru)
            ->where('stok >=', $jumlah_baru);

        if (
            $status_lama !== 'Dipinjam' ||
            $id_barang_baru !== $id_barang_lama ||
            $jumlah_baru > $jumlah_lama
        ) {
            $this->db->where('kondisi', 'Baik');
        }

        $stok_ok = $this->db->update('tb_inventaris');
        $stok_ok = $stok_ok && $this->db->affected_rows() === 1;
    }

    if (!$stok_ok || $this->db->trans_status() === false) {
        $this->db->trans_rollback();
        $this->session->set_flashdata(
            'error',
            'Perubahan dibatalkan. Periksa ketersediaan dan kondisi barang.'
        );
        redirect('peminjaman');
    }

    $this->db->trans_commit();
    $this->session->set_flashdata(
        'success', 'Data peminjaman berhasil diperbarui!'
    );
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
        // fix pembacaan hasil di controller agar menampilkan pesan error yang sesuai
        $pesan_error = [
            'data_tidak_valid' =>
                'Data peminjaman tidak ditemukan atau jumlahnya tidak valid.',
            'transisi_tidak_valid' =>
                'Perubahan status tidak diperbolehkan. '
                . 'Pinjaman aktif harus diproses melalui pengembalian.',
            'tidak_berubah' =>
                'Status sudah sama. Tidak ada perubahan stok.',
            'data_berubah' =>
                'Data transaksi sudah berubah. Muat ulang halaman.',
            'stok_atau_kondisi' =>
                'Gagal menyetujui! Stok tidak mencukupi '
                . 'atau kondisi barang bukan Baik.'
        ];

        if ($hasil === true) {
            $this->session->set_flashdata(
                'success',
                'Status peminjaman berhasil diperbarui menjadi '
                . $status_decode . '.'
            );
        } else {
            $pesan = is_string($hasil)
                ? ($pesan_error[$hasil] ?? 'Gagal memperbarui peminjaman.')
                : 'Terjadi kesalahan saat memperbarui peminjaman.';

            $this->session->set_flashdata('error', $pesan);
        }

        redirect('peminjaman');
    }

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
