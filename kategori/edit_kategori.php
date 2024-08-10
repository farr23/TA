<?php

session_start();

if(!isset($_SESSION["sslogin"])) {
    header("location: ../auth/login.php");
    exit();
  }

require "../config/config.php";
require "../config/function.php";
require "../module/mode_kategori.php";

$title = "Edit Kategori - Cafe Qita";
require "../partials/header.php";
require "../partials/navbar.php";
require "../partials/sidebar.php";

//fungsi update data
if(isset($_POST['update'])){
    if(update($_POST)){
        echo "<script>
            document.location.href = 'data_kategori.php?msg=updated';
        </script>";
    }
}

$id = $_GET['id'];

$sqledit    = "SELECT * FROM tb_kategori WHERE id_kategori = $id";
$kategori       = getData($sqledit)[0];

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
              <li class="breadcrumb-item active">Edit Kategori</li>
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
                <h3 class="card-title"><i class="fas fa-plus text-sm mr-2"></i> Edit Kategori</h3>
                <button type="submit" name="update" class="btn btn-primary btn-sm float-right"><i class="fas fa-save icon-sm mr-1"></i> Update</button>
                <button type="reset" class="btn btn-danger btn-sm float-right mr-2"><i class="fas fa-times icon-sm mr-1"></i> Reset</button>
            </div> 
            <div class="card-body">
                <div class="row">
                    <!-- name iku masuk nang mode kategori -->
                    <input type="hidden" value="<?= $kategori['id_kategori'] ?>" name="id" >
                    <div class="col-lg-8 mb-3">
                        <div class="form-group">
                            <label for="nm_kategori">Nama Kategori</label>
                            <input type="text" name="nm_kategori" class="form-control" id="nm_kategori" placeholder="nama kategori" autofocus value="<?= $kategori['nm_kategori'] ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="status">Status *</label>
                            <select name="status" id="status" class="form-control" required>
                                <option value="">-- Pilih Status --</option>
                                <option value="1" <?= $kategori['status'] == '1' ? 'selected' : '' ?>>Aktif</option>
                                <option value="0" <?= $kategori['status'] == '0' ? 'selected' : '' ?>>Tidak Aktif</option>
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