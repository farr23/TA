<?php
require_once "../config/config.php";

function insert($noProduksi, $idBahan, $jumlah, $satuan)
{
    global $koneksi;
    $query = "INSERT INTO tb_produksi_detail (id_produksi, id_bahan, jumlah, satuan) VALUES ('$noProduksi', '$idBahan', '$jumlah', '$satuan')";
    echo $query;
    if (!mysqli_query($koneksi, $query)) {
        echo "Error: " . mysqli_error($koneksi);
    }
}

function delete($idBahan, $noProduksi, $qty)
{
    global $koneksi;
    $query = "DELETE FROM tb_produksi_detail WHERE id_produksi = '$noProduksi' AND id_bahan = '$idBahan' AND jumlah = '$qty'";
    mysqli_query($koneksi, $query);
}

function simpan($data)
{
    global $koneksi;
    $noProduksi = $data['no_transprod'];
    $tglProduksi = $data['tglproduksi'];
    $idProduk = $data['idproduk'];
    $qty = $data['qty'];

    $query = "INSERT INTO tb_produksi (no_produksi, tgl_produksi, id_produk, total_produksi) VALUES ('$noProduksi', '$tglProduksi', '$idProduk', '$qty')";
    if (mysqli_query($koneksi, $query)) {
        foreach ($data['idbahan'] as $index => $value) {
            $bahan = $data['idbahan'][$index];
            $jumlahBahan = $data['jumlahbahan'][$index] * $qty;
            $queryInsertDetail = "INSERT INTO tb_produksi_detail VALUES('', '$noProduksi', '$bahan', '$jumlahBahan')";
            mysqli_query($koneksi, $queryInsertDetail);

            $queryUpdateStok = "UPDATE tb_bahan SET stok = stok - '$jumlahBahan' WHERE id_bahan = '$bahan'";
            mysqli_query($koneksi, $queryUpdateStok);
        }
        return true;
    } else {
        return false;
    }
}

function generateno()
{
    global $koneksi;
    $query = "SELECT MAX(id_produksi) as max_id FROM tb_produksi";
    $result = mysqli_query($koneksi, $query);
    $row = mysqli_fetch_assoc($result);
    $max_id = $row['max_id'];
    $noProduksi = "PROD" . str_pad($max_id + 1, 4, '0', STR_PAD_LEFT);
    return $noProduksi;
}

function totalproduksi($noProduksi)
{
    global $koneksi;
    $query = "SELECT SUM(jumlah) as total FROM tb_produksi_detail WHERE id_produksi = '$noProduksi'";
    $result = mysqli_query($koneksi, $query);
    $row = mysqli_fetch_assoc($result);
    return $row['total'];
}

//quotation
function updateQuotationStatus($no_quotation, $status) {
    global $koneksi;
    $query = "UPDATE tb_quotation SET status = ? WHERE no_quotation = ?";
    $stmt = mysqli_prepare($koneksi, $query);
    mysqli_stmt_bind_param($stmt, 'ss', $status, $no_quotation);
    return mysqli_stmt_execute($stmt);
}

function getProcessedQuotations() {
    global $koneksi;
    $query = "SELECT * FROM tb_quotation WHERE status = 'processed'";
    $result = mysqli_query($koneksi, $query);
    $quotations = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $quotations[] = $row;
    }
    return $quotations;
}

function getQuotations() {
    global $koneksi;
    $query = "SELECT * FROM tb_quotation";
    $result = mysqli_query($koneksi, $query);
    $quotations = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $quotations[] = $row;
    }
    return $quotations;
}

function getQuotationDetails($no_quotation) {
    global $koneksi;
    $query = "SELECT * FROM tb_quotation_detail WHERE no_quotation = '$no_quotation'";
    $result = mysqli_query($koneksi, $query);
    $details = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $details[] = $row;
    }
    return $details;
}

function getQuotationByNo($no_quotation) {
    global $koneksi;
    $query = "SELECT * FROM tb_quotation WHERE no_quotation = '$no_quotation'";
    $result = mysqli_query($koneksi, $query);
    return mysqli_fetch_assoc($result);
}

function insertQuotation($quotation) {
    global $koneksi;
    $query = "INSERT INTO tb_quotation (no_quotation, tgl_quotation, nm_customer, total, keterangan, status) VALUES (?, ?, ?, ?, ?, 'pending')";
    $stmt = mysqli_prepare($koneksi, $query);
    mysqli_stmt_bind_param($stmt, 'sssis', $quotation['noquotation'], $quotation['tglnota'], $quotation['customer'], $quotation['total'], $quotation['keterangan']);
    return mysqli_stmt_execute($stmt);
}

function insertQuotationDetail($detail) {
    global $koneksi;
    $query = "INSERT INTO tb_quotation_detail (no_quotation, id_produk, nm_produk, qty, harga, jml_harga) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($koneksi, $query);
    mysqli_stmt_bind_param($stmt, 'sssiis', $detail['noquotation'], $detail['id_produk'], $detail['nm_produk'], $detail['qty'], $detail['harga'], $detail['jml_harga']);
    return mysqli_stmt_execute($stmt);
}

function totalQuotation($no_quotation) {
    global $koneksi;
    $query = "SELECT SUM(jml_harga) as total FROM tb_quotation_detail WHERE no_quotation = ?";
    $stmt = mysqli_prepare($koneksi, $query);
    mysqli_stmt_bind_param($stmt, 's', $no_quotation);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    return $row['total'];
}


?>