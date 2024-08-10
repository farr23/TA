<?php
session_start();

if (!isset($_SESSION["sslogin"])) {
    header("location: ../auth/login.php");
    exit();
}

require "../config/config.php";
require "../config/function.php";
require "../module/mode_pesan.php";

$title = "Transaksi Pesanan - Cafe Qita";
require "../partials/header.php";
require "../partials/navbar.php";
require "../partials/sidebar.php";

if (!isset($_SESSION['pesanan'])) {
    $_SESSION['pesanan'] = [];
}

$kode = @$_GET['pilihproduk'] ? $_GET['pilihproduk'] : '';
$selectproduk = null;

if ($kode) {
    $dataproduk = mysqli_query($koneksi, "
        SELECT 
            p.*, 
            COALESCE(SUM(pro.total_produksi), 0) AS stok 
        FROM 
            tb_produk p 
            LEFT JOIN tb_produksi pro ON p.id_produk = pro.id_produk 
        WHERE 
            p.id_produk = '$kode'
    ");
    if (mysqli_num_rows($dataproduk) > 0) {
        $selectproduk = mysqli_fetch_assoc($dataproduk);
    } else {
        echo "<script>
                alert('Produk tidak ada');
                document.location = 'index.php';
              </script>";
    }
}

if (isset($_POST['addproduk'])) {
    $produk = [
        'id_produk' => $_POST['kodeproduk'],
        'nm_produk' => $_POST['nmproduk'],
        'harga' => $_POST['harga'],
        'qty' => $_POST['qty'],
        'jml_harga' => $_POST['jmlharga'],
        'tglpesan' => $_POST['tglpesan'],
        'nopesan' => $_POST['nopesan'],
        'status' => 'Pending'
    ];
    $_SESSION['pesanan'][] = $produk;
}

if (isset($_POST['simpan'])) {
    $tgl = $_POST['tglpesan'];
    $nopesan = $_POST['nopesan'];
    $customer = $_POST['customer'];
    $keterangan = $_POST['keterangan'];
    $bayar = $_POST['bayar'];
    $kembalian = $_POST['kembalian'];

    $total = array_sum(array_column($_SESSION['pesanan'], 'jml_harga'));

    if ($bayar < $total) {
        echo "<script>
                alert('Pembayaran tidak cukup');
                window.history.back();
              </script>";
        exit();
    }

    $pesanan = [
        'nopesan' => $nopesan,
        'tglpesan' => $tgl,
        'customer' => $customer,
        'total' => $total,
        'keterangan' => $keterangan,
        'bayar' => $bayar,
        'kembalian' => $kembalian
    ];

    if (insertPesanan($pesanan)) {
        foreach ($_SESSION['pesanan'] as $produk) {
            kurangiStok($produk['id_produk'], $produk['qty']);
            insertDetailPesanan($produk);
        }
        unset($_SESSION['pesanan']);
        echo "<script>
                document.location = 'index.php?tgl=$tgl';
              </script>";
    }
}

$nopesan = generateNo();

?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Pesanan Produk</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= $main_url ?>dashboard.php">Home</a></li>
                        <li class="breadcrumb-item active">Tambah Pesanan</li>
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
                                <label for="nopesan" class="col-sm-2 col-form-label">No Pesan</label>
                                <div class="col-sm-4">
                                    <input type="text" name="nopesan" class="form-control" id="nopesan" value="<?= $nopesan ?>" readonly>
                                </div>
                                <label for="tglpesan" class="col-sm-2 col-form-label">Tgl Pesan</label>
                                <div class="col-sm-4">
                                    <input type="date" name="tglpesan" class="form-control" id="tglpesan" value="<?= @$_GET['tgl'] ? $_GET['tgl'] : date('Y-m-d') ?>" required>
                                </div>
                            </div>
                            <div class="form-group row mb-2">
                                <label for="kodeproduk" class="col-sm-2 col-form-label">SKU</label>
                                <div class="col-sm-10">
                                    <select name="kodeproduk" id="kodeproduk" class="form-control">
                                        <option value="">-- Pilih Kode Produk --</option>
                                        <?php
                                        $produk = getProducts($koneksi);
                                        foreach ($produk as $pdk) { ?>
                                            <option value="<?= $pdk['id_produk'] ?>" <?= @$_GET['pilihproduk'] == $pdk['id_produk'] ? 'selected' : '' ?>><?= $pdk['id_produk'] . " | " . $pdk['nm_produk'] ?></option>
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
                            <h6 class="font-weight-bold text-right">Total Pesanan</h6>
                            <h1 class="font-weight-bold text-right" style="font-size: 40pt;">
                            <input type="hidden" name="total" id="total" value="<?= totalPesanan() ?>"><?= number_format(totalPesanan(),0,',','.') ?></h1>
                        </div>
                    </div>
                </div>
                <div class="card pt-1 pb-2 px-3">
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="form-group">
                                <input type="hidden" value="<?= @$_GET['pilihproduk'] ? $selectproduk['id_produk'] : '' ?>" name="kodeproduk">
                                <label for="nmproduk">Nama Produk</label>
                                <input type="text" name="nmproduk" class="form-control form-control-sm" id="nmproduk" value="<?= @$_GET['pilihproduk'] ? $selectproduk['nm_produk'] : '' ?>" readonly>
                            </div>
                        </div>
                        <div class="col-lg-1">
                            <div class="form-group">
                                <label for="stok">Stok Produk</label>
                                <input type="number" name="stok" class="form-control form-control-sm" id="stok" value="<?= @$_GET['pilihproduk'] ? $selectproduk['stok'] : '' ?>" readonly>
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div class="form-group">
                                <label for="harga">Harga</label>
                                <input type="number" name="harga" class="form-control form-control-sm" id="harga" value="<?= @$_GET['pilihproduk'] ? $selectproduk['harga_jual'] : '' ?>" readonly>
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div class="form-group">
                                <label for="qty">Qty</label>
                                <input type="number" name="qty" class="form-control form-control-sm" id="qty" value="<?= @$_GET['pilihproduk'] ? 1 : '' ?>">
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div class="form-group">
                                <label for="jmlharga">Jumlah Harga</label>
                                <input type="number" name="jmlharga" class="form-control form-control-sm" id="jmlharga" value="<?= @$_GET['pilihproduk'] ? $selectproduk['harga_jual'] : '' ?>" readonly>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-sm btn-info btn-block" name="addproduk"><i class="fas fa-cart-plus fa-sm mr-2"></i>Tambah Produk</button>
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
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                            $no = 1;
                            foreach ($_SESSION['pesanan'] as $detail) { ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= $detail['id_produk'] ?></td>
                                    <td><?= $detail['nm_produk'] ?></td>
                                    <td class="text-right"><?= number_format($detail['harga'], 0, ',', '.') ?></td>
                                    <td class="text-right"><?= $detail['qty'] ?></td>
                                    <td class="text-right"><?= number_format($detail['jml_harga'], 0, ',', '.') ?></td>
                                    <td><?= isset($detail['status']) ? $detail['status'] : 'Unknown' ?></td>
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
                                    $customers = getCustomers($koneksi);
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
                        <div class="form-group row mb-2">
                            <label for="bayar" class="col-sm-3 col-form-label">Bayar</label>
                            <div class="col-sm-9">
                                <input type="number" name="bayar" class="form-control form-control-sm text-right" id="bayar">
                            </div>
                        </div>
                        <div class="form-group row mb-2">
                            <label for="kembalian" class="col-sm-3 col-form-label">Kembalian</label>
                            <div class="col-sm-9">
                                <input type="number" name="kembalian" class="form-control form-control-sm text-right" id="kembalian" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 p-2">
                        <button type="submit" name="simpan" id="simpan" class="btn btn-primary btn-sm btn-block"><i class="fas fa-save fa-sm mr-2"></i>Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </section>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        let pilihproduk = document.getElementById('kodeproduk');
        let tgl = document.getElementById('tglpesan');

        pilihproduk.addEventListener('change', function(){
            let selectedValue = this.value;
            if (selectedValue) {
                document.location.href = '?pilihproduk=' + selectedValue + '&tgl=' + tgl.value;
            }
        });

        let qty = document.getElementById('qty');
        let harga = document.getElementById('harga');
        let jmlharga = document.getElementById('jmlharga');
        qty.addEventListener('input', function(){
            jmlharga.value = qty.value * harga.value;
        });

        let bayar = document.getElementById('bayar');
        let total = document.getElementById('total').value;
        let kembalian = document.getElementById('kembalian');

        bayar.addEventListener('input', function() {
            kembalian.value = bayar.value - total;
        });
    });
</script>

<?php
require "../partials/footer.php";
?>
