<?php

session_start();

if (!isset($_SESSION["sslogin"])) {
    header("location: ../auth/login.php");
    exit();
}

require "../config/config.php";
require "../config/function.php";
require "../module/mode_beli.php";

$title = "Bahan Masuk dari Pembelian - Cafe Qita";
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
                    <h1 class="m-0">Bahan Masuk dari Pembelian</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= $main_url ?>dashboard.php">Home</a></li>
                        <li class="breadcrumb-item active">Bahan Masuk</li>
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
                    <h3 class="card-title"><i class="fas fa-list text-sm mr-2"></i> Data Bahan Masuk</h3>
                </div>
                <div class="card-body table-responsive p-3">
                    <table class="table table-hover text-nowrap" id="tbdata">
                        <thead>
                            <tr>
                                <th>Kode Pembelian</th>
                                <th>Tanggal Pembelian</th>
                                <th>Nama Bahan</th>
                                <th class="text-center">Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $bahan_masuk = getData("
                                SELECT b.no_transbeli, b.tgl_transbeli, d.id_bahan, d.nm_bahan, d.jumlah 
                                FROM trans_pembelian b
                                JOIN trans_pemdetail d ON b.no_transbeli = d.no_transbeli
                            ");
                            foreach ($bahan_masuk as $bhn) { ?>
                                <tr>
                                    <td><?= $bhn['no_transbeli'] ?></td>
                                    <td><?= $bhn['tgl_transbeli'] ?></td>
                                    <td><?= $bhn['nm_bahan'] ?></td>
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
