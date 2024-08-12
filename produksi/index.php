<?php
session_start();

if (!isset($_SESSION["sslogin"])) {
    header("location: ../auth/login.php");
    exit();
}

require "../config/config.php";
require "../config/function.php";
require "../module/mode_produksi.php";

$title = "Transaksi Produksi - Cafe Qita";
require "../partials/header.php";
require "../partials/navbar.php";
require "../partials/sidebar.php";

$msg = isset($_GET['msg']) ? $_GET['msg'] : '';

if ($msg == 'deleted') {
    $idbahan = $_GET['idbahan'];
    $noproduksi = $_GET['noproduksi'];
    $qty = $_GET['qty'];
    $tgl = $_GET['tgl'];
    delete($idbahan, $noproduksi, $qty);
    echo "<script>
            document.location = '?tgl=$tgl';
    </script>";
}

if (isset($_GET['idpesanan'])) {
    $idPesanan = $_GET['idpesanan'];
    $queryGetPesanan = "SELECT * FROM tb_detail_pesanan 
    LEFT JOIN tb_pesanan ON tb_detail_pesanan.id_pesanan = tb_pesanan.id_pesanan
    LEFT JOIN tb_produk ON tb_detail_pesanan.id_produk = tb_produk.id_produk
    LEFT JOIN tb_kategori ON tb_produk.id_kategori = tb_kategori.id_kategori
    WHERE tb_detail_pesanan.id_pesanan ='$idPesanan'";

    $getPesanan = $koneksi->query($queryGetPesanan);

    while ($row = mysqli_fetch_assoc($getPesanan)) {
        $idProduk = $row['id_produk'];
        $queryGetResep = "SELECT * FROM tb_resep LEFT JOIN tb_bahan ON tb_resep.id_bahan = tb_bahan.id_bahan WHERE id_produk = '$idProduk'";
        $getResep = $koneksi->query($queryGetResep);
        while ($rowResep = mysqli_fetch_assoc($getResep)) {
            $row['resep'][] = $rowResep;
        }
        $pesanan[] = $row;
    }
}

if (isset($_POST['simpan'])) {

    if (simpan($_POST)) {
        echo "<script>
                alert('Data produksi berhasil disimpan');
                document.location = 'hasil_produksi.php';
        </script>";
    }
}

$pendingOrders = getData("SELECT * FROM tb_pesanan WHERE status = 'Pending'");


$notransproduksi = generateno();

?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Produksi</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= $main_url ?>dashboard.php">Home</a></li>
                        <li class="breadcrumb-item active">Tambah Produksi</li>
                    </ol>
                </div>
            </div>
        </div>


        <section>
            <div class="container-fluid">
                <?php
                if (isset($_GET['idpesanan'])) {
                    ?>
                    <form action="" method="post">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card card-outline card-warning p-3">
                                    <div class="form-group row mb-2">
                                        <label for="no_transprod" class="col-sm-2 col-form-label">No Produksi</label>
                                        <div class="col-sm-4">
                                            <input type="text" name="no_transprod" class="form-control" id="no_transprod"
                                                value="<?= $notransproduksi ?>" readonly>
                                        </div>
                                        <label for="tglproduksi" class="col-sm-2 col-form-label">Tgl Produksi</label>
                                        <div class="col-sm-4">
                                            <input type="date" name="tglproduksi" class="form-control" id="tglproduksi"
                                                value="<?= isset($_GET['tgl']) ? $_GET['tgl'] : date('Y-m-d') ?>" required>
                                        </div>
                                    </div>

                                </div>
                            </div>

                        </div>
                        <?php
                        foreach ($pesanan as $key => $value) {
                            ?>
                            <div class="card pb-2 px-3">
                                <div class="card-header text-center">
                                    <?= $value['jumlah'] . ' ' . $value['nm_produk'] ?>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <?php
                                        foreach ($value['resep'] as $resep) {
                                            ?>
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label for="bahan" class="form-label">Bahan</label>
                                                        <input type="text" class="form-control" name="bahan[<?= $key ?>][]" id="bahan[<?= $key ?>][]"
                                                            aria-describedby="helpId" placeholder="" value="<?= $resep['nm_bahan'] ?>" readonly />
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <label for="jumlahbahan" class="form-label">Jumlah</label>
                                                    <input type="text" class="form-control" name="jumlahbahan[<?= $key ?>][]" id="jumlahbahan[<?= $key ?>][]"
                                                        aria-describedby="helpId" placeholder="" value="<?= $value['jumlah'] * $resep['jumlah'] ?>" readonly/>
                                                </div>
                                            </div>
                                            <?php
                                        }
                                        ?>
                                    </div>
                                </div>
                                <div class="card-footer text-muted text-center">
                                    <?= $value['nm_kategori'] ?>
                                </div>
                            </div>
                            <?php
                        }
                        ?>
                        <div class="card-footer">
                            <button type="submit" name="simpan" class="btn btn-primary">Simpan</button>
                            <a href="index.php" class="btn btn-secondary">Batal</a>
                        </div>
                    </form>
                    <?php
                }
                ?>
            </div>
            <h5>Pesanan Siap Diproduksi</h5>
            <table class="table table-sm table-hover text-nowrap">
                <thead>
                    <tr>
                        <th>No Pesan</th>
                        <th>Tanggal</th>
                        <th>Customer</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pendingOrders as $order) { ?>
                        <tr>
                            <td><?= $order['id_pesanan'] ?></td>
                            <td><?= $order['tanggal_pesanan'] ?></td>
                            <td><?= $order['nm_customer'] ?></td>
                            <td><?= number_format($order['total'], 0, ',', '.') ?></td>
                            <td><?= $order['status'] ?></td>
                            <td>
                                <form action="" method="post">
                                    <input type="hidden" name="id_pesanan" value="<?= $order['id_pesanan'] ?>">

                                    <?php
                                    if (isset($_GET['idpesanan'])) {
                                        if ($_GET['idpesanan'] == $order['id_pesanan']) {
                                            ?>
                                            <a name="prosespesanan" id="prosespesanan" class="btn btn-primary"
                                                href="?idpesanan=<?= $order['id_pesanan'] ?>" disabled role="button">Dipilih</a>
                                            <?php
                                        } else {
                                            ?>
                                            <a name="prosespesanan" id="prosespesanan" class="btn btn-success"
                                                href="?idpesanan=<?= $order['id_pesanan'] ?>" role="button">Proses Pesanan</a>
                                            <?php
                                        }
                                    } else {
                                        ?>
                                        <a name="prosespesanan" id="prosespesanan" class="btn btn-success"
                                            href="?idpesanan=<?= $order['id_pesanan'] ?>" role="button">Proses Pesanan</a>
                                        <?php
                                    }
                                    ?>

                                </form>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </section>
    </div>



    <?php
    require "../partials/footer.php";
    ?>