<?php

require "../config/config.php"; 

function insertResep($data) {
    global $koneksi; // Pastikan $koneksi adalah koneksi database
    $id_resep = mysqli_real_escape_string($koneksi, $data['id_resep']);
    $id_produk = mysqli_real_escape_string($koneksi, $data['id_produk']);
    $id_bahan = mysqli_real_escape_string($koneksi, $data['id_bahan']);
    $jumlah = mysqli_real_escape_string($koneksi, $data['jumlah']);
    $status = mysqli_real_escape_string($koneksi, $data['status']);
    
    $query = "INSERT INTO tb_resep (id_resep, id_produk, id_bahan, jumlah, status) 
              VALUES ('$id_resep', '$id_produk', '$id_bahan', '$jumlah', '$status')";
    return mysqli_query($koneksi, $query);
}

function updateResep($data) {
    global $koneksi; // Pastikan $koneksi adalah koneksi database
    $id_resep = mysqli_real_escape_string($koneksi, $data['id_resep']);
    $id_produk = mysqli_real_escape_string($koneksi, $data['id_produk']);
    $id_bahan = mysqli_real_escape_string($koneksi, $data['id_bahan']);
    $jumlah = mysqli_real_escape_string($koneksi, $data['jumlah']);
    $status = mysqli_real_escape_string($koneksi, $data['status']);
    
    $query = "UPDATE tb_resep SET 
              id_produk = '$id_produk', 
              id_bahan = '$id_bahan', 
              jumlah = '$jumlah', 
              status = '$status' 
              WHERE id_resep = '$id_resep'";
    return mysqli_query($koneksi, $query);
}

function deleteResep($id_resep) {
    global $koneksi; // Pastikan $koneksi adalah koneksi database
    $id_resep = mysqli_real_escape_string($koneksi, $id_resep);
    $query = "DELETE FROM tb_resep WHERE id_resep = '$id_resep'";
    return mysqli_query($koneksi, $query);
}

// Fungsi tambahan untuk generate ID
function generateid() {
    global $koneksi; // Pastikan $koneksi adalah koneksi database
    $query = "SELECT MAX(id_resep) as max_id FROM tb_resep";
    $result = mysqli_query($koneksi, $query);
    $row = mysqli_fetch_assoc($result);
    $max_id = $row['max_id'];
    $new_id = sprintf("RS%04d", intval(substr($max_id, 2)) + 1);
    return $new_id;
}
