<?php
require "../config/config.php";
require "../config/function.php";

if (isset($_GET['id_produk'])) {
    $id_produk = $_GET['id_produk'];
    $produkData = getData("SELECT * FROM tb_produk WHERE id_produk = '$id_produk'");
    $kategoriData = getData("SELECT * FROM tb_kategori WHERE id_kategori = (SELECT id_kategori FROM tb_produk WHERE id_produk = '$id_produk')");
    
    if (!empty($produkData) && !empty($kategoriData)) {
        $response = [
            'nm_produk' => $produkData[0]['nm_produk'],
            'nm_kategori' => $kategoriData[0]['nm_kategori']
        ];
        echo json_encode($response);
    } else {
        echo json_encode(['error' => 'Produk or Kategori not found']);
    }
} else {
    echo json_encode(['error' => 'Invalid request']);
}
?>
