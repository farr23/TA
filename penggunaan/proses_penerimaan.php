<?php
session_start();

if (!isset($_SESSION["sslogin"])) {
    header("location: ../auth/login.php");
    exit();
}

require "../config/config.php";

// Mengambil data dari form
$id_bahan = $_POST['id_bahan'];
$jumlah_masuk = $_POST['jumlah_masuk'];
$keterangan = $_POST['keterangan'];
$tanggal = date('Y-m-d'); // Tanggal penerimaan bahan, bisa disesuaikan

// Validasi input
if (empty($id_bahan) || empty($jumlah_masuk)) {
    header("Location: trans_masuk.php?status=error");
    exit();
}

// Mengambil data jumlah awal bahan dari tb_bahan
$sql_check = "SELECT stok FROM tb_bahan WHERE id_bahan = ?";
$stmt_check = $koneksi->prepare($sql_check);

if ($stmt_check === false) {
    die("Prepare failed: " . $koneksi->error);
}

$stmt_check->bind_param("i", $id_bahan);
$stmt_check->execute();
$result_check = $stmt_check->get_result();
$data_bahan = $result_check->fetch_assoc();

if (!$data_bahan) {
    // Jika id_bahan tidak ditemukan
    header("Location: trans_masuk.php?status=not_found");
    exit();
}

$jumlah_awal = $data_bahan['stok_akhir'];
$jumlah_akhir = $jumlah_awal + $jumlah_masuk;

// Menyimpan data ke tabel tb_penerimaan_bahan
$sql_insert = "INSERT INTO tb_penerimaan_bahan (id_bahan, jumlah_masuk, keterangan, tgl_penerimaan) VALUES (?, ?, ?, ?)";
$stmt_insert = $koneksi->prepare($sql_insert);

if ($stmt_insert === false) {
    die("Prepare failed: " . $koneksi->error);
}

$stmt_insert->bind_param("iiss", $id_bahan, $jumlah_masuk, $keterangan, $tanggal);

if ($stmt_insert->execute()) {
    // Update stok bahan di tb_bahan
    $sql_update = "UPDATE tb_bahan SET stok = ? WHERE id_bahan = ?";
    $stmt_update = $koneksi->prepare($sql_update);

    if ($stmt_update === false) {
        die("Prepare failed: " . $koneksi->error);
    }

    $stmt_update->bind_param("ii", $jumlah_akhir, $id_bahan);
    $stmt_update->execute();

    // Jika berhasil, redirect ke daftar penerimaan bahan
    header("Location: daftar_penerimaan.php?status=success");
    exit();
} else {
    // Jika gagal, redirect kembali ke form dengan status error
    header("Location: trans_masuk.php?status=error");
    exit();
}

$stmt_check->close();
$stmt_insert->close();
$stmt_update->close();
$koneksi->close();
?>
