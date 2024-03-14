<?php

function cek_login()
{
    $ci = get_instance();
    if (!$ci->session->has_userdata('login_session'))
    {
        set_pesan('silahkan login.');
        redirect('auth');
    }
}

function is_admin()
{
    $ci = get_instance();
    $role = $ci->session->userdata('login_session')['role'];

    $status = true;

    if ($role != 'admin')
    {
        $status = false;
    }

    return $status;
}

function is_gudang()
{
    $ci = get_instance();
    $role = $ci->session->userdata('login_session')['role'];

    $status = true;

    if ($role != 'gudang')
    {
        $status = false;
    }

    return $status;
}

function is_kandang()
{
    $ci = get_instance();
    $role = $ci->session->userdata('login_session')['role'];

    $status = true;

    if ($role != 'kandang')
    {
        $status = false;
    }

    return $status;
}

function set_pesan($pesan, $tipe = true)
{
    $ci = get_instance();
    if ($tipe)
    {
        $ci->session->set_flashdata('pesan', "<div class='alert alert-success'><strong>SUCCESS!</strong> {$pesan} <button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button></div>");
    }
    else
    {
        $ci->session->set_flashdata('pesan', "<div class='alert alert-danger'><strong>ERROR!</strong> {$pesan} <button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button></div>");
    }
}

function userdata($field)
{
    $ci = get_instance();
    $ci->load->model('Admin_model', 'admin');

    $userId = $ci->session->userdata('login_session')['user'];
    return $ci->admin->get('user', ['id_user' => $userId])[$field];
}

function output_json($data)
{
    $ci = get_instance();
    $data = json_encode($data);
    $ci->output->set_content_type('application/json')->set_output($data);
}

function ubahKodeKeluar($idKode)
{
    $kode = 'T-BK-' . date('ymd');
    $kode_terakhir = $idKode;
    $kode_tambah = substr($kode_terakhir, -5, 5);
    $kode_tambah++;
    $number = str_pad($kode_tambah, 5, '0', STR_PAD_LEFT);
    $hasil = $kode . $number;
    return $hasil;
}
