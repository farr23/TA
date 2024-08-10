<?php
session_start();

if (!isset($_SESSION["sslogin"])) {
    header("location: ../auth/login.php");
    exit();
}

require "../config/config.php"; // Koneksi ke database
require "../config/function.php";

$title = "Stok Bahan - Cafe Qita";
require "../partials/header.php"; // Header halaman
require "../partials/navbar.php"; // Navbar halaman
require "../partials/sidebar.php"; // Sidebar halaman

// Query untuk mendapatkan data bahan dari tabel tb_bahan
$sql = "SELECT id_bahan, nm_bahan, harga_beli, stok, satuan FROM tb_bahan WHERE status = 1";
$result = mysqli_query($koneksi, $sql);
?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Stok Bahan</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= $main_url ?>dashboard.php">Home</a></li>
                        <li class="breadcrumb-item active">Stok Bahan</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Daftar Stok Bahan</h3>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>ID Bahan</th>
                                <th>Nama Bahan</th>
                                <th>Harga Beli</th>
                                <th>Stok</th>
                                <th>Satuan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (mysqli_num_rows($result) > 0): ?>
                                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                    <tr>
                                        <td><?= $row['id_bahan'] ?></td>
                                        <td><?= $row['nm_bahan'] ?></td>
                                        <td><?= $row['harga_beli'] ?></td>
                                        <td><?= $row['stok'] ?></td>
                                        <td><?= $row['satuan'] ?></td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" class="text-center">Tidak ada data</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>

<?php
require "../partials/footer.php"; // Footer halaman
mysqli_close($koneksi); // Menutup koneksi ke database
?>
