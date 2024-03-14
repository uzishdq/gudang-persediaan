<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Admin_model extends CI_Model
{
    public function get($table, $data = null, $where = null)
    {
        if ($data != null)
        {
            return $this->db->get_where($table, $data)->row_array();
        }
        else
        {
            return $this->db->get_where($table, $where)->result_array();
        }
    }

    public function update($table, $pk, $id, $data)
    {
        $this->db->where($pk, $id);
        return $this->db->update($table, $data);
    }

    public function insert($table, $data, $batch = false)
    {
        return $batch ? $this->db->insert_batch($table, $data) : $this->db->insert($table, $data);
    }

    public function delete($table, $pk, $id)
    {
        return $this->db->delete($table, [$pk => $id]);
    }

    public function getUsers($id)
    {
        /**
         * ID disini adalah untuk data yang tidak ingin ditampilkan. 
         * Maksud saya disini adalah 
         * tidak ingin menampilkan data user yang digunakan, 
         * pada managemen data user
         */
        $this->db->where('id_user !=', $id);
        return $this->db->get('user')->result_array();
    }

    // public function getListNoPO() {
    //     $this->db->select('id_barang_masuk');
    //     $this->db->distinct();
    //     $query = $this->db->get('barang_masuk');
    
    //     return $query->result_array();
    // }

    public function getBarang()
    {
        $this->db->join('jenis j', 'b.jenis_id = j.id_jenis');
        $this->db->join('satuan s', 'b.satuan_id = s.id_satuan');
        $this->db->join('supplier su', 'b.supplier_id = su.id_supplier');

        $this->db->order_by('id_barang');
        return $this->db->get('barang b')->result_array();
    }

    public function getBarangMasuk($limit = null, $id_barang = null, $range = null)
    {
        $this->db->select('*');
        $this->db->join('user u', 'bm.user_id = u.id_user');
        $this->db->join('supplier sp', 'bm.supplier_id = sp.id_supplier');
        $this->db->join('barang b', 'bm.barang_id = b.id_barang');
        $this->db->join('satuan s', 'b.satuan_id = s.id_satuan');
        if ($limit != null)
        {
            $this->db->limit($limit);
        }

        if ($id_barang != null)
        {
            $this->db->where('id_barang', $id_barang);
        }

        if ($range != null)
        {
            $this->db->where('tanggal_masuk' . ' >=', $range['mulai']);
            $this->db->where('tanggal_masuk' . ' <=', $range['akhir']);
        }

        $this->db->order_by('id_barang_masuk', 'DESC');
        return $this->db->get('barang_masuk bm')->result_array();
    }

    public function getRetur($limit = null, $id_barang = null, $range = null)
    {
        $this->db->select('*');
        $this->db->join('user u', 'bm.user_id = u.id_user');
        $this->db->join('supplier sp', 'bm.supplier_id = sp.id_supplier');
        $this->db->join('barang b', 'bm.barang_id = b.id_barang');
        $this->db->join('satuan s', 'b.satuan_id = s.id_satuan');
        $this->db->where('bm.jumlah_ditolak >', 0);
        if ($limit != null)
        {
            $this->db->limit($limit);
        }

        if ($id_barang != null)
        {
            $this->db->where('id_barang', $id_barang);
        }

        if ($range != null)
        {
            $this->db->where('tanggal_masuk' . ' >=', $range['mulai']);
            $this->db->where('tanggal_masuk' . ' <=', $range['akhir']);
        }

        $this->db->order_by('id_barang_masuk', 'DESC');
        return $this->db->get('barang_masuk bm')->result_array();
    }

    public function getPO($limit = null, $id_supplier = null, $range = null)
    {
        $this->db->select('*');
        $this->db->join('user u', 'bm.user_id = u.id_user');
        $this->db->join('supplier sp', 'bm.supplier_id = sp.id_supplier');
        $this->db->join('barang b', 'bm.barang_id = b.id_barang');
        $this->db->join('satuan s', 'b.satuan_id = s.id_satuan');
        if ($limit != null)
        {
            $this->db->limit($limit);
        }

        if ($id_supplier != null)
        {
            $this->db->where('bm.supplier_id', $id_supplier);
        }

        if ($range != null)
        {
            $this->db->where('tanggal_masuk' . ' >=', $range['mulai']);
            $this->db->where('tanggal_masuk' . ' <=', $range['akhir']);
        }

        $this->db->order_by('id_barang_masuk', 'DESC');
        return $this->db->get('barang_masuk bm')->result_array();
    }

    public function getBarangNol($range = null)
    {
        // $this->db->select('*');
        // $this->db->join('supplier sp', 'b.supplier_id = sp.id_supplier');
        // $this->db->join('satuan s', 'b.satuan_id = s.id_satuan');

        // $this->db->where('b.stok' . ' =', 0);
        // $this->db->order_by('b.id_barang', 'DESC');
        // return $this->db->get('barang b')->result_array();

        $this->db->where('stok', 0);
        $query=$this->db->get('barang');
        $result=$query->result();
        return $this->db->get('barang')->result_array();

    }

    public function insert_barang_keluar($data)
    {
        return $this->db->insert_batch('barang_keluar', $data);
    }

    public function insert_barang_masuk($data)
    {
        return $this->db->insert_batch('barang_masuk', $data);
    }

    public function ubahKodeKeluar($idKode)
    {
        $kode = 'T-BK-' . date('ymd');
        $kode_terakhir = $idKode;
        $kode_tambah = substr($kode_terakhir, -5, 5);
        $kode_tambah++;
        $number = str_pad($kode_tambah, 5, '0', STR_PAD_LEFT);
        $hasil = $kode . $number;
        return $hasil;
    }

    public function getReturn($limit = null, $id_supplier = null, $range = null)
    {
        $this->db->select('*');
        $this->db->join('user u', 'bm.user_id = u.id_user');
        $this->db->join('supplier sp', 'bm.supplier_id = sp.id_supplier');
        $this->db->join('barang b', 'bm.barang_id = b.id_barang');
        $this->db->join('satuan s', 'b.satuan_id = s.id_satuan');
        if ($limit != null)
        {
            $this->db->limit($limit);
        }

        if ($id_supplier != null)
        {
            $this->db->where('bm.supplier_id', $id_supplier);
        }

        if ($range != null)
        {
            $this->db->where('tanggal_masuk' . ' >=', $range['mulai']);
            $this->db->where('tanggal_masuk' . ' <=', $range['akhir']);
        }

        $this->db->order_by('id_barang_masuk', 'DESC');
        return $this->db->get('barang_masuk bm')->result_array();
    }

    public function getBarangKeluar($limit = null, $id_barang = null, $range = null)
    {
        $this->db->select('*');
        $this->db->join('user u', 'bk.user_id = u.id_user');
        $this->db->join('barang b', 'bk.barang_id = b.id_barang');
        $this->db->join('satuan s', 'b.satuan_id = s.id_satuan');
        if ($limit != null)
        {
            $this->db->limit($limit);
        }
        if ($id_barang != null)
        {
            $this->db->where('id_barang', $id_barang);
        }
        if ($range != null)
        {
            $this->db->where('tanggal_keluar' . ' >=', $range['mulai']);
            $this->db->where('tanggal_keluar' . ' <=', $range['akhir']);
        }
        $this->db->order_by('id_barang_keluar', 'DESC');
        return $this->db->get('barang_keluar bk')->result_array();
    }

    public function getMax($table, $field, $kode = null)
    {
        $this->db->select_max($field);
        if ($kode != null)
        {
            $this->db->like($field, $kode, 'after');
        }
        return $this->db->get($table)->row_array()[$field];
    }

    public function count($table)
    {
        return $this->db->count_all($table);
    }

    public function sum($table, $field)
    {
        $this->db->select_sum($field);
        return $this->db->get($table)->row_array()[$field];
    }

    public function min($table, $field, $min)
    {
        $field = $field . ' <=';
        $this->db->where($field, $min);
        return $this->db->get($table)->result_array();
    }

    public function chartBarangMasuk($bulan)
    {
        $like = 'T-BM-' . date('y') . $bulan;
        $this->db->like('id_barang_masuk', $like, 'after');
        return count($this->db->get('barang_masuk')->result_array());
    }

    public function chartBarangKeluar($bulan)
    {
        $like = 'T-BK-' . date('y') . $bulan;
        $this->db->like('id_barang_keluar', $like, 'after');
        return count($this->db->get('barang_keluar')->result_array());
    }

    public function laporan($table, $mulai, $akhir)
    {
        $tgl = $table == 'barang_masuk' ? 'tanggal_masuk' : 'tanggal_keluar';
        $this->db->where($tgl . ' >=', $mulai);
        $this->db->where($tgl . ' <=', $akhir);
        return $this->db->get($table)->result_array();
    }

    public function cekStok($id)
    {
        $this->db->join('satuan s', 'b.satuan_id=s.id_satuan');
        return $this->db->get_where('barang b', ['id_barang' => $id])->row_array();
    }

    public function getBySupplier($id)
    {
        $this->db->join('satuan s', 'b.satuan_id = s.id_satuan');
        $this->db->where('supplier_id'. '=', $id);
        return $this->db->get('barang b')->result_array();
    }

    public function getBarangNolBySupplier($id)
    {
        $this->db->join('satuan s', 'b.satuan_id = s.id_satuan');
        $this->db->where('supplier_id'. '=', $id);
        $this->db->where('stok', 0);
        return $this->db->get('barang b')->result_array();
    }

    public function getBarangId($id)
    {
        $this->db->join('satuan s', 'b.satuan_id = s.id_satuan');
        $this->db->where('id_barang'. '=', $id);
        return $this->db->get('barang b')->result_array();
    }

    public function format_tanggal($tanggal_masuk) {
        $tanggal_hari_ini = date('d-m-Y');
        $format_tanggal_masuk = date('d-m-Y', strtotime($tanggal_masuk));
        $format_tanggal = $tanggal_hari_ini . ' - ' . $format_tanggal_masuk;
        return $format_tanggal;
    }
}