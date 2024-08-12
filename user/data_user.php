<?php

session_start();

if(!isset($_SESSION["sslogin"])) {
    header("location: ../auth/login.php");
    exit();
  }

require "../config/config.php";
require "../config/function.php";
require "../module/mode_user.php";

$title = "User - Cafe Qita";
require "../partials/header.php";
require "../partials/navbar.php";
require "../partials/sidebar.php";


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
              <li class="breadcrumb-item active">User</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-list fa-sm"></i> Data User</h3>
                    <div class="card-tools">
                        <a href="<?= $main_url ?>user/add_user.php" class="btn btn-sm btn-primary"><i class="fas fa-plus fa-sm mr-1"></i> Tambah User </a>
                    </div>
                </div>
                <div class="card-body table-responsive p-3">
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Foto</th>
                                <th>Username</th>
                                <th>Nama Lengkap</th>
                                <th>Alamat</th>
                                <th>Level User</th>
                                <th style="width :10%;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            $users = getData("SELECT * FROM tb_user");
                            foreach($users as $user) : 
                            ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><img src="../asset/image/<?= $user['foto'] ?>" class="rounded-circle" alt="" width="60px"></td>
                                <td><?= $user['username']?></td>
                                <td><?= $user['fullname']?></td>
                                <td><?= $user['alamat']?></td>
                                <td>
                                    <?php
                                    if($user['level'] == 1){
                                        echo "Administrator";
                                    } else if($user['level'] == 2){
                                        echo "Manager";
                                    } else if($user['level'] == 3){
                                        echo "Operator";
                                    } else {
                                        echo "Operator2";
                                    }
                                    ?>
                                </td>
                                <td>
                                    <a href="edit_user.php?id=<?= $user ['user_id'] ?>" class="btn btn-sm btn-warning" title="edit user"><i class="fas fa-edit"></i></a>
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