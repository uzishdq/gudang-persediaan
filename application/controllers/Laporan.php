<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Laporan extends CI_Controller
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
    $this->form_validation->set_rules('transaksi', 'Transaksi', 'required|in_list[barang_masuk,barang_keluar,pembelian,barang_return,barang_retur]');
    $this->form_validation->set_rules('tanggal', 'Periode Tanggal', 'required');

    if ($this->form_validation->run() == false)
    {
        $data['title'] = "Cetak Transaksi";
        $data['supplier'] = $this->admin->get('supplier');
        $this->template->load('templates/dashboard', 'laporan/form', $data);
    }
    else
    {
        $input = $this->input->post(null, true);
        $table = $input['transaksi'];
        $tanggal = $input['tanggal'];

        $pecah = explode(' - ', $tanggal);
        $mulai = date('Y-m-d', strtotime($pecah[0]));
        $akhir = date('Y-m-d', strtotime(end($pecah)));

        $query = '';

        if ($table == 'barang_masuk')
        {
             $query = $this->admin->getBarangMasuk(null, null, ['mulai' => $mulai, 'akhir' => $akhir]);
        }
        elseif ($table == 'barang_keluar')
        {
            $query = $this->admin->getBarangKeluar(null, null, ['mulai' => $mulai, 'akhir' => $akhir]);
        }
        elseif ($table == 'barang_retur')
        {
            $query = $this->admin->getRetur(null, null, ['mulai' => $mulai, 'akhir' => $akhir]);
        }
        elseif ($table == 'barang_return')
        {
            $id_supplier = $input["supplier_id"];
            $query = $this->admin->getReturn(null, $id_supplier, ['mulai' => $mulai, 'akhir' => $akhir]);
        }
        else
        {
            $id_supplier = $input["supplier_id"];
            $query = $this->admin->getPO(null, $id_supplier, ['mulai' => $mulai, 'akhir' => $akhir]);
        }
        $this-> _cetak($query,$table,$tanggal);

        // // Ambil nomor PO yang berada dalam rentang tanggal filter
        // $list_no_po = $this->admin->getListNoPO($mulai, $akhir);

        // // Menyimpan data ke variabel untuk digunakan di view
        // $data['list_no_po'] = $list_no_po;
        // $data['table'] = $table;
        // $data['tanggal'] = $tanggal;

    }
}


    private function _cetak($data, $table_, $tanggal)
    {

        $this->load->library('CustomPDF');
        // $table = $table_ == 'barang_masuk' ? 'Barang Masuk' : 'Barang Keluar';
        $table = ($table_ == 'barang_masuk') ? 'Laporan Barang Masuk' : (($table_ == 'barang_keluar') ? 'Laporan Barang Keluar' : (($table_ == 'barang_return') ? 'Surat Penggantian Barang' : (($table_ == 'barang_retur') ? 'Laporan Barang Retur' : 'Purchase Order')));

        $pdf = new FPDF();
        $pdf->AddPage('P', 'Letter');
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(190, 7, $table, 0, 1, 'C');

        // $pdf->Cell(190, 4, 'Tanggal : ' . $tanggal, 0, 1, 'L');
        $pdf->Ln(10);

        $pdf->SetFont('Arial', 'B', 10);

        if ($table_ == 'barang_masuk')
        {
            $pdf->Cell(0, 10, ' Bandung, '.date('d-m-Y'), 0, 1, 'R');

            $pdf->Cell(10, 7, 'No.', 1, 0, 'C');
            $pdf->Cell(25, 7, 'Tgl Masuk', 1, 0, 'C');
            $pdf->Cell(35, 7, 'No PO', 1, 0, 'C');
            $pdf->Cell(55, 7, 'Nama Barang', 1, 0, 'C');
            $pdf->Cell(40, 7, 'Supplier', 1, 0, 'C');
            $pdf->Cell(30, 7, 'Jumlah Masuk', 1, 0, 'C');
            $pdf->Ln();

            $no = 1;
            foreach ($data as $d)
            {
                $pdf->SetFont('Arial', '', 10);
                $pdf->Cell(10, 7, $no++ . '.', 1, 0, 'C');
                $pdf->Cell(25, 7, $d['tanggal_masuk'], 1, 0, 'C');
                $pdf->Cell(35, 7, $d['id_barang_masuk'], 1, 0, 'C');
                $pdf->Cell(55, 7, $d['nama_barang'], 1, 0, 'L');
                $pdf->Cell(40, 7, $d['nama_supplier'], 1, 0, 'L');
                $pdf->Cell(30, 7, $d['jumlah_masuk'] . ' ' . $d['nama_satuan'], 1, 0, 'C');
                $pdf->Ln();
            }
        }
        elseif ($table_ == 'barang_keluar')
        {
            $pdf->Cell(0, 10, ' Bandung, '.date('d-m-Y'), 0, 1, 'R');
            
            $pdf->Cell(10, 7, 'No.', 1, 0, 'C');
            $pdf->Cell(25, 7, 'Tgl Masuk', 1, 0, 'C');
            $pdf->Cell(35, 7, 'ID Transaksi', 1, 0, 'C');
            $pdf->Cell(95, 7, 'Nama Barang', 1, 0, 'C');
            $pdf->Cell(30, 7, 'Jumlah Keluar', 1, 0, 'C');
            $pdf->Ln();

            $no = 1;
            foreach ($data as $d)
            {
                $pdf->SetFont('Arial', '', 10);
                $pdf->Cell(10, 7, $no++ . '.', 1, 0, 'C');
                $pdf->Cell(25, 7, $d['tanggal_keluar'], 1, 0, 'C');
                $pdf->Cell(35, 7, $d['id_barang_keluar'], 1, 0, 'C');
                $pdf->Cell(95, 7, $d['nama_barang'], 1, 0, 'L');
                $pdf->Cell(30, 7, $d['jumlah_keluar'] . ' ' . $d['nama_satuan'], 1, 0, 'C');
                $pdf->Ln();
            }
        }
        elseif ($table_ == 'barang_retur')
        {
            $pdf->Cell(0, 10, ' Bandung, '.date('d-m-Y'), 0, 1, 'R');
        
            $pdf->Cell(10, 7, 'No.', 1, 0, 'C');
            $pdf->Cell(25, 7, 'Tgl Keluar', 1, 0, 'C');
            $pdf->Cell(35, 7, 'No PO', 1, 0, 'C');
            $pdf->Cell(95, 7, 'Nama Barang', 1, 0, 'C');
            $pdf->Cell(30, 7, 'Barang Ditolak', 1, 0, 'C');
            $pdf->Ln();

            $no = 1;
            foreach ($data as $d)
            {
                $pdf->SetFont('Arial', '', 10);
                $pdf->Cell(10, 7, $no++ . '.', 1, 0, 'C');
                $pdf->Cell(25, 7, $d['tanggal_masuk'], 1, 0, 'C');
                $pdf->Cell(35, 7, $d['id_barang_masuk'], 1, 0, 'C');
                $pdf->Cell(95, 7, $d['nama_barang'], 1, 0, 'L');
                $pdf->Cell(30, 7, $d['jumlah_ditolak'] . ' ' . $d['nama_satuan'], 1, 0, 'C');
                $pdf->Ln();
            }
        }
        elseif ($table_ == 'barang_return')
        {
            $pdf->Image('assets/img/KAREUMBI FARM.jpg', 10, 5, -600);
            $pdf->Cell(190, 4, 'Nomor PO: ' . $data[0]['id_barang_masuk'], 0, 1, 'L');
            $pdf->Ln(5);
            $pdf->Cell(190, 4, 'Kepada: ' . $data[0]['nama_supplier'], 0, 1, 'L');
            $pdf->Ln(5);
            $pdf->Cell(190, 4, 'Tanggal: ' . $this->admin->format_tanggal($data[0]['tanggal_masuk']), 0, 1, 'L');
            $pdf->Ln(10);
            
            $pdf->Cell(10, 7, 'No.', 1, 0, 'C');
            $pdf->Cell(50, 7, 'Nama Barang', 1, 0, 'C');
            $pdf->Cell(40, 7, 'Supplier', 1, 0, 'C');
            $pdf->Cell(40, 7, 'Jumlah Barang', 1, 0, 'C');
            $pdf->Cell(50, 7, 'Keterangan', 1, 0, 'C');
            $pdf->Ln();

            $no = 1;
            foreach ($data as $d)
            {
                $pdf->SetFont('Arial', '', 10);
                $pdf->Cell(10, 7, $no++ . '.', 1, 0, 'C');
                $pdf->Cell(50, 7, $d['nama_barang'], 1, 0, 'L');
                $pdf->Cell(40, 7, $d['nama_supplier'], 1, 0, 'L');
                $pdf->Cell(40, 7, $d['jumlah_ditolak'] . ' ' . $d['nama_satuan'], 1, 0, 'C');
                $pdf->Cell(50, 7, 'Barang Cacat', 1, 0, 'C');
                $pdf->Ln();
            }

        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(0, 10, ' Bandung, '.date('d-m-Y'), 0, 1, 'R');
        $pdf->Ln(10);

        $pdf->Cell(60, 10, 'Yang Menerima,', 0, 0, 'L');
        $pdf->Cell(100, 10, 'Pengirim,', 0, 0, 'R');
        $pdf->Ln(20);
        $pdf->Cell(0, 10, '(______________________)', 0, 0, 'L');

        $pdf->Cell(-20, 10, '(Rizki Adlrian Prabowo)', 0, 0, 'R');

        $pdf->Ln(10);
        }
        else
        {
            $pdf->Image('assets/img/KAREUMBI FARM.jpg', 10, 5, -600);
            $pdf->Cell(190, 4, 'Nomor PO: ' . $data[0]['id_barang_masuk'], 0, 1, 'L');
            $pdf->Ln(5);
            $pdf->Cell(190, 4, 'Kepada: ' . $data[0]['nama_supplier'], 0, 1, 'L');
            $pdf->Ln(5);
            $pdf->Cell(190, 4, 'Tanggal Pembelian: ' . $this->admin->format_tanggal($data[0]['tanggal_masuk']), 0, 1, 'L');
            $pdf->Ln(10);
            
            $pdf->Cell(10, 7, 'No.', 1, 0, 'C');
            $pdf->Cell(50, 7, 'Nama Barang', 1, 0, 'C');
            $pdf->Cell(40, 7, 'Supplier', 1, 0, 'C');
            $pdf->Cell(40, 7, 'Jumlah Barang', 1, 0, 'C');
            $pdf->Cell(50, 7, 'Keterangan', 1, 0, 'C');
            $pdf->Ln();

            $fill = false;
            $firstIteration = true;
            $no = 1;
            $w = array(10, 50, 40, 40,50);
            foreach ($data as $d)
            {
                $pdf->SetFont('Arial', '', 10);
                $pdf->Cell(10, 7, $no++ . '.', 1, 0, 'C');
                $pdf->Cell(50, 7, $d['nama_barang'], 1, 0, 'L');
                $pdf->Cell(40, 7, $d['nama_supplier'], 1, 0, 'L');
                $pdf->Cell(40, 7, $d['jumlah_masuk'] . ' ' . $d['nama_satuan'], 1, 0, 'C');
                // $pdf->Cell(50, 7, 'kirim', 1, 0, 'C');
                if ($firstIteration) {
                    $pdf->Cell(50,7,"Kirim barang kepada",'LR',0,'C',$fill);  
                    $firstIteration = false; // Set variabel ke false setelah iterasi pertama
                }else {
                    $pdf->Cell(50,7,$d['nama_supplier'],'LR',0,'C',$fill);  
                }

                $pdf->Ln();
            }
        $pdf->Cell(array_sum($w),0,'','T');

        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(0, 10, ' Bandung, '.date('d-m-Y'), 0, 1, 'R');
        $pdf->Ln(10);

        $pdf->Cell(60, 10, 'Yang Menerima,', 0, 0, 'L');
        $pdf->Cell(100, 10, 'Pengirim,', 0, 0, 'R');
        $pdf->Ln(20);
        $pdf->Cell(0, 10, '(______________________)', 0, 0, 'L');

        $pdf->Cell(-20, 10, '(Rizki Adlrian Prabowo)', 0, 0, 'R');

        $pdf->Ln(10);
        }

        


        $file_name = $table . ' ' . $tanggal;
        $pdf->Output('I', $file_name);
    }



