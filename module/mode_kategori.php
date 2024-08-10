<?php

if(userlogin()['level'] == 3){
    header("location:" . $main_url . "error_page.php");
    exit();
}

function insert($data){
    global $koneksi;

    $nmkt   = mysqli_real_escape_string($koneksi, $data['nm_kategori']);
    $status   = mysqli_real_escape_string($koneksi, $data['status']);

    $sqlkategori = "INSERT INTO tb_kategori VALUES (null, '$nmkt', '$status')";
    mysqli_query($koneksi, $sqlkategori);
    
    return mysqli_affected_rows($koneksi);
}

//hapus data
function delete($id){
    global $koneksi;

    $sqldelete = "DELETE FROM tb_kategori WHERE id_kategori = $id";
    mysqli_query($koneksi, $sqldelete);

    return mysqli_affected_rows($koneksi);
}

function update($data){
    global $koneksi;

    $id     = mysqli_real_escape_string($koneksi, $data['id']);
    $nmkt   = mysqli_real_escape_string($koneksi, $data['nm_kategori']);
    $status   = mysqli_real_escape_string($koneksi, $data['status']);

    $sqlkategori = "UPDATE tb_kategori SET
                    nm_kategori     = '$nmkt',
                    status     = '$status'
                    WHERE id_kategori = $id
                    ";
    mysqli_query($koneksi, $sqlkategori);
    
    return mysqli_affected_rows($koneksi);
}
?>