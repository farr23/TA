<?php
session_start();

if (!isset($_SESSION["sslogin"])) {
    header("location: ../auth/login.php");
    exit();
}

require "../config/config.php";
require "../config/function.php";
require "../module/mode_detail.php";

$title = "Detail Pembelian - Cafe Qita";
require "../partials/header.php";
require "../partials/navbar.php";
require "../partials/sidebar.php";

$no_transbeli = isset($_GET['no_transbeli']) ? $_GET['no_transbeli'] : '';
if (empty($no_transbeli)) {
    echo "<p>Nomor transaksi tidak tersedia.</p>";
    exit();
}

// Ambil data transaksi pembelian
$pembelian = getData("SELECT * FROM trans_pembelian WHERE no_transbeli = '$no_transbeli'")[0];
if (!$pembelian) {
    echo "<p>Data transaksi tidak ditemukan.</p>";
    exit();
}

// Ambil detail pembelian
$detail_pembelian = getData("SELECT * FROM trans_pemdetail WHERE no_transbeli = '$no_transbeli'");

?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Detail Pembelian</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= $main_url ?>dashboard.php">Home</a></li>
                        <li class="breadcrumb-item"><a href="transaksi_pembelian.php">Transaksi Pembelian</a></li>
                        <li class="breadcrumb-item active">Detail Pembelian</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>

    <section>
        <div class="container-fluid">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h5>No Transaksi: <?= $pembelian['no_transbeli'] ?></h5>
                    <p>Tanggal: <?= $pembelian['tgl_transbeli'] ?></p>
                    <p>Supplier: <?= $pembelian['nm_supplier'] ?></p>
                    <p>Total: Rp. <?= number_format($pembelian['total'], 2, ',', '.') ?></p>
                    <p>Keterangan: <?= $pembelian['keterangan'] ?></p>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>ID Bahan</th>
                                    <th>Nama Bahan</th>
                                    <th class="text-right">Harga</th>
                                    <th class="text-right">Jumlah</th>
                                    <th class="text-right">Jumlah Harga</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $no = 1;
                                foreach ($detail_pembelian as $detail) { ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><?= $detail['id_bahan'] ?></td>
                                        <td><?= $detail['nm_bahan'] ?></td>
                                        <td class="text-right"><?= number_format($detail['harga_beli'], 2, ',', '.') ?></td>
                                        <td class="text-right"><?= $detail['jumlah'] ?></td>
                                        <td class="text-right"><?= number_format($detail['jml_harga'], 2, ',', '.') ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer">
                    <a href="transaksi_pembelian.php" class="btn btn-secondary">Kembali</a>
                </div>
            </div>
        </div>
    </section>
</div>

<?php
require "../partials/footer.php";
?>
