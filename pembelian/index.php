<?php

session_start();

if (!isset($_SESSION["sslogin"])) {
    header("location: ../auth/login.php");
    exit();
}

require "../config/config.php";
require "../config/function.php";
require "../module/mode_beli.php";

$title = "Transaksi Pembelian - Cafe Qita";
require "../partials/header.php";
require "../partials/navbar.php";
require "../partials/sidebar.php";

if (isset($_GET['msg'])) {
    $msg = $_GET['msg'];
} else {
    $msg = '';
}

if ($msg == 'deleted') {
    $idbhn = $_GET['idbhn'];
    $idbeli = $_GET['idbeli'];
    $qty = $_GET['qty'];
    $tgl = $_GET['tgl'];
    delete($idbhn, $idbeli, $qty);
    echo "<script>
            document.location = '?tgl=$tgl';
    </script>";
}

// Initialize $kode and retrieve the selected ingredient if $kode is set
$kode = @$_GET['pilihbhn'] ? @$_GET['pilihbhn'] : '';

if ($kode) {
    $selectbhn = getData("SELECT * FROM tb_bahan WHERE id_bahan = '$kode'")[0];
}

if (isset($_POST['addbhn'])) {
    $tgl = $_POST['tglnota'];
    $qty = $_POST['qty'];
    if (empty($qty)) {
        echo "<script>alert('Jumlah produk tidak boleh kosong!');</script>";
    } else {
        if (insert($_POST)) {
            echo "<script>
                    document.location = 'index.php?tgl=$tgl';
            </script>";
        }
    }
}

if (isset($_POST['simpan'])) {
    if (simpan($_POST)) {
        echo "<script>
                alert('data pembelian berhasil disimpan');
                document.location = 'cetak_struk.php?no_transbeli=$notransbeli';
        </script>";
    }
}

$notransbeli = generateno();