//     private function _cetak($data, $table_, $tanggal)
// {
//     require_once 'vendor/autoload.php';

//     $table = ($table_ == 'barang_masuk') ? 'Laporan Barang Masuk' : (($table_ == 'barang_keluar') ? 'Laporan Barang Keluar' : 'Pembelian');

//     $pdf = new \TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
//     $pdf->AddPage('P', 'Letter');

//     // Judul
//     $pdf->SetFont('helvetica', 'B', 16);
//     $pdf->Cell(0, 10, $table, 0, 1, 'C');
//     $pdf->SetFont('helvetica', '', 12);
//     $pdf->Cell(0, 10, 'Tanggal: ' . $tanggal, 0, 1, 'C');

//     // Tabel Data
//     $pdf->SetFont('helvetica', 'B', 12);
//     $pdf->Ln(10);

//     if ($table_ == 'barang_masuk') {
//         $pdf->Cell(10, 7, 'No.', 1, 0, 'C');
//         $pdf->Cell(30, 7, 'Tgl Masuk', 1, 0, 'C');
//         $pdf->Cell(40, 7, 'ID Barang', 1, 0, 'C');
//         $pdf->Cell(70, 7, 'Nama Barang', 1, 0, 'C');
//         $pdf->Cell(40, 7, 'Supplier', 1, 0, 'C');
//         $pdf->Cell(30, 7, 'Jumlah', 1, 0, 'C');
//         $pdf->Ln();

