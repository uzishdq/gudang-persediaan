<?= $this->session->flashdata('pesan'); ?>
<div class="card shadow-sm border-bottom-primary">
    <div class="card-header bg-white py-3">
        <div class="row">
            <div class="col">
                <h4 class="h5 align-middle m-0 font-weight-bold text-primary">
                    Riwayat Data Barang Masuk
                </h4>
            </div>
            <?php if (is_gudang()) : ?>
            <div class="col-auto">
                <a href="<?= base_url('barangmasuk/add') ?>" class="btn btn-sm btn-primary btn-icon-split">
                    <span class="icon">
                        <i class="fa fa-plus"></i>
                    </span>
                    <span class="text">
                        Input Barang Masuk
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
                    <th>No Transaksi</th>
                    <th>Tanggal Masuk</th>
                    <th>Supplier</th>
                    <th>Nama Barang</th>
                    <th>Jumlah Masuk</th>
                    <th>Hapus</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                if ($barangmasuk) :
                    foreach ($barangmasuk as $bm) :
                        if($bm['is_verifikasi'] == 0) :
                        else: ?> 
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= $bm['id_barang_masuk']; ?></td>
                            <td><?= $bm['tanggal_masuk']; ?></td>
                            <td><?= $bm['nama_supplier']; ?></td>
                            <td><?= $bm['nama_barang']; ?></td>
                            <td><?= $bm['jumlah_keseluruhan'] . ' ' . $bm['nama_satuan']; ?></td>
                            <td>
                                <a onclick="return confirm('Yakin ingin hapus?')" href="<?= base_url('barangmasuk/delete/') . $bm['id_barang_masuk'] ?>" class="btn btn-danger btn-circle btn-sm"><i class="fa fa-trash"></i></a>
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