?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Pembelian</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= $main_url ?>dashboard.php">Home</a></li>
                        <li class="breadcrumb-item active">Tambah Pembelian</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>

    <section>
        <div class="container-fluid">
            <form action="" method="post">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="card card-outline card-warning p-3">
                            <div class="form-group row mb-2">
                                <label for="no_transbeli" class="col-sm-2 col-form-label">No Nota</label>
                                <div class="col-sm-4">
                                    <input type="text" name="no_transbeli" class="form-control" id="no_transbeli" value="<?= $notransbeli ?>" readonly required>
                                </div>
                                <label for="tglnota" class="col-sm-2 col-form-label">Tgl Nota</label>
                                <div class="col-sm-4">
                                    <input type="date" name="tglnota" class="form-control" id="tglnota" value="<?= @$_GET['tgl'] ? $_GET['tgl'] : date('Y-m-d') ?>" required>
                                </div>
                            </div>
                            <div class="form-group row mb-2">
                                <label for="idbahan" class="col-sm-2 col-form-label">SKU</label>
                                <div class="col-sm-10">
                                    <select name="idbahan" id="idbahan" class="form-control">
                                        <option value="">-- Pilih Kode Bahan --</option>
                                        <?php
                                        $bahan = getData("SELECT * FROM tb_bahan");
                                        foreach ($bahan as $bhn) { ?>
                                            <option value="?pilihbhn=<?= $bhn['id_bahan'] ?><?= @$_GET['pilihbhn'] == $bhn['id_bahan'] ? 'selected' : null ?>"><?= $bhn['id_bahan'] . " | " . $bhn['nm_bahan'] ?></option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="card card-outline card-danger pt-3 px-3 pb-2">
                            <h6 class="font-weight-bold text-right">Total Pembelian</h6>
                            <h1 class="font-weight-bold text-right" style="font-size: 40pt;">
                            <input type="hidden" name="total" value="<?= totalbeli($notransbeli) ?>">    
                            <?= number_format(totalbeli($notransbeli), 0, ',', '.') ?></h1>
                        </div>
                    </div>
                </div>
                <div class="card pt-1 pb-2 px-3">
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="form-group">
                                <input type="hidden" value="<?= @$_GET['pilihbhn'] ? $selectbhn['id_bahan'] : '' ?>" name="idbahan">
                                <label for="nmbahan">Nama Bahan</label>
                                <input type="text" name="nmbahan" class="form-control form-control-sm" id="nmbahan" value="<?= @$_GET['pilihbhn'] ? $selectbhn['nm_bahan'] : '' ?>" readonly>
                            </div>
                        </div>
                        <div class="col-lg-1">
                            <div class="form-group">
                                <label for="stok">Stok Bahan</label>
                                <input type="number" name="stok" class="form-control form-control-sm" id="stok" value="<?= @$_GET['pilihbhn'] ? $selectbhn['stok'] : '' ?>" readonly>
                            </div>
                        </div>
                        <div class="col-lg-1">
                            <div class="form-group">
                                <label for="satuan">Satuan</label>
                                <input type="text" name="satuan" class="form-control form-control-sm" id="satuan" value="<?= @$_GET['pilihbhn'] ? $selectbhn['satuan'] : '' ?>" readonly>
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div class="form-group">
                                <label for="harga">Harga</label>
                                <input type="number" name="harga" class="form-control form-control-sm" id="harga" value="<?= @$_GET['pilihbhn'] ? $selectbhn['harga_beli'] : '' ?>" readonly>
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div class="form-group">
                                <label for="qty">Qty</label>
                                <input type="number" name="qty" class="form-control form-control-sm" id="qty" value="<?= @$_GET['pilihbhn'] ? 1 : '' ?>">
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div class="form-group">
                                <label for="jmlharga">Jumlah Harga</label>
                                <input type="number" name="jmlharga" class="form-control form-control-sm" id="jmlharga" value="<?= @$_GET['pilihbhn'] ? $selectbhn['harga_beli'] : '' ?>" readonly>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-sm btn-info btn-block" name="addbhn"><i class="fas fa-cart-plus fa-sm mr-2"></i>Tambah Bahan</button>
                </div>
                <div class="card card-outline card-success table-responsive px-2">
                    <table class="table table-sm table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode Bahan</th>
                                <th>Nama Bahan</th>
                                <th class="text-right">Harga</th>
                                <th class="text-right">Qty</th>
                                <th class="text-right">Jumlah Harga</th>
                                <th class="text-center" width="10%">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            $bhndetail = getData("SELECT * FROM trans_pemdetail WHERE no_transbeli = '$notransbeli'");
                            foreach ($bhndetail as $detail) { ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= $detail['id_bahan'] ?></td>
                                    <td><?= $detail['nm_bahan'] ?></td>
                                    <td class="text-right"><?= number_format($detail['harga_beli'], 0, ',', '.') ?></td>
                                    <td class="text-right"><?= $detail['jumlah'] ?></td>
                                    <td class="text-right"><?= number_format($detail['jml_harga'], 0, ',', '.') ?></td>
                                    <td class="text-center">
                                        <a href="?idbhn=<?= $detail['id_bahan'] ?>&idbeli=<?= $detail['no_transbeli'] ?>&qty=<?= $detail['jumlah'] ?>&tgl=<?= $detail['tgl_transbeli'] ?>&msg=deleted" class="btn btn-sm btn-danger" title="hapus barang" onclick="return confirm('Apakah anda yakin?')"><i class="fas fa-trash"></i></a>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>

                <div class="row">
                    <div class="col-lg-6 p-2">
                        <div class="form-group row mb-2">
                        <label for="supplier" class="col-sm-3 col-form-label col-form-label-sm">Supplier</label>
                        <div class="col-sm-9">
                            <select name="supplier" id="supplier" class="form-control form-control-sm">
                                <option value="">-- Pilih Supplier --</option>
                                <?php
                                        $suppliers = getData("SELECT * FROM tb_supplier WHERE status = '1'");
                                        foreach ($suppliers as $supplier) { ?>
                                            <option value="<?= $supplier['nm_supplier']?>"><?= $supplier['nm_supplier']?></option>
                                        <?php
                                        }
                                        ?>
                            </select>
                        </div>
                        </div>
                        <div class="form-group row mb-2">
                            <label for="keterangan" class="col-sm-3 col-form-label">Keterangan</label>
                            <div class="col-sm-9">
                                <textarea name="keterangan" id="keterangan" class="form-control form-control-sm"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 p-2">
                        <button type="submit" name="simpan" id="simpan" class="btn btn-primary btn-sm btn-block"><i class="fas fa-save fa-sm mr-2"></i>Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </section>

<script>
    let pilihbhn = document.getElementById('idbahan');
    let tgl = document.getElementById('tglnota');
    pilihbhn.addEventListener('change', function(){
        document.location.href = this.options[this.selectedIndex].value + '&tgl=' + tgl.value;
    })

    let qty = document.getElementById('qty');
    let jmlharga = document.getElementById('jmlharga');
    let harga = document.getElementById('harga');
    qty.addEventListener('input', function(){
        jmlharga.value = qty.value * harga.value;
    })
</script>

<?php

require "../partials/footer.php";

?>
