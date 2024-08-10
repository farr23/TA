<?php
require "../config/config.php";

// Fungsi untuk menyimpan transaksi pembelian
function simpanPembelian($data) {
    global $koneksi;

    $no_transbeli = $data['no_transbeli'];
    $tglnota = $data['tglnota'];
    $supplier = $data['supplier'];
    $keterangan = $data['keterangan'];
    $total = $data['total'];

    // Query untuk menyimpan data pembelian
    $query = "INSERT INTO trans_pembelian (no_transbeli, tgl_transbeli, nm_supplier, total, keterangan) VALUES (?, ?, ?, ?, ?)";
    $stmt = $koneksi->prepare($query);
    $stmt->bind_param('sssss', $no_transbeli, $tglnota, $supplier, $total, $keterangan);

    return $stmt->execute();
}

// Fungsi untuk menyimpan detail pembelian
function simpanDetailPembelian($data) {
    global $koneksi;

    $no_transbeli = $data['no_transbeli'];
    $id_bahan = $data['id_bahan'];
    $nm_bahan = $data['nmbahan'];
    $jumlah = $data['qty'];
    $harga_beli = $data['harga'];
    $jml_harga = $jumlah * $harga_beli;

    // Query untuk menyimpan data detail pembelian
    $query = "INSERT INTO trans_pemdetail (no_transbeli, tgl_transbeli, id_bahan, nm_bahan, jumlah, harga_beli, jml_harga) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $koneksi->prepare($query);
    $stmt->bind_param('sssssss', $no_transbeli, $data['tglnota'], $id_bahan, $nm_bahan, $jumlah, $harga_beli, $jml_harga);

    return $stmt->execute();
}

// Fungsi untuk menghapus detail pembelian
function deleteDetail($idbhn, $idbeli, $qty) {
    global $koneksi;

    // Query untuk menghapus detail pembelian
    $query = "DELETE FROM trans_pemdetail WHERE id_bahan = ? AND no_transbeli = ? AND jumlah = ?";
    $stmt = $koneksi->prepare($query);
    $stmt->bind_param('sss', $idbhn, $idbeli, $qty);

    return $stmt->execute();
}

// Fungsi untuk menghitung total pembelian
function totalbeli($no_transbeli) {
    global $koneksi;

    // Query untuk menghitung total pembelian
    $query = "SELECT SUM(jml_harga) AS total FROM trans_pemdetail WHERE no_transbeli = ?";
    $stmt = $koneksi->prepare($query);
    $stmt->bind_param('s', $no_transbeli);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();

    return $data['total'] ?? 0;
}

// Fungsi untuk menghasilkan nomor transaksi pembelian
function generateno() {
    global $koneksi;

    $query = "SELECT RIGHT(no_transbeli, 6) AS last_no FROM trans_pembelian ORDER BY no_transbeli DESC LIMIT 1";
    $result = $koneksi->query($query);
    $data = $result->fetch_assoc();
    $last_no = $data['last_no'] ?? '000000';

    $new_no = str_pad((int)$last_no + 1, 6, '0', STR_PAD_LEFT);

    return 'TRX-' . date('ymd') . '-' . $new_no;
}
?>
