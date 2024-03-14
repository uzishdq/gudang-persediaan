<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Barangkeluar extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        cek_login();

        $this->load->model('Admin_model', 'admin');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $data['title'] = "Barang keluar";
        $data['barangkeluar'] = $this->admin->getBarangkeluar();
        $this->template->load('templates/dashboard', 'barang_keluar/data', $data);
    }

    private function _validasi()
    {
        $this->form_validation->set_rules('barang[0][tanggal_keluar]', 'Tanggal Keluar', 'required|trim');
        $this->form_validation->set_rules('barang[0][barang_id]', 'Barang', 'required');

        $input = $this->input->post('barang_id', true);
        $barang = $this->admin->get('barang', ['id_barang' => $input]);
        $stok = $barang['stok'] ?? null;

        if ($stok !== null)
        {
            $stok_valid = $stok + 1;
            $this->form_validation->set_rules(
                'jumlah_keluar',
                'Jumlah Keluar',
                "required|trim|numeric|greater_than[0]|less_than[{$stok_valid}]",
                [
                    'less_than' => "Jumlah Keluar tidak boleh lebih dari {$stok}"
                ]
            );
        }
    }

    public function add()
    {
        $this->_validasi();
        if ($this->form_validation->run() == false)
        {
            $data['title'] = "Barang Keluar";
            $data['barang'] = $this->admin->get('barang', null, ['stok >' => 0]);

            // Mendapatkan dan men-generate kode transaksi barang keluar
            $kode = 'T-BK-' . date('ymd');
            $kode_terakhir = $this->admin->getMax('barang_keluar', 'id_barang_keluar', $kode);
            $kode_tambah = substr($kode_terakhir, -5, 5);
            $kode_tambah++;
            $number = str_pad($kode_tambah, 5, '0', STR_PAD_LEFT);
            $data['id_barang_keluar'] = $kode . $number;
            $data['user'] = $this->session->userdata('login_session')['user'];

            $this->template->load('templates/dashboard', 'barang_keluar/add', $data);
        }
        else
        {
            $inputs = $this->input->post('barang');
            $data = array();

            foreach ($inputs as $barang)
            {

                $data[] = array(
                    'id_barang_keluar' => $barang['id_barang_keluar'],
                    'user_id' => $barang['user_id'],
                    'barang_id' => $barang['barang_id'],
                    'jumlah_keluar' => $barang['jumlah_keluar'],
                    'tanggal_keluar' => $barang['tanggal_keluar']
                );
            }

            $insert = $this->admin->insert_barang_keluar($data);
            if ($insert)
            {
                set_pesan('data berhasil disimpan.');
                redirect('barangkeluar');
            }
            else
            {
                set_pesan('Opps ada kesalahan!');
                redirect('barangkeluar/add');
            }
        }
    }

    public function delete($getId)
    {
        $id = encode_php_tags($getId);
        if ($this->admin->delete('barang_keluar', 'id_barang_keluar', $id))
        {
            set_pesan('data berhasil dihapus.');
        }
        else
        {
            set_pesan('data gagal dihapus.', false);
        }
        redirect('barangkeluar');
    }
}
