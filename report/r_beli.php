<?php

session_start();

if(!isset($_SESSION["sslogin"])) {
    header("location: ../auth/login.php");
    exit();
  }

require "../config/config.php";
require "../config/function.php";

$tgl1 = $_GET['tgl1'];
$tgl2 = $_GET['tgl2'];
$databeli = getData("SELECT * FROM trans_pembelian WHERE tgl_transbeli BETWEEN '$tgl1' AND '$tgl2'");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Pembelian</title>
</head>
<body>
    
  <div style="text-align: center;">
    <h2 style="margin-bottom: -15px;">Rekap Laporan Pembelian</h2>
    <h2 style="margin-bottom: 15px;">cafeqita</h2>
  </div>

  <table>
    <thead>
        <tr>
            <td colspan="5" style="height: 5px;">
                <hr style="margin-bottom: 2px; margin-left: -5px;", size="3", color="gray">
            </td>
        </tr>
        <tr>
            <th>No</th>
            <th style="width: 120px;">Tgl Pembelian</th>
            <th style="width: 120px;">ID Pembelian</th>
            <th style="width: 300px;">Supplier</th>
            <th>Total Pembelian</th>
        </tr>
        <tr>
            <td colspan="5" style="height: 5px;">
                <hr style="margin-bottom: 2px; margin-left: -5px; margin-top: 1px;", size="3", color="gray">
            </td>
        </tr>
    </thead>
    <tbody>
        <?php
        $no = 1;
        foreach($databeli as $data){?>
            <tr>
                <td><?= $no++ ?></td>
                <td align="center"><?= in_date($data['tgl_transbeli']) ?></td>
                <td align="center"><?= $data['no_transbeli'] ?></td>
                <td><?= $data['nm_supplier']?></td>
                <td align="right"><?= number_format($data['total'],0,',','.') ?></td>
            </tr>
            <?php
        }
        ?>
    </tbody>
    <tfoot>
        <tr>
        <td colspan="5" style="height: 5px;">
                <hr style="margin-bottom: 2px; margin-left: -5px; margin-top: 1px;", size="3", color="gray">
            </td>
        </tr>
    </tfoot>
  </table>

  <script>
    window.print();
  </script>

</body>
</html>