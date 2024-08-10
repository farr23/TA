<?php

// Pastikan Anda sudah membuat koneksi ke database dan menyimpannya dalam variabel $koneksi

function generateno(){
    global $koneksi;

    $queryno = mysqli_query($koneksi, "SELECT max(no_transbeli) as maxno FROM trans_pembelian");
    $row = mysqli_fetch_assoc($queryno);
    $maxno = $row["maxno"];

    $nourut = (int) substr($maxno, 2, 4);
    $nourut++;
    $newno = 'BL' . sprintf("%04s", $nourut);

    return $newno;
}

function totalbeli($notransbeli){
    global $koneksi;

    $totalbeli = mysqli_query($koneksi, "SELECT sum(jml_harga) AS total FROM trans_pemdetail WHERE no_transbeli = '$notransbeli'");
    $data = mysqli_fetch_assoc($totalbeli);
    $total = $data["total"];

    return $total;
}

function insertTransaksiDetail($data) {
    global $koneksi;

    $no         = mysqli_real_escape_string($koneksi, $data['no_transbeli']);
    $tgl        = mysqli_real_escape_string($koneksi, $data['tglnota']);
    $kode       = mysqli_real_escape_string($koneksi, $data['idbahan']);
    $nmbhn      = mysqli_real_escape_string($koneksi, $data['nmbahan']);
    $qty        = mysqli_real_escape_string($koneksi, $data['qty']);
    $harga      = mysqli_real_escape_string($koneksi, $data['harga']);
    $jmlharga   = mysqli_real_escape_string($koneksi, $data['jmlharga']);

    // Check if the ingredient already exists in trans_pemdetail
    $cekbhn = mysqli_query($koneksi, "SELECT * FROM trans_pemdetail WHERE no_transbeli = '$no' AND id_bahan = '$kode'");
    if(mysqli_num_rows($cekbhn)){
        echo "<script>alert('Bahan sudah ada');</script>";
        return false;
    }

    // Check if qty is not empty and greater than 0
    if(empty($qty) || $qty < 1){
        echo "<script>alert('Qty bahan tidak boleh kosong atau kurang dari 1');</script>";
        return false;
    } else {
        $sqlbeli = "INSERT INTO trans_pemdetail VALUES(null, '$no', '$tgl', '$kode', '$nmbhn', '$qty', '$harga', '$jmlharga')";
        if (!mysqli_query($koneksi, $sqlbeli)) {
            echo "Error: " . mysqli_error($koneksi);
            return false;
        }
    }

    return true;
}

function updateStokBahan($kode, $qty) {
    global $koneksi;

    $updateBahan = "UPDATE tb_bahan SET stok = stok + $qty WHERE id_bahan = '$kode'";
    if (!mysqli_query($koneksi, $updateBahan)) {
        echo "Error: " . mysqli_error($koneksi);
        return false;
    }

    return true;
}

function updateStokGudang($kode, $qty) {
    global $koneksi;

    $cekgudang = mysqli_query($koneksi, "SELECT * FROM riwayat_kartu_stok_bahan WHERE id_bahan = '$kode'");
    if(mysqli_num_rows($cekgudang)){
        // Update stock in tb_gudang if it exists
        $updateGudang = "UPDATE riwayat_kartu_stok_bahan SET stok = stok + $qty WHERE id_bahan = '$kode'";
    } else {
        // Insert new item into tb_gudang if it doesn't exist
        $updateGudang = "INSERT INTO riwayat_kartu_stok_bahan (id_riwayat, id_bahan, stok) VALUES (null, '$kode', '$qty')";
    }

    if (!mysqli_query($koneksi, $updateGudang)) {
        echo "Error: " . mysqli_error($koneksi);
        return false;
    }

    return true;
}

function insert($data) {
    if (insertTransaksiDetail($data) && updateStokBahan($data['idbahan'], $data['qty']) && updateStokGudang($data['idbahan'], $data['qty'])) {
        return true;
    }

    return false;
}

$qty = isset($data['qty']) ? intval($data['qty']) : 0;

    // Periksa jika qty kosong atau tidak valid
    if ($qty <= 0) {
        return false;
    }

function delete($idbhn, $idbeli, $qty){
    global $koneksi;

    $sqldel = "DELETE FROM trans_pemdetail WHERE id_bahan = '$idbhn' AND no_transbeli = '$idbeli'";
    if (!mysqli_query($koneksi, $sqldel)) {
        echo "Error: " . mysqli_error($koneksi);
        return false;
    }

    $updateBahan = "UPDATE tb_bahan SET stok = stok - $qty WHERE id_bahan = '$idbhn'";
    if (!mysqli_query($koneksi, $updateBahan)) {
        echo "Error: " . mysqli_error($koneksi);
        return false;
    }

    return true;
}

function simpan($data){
    global $koneksi;

    $notransbeli = mysqli_real_escape_string($koneksi, $data['no_transbeli']);
    $tgl = mysqli_real_escape_string($koneksi, $data['tglnota']);
    $total = mysqli_real_escape_string($koneksi, $data['total']);
    $supplier = mysqli_real_escape_string($koneksi, $data['supplier']);
    $keterangan = mysqli_real_escape_string($koneksi, $data['keterangan']);

    $sqlbeli = "INSERT INTO trans_pembelian VALUES ('$notransbeli', '$tgl', '$supplier', '$total', '$keterangan')";
    if (!mysqli_query($koneksi, $sqlbeli)) {
        echo "Error: " . mysqli_error($koneksi);
        return false;
    }

    return true;
}

?>
