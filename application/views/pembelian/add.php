<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm border-bottom-primary">
            <div class="card-header bg-white py-3">
                <div class="row">
                    <div class="col">
                        <h4 class="h5 align-middle m-0 font-weight-bold text-primary">
                            Form Data Pembelian
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
                <?= form_open(); ?>
                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="id_barang_masuk">No PO</label>
                    <div class="col-md-4">
                        <input hidden value="<?= $user; ?>" name="barang[0][user_id]" type="text" readonly="readonly" class="form-control">
                        <input value="<?= $id_barang_masuk; ?>" id="input_0_id" name="barang[0][id_barang_masuk]" type="text" readonly="readonly" class="form-control">
                        <?= form_error('id_barang_masuk', '<small class="text-danger">', '</small>'); ?>
                    </div>
                    <button type="button" class="btn btn-primary btn-sm mr-2" onclick="addBarang2()"><i class="fa fa-plus"></i>Tambah Barang</button>
                    <button type="button" class="btn btn-warning btn-sm" onclick="cancelForm2()">Cancel</button>
                </div>
                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="tanggal_masuk">Tanggal Pembelian</label>
                    <div class="col-md-4">
                        <input value="<?= set_value('tanggal_masuk', date('Y-m-d', strtotime(date('Y-m-d') . ' + 7 days'))); ?>" name="barang[0][tanggal_masuk]" id="tanggal_masuk" type="text" class="form-control date" placeholder="Tanggal Pembelian">
                        <?= form_error('tanggal_masuk', '<small class="text-danger">', '</small>'); ?>
                    </div>
                </div>
                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="supplier_id">Supplier</label>
                    <div class="col-md-5">
                        <div class="input-group">
                            <select name="barang[0][supplier_id]" id="supplier_id" onchange="getBysupllierId(this)" class="custom-select">
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

                <div id="form-container2">
                    <div class="barang2">
                        <div class="row form-group">
                            <label class="col-md-4 text-md-right" for="barang_id">Barang</label>
                            <div class="col-md-5">
                                <div class="input-group">
                                    <select name="barang[0][barang_id]" id="barang" onchange="getBarangId(this)" class="custom-select">
                                        <option value="" selected disabled>Pilih Barang</option>
                                    </select>
                                    <div class="input-group-append">
                                        <a class="btn btn-primary" href="<?= base_url('barang/add'); ?>"><i class="fa fa-plus"></i></a>
                                    </div>
                                </div>
                                <?= form_error('barang_id', '<small class="text-danger">', '</small>'); ?>
                            </div>
                        </div>
                        <div class="row form-group">
                            <label class="col-md-4 text-md-right" for="jumlah_masuk">Jumlah Barang</label>
                            <div class="col-md-5">
                                <div class="input-group">
                                    <input value="<?= set_value('jumlah_masuk'); ?>" name="barang[0][jumlah_masuk]" id="jumlah_masuk" type="number" class="form-control" placeholder="Jumlah Barang">
                                    <div class="input-group-append">
                                        <span class="input-group-text" id="satuan">Satuan</span>
                                    </div>
                                </div>
                                <?= form_error('jumlah_masuk', '<small class="text-danger">', '</small>'); ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="stok">Stok</label>
                    <div class="col-md-5">
                        <input readonly="readonly" id="stok" type="number" class="form-control">
                    </div>
                </div> -->

                <!-- <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="total_stok">Total Stok</label>
                    <div class="col-md-5">
                        <input readonly="readonly" id="total_stok" type="number" class="form-control">
                    </div>
                </div> -->
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

