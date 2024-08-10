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

$selectedProduk = null;
$selectedKategori = null;
if (isset($_GET['pilihproduk'])) {
    $kode = $_GET['pilihproduk'];
    $produkData = getData("SELECT * FROM tb_produk WHERE id_produk = '$kode'");
    $kategoriData = getData("SELECT * FROM tb_kategori WHERE id_kategori = (SELECT id_kategori FROM tb_produk WHERE id_produk = '$kode')AND status = '1'");

    $selectedProduk = !empty($produkData) ? $produkData[0] : null;
    $selectedKategori = !empty($kategoriData) ? $kategoriData[0] : null;
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
                                <div class="form-group row mb-2">
                                    <label for="idproduk" class="col-sm-2 col-form-label">Produk</label>
                                    <div class="col-sm-10">
                                        <select name="idproduk" id="idproduk" class="form-control"
                                            onchange="location = this.value;">
                                            <option value="">-- Pilih Kode Produk --</option>
                                            <?php
                                            $produk = getData("SELECT * FROM tb_produk WHERE status = '1'");
                                            foreach ($produk as $pdk) { ?>
                                                <option value="?pilihproduk=<?= $pdk['id_produk'] ?>"
                                                    <?= isset($_GET['pilihproduk']) && $_GET['pilihproduk'] == $pdk['id_produk'] ? 'selected' : '' ?>>
                                                    <?= $pdk['id_produk'] . " | " . $pdk['nm_produk'] ?>
                                                </option>
                                                <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <?php
                                if (isset($_GET['pilihproduk'])) {
                                    $kodeProduk = $_GET['pilihproduk'];
                                    $queryGetResep = "SELECT * FROM tb_resep LEFT JOIN tb_bahan ON tb_resep.id_bahan = tb_bahan.id_bahan WHERE tb_resep.id_produk = '$kodeProduk'";
                                    $getResep = mysqli_query($koneksi, $queryGetResep);
                                    if (mysqli_num_rows($getResep) > 0) {
                                        while ($row = mysqli_fetch_assoc($getResep)) {
                                            ?>
                                            <div id="bahan-section">
                                                <div class="bahan-item">
                                                    <div class="row">
                                                        <div class="col-lg-6">
                                                            <div class="mb-3">
                                                                <label class="form-label">Bahan:</label>
                                                                <input type="text" class="form-control" name="idbahan[]"
                                                                    id="idbahan[]" value="<?= $row['id_bahan'] ?>" readonly hidden />
                                                                <input type="text" class="form-control" name="bahan" id="bahan"
                                                                    placeholder="Bahan" value="<?= $row['nm_bahan'] ?>" readonly />
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <div class="mb-3">
                                                                <label class="form-label">Jumlah:</label>
                                                                <input type="text" class="form-control" name="jumlahbahan[]"
                                                                    id="jumlahbahan[]" placeholder="Jumlah"
                                                                    value="<?= $row['jumlah'] ?>" readonly />
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php
                                        }
                                    } else {
                                        ?>
                                        <div>
                                            <h5>Belum Terdata</h5>
                                        </div>
                                        <?php
                                    }
                                }
                                ?>
                            </div>
                        </div>

                    </div>
                    <div class="card pt-1 pb-2 px-3">
                        <div class="row">
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <input type="hidden"
                                        value="<?= isset($_GET['pilihproduk']) ? $selectedProduk['id_produk'] : '' ?>"
                                        name="idproduk">
                                    <label for="nmproduk">Nama Produk</label>
                                    <input type="text" name="nmproduk" id="nmproduk" class="form-control"
                                        value="<?= isset($selectedProduk['nm_produk']) ? $selectedProduk['nm_produk'] : '' ?>"
                                        readonly>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label for="kategori">Kategori</label>
                                    <input type="text" name="kategori" id="kategori" class="form-control"
                                        value="<?= isset($selectedKategori['nm_kategori']) ? $selectedKategori['nm_kategori'] : '' ?>"
                                        readonly>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label for="qty">Jumlah</label>
                                    <input type="number" name="qty" id="qty" class="form-control" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" name="simpan" class="btn btn-primary">Simpan</button>
                        <a href="index.php" class="btn btn-secondary">Batal</a>
                    </div>
                </form>
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
                    <button type="submit" name="processOrder" class="btn btn-sm btn-success">Proses Produksi</button>
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