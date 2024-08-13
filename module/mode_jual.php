<?php

function generateno()
{
    global $koneksi;

    $queryno = mysqli_query($koneksi, "SELECT max(no_transjual) as maxno FROM trans_penjualan");
    $row = mysqli_fetch_assoc($queryno);
    $maxno = $row["maxno"];

    $nourut = (int) substr($maxno, 2, 4);
    $nourut++;
    $newno = 'PJ' . sprintf("%04s", $nourut);

    return $newno;
}

// Fungsi untuk mengurangi stok produk di tabel tb_produk
function kurangiStok($id_produk, $qty)
{
    global $koneksi;
    // Mengambil total stok dari tabel tb_produk
    $produk = mysqli_query($koneksi, "SELECT * FROM tb_produk WHERE id_produk = '$id_produk'");
    while ($row = mysqli_fetch_assoc($produk)) {
        if ($qty <= $row['stok']) {
            // Kurangi stok pada catatan produksi saat ini
            $new_qty = $row['stok'] - $qty;
            mysqli_query($koneksi, "UPDATE tb_produk SET stok = '$new_qty' WHERE id_produk = '{$row['id_produk']}'");
            break;
        } else {
            // Kurangi stok pada catatan produksi saat ini dan lanjutkan ke catatan produksi berikutnya
            $qty -= $row['stok'];
            mysqli_query($koneksi, "UPDATE tb_produk SET stok = 0 WHERE id_produk = '{$row['id_produk']}'");
        }
    }
}

function totalJual($nojual)
{
    global $koneksi;

    $totaljual = mysqli_query($koneksi, "SELECT sum(jml_harga) AS total FROM trans_penjudetail WHERE no_transjual = '$nojual'");
    $data = mysqli_fetch_assoc($totaljual);
    return $data["total"];
}

// Fungsi untuk memasukkan data penjualan detail
function insertDetail($data, $noTransaksi, $tglTransaksi)
{
    global $koneksi;

    $nojual = $noTransaksi;
    $tglnota = $tglTransaksi;
    $id_produk = $data["id_produk"];
    $nm_produk = $data["nm_produk"];
    $harga_jual = $data["harga_jual"];
    $qty = $data["jumlah"];
    $jml_harga = $data["jml_harga"];
    $stok = $data["stok"];

    // Check if qty is not empty and greater than 0
    if (empty($qty) || $qty < 1) {
        echo "<script>alert('Qty produk tidak boleh kosong atau kurang dari 1');</script>";
        return false;
    } else if ($qty > $stok) {
        echo "<script>alert('Stok produk tidak mencukupi');</script>";
        return false;
    } else {
        $sqljual = "INSERT INTO trans_penjudetail VALUES(null, '$nojual', '$tglnota', '$id_produk', '$nm_produk', '$qty', '$harga_jual', '$jml_harga')";
        mysqli_query($koneksi, $sqljual);
    }

    // Update stock
    mysqli_query($koneksi, "UPDATE tb_produk SET stok = stok - $qty WHERE id_produk = '$id_produk'");

    return mysqli_affected_rows($koneksi);
}

// Fungsi untuk memasukkan data penjualan utama
function insertPenjualan($penjualan)
{
    global $koneksi;

    $nojual = $penjualan["nojual"];
    $tglnota = $penjualan["tglnota"];
    $customer = $penjualan["customer"];
    $total = $penjualan["total"];
    $keterangan = $penjualan["keterangan"];
    $bayar = $penjualan["bayar"];
    $kembalian = $penjualan["kembalian"];

    $sql = "INSERT INTO trans_penjualan (no_transjual, tgl_transjual, nm_customer, total, keterangan, jml_bayar, kembalian) 
            VALUES ('$nojual', '$tglnota', '$customer', '$total', '$keterangan', '$bayar', '$kembalian')";

    $insert = mysqli_query($koneksi, $sql);

    if ($insert) {
        return true;
    } else {
        return false;
    }
}

function insertQuotation($quotation)
{
    global $koneksi;
    $query = "INSERT INTO tb_quotation (no_quotation, tgl_quotation, nm_customer, total, keterangan) VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($koneksi, $query);
    mysqli_stmt_bind_param($stmt, 'sssis', $quotation['noquotation'], $quotation['tglnota'], $quotation['customer'], $quotation['total'], $quotation['keterangan']);
    return mysqli_stmt_execute($stmt);
}

function insertQuotationDetail($detail)
{
    global $koneksi;
    $query = "INSERT INTO tb_quotation_detail (id_quotation, id_produk, nm_produk, qty, harga, jml_harga) VALUES ((SELECT id_quotation FROM tb_quotation WHERE no_quotation = ?), ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($koneksi, $query);
    mysqli_stmt_bind_param($stmt, 'sisidd', $detail['noquotation'], $detail['id_produk'], $detail['nm_produk'], $detail['qty'], $detail['harga'], $detail['jml_harga']);
    return mysqli_stmt_execute($stmt);
}

function updateQuotationStatus($no_quotation, $status)
{
    global $koneksi;
    $query = "UPDATE tb_quotation SET status = ? WHERE no_quotation = ?";
    $stmt = mysqli_prepare($koneksi, $query);
    mysqli_stmt_bind_param($stmt, 'ss', $status, $no_quotation);
    return mysqli_stmt_execute($stmt);
}

function getProcessedQuotations()
{
    global $koneksi;
    $query = "SELECT * FROM tb_quotation WHERE status = 'processed'";
    $result = mysqli_query($koneksi, $query);
    $quotations = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $quotations[] = $row;
    }
    return $quotations;
}


?>