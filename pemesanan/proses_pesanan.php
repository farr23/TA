<?php
// Koneksi ke database

require "../config/config.php";
require "../config/function.php";

$title = "Transaksi Produksi - Cafe Qita";
require "../partials/header.php";
require "../partials/navbar.php";
require "../partials/sidebar.php";

if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Ambil data dari form
$tanggal_pesanan = $_POST['tanggal_pesanan'];
$id_pelanggan = $_POST['id_customer'];
$id_produk = $_POST['id_produk'];
$jumlah = $_POST['jumlah'];
$harga_satuan = $_POST['harga_satuan'];

// Validasi input
if(empty($tanggal_pesanan) || empty($id_pelanggan) || empty($id_produk) || empty($jumlah) || empty($harga_satuan)) {
    die("Data tidak lengkap. Silakan kembali dan lengkapi semua data.");
}

// Mulai transaksi
mysqli_begin_transaction($koneksi);

try {
    // Insert ke tabel tb_pesanan
    $total_harga = 0;
    for ($i = 0; $i < count($id_produk); $i++) {
        $total_harga += $jumlah[$i] * $harga_satuan[$i];
    }

    $sql_pesanan = "INSERT INTO tb_pesanan (tanggal_pesanan, id_customer, status, total_harga) 
                    VALUES ('$tanggal_pesanan', $id_pelanggan, 'Pending', $total_harga)";
    if (!mysqli_query($koneksi, $sql_pesanan)) {
        throw new Exception("Error: " . mysqli_error($koneksi));
    }

    // Ambil ID pesanan terakhir yang baru dimasukkan
    $id_pesanan = mysqli_insert_id($koneksi);

    // Insert ke tabel tb_detail_pesanan
    for ($i = 0; $i < count($id_produk); $i++) {
        $subtotal = $jumlah[$i] * $harga_satuan[$i];
        $sql_detail_pesanan = "INSERT INTO tb_detail_pesanan (id_pesanan, id_produk, jumlah, harga_satuan, subtotal)
                               VALUES ($id_pesanan, {$id_produk[$i]}, {$jumlah[$i]}, {$harga_satuan[$i]}, $subtotal)";
        if (!mysqli_query($koneksi, $sql_detail_pesanan)) {
            throw new Exception("Error: " . mysqli_error($koneksi));
        }
    }

    // Commit transaksi
    mysqli_commit($koneksi);
    echo "Pesanan berhasil disimpan.";

} catch (Exception $e) {
    // Rollback transaksi jika ada kesalahan
    mysqli_rollback($koneksi);
    echo "Pesanan gagal disimpan: " . $e->getMessage();
}

// Tutup koneksi
mysqli_close($koneksi);
?>

<?php

require "../partials/footer.php";

?>
