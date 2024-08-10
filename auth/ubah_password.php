<?php

session_start();

if(!isset($_SESSION["sslogin"])) {
    header("location: ../auth/login.php");
    exit();
  }

require "../config/config.php";
require "../config/function.php";
require "../module/mode_password.php";

$title = "Ganti Password - Cafe Qita";
require "../partials/header.php";
require "../partials/navbar.php";
require "../partials/sidebar.php";

//update password
if(isset($_POST['simpan'])){
    if(update($_POST)){
        echo "<script>
            alert('Password telah diperbarui');
            document.location='ubah_password.php'; 
        </script>";
    }
}

//error password konfirmasi
if(isset($_GET['msg'])){
    $msg = $_GET['msg'];
} else {
    $msg = '';
}

$alert1 = '<small class="text-danger pl-2 font-italic">Konfirmasi password berbeda</small>';
$alert2 = '<small class="text-danger pl-2 font-italic">Current password berbeda</small>';

?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Password</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?= $main_url ?>dashboard.php">Home</a></li>
              <li class="breadcrumb-item active">Password</li>
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
                        <h3 class="card-title"><i class="fas fa-key mr-1 text-sm"></i> Ganti Password</h3>
                        <button type="submit" name="simpan" class="btn btn-primary btn-sm float-right"><i class="fas fa-edit text-sm mr-1"></i> Simpan</button>
                        <button type="reset" name="reset" class="btn btn-danger btn-sm float-right mr-1"><i class="fas fa-times text-sm mr-1"></i> Reset</button>
                    </div>
                    <div class="card-body">
                        <div class="col-lg-8 mb-3">
                            <div class="form-group">
                                <label for="curpass">Current Password</label>
                                <input type="password" name="curpass" id="curpass" class="form-control" placeholder="Masukkan password anda saat ini" reuired>
                                <?php if($msg == 'err2'){
                                    echo $alert2;
                                } ?>
                            </div>
                            <div class="form-group">
                                <label for="newpass">New Password</label>
                                <input type="password" name="newpass" id="newpass" class="form-control" placeholder="Masukkan password baru anda" reuired>
                            </div>
                            <div class="form-group">
                                <label for="confpass"> Confirm Password</label>
                                <input type="password" name="confpass" id="confpass" class="form-control" placeholder="Masukkan kembali password baru anda" reuired>
                                <?php if($msg == 'err1'){
                                    echo $alert1;
                                } ?>
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