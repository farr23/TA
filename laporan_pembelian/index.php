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

$pembelian = getData("SELECT * FROM trans_pembelian");


?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Laporan Pembelian</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?= $main_url ?>dashboard.php">Home</a></li>
              <li class="breadcrumb-item"><a href="<?= $main_url ?>laporan_pembelian/laporan.php">Laporan Pembelian</a></li>
              <li class="breadcrumb-item active">Daftar Pembelian</li>
            </ol>
        </div><!-- /.col -->
    </div><!-- /.row -->
</div><!-- /.container-fluid -->
</div>

<section class="content">
    <div class="container-fluid">
        <div class="card">
        <div class="card-header">
                <h3 class="card-title"><i class="fas fa-list text-sm mr-2"></i> Data Pembelian</h3>
                <button type="button" class="btn btn-sm btn-outline-primary float-right" data-toggle="modal" data-target="#mdlperiodebeli"><i class="fas fa-print"> Cetak</i></button>
            </div>
        </div>
        <div class="card-body table-responsive p-3">
                <table class="table table-hover text-nowrap" id="tbdata">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>No Pembelian</th>
                            <th>Tanggal Pembelian</th>
                            <th>Supplier</th>
                            <th>Total Pembelian</th>
                            <th style="width: 10%;" class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        foreach($pembelian as $beli){?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= $beli['no_transbeli'] ?></td>
                                <td><?= in_date($beli['tgl_transbeli']) ?></td>
                                <td><?= $beli['nm_supplier'] ?></td>
                                <td class="text-center"><?= number_format($beli['total'],0,",",".") ?></td>
                                <td class="text-center"><a href="detail_pembelian.php?id=<?= $beli['no_transbeli'] ?>&tgl=<?= in_date($beli['tgl_transbeli']) ?>" class="btn btn-sm btn-info" title="rincian bahan">Detail</a></td>
                            </tr>
                            <?php
                        }
                        ?>
</tbody>
</table>
</div>
    </div>
</section>

<div class="modal fade" id="mdlperiodebeli">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Periode Pembelian</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group row">
                    <label for="nmbhn" class="col-sm-3 col-form-label">Tanggal awal</label>
                    <div class="col-sm-9">
                        <input type="date" class="form-control" id="tgl1">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="nmbhn" class="col-sm-3 col-form-label">Tanggal akhir</label>
                    <div class="col-sm-9">
                        <input type="date" class="form-control" id="tgl2">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="printDoc()"><i class="fas fa-print"></i> Cetak</button>
            </div>
        </div>
    </div>
</div>

<script>
    let tgl1 = document.getElementById('tgl1');
    let tgl2 = document.getElementById('tgl2');

    function printDoc(){
        if(tgl1.value != "" && tgl2.value != ""){
            window.open("../report/r_beli.php?tgl1=" + tgl1.value + "&tgl2=" + tgl2.value, "", "width=900, height=600, left=100");
        }
    }
</script>


<?php

require "../partials/footer.php";

?>