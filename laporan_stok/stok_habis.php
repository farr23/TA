<?php
session_start();

if (!isset($_SESSION["sslogin"])) {
    header("location: ../auth/login.php");
    exit();
}

require "../config/config.php";
require "../config/function.php";

$title = "Laporan Stok Habis - Cafe Qita";
require "../partials/header.php";
require "../partials/navbar.php";
require "../partials/sidebar.php";

// Mengambil data dari tabel tb_bahan yang stoknya 0
$sql_habis = "SELECT * FROM tb_bahan WHERE stok = 0";
$result_habis = $koneksi->query($sql_habis);

if (!$result_habis) {
    die("Query Error: " . $koneksi->error);
}
?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Laporan Stok Habis</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= $main_url ?>dashboard.php">Home</a></li>
                        <li class="breadcrumb-item active">Stok Habis</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>

    <div class="container-fluid">
        <?php
        if ($result_habis->num_rows > 0) {
            echo "<div class='card mb-4'>";
            echo "<div class='card-header'>";
            echo "<h5>Stok Bahan Habis</h5>";
            echo "</div>";
            echo "<div class='card-body'>";
            echo "<div class='table-responsive'>";
            echo "<table class='table table-bordered table-sm'>";
            echo "<thead>";
            echo "<tr>";
            echo "<th>ID Bahan</th>";
            echo "<th>Nama Bahan</th>";
            echo "<th>Harga Beli</th>";
            echo "<th>Harga Jual</th>";
            echo "<th>Stok</th>";
            echo "<th>Satuan</th>";
            echo "<th>Status</th>";
            echo "</tr>";
            echo "</thead>";
            echo "<tbody>";
            while ($row = $result_habis->fetch_assoc()) {
                echo "<tr>";
                echo "<td>".$row["id_bahan"]."</td>";
                echo "<td>".$row["nm_bahan"]."</td>";
                echo "<td>Rp. ".number_format($row["harga_beli"], 2, ',', '.')."</td>";
                echo "<td>Rp. ".number_format($row["harga_jual"], 2, ',', '.')."</td>";
                echo "<td>".$row["stok"]." ".$row["satuan"]."</td>";
                echo "<td>".$row["satuan"]."</td>";
                echo "<td>".($row["status"] ? 'Aktif' : 'Non-Aktif')."</td>";
                echo "</tr>";
            }
            echo "</tbody>";
            echo "</table>";
            echo "</div>";
            echo "</div>";
            echo "</div>";
        } else {
            echo "<p>Semua bahan memiliki stok yang memadai.</p>";
        }
        ?>
    </div>
</div>

<?php
require "../partials/footer.php";
?>
