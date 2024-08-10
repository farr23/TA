<?php

if(userlogin()['level'] == 3){
    header("location:" . $main_url . "error_page.php");
    exit();
}

function generateid(){
    global $koneksi;

    $queryid = mysqli_query($koneksi, "SELECT max(id_produk) as maxid FROM tb_produk");
    $data = mysqli_fetch_array($queryid);
    $maxid = $data['maxid'];

    // memastikan $maxid tidak kosong
    if($maxid){
        $nourut = (int) substr($maxid, 2, 3);
        $nourut++;
        $maxid = "P-" . sprintf("%03s", $nourut);
    } else {
        $maxid = "P-001";
    }
    return $maxid;
}

// simpan
function insert($data){
    global $koneksi;

    $id = mysqli_real_escape_string($koneksi, $data['id_produk']);
    $nmproduk = mysqli_real_escape_string($koneksi, $data['nm_produk']);
    $idkategori = mysqli_real_escape_string($koneksi, $data['idkategori']); // Perbaikan variabel idkategori
    $desc = mysqli_real_escape_string($koneksi, $data['deskripsi']);
    $hargajl = mysqli_real_escape_string($koneksi, $data['harga_jual']);
    $hargaml = mysqli_real_escape_string($koneksi, $data['harga_modal']);
    $sm = mysqli_real_escape_string($koneksi, $data['stok_min']);
    $gambar = mysqli_real_escape_string($koneksi, $_FILES['gambar']['name']);
    $status = mysqli_real_escape_string($koneksi, $data['status']);

    // upload gambar produk
    if ($gambar != null) {
        $gambar = uploadimg(null, $id);
    } else {
        $gambar = 'default.png';
    }

    // jika gambar tidak sesuai validasi
    if ($gambar == '') {
        return false;
    }

    $sqlproduk = "INSERT INTO tb_produk (id_produk, nm_produk, id_kategori, deskripsi, harga_jual, harga_modal, stok_min, gambar, status) VALUES ('$id', '$nmproduk', '$idkategori', '$desc', '$hargajl', '$hargaml', '$sm', '$gambar', '$status')";
    mysqli_query($koneksi, $sqlproduk);

    return mysqli_affected_rows($koneksi);
}

function delete($id, $gbr){
    global $koneksi;

    $sqldel = "DELETE FROM tb_produk WHERE id_produk = '$id'";
    mysqli_query($koneksi, $sqldel);
    if($gbr != 'default.png'){
        unlink('../asset/image/' . $gbr);
    }

    return mysqli_affected_rows($koneksi);
}

function update($data){
    global $koneksi;
    
    $id = mysqli_real_escape_string($koneksi, $data['id_produk']);
    $nmproduk = mysqli_real_escape_string($koneksi, $data['nm_produk']);
    $idkategori = mysqli_real_escape_string($koneksi, $data['idkategori']); // Perbaikan variabel idkategori
    $desc = mysqli_real_escape_string($koneksi, $data['deskripsi']);
    $hargajl = mysqli_real_escape_string($koneksi, $data['harga_jual']);
    $hargaml = mysqli_real_escape_string($koneksi, $data['harga_modal']);
    $sm = mysqli_real_escape_string($koneksi, $data['stok_min']);
    $gbrlama = mysqli_real_escape_string($koneksi, $data['oldImg']);
    $gambar = mysqli_real_escape_string($koneksi, $_FILES['gambar']['name']);
    $status = mysqli_real_escape_string($koneksi, $data['status']);

    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
        $gambar = $_FILES['gambar']['name'];
    } else {
        $gambar = '';
    }

    // gambar cek
    if ($gambar != null) {
        if($gbrlama == 'default.png'){
            $nmgbr = $id;
        } else {
            $nmgbr = $id . '-' . rand(10, 1000);
        }
        $imgprd = uploadimg(null, $id);
        if($gbrlama != 'default.png'){
            @unlink('../asset/image/'.$gbrlama);
        }
    } else {
        $imgprd = $gbrlama;
    }
    
    mysqli_query($koneksi, "UPDATE tb_produk SET
                        nm_produk = '$nmproduk',
                        id_kategori = '$idkategori',
                        deskripsi = '$desc',
                        harga_jual = '$hargajl',
                        harga_modal = '$hargaml',
                        stok_min = '$sm',
                        gambar = '$imgprd',
                        status = '$status'
                        WHERE id_produk = '$id'
                        ");

    return mysqli_affected_rows($koneksi);
}

?>
