<?php

function getProducts($koneksi, $status = 'aktif') {
    $query = "SELECT * FROM tb_produk WHERE status = '$status'";
    $result = mysqli_query($koneksi, $query);
    $produk = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $produk[] = $row;
    }
    return $produk;
}

function getCustomers($koneksi) {
    $query = "SELECT * FROM tb_customer";
    $result = mysqli_query($koneksi, $query);
    $customers = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $customers[] = $row;
    }
    return $customers;
}

function insertPesanan($data) {
    global $koneksi;
    $query = "INSERT INTO tb_pesanan (id_pesanan, tanggal_pesanan, nm_customer, total, keterangan, jml_bayar, kembalian, status) 
              VALUES ('{$data['nopesan']}', '{$data['tglpesan']}', '{$data['customer']}', '{$data['total']}', 
                      '{$data['keterangan']}', '{$data['bayar']}', '{$data['kembalian']}', 'Pending')";
    return mysqli_query($koneksi, $query);
}

function updateStatusPesanan($nopesan, $status) {
    global $koneksi;
    $query = "UPDATE tb_pesanan SET status = '$status' WHERE nopesan = '$nopesan'";
    return mysqli_query($koneksi, $query);
}



function insertDetailPesanan($produk) {
    global $koneksi;
    $query = "INSERT INTO tb_detail_pesanan (id_pesanan, tanggal_pesanan, id_produk, nm_produk, jumlah, harga_jual, jml_harga)
              VALUES ('{$produk['nopesan']}', '{$produk['tglpesan']}', '{$produk['id_produk']}', '{$produk['nm_produk']}', {$produk['qty']}, {$produk['harga']}, {$produk['jml_harga']})";
    return mysqli_query($koneksi, $query);
}



function kurangiStok($id_produk, $qty) {
    global $koneksi;
    $query = "UPDATE tb_produk SET stok = stok - $qty WHERE id_produk = '$id_produk'";
    return mysqli_query($koneksi, $query);
}

function totalPesanan() {
    return array_sum(array_column($_SESSION['pesanan'], 'jml_harga'));
}

function generateno(){
    global $koneksi;

    $queryno = mysqli_query($koneksi, "SELECT max(id_pesanan) as maxno FROM tb_pesanan");
    $row = mysqli_fetch_assoc($queryno);
    $maxno = $row["maxno"];

    $nourut = (int) substr($maxno, 2, 4);
    $nourut++;
    $newno = 'PS' . sprintf("%04s", $nourut);

    return $newno;
}
