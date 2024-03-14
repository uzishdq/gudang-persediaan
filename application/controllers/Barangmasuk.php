<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Barangmasuk extends CI_Controller
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
        $data['title'] = "Barang Masuk";
        $data['barangmasuk'] = $this->admin->getBarangMasuk();
        $this->template->load('templates/dashboard', 'barang_masuk/data', $data);
    }

    private function _validasi()
    {
        $this->form_validation->set_rules('barang[0][tanggal_masuk]', 'Tanggal Masuk', 'required|trim');
        $this->form_validation->set_rules('barang[0][supplier_id]', 'Supplier', 'required');
        $this->form_validation->set_rules('barang[0][barang_id]', 'Barang', 'required');
        $this->form_validation->set_rules('barang[0][jumlah_masuk]', 'Jumlah Masuk', 'required|trim|numeric|greater_than[0]');
    }

    public function add()
    {
        $this->_validasi();
        if ($this->form_validation->run() == false)
        {
            $data['title'] = "Barang Masuk";
            // $data['supplier'] = $this->admin->get('supplier');
            // $data['barang'] = $this->admin->get('barang');
            $data['barangmasuk'] = $this->admin->getBarangMasuk();

            // Mendapatkan dan men-generate kode transaksi barang masuk
            $kode = '';
            $kode_terakhir = $this->admin->getMax('barang_masuk', 'id_barang_masuk', $kode);
            $kode_tambah = substr($kode_terakhir, -5, 5);
            $kode_tambah++;
            $number = str_pad($kode_tambah, 5, '0', STR_PAD_LEFT);
            $data['id_barang_masuk'] = $kode . $number;
            $data['user'] = $this->session->userdata('login_session')['user'];

            $this->template->load('templates/dashboard', 'barang_masuk/add', $data);
        }
        else
        {
            $inputs = $this->input->post('barang');
            $data = array();
            foreach ($inputs as $barang)
            {
                $data[] = array(
                    'id_barang_masuk' => $barang['id_barang_masuk'],
                    'supplier_id' => $barang['supplier_id'],
                    'user_id' => $barang['user_id'],
                    'barang_id' => $barang['barang_id'],
                    'jumlah_masuk' => $barang['jumlah_masuk'],
                    'tanggal_masuk' => $barang['tanggal_masuk']
                );
            }
            
            $data_yang_dicari = null;
            $dataupdate = array(
                'jumlah_ditolak' => 0
            );
            
            foreach ($inputs as $barang){
                $data_yang_dicari = $barang['id_barang_return'];
            }

            $update = $this->admin->update('barang_masuk','id_barang_masuk',$data_yang_dicari,$dataupdate);
            $insert = $this->admin->insert_barang_masuk($data);

            if ($insert)
            {
                set_pesan('data berhasil disimpan.');
                redirect('barangmasuk');
            }
            else
            {
                set_pesan('Opps ada kesalahan!');
                redirect('barangmasuk/add');
            }
        }
    }

    public function delete($getId)
    {
        $id = encode_php_tags($getId);
        if ($this->admin->delete('barang_masuk', 'id_barang_masuk', $id))
        {
            set_pesan('data berhasil dihapus.');
        }
        else
        {
            set_pesan('data gagal dihapus.', false);
        }
        redirect('barangmasuk');
    }
}
