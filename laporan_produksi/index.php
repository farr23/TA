<?php
session_start();

if (!isset($_SESSION["sslogin"])) {
    header("location: ../auth/login.php");
    exit();
}

require "../config/config.php"; // Pastikan file ini memuat koneksi ke database
require "../config/function.php"; // Jika ada fungsi tambahan
require "../module/mode_bahan.php"; // Jika ada modul tambahan

$title = "Laporan Produksi - Cafe Qita";
require "../partials/header.php";
require "../partials/navbar.php";
require "../partials/sidebar.php";

// Pagination setup
$items_per_page = 5; // Jumlah item per halaman
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $items_per_page;

// Mengambil data dari tabel tb_produksi
$sql = "SELECT * FROM tb_produksi LIMIT $items_per_page OFFSET $offset";
$result = $koneksi->query($sql);

// Menghitung total data untuk pagination
$sql_count = "SELECT COUNT(*) as total FROM tb_produksi";
$result_count = $koneksi->query($sql_count);
$total_items = $result_count->fetch_assoc()['total'];
$total_pages = ceil($total_items / $items_per_page);

// Mengambil data untuk grafik
$sql_graph = "SELECT tgl_produksi, total_produksi FROM tb_produksi";
$result_graph = $koneksi->query($sql_graph);

$data_labels = [];
$data_values = [];

while ($row_graph = $result_graph->fetch_assoc()) {
    $data_labels[] = $row_graph['tgl_produksi'];
    $data_values[] = $row_graph['total_produksi'];
}
?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Laporan Produksi</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= $main_url ?>dashboard.php">Home</a></li>
                        <li class="breadcrumb-item active">Laporan Produksi</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <div class="container-fluid">
        <!-- Grafik -->
        <div class="card mb-4">
            <div class="card-header">
                <h5>Grafik Total Produksi</h5>
                <a href="<?= $main_url ?>laporan_produksi/index.php" class="btn btn-sm btn-primary float-right"><i class="fas fa-plus text-sm mr-2"></i>Daftar Produksi</a>
            </div>
            <div class="card-body">
                <canvas id="produksiChart"></canvas>
            </div>
        </div>

        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $id_produksi = $row["id_produksi"];
                $no_produksi = $row["no_produksi"];
                $tgl_produksi = $row["tgl_produksi"];
                $id_produk = $row["id_produk"];
                $total_produksi = $row["total_produksi"];

                echo "<div class='card mb-4'>";
                echo "<div class='card-header'>";
                echo "<h5>No Produksi: $no_produksi</h5>";
                echo "<p>Tanggal: $tgl_produksi</p>";
                echo "<p>ID Produk: $id_produk</p>";
                echo "<p>Total Produksi: ".number_format($total_produksi, 2, ',', '.')."</p>";
                echo "</div>";
                echo "<div class='card-body'>";
                
                // Mengambil data detail produksi
                $sql_detail = "SELECT * FROM tb_produksi_detail WHERE no_produksi = '$no_produksi'";
                $result_detail = $koneksi->query($sql_detail);

                if ($result_detail->num_rows > 0) {
                    echo "<div class='table-responsive'>";
                    echo "<table class='table table-bordered table-sm'>";
                    echo "<thead>";
                    echo "<tr>";
                    echo "<th>ID Produksi Detail</th>";
                    echo "<th>No Produksi</th>";
                    echo "<th>ID Bahan</th>";
                    echo "<th>Jumlah Bahan</th>";
                    echo "</tr>";
                    echo "</thead>";
                    echo "<tbody>";
                    while ($row_detail = $result_detail->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>".$row_detail["id_produksi_detail"]."</td>";
                        echo "<td>".$row_detail["no_produksi"]."</td>";
                        echo "<td>".$row_detail["id_bahan"]."</td>";
                        echo "<td>".$row_detail["jumlah"]."</td>";
                        echo "</tr>";
                    }
                    echo "</tbody>";
                    echo "</table>";
                    echo "</div>";
                } else {
                    echo "<p>Detail produksi tidak ditemukan.</p>";
                }
                
                echo "</div>";
                echo "</div>";
            }
        } else {
            echo "<p>Data produksi tidak ditemukan.</p>";
        }
        ?>

        <!-- Pagination -->
        <nav aria-label="Page navigation">
            <ul class="pagination justify-content-center">
                <?php if ($page > 1): ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=<?= $page - 1 ?>" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                        <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>

                <?php if ($page < $total_pages): ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=<?= $page + 1 ?>" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    var ctx = document.getElementById('produksiChart').getContext('2d');
    var produksiChart = new Chart(ctx, {
        type: 'line', // Jenis grafik, bisa diubah sesuai kebutuhan
        data: {
            labels: <?= json_encode($data_labels) ?>,
            datasets: [{
                label: 'Total Produksi',
                data: <?= json_encode($data_values) ?>,
                borderColor: 'rgba(75, 192, 192, 1)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>

<?php
require "../partials/footer.php";
?>
