<?php

session_start();

if(!isset($_SESSION["sslogin"])) {
    header("location: ../auth/login.php");
    exit();
  }

require "../config/config.php";
require "../config/function.php";
require "../module/mode_kategori.php";

$title = "Data Kategori - Cafe Qita";
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
    $alert = '<div class="alert alert-success alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                  <h5><i class="icon fas fa-check"></i> Alert!</h5>
                  Kategori berhasil dihapus
                </div>';
}

if($msg == 'updated'){
    $alert = '<div class="alert alert-success alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                  <h5><i class="icon fas fa-check-circle"></i> Alert!</h5>
                  Kategori berhasil diperbarui
                </div>';
}

if($msg == 'aborted'){
    $alert = '<div class="alert alert-danger alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                  <h5><i class="icon fas fa-exclamation-triangle"></i> Alert!</h5>
                  Kategori gagal dihapus
                </div>';
}

?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Kategori</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?= $main_url ?>dashboard.php">Home</a></li>
              <li class="breadcrumb-item active">Data Kategori</li>
            </ol>
        </div><!-- /.col -->
    </div><!-- /.row -->
</div><!-- /.container-fluid -->
</div>

<section>
    <div class="container-fluid">
        <div class="card">
            <?php
            if($alert != ''){
                echo $alert;
            } 
            ?>
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-list text-sm mr-2"></i> Data Kategori</h3>
                <a href="<?= $main_url ?>kategori/add_kategori.php" class="btn btn-sm btn-primary float-right"><i class="fas fa-plus text-sm mr-2"></i>Tambah Kategori</a>
            </div>
            <div class="card-body table-responsive p-3">
                <table class="table table-hover text-nowrap" id="tbdata">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th style="width: 10%;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        $kategoris = getData("SELECT * FROM tb_kategori");
                        foreach($kategoris as $kategori) :
                        ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= $kategori['nm_kategori'] ?></td>
                            <td>
                                        <span class="<?= $kategori['status'] == '1' ? 'badge badge-success' : 'badge badge-danger' ?>">
                                        <?= $kategori['status'] == '1' ? 'Aktif' : 'Tidak Aktif' ?>
                                    </td>
                            <td>
                                <a href="edit_kategori.php?id=<?= $kategori['id_kategori']?>" class="btn btn-sm btn-warning" title="edit kategori"><i class="fas fa-pen"></i></a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

<?php

require "../partials/footer.php";

?>