<?php

if(userlogin()['level'] == 3){
    header("location:" . $main_url . "error_page.php");
    exit();
}

function insert($data){
    global $koneksi;

    $nmcs   = mysqli_real_escape_string($koneksi, $data['nm_customer']);
    $telp   = mysqli_real_escape_string($koneksi, $data['telp']);
    $alm   = mysqli_real_escape_string($koneksi, $data['alamat']);
    $status   = mysqli_real_escape_string($koneksi, $data['status']);

    $sqlcustomer = "INSERT INTO tb_customer VALUES (null, '$nmcs', '$telp', '$alm', '$status')";
    mysqli_query($koneksi, $sqlcustomer);
    
    return mysqli_affected_rows($koneksi);
}

//hapus data
function delete($id){
    global $koneksi;

    $sqldelete = "DELETE FROM tb_customer WHERE id_customer = $id";
    mysqli_query($koneksi, $sqldelete);

    return mysqli_affected_rows($koneksi);
}

function update($data){
    global $koneksi;

    $id     = mysqli_real_escape_string($koneksi, $data['id']);
    $nmcs   = mysqli_real_escape_string($koneksi, $data['nm_customer']);
    $telp   = mysqli_real_escape_string($koneksi, $data['telp']);
    $alm    = mysqli_real_escape_string($koneksi, $data['alamat']);
    $status    = mysqli_real_escape_string($koneksi, $data['status']);

    $sqlcustomer = "UPDATE tb_customer SET
                    nm_customer     = '$nmcs',
                    telp            = '$telp',
                    alamat          = '$alm',
                    status          = '$status'
                    WHERE id_customer = $id
                    ";
    mysqli_query($koneksi, $sqlcustomer);
    
    return mysqli_affected_rows($koneksi);
}
?>