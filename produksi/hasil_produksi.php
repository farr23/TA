<?php
session_start();

if (!isset($_SESSION["sslogin"])) {
    header("location: ../auth/login.php");
    exit();
}

require "../config/config.php";
require "../config/function.php";

$title = "Transaksi Produksi - Cafe Qita";
require "../partials/header.php";
require "../partials/navbar.php";
require "../partials/sidebar.php";
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
                        <li class="breadcrumb-item active">Produksi</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

<section class="content">
    <div class="container-fluid">
        <!-- Menampilkan pesan sukses atau error -->
        <?php 
        if(isset($_SESSION['error_message'])){
            echo '<div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>'.
            $_SESSION['error_message'].'
            </div>';
            unset($_SESSION['error_message']);
        }else if(isset($_SESSION['success_message'])){
            echo '<div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>'.
            $_SESSION['success_message'].'
            </div>';
            unset($_SESSION['success_message']);
        }                                          
        ?>

        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Produksi</h3>
            </div>
            <div class="card-body">
                <div style="padding-bottom:1.25rem">
                    <a href="index.php?link=add_produksi" class="btn btn-info"><i class="fa fa-plus"></i> Add Data</a>
                </div>

                <table id="tbdata" class="table table-bordered table-striped" style="text-align:center">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Kode Produksi</th>
                            <th>Produk</th>
                            <th>Qty Dihasilkan</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Koneksi ke database
                        $koneksi = new mysqli("localhost", "root", "", "db_cqmalang");

                        // Cek koneksi
                        if ($koneksi->connect_error) {
                            die("Connection failed: " . $koneksi->connect_error);
                        }

                        // Query untuk mengambil data dari tabel tb_produksi dan mengurutkannya berdasarkan tgl_produksi terbaru
                        $sql = "SELECT tb_produksi.id_produksi, tb_produksi.no_produksi, tb_produksi.tgl_produksi, tb_produk.nm_produk, tb_produksi.total_produksi 
                                FROM tb_produksi
                                JOIN tb_produk ON tb_produksi.id_produk = tb_produk.id_produk
                                ORDER BY tb_produksi.tgl_produksi DESC"; // Mengurutkan berdasarkan tanggal produksi terbaru
                        $result = $koneksi->query($sql);

                        // Tampilkan data produksi dalam tabel
                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                        ?>
                            <tr>
                                <td><?= date('d-m-Y', strtotime($row["tgl_produksi"])) ?></td>
                                <td><?= $row["no_produksi"] ?></td>
                                <td><?= $row["nm_produk"] ?></td>
                                <td><?= $row["total_produksi"] ?></td>
                                <td>
                                    <a href="dashboard.php?link=detail_produksi&id_produksi=<?= $row["id_produksi"] ?>" class="btn btn-primary">Detail</a>
                                </td>
                            </tr>
                        <?php
                            }
                        } else {
                            echo "<tr><td colspan='6'>Tidak ada data</td></tr>";
                        }

                        $koneksi->close();
                        ?>
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                <!-- Optional Footer -->
            </div>
        </div>
    </div>
</section>

<script>
    $(document).ready(function() {
        $('#tbdata').DataTable({ // Memastikan penggunaan ID yang benar dengan tanda #
            "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]], // Menambahkan opsi Show Entries
            "pageLength": 10, // Jumlah default baris yang ditampilkan
            "order": [[0, "desc"]] // Mengurutkan data berdasarkan kolom tanggal secara descending
        });
    });
</script>

<?php

require "../partials/footer.php";

?>