//         $pdf->SetFont('helvetica', '', 10);
//         $no = 1;
//         foreach ($data as $d) {
//             $pdf->Cell(10, 7, $no++, 1, 0, 'C');
//             $pdf->Cell(30, 7, $d['tanggal_masuk'], 1, 0, 'C');
//             $pdf->Cell(40, 7, $d['id_barang_masuk'], 1, 0, 'C');
//             $pdf->Cell(70, 7, $d['nama_barang'], 1, 0, 'L');
//             $pdf->Cell(40, 7, $d['nama_supplier'], 1, 0, 'L');
//             $pdf->Cell(30, 7, $d['jumlah_masuk'] . ' ' . $d['nama_satuan'], 1, 0, 'C');
//             $pdf->Ln();
//         }
//     } elseif ($table_ == 'barang_keluar') {
//         $pdf->Cell(10, 7, 'No.', 1, 0, 'C');
//         $pdf->Cell(30, 7, 'Tgl Keluar', 1, 0, 'C');
//         $pdf->Cell(40, 7, 'ID Transaksi', 1, 0, 'C');
//         $pdf->Cell(70, 7, 'Nama Barang', 1, 0, 'C');
//         $pdf->Cell(40, 7, 'Jumlah Keluar', 1, 0, 'C');
//         $pdf->Ln();

