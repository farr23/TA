<?php

session_start();

if(!isset($_SESSION["sslogin"])) {
    header("location: ../auth/login.php");
    exit();
  }

require "../config/config.php";
require "../config/function.php";
require "../module/mode_kategori.php";

$title = "Tambah Kategori - Cafe Qita";
require "../partials/header.php";
require "../partials/navbar.php";
require "../partials/sidebar.php";

$alert = '';

if(isset($_POST['simpan'])){
    if(insert($_POST)){
        $alert = '<div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="icon fas fa-check"></i>Kategori berhasil ditambahkan
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
        </button>
        </div>';
    }
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
              <li class="breadcrumb-item"><a href="<?= $main_url ?>kategori/data_kategori.php">Kategori</a></li>
              <li class="breadcrumb-item active">Add Kategori</li>
            </ol>
        </div><!-- /.col -->
    </div><!-- /.row -->
</div><!-- /.container-fluid -->
</div>

<section class="content">
    <div class="container-fluid">
        <div class="card">
            <form action="" method="post">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-plus text-sm mr-2"></i> Tambah Kategori</h3>
                <button type="submit" name="simpan" class="btn btn-primary btn-sm float-right"><i class="fas fa-save icon-sm mr-1"></i> Simpan</button>
                <button type="reset" class="btn btn-danger btn-sm float-right mr-2"><i class="fas fa-times icon-sm mr-1"></i> Reset</button>
            </div> 
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-8 mb-3">
                    <?php
                    if($alert != ''){
                        echo $alert;
                    }
                    ?>
                        <div class="form-group">
                            <label for="nm_kategori">Nama Kategori</label>
                            <input type="text" name="nm_kategori" class="form-control" id="nm_kategori" placeholder="nama kategori" autofocus autocomplete="off" required>
                        </div>
                        <div class="form-group">
                            <label for="status">Status *</label>
                            <select name="status" id="status" class="form-control" required>
                                <option value="">-- Pilih Status --</option>
                                <option value="1">Aktif</option>
                                <option value="0">Tidak Aktif</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            </form>
        </div>
    </div>
</section>


<?php

require "../partials/footer.php";

?>