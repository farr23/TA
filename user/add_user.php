<?php

session_start();

if(!isset($_SESSION["sslogin"])) {
    header("location: ../auth/login.php");
    exit();
  }

require "../config/config.php";
require "../config/function.php";
require "../module/mode_user.php";

$title = "Tambah User - Cafe Qita";
require "../partials/header.php";
require "../partials/navbar.php";
require "../partials/sidebar.php";

if(isset($_POST['simpan'])){
    if(insert($_POST) > 0) {
        echo "<script>
                alert('user baru berhasil registrasi');
            </script>";
    }
}

?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Users</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?= $main_url ?>dashboard.php">Home</a></li>
              <li class="breadcrumb-item"><a href="<?= $main_url ?>user/data_user.php">Users</a></li>
              <li class="breadcrumb-item active">Add User</li>
            </ol>
        </div><!-- /.col -->
    </div><!-- /.row -->
</div><!-- /.container-fluid -->
</div>

<section class="content">
    <div class="container-fluid">
        <div class="card">
            <form action="" method="post" enctype="multipart/form-data">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-plus text-sm mr-2"></i> Tambah User</h3>
                <button type="submit" name="simpan" class="btn btn-primary btn-sm float-right"><i class="fas fa-save icon-sm mr-1"></i> Simpan</button>
                <button type="reset" class="btn btn-danger btn-sm float-right mr-2"><i class="fas fa-times icon-sm mr-1"></i> Reset</button>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-8 mb-3">
                        <div class="form-group">
                            <label for="username">Username</label>
                            <!-- name dengan id sama -->
                            <input type="text" name="username" class="form-control" id="username" placeholder="masukkan username" autofocus autocomplete="off" required>
                        </div>
                        <div class="form-group">
                            <label for="fullname">Nama Lengkap</label>
                            <!-- name dengan id sama -->
                            <input type="text" name="fullname" class="form-control" id="fullname" placeholder="masukkan nama lengkap" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <!-- name dengan id sama -->
                            <input type="password" name="password" class="form-control" id="password" placeholder="masukkan password" required>
                        </div>
                        <div class="form-group">
                            <label for="password2">Konfirmasi Password</label>
                            <!-- name dengan id sama -->
                            <input type="password" name="password2" class="form-control" id="password2" placeholder="masukkan kembali password" required>
                        </div>
                        <div class="form-group">
                            <label for="level">Level</label>
                            <select name="level" id="level" class="form-control" required>
                                <option value="">-- Level User --</option>
                                <option value="1">Administrator</option>
                                <option value="2">Manager</option>
                                <option value="3">Operator</option>
                                <option value="3">Operator2</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="alamat">Alamat</label>
                            <textarea name="alamat" id="alamat" cols="" rows="3" class="form-control" placeholder="masukkan alamat di sini" required></textarea>
                        </div>
                    </div>
                    <div class="col-lg-4 text-center">
                        <img src="<?= $main_url ?>asset/image/default.png" class="profile_user_image img-circle mb-3" alt="">
                        <input type="file" class="form-control" name="image">
                        <span class="text-sm">Type file gambar JPG | PNG </span></br>
                        <span class="text-sm">Width = Height</span>
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