<?php

session_start();

if(!isset($_SESSION["sslogin"])) {
    header("location: ../auth/login.php");
    exit();
  }

require "../config/config.php";
require "../config/function.php";
require "../module/mode_bahan.php";

$title = "Laporan - Cafe Qita";
require "../partials/header.php";
require "../partials/navbar.php";
require "../partials/sidebar.php";

$id = $_GET['id'];
$tgl = $_GET['tgl'];

$pembelian = getData("SELECT * FROM trans_pemdetail WHERE no_transbeli = '$id'");


?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Detail Pembelian</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?= $main_url ?>dashboard.php">Home</a></li>
              <li class="breadcrumb-item"><a href="<?= $main_url ?>laporan_pembelian">Pembelian</a></li>
              <li class="breadcrumb-item active">Detail</li>
            </ol>
        </div><!-- /.col -->
    </div><!-- /.row -->
</div><!-- /.container-fluid -->
</div>

<section class="content">
    <div class="container-fluid">
        <div class="card">
        <div class="card-header">
                <h3 class="card-title"><i class="fas fa-list text-sm mr-2"></i> Rincian Bahan</h3>
                <button type="button" class="btn btn-sm btn-success float-right"><?= $tgl ?></i></button>
                <button type="button" class="btn btn-sm btn-warning float-right mr-2"><?= $id ?></i></button>
            </div>
            <div class="card-body table-responsive p-3">
                <table class="table table-hover text-nowrap">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode Bahan</th>
                            <th>Nama Bahan</th>
                            <th>Harga Beli</th>
                            <th class="text-center">Qty</th>
                            <th class="text-center">Jumlah Harga</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        foreach($pembelian as $beli){?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= $beli['id_bahan'] ?></td>
                                <td><?= $beli['nm_bahan'] ?></td>
                                <td class="text-center"><?= number_format($beli['harga_beli'],0,",",".") ?></td>
                                <td class="text-center"><?= $beli['jumlah']?></td>
                                <td class="text-center"><?= number_format($beli['jml_harga'],0,",",".") ?></td>
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