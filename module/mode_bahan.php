<?php

if (userlogin()['level'] == 3) {
    header("location:" . $main_url . "error_page.php");
    exit();
}

function generateid(){
    global $koneksi;

    $queryid = mysqli_query($koneksi, "SELECT max(id_bahan) as maxid FROM tb_bahan");
    $data = mysqli_fetch_array($queryid);
    $maxid = $data['maxid'];

    //memastikan $maxid tidak kosong
    if ($maxid) {
        $nourut = (int) substr($maxid, 2, 3);
        $nourut++;
        $maxid = "B-" . sprintf("%03s", $nourut);
    } else {
        $maxid = "B-001";
    }
    return $maxid;
}

//simpan
function insert($data){
    global $koneksi;

    $id = mysqli_real_escape_string($koneksi, $data['id_bahan']);
    $nmbahan = mysqli_real_escape_string($koneksi, $data['nm_bahan']);
    $hargabl = mysqli_real_escape_string($koneksi, $data['harga_beli']);
    $hargajl = mysqli_real_escape_string($koneksi, $data['harga_jual']);
    $satuan = mysqli_real_escape_string($koneksi, $data['satuan']);
    $sm = mysqli_real_escape_string($koneksi, $data['stok_min']);
    $gambar = mysqli_real_escape_string($koneksi, $_FILES['gambar']['name']);
    $status = mysqli_real_escape_string($koneksi, $data['status']);

    //upload gambar bahan
    if ($gambar != null) {
        $gambar = uploadimg(null, $id);
    } else {
        $gambar = 'default.png';
    }

    //jika gambar tidak sesuai validasi
    if ($gambar == '') {
        return false;
    }

    $sqlbahan = "INSERT INTO tb_bahan (id_bahan, nm_bahan, harga_beli, harga_jual, stok, satuan, stok_min, gambar, status) 
                 VALUES ('$id', '$nmbahan', '$hargabl', '$hargajl', 0, '$satuan', '$sm', '$gambar', '$status')";
    mysqli_query($koneksi, $sqlbahan);

    return mysqli_affected_rows($koneksi);
}

function delete($id, $gbr){
    global $koneksi;

    $sqldel = "DELETE FROM tb_bahan WHERE id_bahan = '$id'";
    mysqli_query($koneksi, $sqldel);
    if ($gbr != 'default.png') {
        unlink('../asset/image/' . $gbr);
    }

    return mysqli_affected_rows($koneksi);
}

function update($data){
    global $koneksi;
    
    $id = mysqli_real_escape_string($koneksi, $data['id_bahan']);
    $nmbahan = mysqli_real_escape_string($koneksi, $data['nm_bahan']);
    $satuan = mysqli_real_escape_string($koneksi, $data['satuan']);
    $hargabl = mysqli_real_escape_string($koneksi, $data['harga_beli']);
    $hargajl = mysqli_real_escape_string($koneksi, $data['harga_jual']);
    $sm = mysqli_real_escape_string($koneksi, $data['stok_min']);
    $gbrlama = mysqli_real_escape_string($koneksi, $data['oldImg']);
    $gambar = mysqli_real_escape_string($koneksi, $_FILES['gambar']['name']);
    $status = mysqli_real_escape_string($koneksi, $data['status']);

    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
        $gambar = $_FILES['gambar']['name'];
    } else {
        $gambar = '';
    }

    //gambar cek
    if ($gambar != null) {
        if ($gbrlama == 'default.png') {
            $nmgbr = $id;
        } else {
            $nmgbr = $id . '-' . rand(10, 1000);
        }
        $imgbhn = uploadimg(null, $id);
        if ($gbrlama != 'default.png') {
            @unlink('../asset/image/' . $gbrlama);
        }
    } else {
        $imgbhn = $gbrlama;
    }

    mysqli_query($koneksi, "UPDATE tb_bahan SET
                        nm_bahan = '$nmbahan',
                        harga_beli = '$hargabl',
                        harga_jual = '$hargajl',
                        satuan = '$satuan',
                        stok_min = '$sm',
                        gambar = '$imgbhn',
                        status = '$status'
                        WHERE id_bahan = '$id'");

    return mysqli_affected_rows($koneksi);
}
