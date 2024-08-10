<?php

function generateno() {
    global $koneksi;

    $queryno = mysqli_query($koneksi, "SELECT max(no_transjual) as maxno FROM trans_penjualan");
    $row = mysqli_fetch_assoc($queryno);
    $maxno = $row["maxno"];

    $nourut = (int) substr($maxno, 2, 4);
    $nourut++;
    $newno = 'PJ' . sprintf("%04s", $nourut);

    return $newno;
}

// Fungsi untuk mengurangi stok produk di tabel tb_produksi
function kurangiStok($id_produk, $qty) {
    global $koneksi;
    // Mengambil total stok dari tabel tb_produksi
    $produksi = mysqli_query($koneksi, "SELECT * FROM tb_produksi WHERE id_produk = '$id_produk' ORDER BY tgl_produksi ASC");
    while ($row = mysqli_fetch_assoc($produksi)) {
        if ($qty <= $row['total_produksi']) {
            // Kurangi stok pada catatan produksi saat ini
            $new_qty = $row['total_produksi'] - $qty;
            mysqli_query($koneksi, "UPDATE tb_produksi SET total_produksi = '$new_qty' WHERE id_produksi = '{$row['id_produksi']}'");
            break;
        } else {
            // Kurangi stok pada catatan produksi saat ini dan lanjutkan ke catatan produksi berikutnya
            $qty -= $row['total_produksi'];
            mysqli_query($koneksi, "UPDATE tb_produksi SET total_produksi = 0 WHERE id_produksi = '{$row['id_produksi']}'");
        }
    }
}

function totalJual($nojual) {
    global $koneksi;

    $totaljual = mysqli_query($koneksi, "SELECT sum(jml_harga) AS total FROM trans_penjudetail WHERE no_transjual = '$nojual'");
    $data = mysqli_fetch_assoc($totaljual);
    return $data["total"];
}

// Fungsi untuk memasukkan data penjualan detail
function insertDetail($data) {
    global $koneksi;

    $nojual = htmlspecialchars($data["nojual"]);
    $tglnota = htmlspecialchars($data["tglnota"]);
    $id_produk = htmlspecialchars($data["kodeproduk"]);
    $nm_produk = htmlspecialchars($data["nmproduk"]);
    $harga_jual = htmlspecialchars($data["harga"]);
    $qty = htmlspecialchars($data["qty"]); // Perbaikan variabel qty
    $jml_harga = htmlspecialchars($data["jmlharga"]);
    $stok = htmlspecialchars($data["stok"]);

    // Check if the product already exists
    $cekpdk = mysqli_query($koneksi, "SELECT * FROM trans_penjudetail WHERE no_transjual = '$nojual' AND id_produk = '$id_produk'");
    if(mysqli_num_rows($cekpdk) > 0) {
        echo "<script>alert('Produk sudah ada');</script>"; 
        return false;
    }

    // Check if qty is not empty and greater than 0
    if(empty($qty) || $qty < 1) {
        echo "<script>alert('Qty produk tidak boleh kosong atau kurang dari 1');</script>"; 
        return false;
    } else if ($qty > $stok) {
        echo "<script>alert('Stok produk tidak mencukupi');</script>"; 
        return false;
    } else {
        $sqljual = "INSERT INTO trans_penjudetail VALUES(null, '$nojual', '$tglnota', '$id_produk', '$nm_produk', '$harga_jual', '$qty', '$jml_harga')";
        mysqli_query($koneksi, $sqljual);
    }

    // Update stock
    mysqli_query($koneksi, "UPDATE tb_produk SET stok = stok - $qty WHERE id_produk = '$id_produk'");

    return mysqli_affected_rows($koneksi);
}

// Fungsi untuk memasukkan data penjualan utama
function insertPenjualan($penjualan) {
    global $koneksi;

    $nojual = htmlspecialchars($penjualan["nojual"]);
    $tglnota = htmlspecialchars($penjualan["tglnota"]);
    $customer = htmlspecialchars($penjualan["customer"]);
    $total = htmlspecialchars($penjualan["total"]);
    $keterangan = htmlspecialchars($penjualan["keterangan"]);
    $bayar = htmlspecialchars($penjualan["bayar"]);
    $kembalian = htmlspecialchars($penjualan["kembalian"]);

    $sql = "INSERT INTO trans_penjualan (no_transjual, tgl_transjual, nm_customer, total, keterangan, jml_bayar, kembalian) 
            VALUES ('$nojual', '$tglnota', '$customer', '$total', '$keterangan', '$bayar', '$kembalian')";

    mysqli_query($koneksi, $sql);

    return mysqli_affected_rows($koneksi);
}

function insertQuotation($quotation) {
    global $koneksi;
    $query = "INSERT INTO tb_quotation (no_quotation, tgl_quotation, nm_customer, total, keterangan) VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($koneksi, $query);
    mysqli_stmt_bind_param($stmt, 'sssis', $quotation['noquotation'], $quotation['tglnota'], $quotation['customer'], $quotation['total'], $quotation['keterangan']);
    return mysqli_stmt_execute($stmt);
}

function insertQuotationDetail($detail) {
    global $koneksi;
    $query = "INSERT INTO tb_quotation_detail (id_quotation, id_produk, nm_produk, qty, harga, jml_harga) VALUES ((SELECT id_quotation FROM tb_quotation WHERE no_quotation = ?), ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($koneksi, $query);
    mysqli_stmt_bind_param($stmt, 'sisidd', $detail['noquotation'], $detail['id_produk'], $detail['nm_produk'], $detail['qty'], $detail['harga'], $detail['jml_harga']);
    return mysqli_stmt_execute($stmt);
}

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


?>
