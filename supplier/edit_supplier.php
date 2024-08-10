<?php

session_start();

if(!isset($_SESSION["sslogin"])) {
    header("location: ../auth/login.php");
    exit();
  }

require "../config/config.php";
require "../config/function.php";
require "../module/mode_supplier.php";

$title = "Edit Supplier - Cafe Qita";
require "../partials/header.php";
require "../partials/navbar.php";
require "../partials/sidebar.php";

//fungsi update data
if(isset($_POST['update'])){
    if(update($_POST)){
        echo "<script>
            document.location.href = 'data_supplier.php?msg=updated';
        </script>";
    }
}


$id = $_GET['id'];

$sqledit    = "SELECT * FROM tb_supplier WHERE id_supplier = $id";
$supplier       = getData($sqledit)[0];

?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Supplier</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?= $main_url ?>dashboard.php">Home</a></li>
              <li class="breadcrumb-item"><a href="<?= $main_url ?>supplier/data_supplier.php">Supplier</a></li>
              <li class="breadcrumb-item active">Edit Supplier</li>
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
                <h3 class="card-title"><i class="fas fa-plus text-sm mr-2"></i> Edit Supplier</h3>
                <button type="submit" name="update" class="btn btn-primary btn-sm float-right"><i class="fas fa-save icon-sm mr-1"></i> Update</button>
                <button type="reset" class="btn btn-danger btn-sm float-right mr-2"><i class="fas fa-times icon-sm mr-1"></i> Reset</button>
            </div> 
            <div class="card-body">
                <div class="row">
                    <!-- name iku masuk nang mode supplier -->
                    <input type="hidden" value="<?= $supplier['id_supplier'] ?>" name="id" >
                    <div class="col-lg-8 mb-3">
                        <div class="form-group">
                            <label for="nm_supplier">Nama Supplier</label>
                            <input type="text" name="nm_supplier" class="form-control" id="nm_supplier" placeholder="nama supplier" autofocus value="<?= $supplier['nm_supplier'] ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="telp">No Telepon</label>
                            <input type="text" name="telp" class="form-control" id="telp" placeholder="nomor telepon supplier" pattern="[0-9]{5,}" title="minimal 5 angka" value="<?= $supplier['telp'] ?>" required>
                        </div>
                        <!-- text area di luar -->
                        <div class="form-group">
                            <label for="deskripsi">Deskripsi</label>
                            <textarea name="deskripsi" id="deskripsi" rows="2" class="form-control" placeholder="Deskripsi supplier" required><?= $supplier['deskripsi'] ?></textarea>
                        </div>
                        <div class="form-group">
                            <label for="alamat">Alamat</label>
                            <textarea name="alamat" id="alamat" rows="3" class="form-control" placeholder="Alamat supplier" required><?= $supplier['alamat']?></textarea>
                        </div>
                        <div class="form-group">
                                    <label for="status">Status *</label>
                                    <select name="status" id="status" class="form-control" required>
                                        <option value="">-- Pilih Status --</option>
                                        <option value="1" <?= $supplier['status'] == '1' ? 'selected' : '' ?>>Aktif</option>
                                        <option value="0" <?= $supplier['status'] == '0' ? 'selected' : '' ?>>Tidak Aktif</option>
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