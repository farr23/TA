<?php
session_start();

if (!isset($_SESSION["sslogin"])) {
    header("location: ../auth/login.php");
    exit();
}

require "../config/config.php";
require "../config/function.php";
require "../module/mode_bahan.php";

$title = "Laporan - Cafe Qita";
require "../partials/header.php";
require "../partials/navbar.php";
require "../partials/sidebar.php";

// Pagination setup
$items_per_page = 5; // Jumlah item per halaman
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $items_per_page;

// Mengambil data dari tabel trans_pembelian
$sql = "SELECT * FROM trans_pembelian LIMIT $items_per_page OFFSET $offset";
$result = $koneksi->query($sql);

// Menghitung total data untuk pagination
$sql_count = "SELECT COUNT(*) as total FROM trans_pembelian";
$result_count = $koneksi->query($sql_count);
$total_items = $result_count->fetch_assoc()['total'];
$total_pages = ceil($total_items / $items_per_page);

// Mengambil data untuk grafik
$sql_graph = "SELECT tgl_transbeli, total FROM trans_pembelian";
$result_graph = $koneksi->query($sql_graph);

$data_labels = [];
$data_values = [];

while ($row_graph = $result_graph->fetch_assoc()) {
    $data_labels[] = $row_graph['tgl_transbeli'];
    $data_values[] = $row_graph['total'];
}
?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Laporan Pembelian</h1>
                    
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= $main_url ?>dashboard.php">Home</a></li>
                        <li class="breadcrumb-item active">Laporan Pembelian</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <div class="container-fluid">
        <!-- Grafik -->
        <div class="card mb-4">
            <div class="card-header">
                <h5>Grafik Total Pembelian</h5>
                <a href="<?= $main_url ?>laporan_pembelian/index.php" class="btn btn-sm btn-primary float-right"><i class="fas fa-plus text-sm mr-2"></i>Daftar Pembelian</a>
            </div>
            <div class="card-body">
                <canvas id="pembelianChart"></canvas>
            </div>
        </div>

        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $no_transbeli = $row["no_transbeli"];
                $tgl_transbeli = $row["tgl_transbeli"];
                $nm_supplier = $row["nm_supplier"];
                $total = $row["total"];
                $keterangan = $row["keterangan"];

                echo "<div class='card mb-4'>";
                echo "<div class='card-header'>";
                echo "<h5>No Transaksi: $no_transbeli</h5>";
                echo "<p>Tanggal: $tgl_transbeli</p>";
                echo "<p>Supplier: $nm_supplier</p>";
                echo "<p>Total: Rp. ".number_format($total, 2, ',', '.')."</p>";
                echo "<p>Keterangan: $keterangan</p>";
                echo "</div>";
                echo "<div class='card-body'>";
                
                // Mengambil data detail pembelian
                $sql_detail = "SELECT * FROM trans_pemdetail WHERE no_transbeli = '$no_transbeli'";
                $result_detail = $koneksi->query($sql_detail);

                if ($result_detail->num_rows > 0) {
                    echo "<div class='table-responsive'>";
                    echo "<table class='table table-bordered table-sm'>";
                    echo "<thead>";
                    echo "<tr>";
                    echo "<th>ID Pembelian</th>";
                    echo "<th>No Transaksi</th>";
                    echo "<th>Tanggal</th>";
                    echo "<th>ID Bahan</th>";
                    echo "<th>Nama Bahan</th>";
                    echo "<th>Jumlah</th>";
                    echo "<th>Harga Beli</th>";
                    echo "<th>Jumlah Harga</th>";
                    echo "</tr>";
                    echo "</thead>";
                    echo "<tbody>";
                    while ($row_detail = $result_detail->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>".$row_detail["id_pembelian"]."</td>";
                        echo "<td>".$row_detail["no_transbeli"]."</td>";
                        echo "<td>".$row_detail["tgl_transbeli"]."</td>";
                        echo "<td>".$row_detail["id_bahan"]."</td>";
                        echo "<td>".$row_detail["nm_bahan"]."</td>";
                        echo "<td>".$row_detail["jumlah"]."</td>";
                        echo "<td>Rp. ".number_format($row_detail["harga_beli"], 2, ',', '.')."</td>";
                        echo "<td>Rp. ".number_format($row_detail["jml_harga"], 2, ',', '.')."</td>";
                        echo "</tr>";
                    }
                    echo "</tbody>";
                    echo "</table>";
                    echo "</div>";
                } else {
                    echo "<p>Detail pembelian tidak ditemukan.</p>";
                }
                
                echo "</div>";
                echo "</div>";
            }
        } else {
            echo "<p>Data pembelian tidak ditemukan.</p>";
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
    var ctx = document.getElementById('pembelianChart').getContext('2d');
    var pembelianChart = new Chart(ctx, {
        type: 'line', // Jenis grafik, bisa diubah sesuai kebutuhan
        data: {
            labels: <?= json_encode($data_labels) ?>,
            datasets: [{
                label: 'Total Pembelian',
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

<!-- Chart.js -->

