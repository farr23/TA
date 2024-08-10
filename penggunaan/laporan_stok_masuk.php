<?php
session_start();

if (!isset($_SESSION["sslogin"])) {
    header("location: ../auth/login.php");
    exit();
}

require "../config/config.php";
require "../config/function.php";

$title = "Laporan Stok Masuk - Cafe Qita";
require "../partials/header.php";
require "../partials/navbar.php";
require "../partials/sidebar.php";

// Pagination setup
$items_per_page = 5; // Jumlah item per halaman
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $items_per_page;

// Filter by Date
$start_date = isset($_GET['start_date']) ? $_GET['start_date'] : '';
$end_date = isset($_GET['end_date']) ? $_GET['end_date'] : '';

// Search by Nama Bahan
$search_query = isset($_GET['search']) ? $_GET['search'] : '';

// Order by Field
$order_by = isset($_GET['order_by']) ? $_GET['order_by'] : 'tgl_transaksi';
$order_type = isset($_GET['order_type']) ? $_GET['order_type'] : 'DESC';

// Query to get data from tb_transaksi_bahan with filters and pagination
$sql = "SELECT * FROM tb_transaksi_bahan WHERE jenis_transaksi = 'Masuk'";

// Add date filter if set
if ($start_date && $end_date) {
    $sql .= " AND tgl_transaksi BETWEEN '$start_date' AND '$end_date'";
}

// Add search filter if set
if ($search_query) {
    $sql .= " AND nm_bahan LIKE '%$search_query%'";
}

// Add order by clause
$sql .= " ORDER BY $order_by $order_type";

// Add pagination
$sql .= " LIMIT $items_per_page OFFSET $offset";

$result = $koneksi->query($sql);

// Menghitung total data untuk pagination
$sql_count = "SELECT COUNT(*) as total FROM tb_transaksi_bahan WHERE jenis_transaksi = 'Masuk'";
if ($start_date && $end_date) {
    $sql_count .= " AND tgl_transaksi BETWEEN '$start_date' AND '$end_date'";
}
if ($search_query) {
    $sql_count .= " AND nm_bahan LIKE '%$search_query%'";
}
$result_count = $koneksi->query($sql_count);
$total_items = $result_count->fetch_assoc()['total'];
$total_pages = ceil($total_items / $items_per_page);
?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Laporan Stok Masuk</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= $main_url ?>dashboard.php">Home</a></li>
                        <li class="breadcrumb-item active">Stok Masuk</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>

    <div class="container-fluid">
        <!-- Filter dan Pencarian -->
        <div class="row mb-4">
            <div class="col-md-6">
                <form method="GET" action="" class="form-inline">
                    <div class="form-group">
                        <label for="start_date">Dari: </label>
                        <input type="date" name="start_date" id="start_date" value="<?= htmlspecialchars($start_date) ?>" class="form-control form-control-sm ml-2 mr-2">
                    </div>
                    <div class="form-group">
                        <label for="end_date">Sampai: </label>
                        <input type="date" name="end_date" id="end_date" value="<?= htmlspecialchars($end_date) ?>" class="form-control form-control-sm ml-2 mr-2">
                    </div>
                    <button type="submit" class="btn btn-sm btn-primary">Filter</button>
                </form>
            </div>
            <div class="col-md-6">
                <form method="GET" action="" class="form-inline float-right">
                    <input type="text" name="search" value="<?= htmlspecialchars($search_query) ?>" class="form-control form-control-sm mr-2" placeholder="Cari bahan...">
                    <button type="submit" class="btn btn-sm btn-secondary">Cari</button>
                </form>
            </div>
        </div>

        <!-- Tabel Stok Masuk -->
        <div class="card mb-4">
            <div class="card-header">
                <h5>Detail Transaksi Stok Masuk</h5>
            </div>
            <div class="card-body">
                <?php if ($result->num_rows > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm">
                            <thead>
                                <tr>
                                    <th><a href="?order_by=id_transaksi&order_type=<?= $order_type == 'ASC' ? 'DESC' : 'ASC' ?>">ID Transaksi</a></th>
                                    <th><a href="?order_by=tgl_transaksi&order_type=<?= $order_type == 'ASC' ? 'DESC' : 'ASC' ?>">Tanggal</a></th>
                                    <th><a href="?order_by=nm_bahan&order_type=<?= $order_type == 'ASC' ? 'DESC' : 'ASC' ?>">Nama Bahan</a></th>
                                    <th>Jumlah Awal</th>
                                    <th>Jumlah Masuk</th>
                                    <th>Jumlah Akhir</th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = $result->fetch_assoc()): ?>
                                    <tr>
                                        <td><?= $row['id_transaksi'] ?></td>
                                        <td><?= $row['tgl_transaksi'] ?></td>
                                        <td><?= $row['nm_bahan'] ?></td>
                                        <td><?= number_format($row['jumlah_awal'], 2, ',', '.') ?></td>
                                        <td><?= number_format($row['jumlah_masuk'], 2, ',', '.') ?></td>
                                        <td><?= number_format($row['jumlah_akhir'], 2, ',', '.') ?></td>
                                        <td><?= $row['keterangan'] ?></td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p>Data stok masuk tidak ditemukan.</p>
                <?php endif; ?>
            </div>
        </div>

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
                        <a class="page-link" href="?page=<?= $i ?>&start_date=<?= $start_date ?>&end_date=<?= $end_date ?>&search=<?= $search_query ?>&order_by=<?= $order_by ?>&order_type=<?= $order_type ?>"><?= $i ?></a>
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
</div>

<?php
require "../partials/footer.php";
?>
