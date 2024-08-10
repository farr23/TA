<?php

session_start();

if (!isset($_SESSION["sslogin"])) {
    header("location: ../auth/login.php");
    exit();
}

require "../config/config.php";
require "../config/function.php";
require "../module/mode_produksi.php";

$title = "Bahan Keluar untuk Produksi - Cafe Qita";
require "../partials/header.php";
require "../partials/navbar.php";
require "../partials/sidebar.php";

if (isset($_GET['msg'])) {
    $msg = $_GET['msg'];
} else {
    $msg = '';
}

$alert = '';
if ($msg == 'deleted') {
    $id = $_GET['id'];
    delete($id);
    $alert = '<div class="alert alert-success alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                  <h5><i class="icon fas fa-check"></i> Alert!</h5>
                  Data bahan berhasil dihapus
                </div>';
}

if ($msg == 'updated') {
    $alert = '<div class="alert alert-success alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                  <h5><i class="icon fas fa-check"></i> Alert!</h5>
                  Data bahan berhasil diperbarui
                </div>';
}

?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Bahan Keluar untuk Produksi</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= $main_url ?>dashboard.php">Home</a></li>
                        <li class="breadcrumb-item active">Bahan Keluar</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <?php
                if ($alert != '') {
                    echo $alert;
                }
                ?>
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-list text-sm mr-2"></i> Data Bahan Keluar</h3>
                    <a href="<?= $main_url ?>penggunaan/trans_keluar.php" class="btn btn-sm btn-primary float-right"><i class="fas fa-plus text-sm mr-2"></i>Form Bahan Keluar</a>
                </div>
                <div class="card-body table-responsive p-3">
                    <table class="table table-hover text-nowrap" id="tbdata">
                        <thead>
                            <tr>
                                <th>ID Produksi</th>
                                <th>Tanggal Produksi</th>
                                <th>ID Bahan</th>
                                <th class="text-center">Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $bahan_keluar = getData("
                                SELECT p.no_produksi, p.tgl_produksi, d.id_bahan, d.jumlah 
                                FROM tb_produksi p
                                JOIN tb_produksi_detail d ON p.no_produksi = d.no_produksi
                            ");
                            foreach ($bahan_keluar as $bhn) { ?>
                                <tr>
                                    <td><?= $bhn['no_produksi'] ?></td>
                                    <td><?= $bhn['tgl_produksi'] ?></td>
                                    <td><?= $bhn['id_bahan'] ?></td>
                                    <td class="text-center"><?= number_format($bhn['jumlah'], 0, ',', '.') ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
<?php

require "../partials/footer.php";

?>
