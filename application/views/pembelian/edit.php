<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm border-bottom-primary">
            <div class="card-header bg-white py-3">
                <div class="row">
                    <div class="col">
                        <h4 class="h5 align-middle m-0 font-weight-bold text-primary">
                            Verifikasi Pembelian
                        </h4>
                    </div>
                    <div class="col-auto">
                        <a href="<?= base_url('pembelian') ?>" class="btn btn-sm btn-secondary btn-icon-split">
                            <span class="icon">
                                <i class="fa fa-arrow-left"></i>
                            </span>
                            <span class="text">
                                Kembali
                            </span>
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <?= $this->session->flashdata('pesan'); ?>
                <?= form_open('', [], ['id_barang_masuk' => $barang_masuk['id_barang_masuk']]); ?>
                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="id_barang_masuk">No PO</label>
                    <div class="col-md-4">
                        <input value="<?= set_value('id_barang_masuk', $barang_masuk['id_barang_masuk']); ?>" type="text" readonly="readonly" class="form-control">
                        <?= form_error('id_barang_masuk', '<small class="text-danger">', '</small>'); ?>
                    </div>
                </div>
                <div hidden class="row form-group">
                    <label class="col-md-4 text-md-right" for="is_verifikasi">is veriv</label>
                    <div class="col-md-4">
                        <input value="<?= set_value('is_verifikasi',1); ?>" type="text" id="is_verifikasi" name="is_verifikasi" readonly="readonly" class="form-control">
                    </div>
                </div>
                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="tanggal_masuk">Tanggal Masuk</label>
                    <div class="col-md-4">
                        <input value="<?= set_value('tanggal_masuk', $barang_masuk['tanggal_masuk']); ?>" name="tanggal_masuk" id="tanggal_masuk" type="text" class="form-control date">
                        <?= form_error('tanggal_masuk', '<small class="text-danger">', '</small>'); ?>
                    </div>
                </div>
                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="supplier_id">Supplier</label>
                    <div class="col-md-5">
                        <div class="input-group">
                            <select name="supplier_id" id="supplier_id" class="custom-select">
                                <?php foreach ($supplier as $s) : ?>
                                    <option <?= $barang_masuk['supplier_id'] == $s['id_supplier'] ? 'selected' : ''; ?> <?= set_select('supplier_id', $s['id_supplier']) ?> value="<?= $s['id_supplier'] ?>"><?= $s['nama_supplier'] ?></option>
                                <?php endforeach; ?>
                            </select>
                            <div class="input-group-append">
                                <a class="btn btn-primary" href="<?= base_url('supplier/add'); ?>"><i class="fa fa-plus"></i></a>
                            </div>
                        </div>
                        <?= form_error('supplier_id', '<small class="text-danger">', '</small>'); ?>
                    </div>
                </div>
                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="barang_id">Barang</label>
                    <div class="col-md-5">
                        <div class="input-group">
                            <select name="barang_id" id="barang_id" class="custom-select" >
                                <?php foreach ($barang as $b) : ?>
                                    <option <?= $barang_masuk['barang_id'] == $b['id_barang'] ? 'selected' : ''; ?> <?= set_select('barang_id', $b['id_barang']) ?> value="<?= $b['id_barang'] ?>"><?= $b['nama_barang'] ?></option>
                                <?php endforeach; ?>
                            </select>
                            <div class="input-group-append">
                                <a class="btn btn-primary" href="<?= base_url('barang/add'); ?>"><i class="fa fa-plus"></i></a>
                            </div>
                        </div>
                        <?= form_error('barang_id', '<small class="text-danger">', '</small>'); ?>
                    </div>
                </div>
                
                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="jumlah_masuk">Jumlah Permintaan Barang</label>
                    <div class="col-md-5">
                        <div class="input-group">
                            <input value="<?= set_value('jumlah_masuk', $barang_masuk['jumlah_masuk']); ?>" name="jumlah_masuk" id="jumlah_masuk" type="number" readonly="readonly" class="form-control" placeholder="Jumlah Permintaan Barang">
                            <div class="input-group-append">
                                <span class="input-group-text" id="satuan">Satuan</span>
                            </div>
                        </div>
                        <?= form_error('jumlah_masuk', '<small class="text-danger">', '</small>'); ?>
                    </div>
                </div>
                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="jumlah_barang_datang">Jumlah Barang Datang</label>
                    <div class="col-md-5">
                        <div class="input-group">
                            <input value="<?= set_value('jumlah_barang_datang'); ?>" name="jumlah_barang_datang" id="jumlah_barang_datang" type="text" class="form-control" placeholder="Jumlah Barang Datang...">
                            <div class="input-group-append">
                            <span class="input-group-text" id="satuan">Satuan</span>
                            </div>
                        </div>
                        <?= form_error('jumlah_barang_datang', '<small class="text-danger">', '</small>'); ?>
                    </div>
                </div>
                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="jumlah_ditolak">Jumlah Ditolak</label>
                    <div class="col-md-5">
                        <div class="input-group">
                            <input value="<?= set_value('jumlah_ditolak'); ?>" name="jumlah_ditolak" id="jumlah_ditolak" type="number" class="form-control" placeholder="Jumlah Ditolak...">
                            <div class="input-group-append">
                                <span class="input-group-text" id="satuan">Satuan</span>
                            </div>
                        </div>
                        <?= form_error('jumlah_ditolak', '<small class="text-danger">', '</small>'); ?>
                    </div>
                </div>
                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="jumlah_keseluruhan">Total Diterima</label>
                    <div class="col-md-5">
                        <input readonly="readonly" id="jumlah_keseluruhan" name="jumlah_keseluruhan" type="number" class="form-control">
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col offset-md-4">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <button type="reset" class="btn btn-secondary">Reset</button>
                    </div>
                </div>
                <?= form_close(); ?>
            </div>
        </div>
    </div>
</div>