<?php
session_start();

if (!isset($_SESSION["sslogin"])) {
    header("location: ../auth/login.php");
    exit();
}

require "../config/config.php";

// Mengambil data dari formulir
$id_bahan = $_POST['id_bahan'];
$jumlah_keluar = $_POST['jumlah_keluar'];
$keterangan = $_POST['keterangan'];
$tgl_transaksi = date('Y-m-d');

// Mendapatkan nama bahan dan jumlah stok saat ini
$sql_bahan = "SELECT nm_bahan, stok FROM tb_bahan WHERE id_bahan = ?";
$stmt_bahan = $koneksi->prepare($sql_bahan);
$stmt_bahan->bind_param('i', $id_bahan);
$stmt_bahan->execute();
$result_bahan = $stmt_bahan->get_result()->fetch_assoc();
$nm_bahan = $result_bahan['nm_bahan'];
$stok_awal = $result_bahan['stok'];

// Menghitung stok akhir
$stok_akhir = $stok_awal - $jumlah_keluar;

// Menyimpan transaksi
$sql_transaksi = "INSERT INTO tb_transaksi_bahan (tgl_transaksi, jenis_transaksi, id_bahan, nm_bahan, jumlah_awal, jumlah_keluar, jumlah_akhir, keterangan) VALUES (?, 'Keluar', ?, ?, ?, ?, ?, ?)";
$stmt_transaksi = $koneksi->prepare($sql_transaksi);
$stmt_transaksi->bind_param('sisiids', $tgl_transaksi, $id_bahan, $nm_bahan, $stok_awal, $jumlah_keluar, $stok_akhir, $keterangan);
$stmt_transaksi->execute();

// Memperbarui stok bahan
$sql_update_bahan = "UPDATE tb_bahan SET stok = ? WHERE id_bahan = ?";
$stmt_update_bahan = $koneksi->prepare($sql_update_bahan);
$stmt_update_bahan->bind_param('di', $stok_akhir, $id_bahan);
$stmt_update_bahan->execute();

header("Location: laporan_stok_keluar.php");
exit();
?>
