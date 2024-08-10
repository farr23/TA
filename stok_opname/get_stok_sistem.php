<?php

require "../config/config.php";

if (isset($_GET['id_bahan'])) {
    $id_bahan = $_GET['id_bahan'];
    $sql = "SELECT stok FROM tb_bahan WHERE id_bahan = '$id_bahan'";
    $result = mysqli_query($koneksi, $sql);
    
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        echo json_encode(['stok_sistem' => $row['stok']]);
    } else {
        echo json_encode(['stok_sistem' => 0]);
    }
}

?>
