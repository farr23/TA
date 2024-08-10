<?php

session_start();

if(!isset($_SESSION["sslogin"])) {
    header("location: ../auth/login.php");
    exit();
}

require "../config/config.php";
require "../config/function.php";
require "../module/mode_produk.php"; 

$title = "Produk - Cafe Qita";
require "../partials/header.php";
require "../partials/navbar.php";
require "../partials/sidebar.php";

if(isset($_GET['msg'])){
    $msg = $_GET['msg'];
}else {
    $msg = '';
}

$alert = '';
if($msg == 'deleted'){
    $id = $_GET['id'];
    $gbr = $_GET['gbr'];
    delete($id, $gbr);
    $alert = '<div class="alert alert-success alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                  <h5><i class="icon fas fa-check"></i> Alert!</h5>
                  Produk berhasil dihapus
                </div>';
}

if($msg == 'updated'){
    $alert = '<div class="alert alert-success alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                  <h5><i class="icon fas fa-check"></i> Alert!</h5>
                  Produk berhasil diperbarui
                </div>';
}

?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Produk</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?= $main_url ?>dashboard.php">Home</a></li>
              <li class="breadcrumb-item active">Produk</li>
            </ol>
        </div><!-- /.col -->
    </div><!-- /.row -->
</div><!-- /.container-fluid -->
</div>

<section class="content">
    <div class="container-fluid">
        <div class="card">
        <?php
            if($alert != ''){
                echo $alert;
            }
            ?>
        <div class="card-header">
                <h3 class="card-title"><i class="fas fa-list text-sm mr-2"></i> Data Produk</h3>
                <a href="<?= $main_url ?>produk/form_produk.php" class="mr-2 btn btn-sm btn-primary float-right"><i class="fas fa-plus fa-sm mr-1"></i> Add Produk</a>
            </div>
            <div class="card-body table-responsive p-3">
                <table class="table table-hover text-nowrap" id="tbdata">
                    <thead>
                        <tr>
                            <th>Gambar</th>
                            <th>ID Produk</th>
                            <th>Nama Produk</th>
                            <th>Kategori</th>
                            <th>Harga Jual</th>
                            <th>Harga Modal</th>
                            <th>Status</th>
                            <th style="width: 10%;" class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        $produk = getData("SELECT p.*, k.nm_kategori FROM tb_produk p JOIN tb_kategori k ON p.id_kategori = k.id_kategori");
                        foreach($produk as $pdk){ ?>
                        <tr>
                            <td>
                                <img src="../asset/image/<?= $pdk['gambar'] ?>" alt="gambar produk" class="rounded-circle" width="60px">
                            </td>
                            <td><?= $pdk['id_produk'] ?></td>
                            <td><?= $pdk['nm_produk'] ?></td>
                            <td><?= $pdk['nm_kategori'] ?></td>
                            <td class="text-center"><?= number_format($pdk['harga_jual'],0,',','.') ?></td>
                            <td class="text-center"><?= number_format($pdk['harga_modal'],0,',','.') ?></td>
                            <td>
                                <span class="<?= $pdk['status'] == '1' ? 'badge badge-success' : 'badge badge-danger' ?>">
                                <?= $pdk['status'] == '1' ? 'Aktif' : 'Tidak Aktif' ?>
                            </td>
                            <td>
                                <a href="form_produk.php?id=<?= $pdk['id_produk'] ?>&msg=editing" class="btn btn-warning btn-sm" title="edit produk"><i class="fas fa-pen"></i></a>
                            </td>
                        </tr>
                        
                    <?php
                    }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

<?php

require "../partials/footer.php";

?>