//         $pdf->SetFont('helvetica', '', 10);
//         $no = 1;
//         foreach ($data as $d) {
//             $pdf->Cell(10, 7, $no++, 1, 0, 'C');
//             $pdf->Cell(30, 7, $d['tanggal_keluar'], 1, 0, 'C');
//             $pdf->Cell(40, 7, $d['id_barang_keluar'], 1, 0, 'C');
//             $pdf->Cell(70, 7, $d['nama_barang'], 1, 0, 'L');
//             $pdf->Cell(40, 7, $d['jumlah_keluar'] . ' ' . $d['nama_satuan'], 1, 0, 'C');
//             $pdf->Ln();
//         }
//     } else {
//         $pdf->Cell(10, 7, 'No.', 1, 0, 'C');
//         $pdf->Cell(30, 7, 'Tgl Transaksi', 1, 0, 'C');
//         $pdf->Cell(40, 7, 'ID Transaksi', 1, 0, 'C');
//         $pdf->Cell(70, 7, 'Nama Barang', 1, 0, 'C');
//         $pdf->Cell(40, 7, 'Supplier', 1, 0, 'C');
//         $pdf->Cell(30, 7, 'Jumlah', 1, 0, 'C');
//         $pdf->Ln();

//         $pdf->SetFont('helvetica', '', 10);
//         $no = 1;
//         foreach ($data as $d) {
//             $pdf->Cell(10, 7, $no++, 1, 0, 'C');
//             $pdf->Cell(30, 7, $d['tanggal_transaksi'], 1, 0, 'C');
//             $pdf->Cell(40, 7, $d['id_transaksi'], 1, 0, 'C');
//             $pdf->Cell(70, 7, $d['nama_barang'], 1, 0, 'L');
//             $pdf->Cell(40, 7, $d['supplier'], 1, 0, 'L');
//             $pdf->Cell(30, 7, $d['jumlah'] . ' ' . $d['satuan'], 1, 0, 'C');
//             $pdf->Ln();
//         }
//     }

//     $file_name = $table . ' ' . $tanggal;
//     $pdf->Output('I', $file_name);
// }


}
