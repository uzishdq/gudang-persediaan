<?= $this->session->flashdata('pesan'); ?>

<?php if (is_admin() || (is_gudang())) : ?>
<div class="card shadow-sm border-bottom-primary">
    <div class="card-header bg-white py-3">
        <div class="row">
            <div class="col">
                <h4 class="h5 align-middle m-0 font-weight-bold text-primary">
                    Riwayat Data Pembelian
                </h4>
            </div>
            <?php if (is_gudang()) : ?>
            <div class="col-auto">
                <a href="<?= base_url('pembelian/add') ?>" class="btn btn-sm btn-primary btn-icon-split">
                    <span class="icon">
                        <i class="fa fa-plus"></i>
                    </span>
                    <span class="text">
                        Input Pembelian
                    </span>
                </a>
            </div>
            <?php endif; ?>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-striped w-100 dt-responsive nowrap" id="dataTable">
            <thead>
                <tr>
                    <th>No. </th>
                    <th>No PO</th>
                    <th>Tanggal Pembelian</th>
                    <th>Supplier</th>
                    <th>Nama Barang</th>
                    <th>Jumlah Permintaan Barang</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                if ($barangmasuk) :
                    foreach ($barangmasuk as $bm) :
                        if ($bm['is_verifikasi'] == 0) :
                        else : ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td><?= $bm['id_barang_masuk']; ?></td>
                                <td><?= $bm['tanggal_masuk']; ?></td>
                                <td><?= $bm['nama_supplier']; ?></td>
                                <td><?= $bm['nama_barang']; ?></td>
                                <td><?= $bm['jumlah_keseluruhan'] . ' ' . $bm['nama_satuan']; ?></td>
                                <td>
                                    <a onclick="return confirm('Yakin ingin hapus?')" href="<?= base_url('pembelian/delete/') . $bm['id_barang_masuk'] ?>" class="btn btn-danger btn-circle btn-sm"><i class="fa fa-trash"></i></a>
                                </td>
                            </tr>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="8" class="text-center">
                            Data Kosong
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php endif; ?>

<?php if ( is_gudang()) : ?>

    <div class="card shadow-sm border-bottom-primary mt-5">
        <div class="card-header bg-white py-3">
            <div class="row">
                <div class="col">
                    <h4 class="h5 align-middle m-0 font-weight-bold text-primary">
                        Verifikasi Data Pembelian   
                    </h4>
                </div>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-striped w-100 dt-responsive nowrap" id="dataTable">
                <thead>
                    <tr>
                        <th>No. </th>
                        <th>No PO</th>
                        <th>Tanggal Masuk</th>
                        <th>Supplier</th>
                        <th>Nama Barang</th>
                        <th>Jumlah Permintaan Barang</th>
                        <th>Jumlah Barang Datang</th>
                        <th>Jumlah Ditolak</th>
                        <th>Total</th>
                        <th>Verifikasi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    if ($barangmasuk) :
                        foreach ($barangmasuk as $bm) :
                            if ($bm['is_verifikasi'] != 0) :
                            else : ?>
                                <tr>
                                    <td><?= $no++; ?></td>
                                    <td><?= $bm['id_barang_masuk']; ?></td>
                                    <td><?= $bm['tanggal_masuk']; ?></td>
                                    <td><?= $bm['nama_supplier']; ?></td>
                                    <td><?= $bm['nama_barang']; ?></td>
                                    <td><?= $bm['jumlah_masuk'] . ' ' . $bm['nama_satuan']; ?></td>
                                    <td><?= $bm['jumlah_barang_datang'] . ' ' . $bm['nama_satuan']; ?></td>
                                    <td><?= $bm['jumlah_ditolak'] . ' ' . $bm['nama_satuan']; ?></td>
                                    <td><?= $bm['jumlah_keseluruhan'] . ' ' . $bm['nama_satuan']; ?></td>
                                    <td>
                                        <a href="<?= base_url('pembelian/toggle/') . $bm['id_barang_masuk'] ?>" class="btn btn-circle btn-sm <?= $bm['is_verifikasi'] == 0 ? 'btn-secondary' : 'btn-success' ?>" title="<?= $bm['is_verifikasi'] ? ' Tolak Pembelian' : 'Verifikasi Pembelian' ?>"><i class="fa fa-fw fa-power-off"></i></a>
                                        <a href="<?= base_url('pembelian/edit/') . $bm['id_barang_masuk'] ?>" class="btn btn-circle btn-sm btn-warning"><i class="fa fa-fw fa-edit"></i></a>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="8" class="text-center">
                                Data Kosong
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php endif; ?>