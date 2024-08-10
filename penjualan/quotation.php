<?php
session_start();

if (!isset($_SESSION["sslogin"])) {
    header("location: ../auth/login.php");
    exit();
}

require "../config/config.php";
require "../config/function.php";
require "../module/mode_produksi.php"; // Pastikan file ini di-include

$title = "Quotation - Cafe Qita";
require "../partials/header.php";
require "../partials/navbar.php";
require "../partials/sidebar.php";

// Inisialisasi session untuk menyimpan detail quotation sementara
if (!isset($_SESSION['quotation'])) {
    $_SESSION['quotation'] = [];
}

$kode = @$_GET['pilihpdk'] ? $_GET['pilihpdk'] : '';
$selectpdk = null; // Pastikan $selectpdk terdefinisi
if ($kode) {
    $tgl = $_GET['tgl'];
    $datapdk = mysqli_query($koneksi, "
        SELECT 
            p.*, 
            COALESCE(SUM(pro.total_produksi), 0) AS stok 
        FROM 
            tb_produk p 
            LEFT JOIN tb_produksi pro ON p.id_produk = pro.id_produk 
        WHERE 
            p.id_produk = '$kode'
    ");
    if (mysqli_num_rows($datapdk) > 0) {
        $selectpdk = mysqli_fetch_assoc($datapdk);
    } else {
        echo "<script>
                alert('produk tidak ada');
                document.location = 'quotation.php?tgl=$tgl';
              </script>";
    }
}

if (isset($_POST['addpdk'])) {
    $produk = [
        'id_produk' => $_POST['kodeproduk'],
        'nm_produk' => $_POST['nmproduk'],
        'harga' => $_POST['harga'],
        'qty' => $_POST['qty'],
        'jml_harga' => $_POST['jmlharga'],
        'tglnota' => $_POST['tglnota'],
        'noquotation' => $_POST['noquotation']
    ];
    $_SESSION['quotation'][] = $produk;
}

if (isset($_POST['simpan'])) {
    $tgl = $_POST['tglnota'];
    $noquotation = $_POST['noquotation'];
    $customer = $_POST['customer'];
    $keterangan = $_POST['keterangan'];

    // Hitung total harga
    $total = array_sum(array_column($_SESSION['quotation'], 'jml_harga'));

    $quotation = [
        'noquotation' => $noquotation,
        'tglnota' => $tgl,
        'customer' => $customer,
        'total' => $total,
        'keterangan' => $keterangan
    ];

    if (insertQuotation($quotation)) {
        foreach ($_SESSION['quotation'] as $produk) {
            insertQuotationDetail($produk);
        }
        unset($_SESSION['quotation']);
        echo "<script>
                document.location = 'quotation.php?tgl=$tgl';
              </script>";
    }
}

$noquotation = generateno();

?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Quotation Produk</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= $main_url ?>dashboard.php">Home</a></li>
                        <li class="breadcrumb-item active">Tambah Quotation</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section>
        <div class="container-fluid">
            <form action="" method="post">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="card card-outline card-warning p-3">
                            <div class="form-group row mb-2">
                                <label for="noquotation" class="col-sm-2 col-form-label">No Nota</label>
                                <div class="col-sm-4">
                                    <input type="text" name="noquotation" class="form-control" id="noquotation" value="<?= $noquotation ?>" readonly>
                                </div>
                                <label for="tglnota" class="col-sm-2 col-form-label">Tgl Nota</label>
                                <div class="col-sm-4">
                                    <input type="date" name="tglnota" class="form-control" id="tglnota" value="<?= @$_GET['tgl'] ? $_GET['tgl'] : date('Y-m-d') ?>" required>
                                </div>
                            </div>
                            <div class="form-group row mb-2">
                                <label for="kodeproduk" class="col-sm-2 col-form-label">SKU</label>
                                <div class="col-sm-10">
                                    <select name="kodeproduk" id="kodeproduk" class="form-control">
                                        <option value="">-- Pilih Kode Produk --</option>
                                        <?php
                                        $produk = getData("SELECT * FROM tb_produk");
                                        foreach ($produk as $pdk) { ?>
                                            <option value="?pilihpdk=<?= $pdk['id_produk'] ?><?= @$_GET['pilihpdk'] == $pdk['id_produk'] ? 'selected' : null ?>"><?= $pdk['id_produk'] . " | " . $pdk['nm_produk'] ?></option>
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
                            <h6 class="font-weight-bold text-right">Total Quotation</h6>
                            <h1 class="font-weight-bold text-right" style="font-size: 40pt;">
                            <input type="hidden" name="total" id="total" value="<?= totalQuotation($noquotation) ?>"><?= number_format(totalQuotation($noquotation),0,',','.') ?></h1>
                        </div>
                    </div>
                  </div>
                  <div class="card pt-1 pb-2 px-3">
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="form-group">
                                <input type="hidden" value="<?= @$_GET['pilihpdk'] ? $selectpdk['id_produk'] : '' ?>" name="kodeproduk">
                                <label for="nmproduk">Nama Produk</label>
                                <input type="text" name="nmproduk" class="form-control form-control-sm" id="nmproduk" value="<?= @$_GET['pilihpdk'] ? $selectpdk['nm_produk'] : '' ?>" readonly>
                            </div>
                        </div>
                        <div class="col-lg-1">
                            <div class="form-group">
                                <label for="stok">Stok Produk</label>
                                <input type="number" name="stok" class="form-control form-control-sm" id="stok" value="<?= @$_GET['pilihpdk'] ? $selectpdk['stok'] : '' ?>" readonly>
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div class="form-group">
                                <label for="harga">Harga</label>
                                <input type="number" name="harga" class="form-control form-control-sm" id="harga" value="<?= @$_GET['pilihpdk'] ? $selectpdk['harga_jual'] : '' ?>" readonly>
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div class="form-group">
                                <label for="qty">Qty</label>
                                <input type="number" name="qty" class="form-control form-control-sm" id="qty" value="<?= @$_GET['pilihpdk'] ? 1 : '' ?>">
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div class="form-group">
                                <label for="jmlharga">Jumlah Harga</label>
                                <input type="number" name="jmlharga" class="form-control form-control-sm" id="jmlharga" value="<?= @$_GET['pilihpdk'] ? $selectpdk['harga_jual'] : '' ?>" readonly>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-sm btn-info btn-block" name="addpdk"><i class="fas fa-cart-plus fa-sm mr-2"></i>Tambah Produk</button>
                  </div>
                  <div class="card card-outline card-success table-responsive px-2">
                    <table class="table table-sm table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode Produk</th>
                                <th>Nama Produk</th>
                                <th class="text-right">Harga</th>
                                <th class="text-right">Qty</th>
                                <th class="text-center">Jumlah Harga</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                            $no = 1;
                            foreach ($_SESSION['quotation'] as $detail) { ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= $detail['id_produk'] ?></td>
                                    <td><?= $detail['nm_produk'] ?></td>
                                    <td class="text-right"><?= number_format($detail['harga'], 0, ',', '.') ?></td>
                                    <td class="text-right"><?= $detail['qty'] ?></td>
                                    <td class="text-right"><?= number_format($detail['jml_harga'], 0, ',', '.') ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
                <div class="row">
                <div class="col-lg-4 p-2">
                        <div class="form-group row mb-2">
                        <label for="customer" class="col-sm-3 col-form-label col-form-label-sm">Customer</label>
                        <div class="col-sm-9">
                            <select name="customer" id="customer" class="form-control form-control-sm">
                                <option value="">-- Pilih Customer --</option>
                                <?php
                                        $customers = getData("SELECT * FROM tb_customer");
                                        foreach ($customers as $customer) { ?>
                                            <option value="<?= $customer['nm_customer'] ?>"><?= $customer['nm_customer'] ?></option>
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
                    <div class="col-lg-4 py-2 px-3">
                    </div>
                    <div class="col-lg-4 p-2">
                        <button type="submit" name="simpan" id="simpan" class="btn btn-primary btn-sm btn-block"><i class="fas fa-save fa-sm mr-2"></i>Simpan</button>
                    </div>
                </div>
              </form>
            </section>

<script>
    let pilihpdk = document.getElementById('kodeproduk');
    let tgl = document.getElementById('tglnota');

    pilihpdk.addEventListener('change', function(){
        document.location.href = this.options[this.selectedIndex].value + '&tgl=' + tgl.value;
    });

    let qty = document.getElementById('qty');
    let harga = document.getElementById('harga');
    let jmlharga = document.getElementById('jmlharga');
    qty.addEventListener('input', function(){
        jmlharga.value = qty.value * harga.value;
    });

    let total = document.getElementById('total').value;

</script>

<?php

require "../partials/footer.php";

?>
