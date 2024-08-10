<?php

if(userlogin()['level'] == 3){
    header("location:" . $main_url . "error_page.php");
    exit();
}

function insert($data){
    global $koneksi;

    $nmsp   = mysqli_real_escape_string($koneksi, $data['nm_supplier']);
    $telp   = mysqli_real_escape_string($koneksi, $data['telp']);
    $des   = mysqli_real_escape_string($koneksi, $data['deskripsi']);
    $alm   = mysqli_real_escape_string($koneksi, $data['alamat']);
    $status   = mysqli_real_escape_string($koneksi, $data['status']);

    $sqlsupplier = "INSERT INTO tb_supplier VALUES (null, '$nmsp', '$telp', '$des', '$alm', '$status')";
    mysqli_query($koneksi, $sqlsupplier);
    
    return mysqli_affected_rows($koneksi);
}

//hapus data
function delete($id){
    global $koneksi;

    $sqldelete = "DELETE FROM tb_supplier WHERE id_supplier = $id";
    mysqli_query($koneksi, $sqldelete);

    return mysqli_affected_rows($koneksi);
}

function update($data){
    global $koneksi;

    $id     = mysqli_real_escape_string($koneksi, $data['id']);
    $nmsp   = mysqli_real_escape_string($koneksi, $data['nm_supplier']);
    $telp   = mysqli_real_escape_string($koneksi, $data['telp']);
    $des    = mysqli_real_escape_string($koneksi, $data['deskripsi']);
    $alm    = mysqli_real_escape_string($koneksi, $data['alamat']);
    $status   = mysqli_real_escape_string($koneksi, $data['status']);

    $sqlsupplier = "UPDATE tb_supplier SET
                    nm_supplier     = '$nmsp',
                    telp            = '$telp',
                    deskripsi       = '$des',
                    alamat          = '$alm',
                    status          = '$status'
                    WHERE id_supplier = $id
                    ";
    mysqli_query($koneksi, $sqlsupplier);
    
    return mysqli_affected_rows($koneksi);
}
?>