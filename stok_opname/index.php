<?php
session_start();

if (!isset($_SESSION["sslogin"])) {
    header("location: ../auth/login.php");
    exit();
}

require "../config/config.php";
require "../config/function.php";

$title = "Data Stok Opname - Cafe Qita";
require "../partials/header.php";
require "../partials/navbar.php";
require "../partials/sidebar.php";

// Mendapatkan bulan dan tahun saat ini
$current_month = date('m');
$current_year = date('Y');

// Mengecek apakah sudah ada stok opname di bulan ini
$sql_check = "
    SELECT COUNT(*) as count 
    FROM tb_stok_opname 
    WHERE MONTH(tgl_opname) = '$current_month' 
    AND YEAR(tgl_opname) = '$current_year'
";
$result_check = mysqli_query($koneksi, $sql_check);
$row_check = mysqli_fetch_assoc($result_check);

$can_add_opname = $row_check['count'] == 0;

// Handle search functionality
$search_query = "";
if (isset($_GET['search'])) {
    $search_query = mysqli_real_escape_string($koneksi, $_GET['search']);
}

// Pagination settings
$limit = 10; // Number of records per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Query to get data from tb_stok_opname with search and pagination
$sql = "
    SELECT 
        o.id_opname, 
        o.tgl_opname, 
        b.nm_bahan, 
        o.stok_sistem, 
        o.stok_fisik, 
        o.selisih, 
        o.keterangan 
    FROM 
        tb_stok_opname o 
        JOIN tb_bahan b ON o.id_bahan = b.id_bahan
    WHERE 
        b.nm_bahan LIKE '%$search_query%'
    ORDER BY o.tgl_opname DESC
    LIMIT $limit OFFSET $offset
";

$result = mysqli_query($koneksi, $sql);

// Get total records for pagination
$total_result = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM tb_stok_opname o JOIN tb_bahan b ON o.id_bahan = b.id_bahan WHERE b.nm_bahan LIKE '%$search_query%'");
$total_row = mysqli_fetch_assoc($total_result);
$total_pages = ceil($total_row['total'] / $limit);
?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Data Stok Opname</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= $main_url ?>dashboard.php">Home</a></li>
                        <li class="breadcrumb-item active">Data Stok Opname</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>

    <section>
        <div class="container-fluid mt-3">
            <div class="row">
                <div class="col-md-6">
                    <?php if ($can_add_opname): ?>
                        <a href="<?= $main_url ?>stok_opname/form_stok_opname.php" class="mr-2 btn btn-sm btn-primary">
                            <i class="fas fa-plus fa-sm mr-1"></i> Add Stok Opname
                        </a>
                    <?php else: ?>
                        <button class="btn btn-sm btn-secondary" disabled>Stok Opname sudah dilakukan bulan ini</button>
                    <?php endif; ?>
                </div>
                <div class="col-md-6">
                    <form method="GET" action="" class="form-inline float-right">
                        <input type="text" name="search" value="<?= htmlspecialchars($search_query) ?>" class="form-control form-control-sm mr-2" placeholder="Cari bahan...">
                        <button type="submit" class="btn btn-sm btn-secondary">Cari</button>
                    </form>
                </div>
            </div>
            <div class="card mt-4">
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>ID Opname</th>
                                <th>Tanggal</th>
                                <th>Bahan</th>
                                <th>Stok Sistem</th>
                                <th>Stok Fisik</th>
                                <th>Selisih</th>
                                <th>Keterangan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (mysqli_num_rows($result) > 0) {
                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo "<tr>";
                                    echo "<td>" . $row['id_opname'] . "</td>";
                                    echo "<td>" . $row['tgl_opname'] . "</td>";
                                    echo "<td>" . $row['nm_bahan'] . "</td>";
                                    echo "<td>" . $row['stok_sistem'] . "</td>";
                                    echo "<td>" . $row['stok_fisik'] . "</td>";
                                    echo "<td>" . $row['selisih'] . "</td>";
                                    echo "<td>" . $row['keterangan'] . "</td>";
                                    echo "<td>
                                            <a href='edit_stok_opname.php?id=" . $row['id_opname'] . "' class='btn btn-warning btn-sm'>Edit</a>
                                          </td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='8' class='text-center'>Tidak ada data</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="mt-3">
                <nav>
                    <ul class="pagination">
                        <?php if ($page > 1): ?>
                            <li class="page-item"><a class="page-link" href="?page=<?= $page - 1 ?>&search=<?= htmlspecialchars($search_query) ?>">Previous</a></li>
                        <?php endif; ?>

                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                            <li class="page-item <?= $i == $page ? 'active' : '' ?>"><a class="page-link" href="?page=<?= $i ?>&search=<?= htmlspecialchars($search_query) ?>"><?= $i ?></a></li>
                        <?php endfor; ?>

                        <?php if ($page < $total_pages): ?>
                            <li class="page-item"><a class="page-link" href="?page=<?= $page + 1 ?>&search=<?= htmlspecialchars($search_query) ?>">Next</a></li>
                        <?php endif; ?>
                    </ul>
                </nav>
            </div>
        </div>
    </section>

<?php
mysqli_close($koneksi);
require "../partials/footer.php";
?>
