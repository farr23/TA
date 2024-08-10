<?php

session_start();

if (!isset($_SESSION["sslogin"])) {
    header("location: ../auth/login.php");
    exit();
}

require "../config/config.php";
require "../config/function.php";
//require "../module/mode_resp.php"; 

$title = "Resep - Cafe Qita";
require "../partials/header.php";
require "../partials/navbar.php";
require "../partials/sidebar.php";

$msg = isset($_GET['msg']) ? $_GET['msg'] : '';

$alert = '';
if ($msg == 'deleted') {
    $id = $_GET['id'];
    delete($id);
    $alert = '<div class="alert alert-success alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                  <h5><i class="icon fas fa-check"></i> Alert!</h5>
                  Resep berhasil dihapus
                </div>';
} elseif ($msg == 'updated') {
    $alert = '<div class="alert alert-success alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                  <h5><i class="icon fas fa-check"></i> Alert!</h5>
                  Resep berhasil diperbarui
                </div>';
}



?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Resep</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= $main_url ?>dashboard.php">Home</a></li>
                        <li class="breadcrumb-item active">Resep</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <?php if ($alert != '') { echo $alert; } ?>
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-list text-sm mr-2"></i> Data Resep</h3>
                    <a href="<?= $main_url ?>resep/form_resep.php" class="mr-2 btn btn-sm btn-primary float-right">
                        <i class="fas fa-plus fa-sm mr-1"></i> Tambah Resep
                    </a>
                </div>
                <div class="card-body table-responsive p-3">
                    <table class="table table-hover text-nowrap" id="tbdata">
                        <thead>
                            <tr>
                                <th>ID Resep</th>
                                <th>ID Produk</th>
                                <th>ID Bahan</th>
                                <th>Jumlah</th>
                                <th>Status</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $resep = getData("SELECT * FROM tb_resep");
                            foreach ($resep as $rsp) { ?>
                            <tr>
                                <td><?= $rsp['id_resep'] ?></td>
                                <td><?= $rsp['id_produk'] ?></td>
                                <td><?= $rsp['id_bahan'] ?></td>
                                <td><?= $rsp['jumlah'] ?></td>
                                <td><?= ucfirst($rsp['status']) ?></td>
                                <td class="text-center">
                                    <a href="form_resep.php?id=<?= $rsp['id_resep'] ?>&msg=editing" class="btn btn-warning btn-sm" title="edit resep">
                                        <i class="fas fa-pen"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>

<?php
require "../partials/footer.php";
?>
