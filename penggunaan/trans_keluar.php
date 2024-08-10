<?php
session_start();

if (!isset($_SESSION["sslogin"])) {
    header("location: ../auth/login.php");
    exit();
}

require "../config/config.php";
require "../config/function.php";

$title = "Form Pengeluaran Bahan - Cafe Qita";
require "../partials/header.php";
require "../partials/navbar.php";
require "../partials/sidebar.php";

// Mendapatkan daftar bahan dari tabel tb_bahan
$sql_bahan = "SELECT id_bahan, nm_bahan FROM tb_bahan WHERE status = 1";
$result_bahan = $koneksi->query($sql_bahan);

if (!$result_bahan) {
    die("Query Error: " . $koneksi->error);
}
?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Form Pengeluaran Bahan</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= $main_url ?>dashboard.php">Home</a></li>
                        <li class="breadcrumb-item"><a href="<?= $main_url ?>penggunaan/bahan_keluar.php">Bahan Keluar</a></li>
                        <li class="breadcrumb-item active">Pengeluaran Bahan</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>

    <div class="container-fluid">
        <form action="proses_pengeluaran.php" method="post">
            <div class="card mb-4">
                <div class="card-header">
                    <h5>Detail Pengeluaran Bahan</h5>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="id_bahan">Nama Bahan</label>
                        <select class="form-control" id="id_bahan" name="id_bahan" required>
                            <option value="">Pilih Bahan</option>
                            <?php while ($row_bahan = $result_bahan->fetch_assoc()): ?>
                                <option value="<?= $row_bahan['id_bahan'] ?>"><?= $row_bahan['nm_bahan'] ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="jumlah_keluar">Jumlah Keluar</label>
                        <input type="number" class="form-control" id="jumlah_keluar" name="jumlah_keluar" step="0.01" min="0" required>
                    </div>
                    <div class="form-group">
                        <label for="keterangan">Keterangan</label>
                        <textarea class="form-control" id="keterangan" name="keterangan"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>

<?php
require "../partials/footer.php";
?>
