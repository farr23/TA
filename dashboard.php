<?php

session_start();

if (!isset($_SESSION["sslogin"])) {
    header("location: auth/login.php");
    exit();
}

require "config/config.php";
require "config/function.php";
require "module/mode_dashboard.php";

$title = "Dashboard - Cafe Qita";
require "partials/header.php";
require "partials/navbar.php";
require "partials/sidebar.php";

$userCount = getUserCount();
$supplierCount = getSupplierCount();
$customerCount = getCustomerCount();
$bahanCount = getBahanCount();

// Data untuk grafik
$pembelian = getData("SELECT tgl_transbeli, total FROM trans_pembelian ORDER BY tgl_transbeli");
$penjualan = getData("SELECT tgl_transjual, total FROM trans_penjualan ORDER BY tgl_transjual");
$produksi = getData("SELECT tgl_produksi, total_produksi FROM tb_produksi ORDER BY tgl_produksi");

// Array untuk grafik
$pembelianData = [];
$penjualanData = [];
$produksiData = [];

foreach ($pembelian as $row) {
    $pembelianData[] = ['date' => $row['tgl_transbeli'], 'total' => $row['total']];
}
foreach ($penjualan as $row) {
    $penjualanData[] = ['date' => $row['tgl_transjual'], 'total' => $row['total']];
}
foreach ($produksi as $row) {
    $produksiData[] = ['date' => $row['tgl_produksi'], 'total' => $row['total_produksi']];
}

?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Dashboard</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= $main_url ?>dashboard.php">Home</a></li>
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            <!-- Small boxes (Stat box) -->
            <div class="row">
                <!-- Boxes for user, supplier, customer, and product counts -->
                <div class="col-lg-3 col-6">
                  <div class="card card-info">
                    <div class="card-body d-flex align-items-center justify-content-between">
                      <div>
                        <h3><?= $userCount ?></h3>
                        <p>Users</p>
                      </div>
                      <div class="icon">
                        <i class="fas fa-users fa-3x "></i>
                      </div>
                    </div>
                    <div class="card-footer">
                      <a href="users.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                  </div>
                </div>
                <div class="col-lg-3 col-6">
    <div class="card card-success">
        <div class="card-body d-flex align-items-center justify-content-between">
            <div>
                <h3><?= $supplierCount ?></h3>
                <p>Supplier</p>
            </div>
            <div class="icon">
                <i class="fas fa-truck fa-3x"></i>
            </div>
        </div>
        <div class="card-footer">
            <a href="suppliers.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
</div>

<div class="col-lg-3 col-6">
    <div class="card card-warning">
        <div class="card-body d-flex align-items-center justify-content-between">
            <div>
                <h3><?= $customerCount ?></h3>
                <p>Customer</p>
            </div>
            <div class="icon">
                <i class="fas fa-user-friends fa-3x"></i>
            </div>
        </div>
        <div class="card-footer">
            <a href="customers.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
</div>

<div class="col-lg-3 col-6">
    <div class="card card-danger">
        <div class="card-body d-flex align-items-center justify-content-between">
            <div>
                <h3><?= $bahanCount ?></h3>
                <p>Bahan</p>
            </div>
            <div class="icon">
                <i class="fas fa-boxes fa-3x"></i>
            </div>
        </div>
        <div class="card-footer">
            <a href="bahan.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
</div>

            </div>
            <!-- /.row -->

            <!-- Grafik Pembelian, Penjualan, Produksi -->
            <div class="row">
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Grafik Pembelian</h3>
                        </div>
                        <div class="card-body">
                            <canvas id="pembelianChart"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Grafik Penjualan</h3>
                        </div>
                        <div class="card-body">
                            <canvas id="penjualanChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-6 mx-auto">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Grafik Produksi</h3>
                        </div>
                        <div class="card-body">
                            <canvas id="produksiChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <!-- /.content -->

<?php
require "partials/footer.php";
?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Data untuk grafik
    const pembelianData = <?= json_encode($pembelianData); ?>;
    const penjualanData = <?= json_encode($penjualanData); ?>;
    const produksiData = <?= json_encode($produksiData); ?>;

    // Helper function untuk memformat data grafik
    function formatChartData(data) {
        return {
            labels: data.map(item => item.date),
            datasets: [{
                label: 'Total',
                data: data.map(item => item.total),
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        };
    }

    // Konfigurasi Grafik Pembelian
    const pembelianCtx = document.getElementById('pembelianChart').getContext('2d');
    new Chart(pembelianCtx, {
        type: 'line',
        data: formatChartData(pembelianData),
        options: {
            responsive: true,
            maintainAspectRatio: false,
        }
    });

    // Konfigurasi Grafik Penjualan
    const penjualanCtx = document.getElementById('penjualanChart').getContext('2d');
    new Chart(penjualanCtx, {
        type: 'line',
        data: formatChartData(penjualanData),
        options: {
            responsive: true,
            maintainAspectRatio: false,
        }
    });

    // Konfigurasi Grafik Produksi
    const produksiCtx = document.getElementById('produksiChart').getContext('2d');
    new Chart(produksiCtx, {
        type: 'line',
        data: formatChartData(produksiData),
        options: {
            responsive: true,
            maintainAspectRatio: false,
        }
    });
</script>
