<?php

session_start();

if(!isset($_SESSION["sslogin"])) {
    header("location: ../auth/login.php");
    exit();
}

require "../config/config.php";
require "../config/function.php";
require "../module/mode_produk.php"; 

$title = "Form Produk - Cafe Qita";
require "../partials/header.php";
require "../partials/navbar.php";
require "../partials/sidebar.php";

if(isset($_GET['msg'])){
    $msg = $_GET['msg'];
    $id = $_GET['id'];
    $sqledit = "SELECT * FROM tb_produk WHERE id_produk = '$id'";
    $produk = getData($sqledit)[0];
} else {
    $msg = "";
}

$alert = '';

if(isset($_POST['simpan'])){
    if($msg != ''){
        if(update($_POST)){
            echo "
                <script>document.location.href = 'index.php?msg=updated';</script>
            ";
        } else {
            echo "
                <script>document.location.href = 'index.php';</script>
            ";
        }
    } else {
        if(insert($_POST)){
            $alert = '<div class="alert alert-success alert-dismissible">
                      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                      <h5><i class="icon fas fa-check"></i> Alert!</h5>
                      Produk berhasil ditambahkan
                    </div>';
        }
    }
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
              <li class="breadcrumb-item"><a href="<?= $main_url ?>produk">Produk</a></li>
              <li class="breadcrumb-item active"><?= $msg != '' ? 'Edit Produk' : 'Add Produk' ?></li>
            </ol>
        </div><!-- /.col -->
    </div><!-- /.row -->
</div><!-- /.container-fluid -->
</div>

<section class="content">
    <div class="container-fluid">
        <div class="card">
            <form action="" method="post" enctype="multipart/form-data">
            <?php
                if($alert != ''){
                    echo $alert;
                }
                ?>
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-pen text-sm mr-2"></i> <?= $msg != '' ? 'Edit Produk' : 'Input Produk' ?></h3>
                <button type="submit" name="simpan" class="btn btn-primary btn-sm float-right"><i class="fas fa-save icon-sm mr-1"></i> Simpan</button>
                <button type="reset" class="btn btn-danger btn-sm float-right mr-2"><i class="fas fa-times icon-sm mr-1"></i> Reset</button>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-8 mb-3 pr3">
                        <div class="form-group">
                            <label for="id_produk">Kode Produk</label>
                            <input type="text" name="id_produk" class="form-control" id="id_produk" value="<?= $msg != '' ? $produk['id_produk'] : generateid() ?>" readonly>
                        </div>
                        <div class="form-group">
                            <label for="nm_produk">Nama Produk *</label>
                            <input type="text" name="nm_produk" class="form-control" id="nm_produk" placeholder="nama produk" value="<?= $msg != '' ? $produk['nm_produk'] : null ?>" autocomplete="off" autofocus required>
                        </div>
                        <div class="form-group">
                            <label for="idkategori">Kategori *</label>
                            <select name="idkategori" id="idkategori" class="form-control">
                                <option value="">-- Pilih Kategori --</option>
                                <?php
                                $kategori = getData("SELECT * FROM tb_kategori");
                                foreach ($kategori as $ktg) { ?>
                                    <option value="<?= $ktg['id_kategori'] ?>" <?= $msg != '' && $produk['id_kategori'] == $ktg['id_kategori'] ? 'selected' : '' ?>><?= $ktg['id_kategori'] . " | " . $ktg['nm_kategori'] ?></option>
                                <?php
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="deskripsi">Deskripsi</label>
                            <input type="text" name="deskripsi" class="form-control" id="deskripsi" placeholder="deskripsi" value="<?= $msg != '' ? $produk['deskripsi'] : null ?>" autocomplete="off" autofocus required>
                        <div class="form-group">
                            <label for="harga_jual">Harga Jual *</label>
                            <input type="number" name="harga_jual" class="form-control" id="harga_jual" placeholder="Rp 0" value="<?= $msg != '' ? $produk['harga_jual'] : null ?>" autocomplete="off" required>
                        </div>
                        <div class="form-group">
                            <label for="harga_modal">Harga Modal *</label>
                            <input type="number" name="harga_modal" class="form-control" id="harga_modal" placeholder="Rp 0" value="<?= $msg != '' ? $produk['harga_modal'] : null ?>" autocomplete="off" required>
                        </div>
                        <div class="form-group">
                            <label for="stok_min">Stok Minimal *</label>
                            <input type="number" name="stok_min" class="form-control" id="stok_min" placeholder="0" value="<?= $msg != '' ? $produk['stok_min'] : null ?>" autocomplete="off" required>
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
                    <div class="col-lg-4 text-center px-3">
                        <input type="hidden" name="oldImg" value="<?= $msg != '' ? $produk['gambar'] : null ?>">
                        <img src="<?= $main_url ?>asset/image/<?= $msg != '' ? $produk['gambar'] : 'default.png' ?>" class="profile-user-image mb-3 mt-4" alt="">
                        <input type="file" class="form-control" name="gambar">
                        <span class="text-sm">Type file gambar JPG | PNG</span>
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