<script type="text/javascript">
    var counter = 1;
    var counterInput = 0;

    function getBysupllierId(selectObject){
        let supplier_id = selectObject.value;
        let url = '<?= base_url('barang/getBarangNolBySupplierId/'); ?>' + supplier_id;
        let barangBySupplier = document.getElementById('barang');
        barangBySupplier.innerHTML = ''; // Clear existing options
        
        $.getJSON(url, function(data) {
            var options = '';
            $.each(data, function(key, value) {
                options += '<option value="' + value.id_barang + '">' + value.nama_barang + '</option>';
            });
            $('#barang').html(options);
        });

    }

    function getBarangId(selectObject){
        let barang_id = selectObject.value;
        let url = '<?= base_url('barang/getSatuanByBarang/'); ?>' + barang_id;
        let barangBySupplier = document.getElementById('satuan');
        
        $.getJSON(url, function(data) {
            barangBySupplier.innerHTML = ''; // Clear existing options
            var dataSatuan = '';
            $.each(data, function(key, value) {
                dataSatuan += '<span>' + value.nama_satuan + '</spane=>';
            });
            $('#satuan').html(dataSatuan);
        });

    }

    function addBarang2() {
        var existingID = document.getElementById('input_' + counterInput + '_id').value;
        var newID = existingID;

        if (existingID === newID) {
            newID = ubahKodeMasuk(existingID);
        }
        var div = document.createElement('div');
        div.className = 'barang2';
        div.innerHTML = `
                        <hr>
                        <input hidden value="<?= $user; ?>" name="barang[${counter}][user_id]" type="text" readonly="readonly" class="form-control">
                        <input hidden value="${newID}" id="input_${counter}_id" name="barang[${counter}][id_barang_masuk]" type="text" readonly="readonly" class="form-control">
                        <input hidden value="<?= set_value('tanggal_masuk', date('Y-m-d')); ?>" name="barang[${counter}][tanggal_masuk]" readonly="readonly" id="tanggal_masuk" type="text" class="form-control date" placeholder="Tanggal Masuk...">
                        <div class="row form-group">
                            <label class="col-md-4 text-md-right" for="supplier_id">Supplier</label>
                            <div class="col-md-5">
                                <div class="input-group">
                                    <select name="barang[${counter}][supplier_id]" id="supplier_id" class="custom-select">
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
                        <div class="row form-group">
                            <label class="col-md-4 text-md-right" for="barang_id">Barang</label>
                            <div class="col-md-5">
                                <div class="input-group">
                                    <select name="barang[${counter}][barang_id]" id="barang" class="custom-select">
                                        <option value="" selected disabled>Pilih Barang</option>
                                        <?php foreach ($barang as $b) : ?>
                                            <option <?= $this->uri->segment(3) == $b['id_barang'] ? 'selected' : '';  ?> <?= set_select('barang_id', $b['id_barang']) ?> value="<?= $b['id_barang'] ?>"><?= $b['id_barang'] . ' | ' . $b['nama_barang'] ?></option>
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
                            <label class="col-md-4 text-md-right" for="jumlah_masuk">Jumlah Barang</label>
                            <div class="col-md-5">
                                <div class="input-group">
                                    <input value="<?= set_value('jumlah_masuk'); ?>" name="barang[${counter}][jumlah_masuk]" id="jumlah_masuk" type="number" class="form-control" placeholder="Jumlah Barang">
                                    <div class="input-group-append">
                                        <span class="input-group-text" id="satuan">Satuan</span>
                                    </div>
                                </div>
                                <?= form_error('jumlah_masuk', '<small class="text-danger">', '</small>'); ?>
                            </div>
                        </div>
            
            `;
        document.querySelector('#form-container2').appendChild(div);
        counter++;
        counterInput++;
    }


    function cancelForm2() {
        var forms = document.querySelectorAll('.barang2');
        for (var i = 1; i < forms.length; i++) {
            forms[i].remove();
        }
        counter = 1;
        counterInput = 0;
    }

    function ubahKodeMasuk(existingInputValue) {
        var lastDigits = parseInt(existingInputValue.slice(-5)) + 1;
        var newDigits = lastDigits.toString().padStart(5, '0');
        var newID = existingInputValue.slice(0, -5) + newDigits;
        return newID;
    }
</script>