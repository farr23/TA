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

function prosespesanan($id_pesanan)
{
    global $koneksi;

    // Ambil detail pesanan
    $queryDetail = "SELECT * FROM tb_detail_pesanan WHERE id_pesanan = '$id_pesanan'";
    $resultDetail = mysqli_query($koneksi, $queryDetail);
    $orderDetails = mysqli_fetch_all($resultDetail, MYSQLI_ASSOC);

    // Buat nomor produksi baru
    $no_produksi = generateno();
    $tgl_produksi = date('Y-m-d'); // Atau ambil dari formulir

    // Masukkan data produksi
    $queryProduksi = "INSERT INTO tb_produksi (no_produksi, tgl_produksi, id_produk, total_produksi, status)
                      VALUES ('$no_produksi', '$tgl_produksi', ?, ?, 'Sedang Diproses')";
    $stmtProduksi = mysqli_prepare($koneksi, $queryProduksi);

    foreach ($orderDetails as $detail) {
        $id_produk = $detail['id_produk'];
        $jumlah = $detail['jumlah'];

        // Masukkan data produksi
        mysqli_stmt_bind_param($stmtProduksi, 'si', $id_produk, $jumlah);
        mysqli_stmt_execute($stmtProduksi);

        // Masukkan detail produksi
        $queryDetailProduksi = "INSERT INTO tb_produksi_detail (no_produksi, id_bahan, jumlah)
                                VALUES ('$no_produksi', ?, ?)";
        $stmtDetailProduksi = mysqli_prepare($koneksi, $queryDetailProduksi);

        // Ambil bahan yang dibutuhkan untuk produk
        $queryBahan = "SELECT * FROM tb_resep LEFT JOIN tb_bahan ON tb_resep.id_bahan = tb_bahan.id_bahan
                       WHERE tb_resep.id_produk = '$id_produk'";
        $bahanList = mysqli_query($koneksi, $queryBahan);
        while ($bahan = mysqli_fetch_assoc($bahanList)) {
            $id_bahan = $bahan['id_bahan'];
            $jumlah_bahan = $bahan['jumlah'] * $jumlah;
            mysqli_stmt_bind_param($stmtDetailProduksi, 'ii', $id_bahan, $jumlah_bahan);
            mysqli_stmt_execute($stmtDetailProduksi);

            // Update stok bahan
            $queryUpdateStok = "UPDATE tb_bahan SET stok = stok - '$jumlah_bahan' WHERE id_bahan = '$id_bahan'";
            mysqli_query($koneksi, $queryUpdateStok);
        }
    }

    // Update status pesanan
    $queryUpdateStatus = "UPDATE tb_pesanan SET status = 'Sedang Diproses' WHERE id_pesanan = '$id_pesanan'";
    mysqli_query($koneksi, $queryUpdateStatus);
}


?>