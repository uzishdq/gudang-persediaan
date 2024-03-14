<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow-sm border-bottom-primary">
            <div class="card-header bg-white py-3">
                <h4 class="h5 align-middle m-0 font-weight-bold text-primary">
                    Form Cetak Barang 
                </h4>
            </div>
            <div class="card-body">
                <?= form_open(); ?>
                <div class="form-group row">
                    <label class="col-md-3 col-form-label text-md-right" for="transaksi">Cetak Transaksi</label>
                    <div class="col-md-9">
                        <?php if (is_gudang()) : ?>
                        <div class="custom-control custom-radio">
                            <input value="barang_masuk" type="radio" id="barang_masuk" name="transaksi" class="custom-control-input">
                            <label class="custom-control-label" for="barang_masuk">Laporan Barang Masuk</label>
                        </div>
                        <div class="custom-control custom-radio">
                            <input value="barang_keluar" type="radio" id="barang_keluar" name="transaksi" class="custom-control-input">
                            <label class="custom-control-label" for="barang_keluar">Laporan Barang Keluar</label>
                        </div>
                        <div class="custom-control custom-radio">
                            <input value="barang_retur" type="radio" id="barang_retur" name="transaksi" class="custom-control-input">
                            <label class="custom-control-label" for="barang_retur">Laporan Barang Retur</label>
                        </div>
                        <?php endif; ?>
                        <?php if (is_admin()) : ?>
                        <div class="custom-control custom-radio text-left">
                            <input value="pembelian" type="radio" id="pembelian" name="transaksi" class="custom-control-input">
                            <label class="custom-control-label" for="pembelian">Purchase Order</label>
                        </div>
                        <div class="custom-control custom-radio text-left">
                            <input value="barang_return" type="radio" id="barang_return" name="transaksi" class="custom-control-input">
                            <label class="custom-control-label" for="barang_return">Surat Penggantian Barang</label>
                        </div>
                        <?= form_error('transaksi', '<span class="text-danger small">', '</span>'); ?>
                        <?php endif; ?>
                    </div>
                </div>
                <?php if (is_admin()) : ?>
                <div class="form-group row">
                    <label class="col-md-3 col-form-label text-md-right" for="supplier_id">Supplier</label>
                    <div class="col-md-5">
                        <div class="input-group">
                            <select name="supplier_id" id="supplier_id" class="custom-select">
                                <option value="" selected disabled>Pilih Supplier</option>
                                <?php foreach ($supplier as $s) : ?>
                                    <option <?= set_select('supplier_id', $s['id_supplier']) ?> value="<?= $s['id_supplier'] ?>"><?= $s['nama_supplier'] ?></option>
                                <?php endforeach; ?>
                            </select>
                            <div class="input-group-append">
                                <a class="btn btn-primary" href="<?= base_url('supplier/add'); ?>"><i class="fa fa-plus"></i></a>
                            </div>
                        </div>
                        <?= form_error('supplier_id', '<small class="text-danger">', '</small>'); ?>
                    </div>
                </div>
                <?php endif; ?>
                <div class="form-group row">
                    <label class="col-lg-3 col-form-label text-lg-right" for="tanggal">Tanggal</label>
                    <div class="col-lg-5">
                        <div class="input-group">
                            <input value="<?= set_value('tanggal'); ?>" name="tanggal" id="tanggal" type="text" class="form-control" placeholder="Periode Tanggal">
                            <div class="input-group-append">
                                <span class="input-group-text"><i class="fa fa-fw fa-calendar"></i></span>
                            </div>
                        </div>
                        <?= form_error('tanggal', '<small class="text-danger">', '</small>'); ?>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-lg-9 offset-lg-3">
                        <button type="submit" class="btn btn-primary btn-icon-split">
                            <span class="icon">
                                <i class="fa fa-print"></i>
                            </span>
                            <span class="text">
                                Cetak
                            </span>
                        </button>
                    </div>
                </div>
                <?= form_close(); ?>
            </div>
        </div>
    </div>
</div>
