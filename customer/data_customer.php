<?php

session_start();

if (!isset($_SESSION["sslogin"])) {
    header("location: ../auth/login.php");
    exit();
}

require "../config/config.php";
require "../config/function.php";
require "../module/mode_customer.php";

$title = "Data Customer - Cafe Qita";
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
    $alert = '<div class="alert alert-success alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                  <h5><i class="icon fas fa-check"></i> Alert!</h5>
                  Customer berhasil dihapus
                </div>';
}

if ($msg == 'updated') {
    $alert = '<div class="alert alert-success alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                  <h5><i class="icon fas fa-check-circle"></i> Alert!</h5>
                  Customer berhasil diperbarui
                </div>';
}

if ($msg == 'aborted') {
    $alert = '<div class="alert alert-danger alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                  <h5><i class="icon fas fa-exclamation-triangle"></i> Alert!</h5>
                  Customer gagal dihapus
                </div>';
}

?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Customer</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= $main_url ?>dashboard.php">Home</a></li>
                        <li class="breadcrumb-item active">Data Customer</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>

    <section>
        <div class="container-fluid">
            <div class="card">
                <?php
                if ($alert != '') {
                    echo $alert;
                }
                ?>
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-list text-sm mr-2"></i> Data Customer</h3>
                    <a href="<?= $main_url ?>customer/add_customer.php" class="btn btn-sm btn-primary float-right"><i class="fas fa-plus text-sm mr-2"></i>Tambah Customer</a>
                </div>
                <div class="card-body table-responsive p-3">
                    <table class="table table-hover text-nowrap" id="tbdata">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Telepon</th>
                                <th>Alamat</th>
                                <th>Status</th>
                                <th style="width: 10%;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            $customers = getData("SELECT * FROM tb_customer");
                            foreach ($customers as $customer) :
                            ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= $customer['nm_customer'] ?></td>
                                    <td><?= $customer['telp'] ?></td>
                                    <td><?= $customer['alamat'] ?></td>
                                    <td>
                                        <span class="<?= $customer['status'] == '1' ? 'badge badge-success' : 'badge badge-danger' ?>">
                                        <?= $customer['status'] == '1' ? 'Aktif' : 'Tidak Aktif' ?>
                                    </td>
                                    <td>
                                        <a href="edit_customer.php?id=<?= $customer['id_customer'] ?>" class="btn btn-sm btn-warning" title="edit customer"><i class="fas fa-pen"></i></a>
                                        <!-- Add delete action here if needed -->
                                    </td>
                                </tr>
                            <?php endforeach; ?>
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
