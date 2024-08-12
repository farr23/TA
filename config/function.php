<?php

function generateNomorNota(){
    $code = "PJ";
    $dateSekarang = date('Ymd');
    $randomInt = rand(100,999);
    $nomorNota = $code . $dateSekarang . $randomInt;
    return $nomorNota;
}

function uploadimg($url = null, $name = null){
    $namafile   = $_FILES['gambar']['name'];
    $ukuran     = $_FILES['gambar']['size'];
    // temporary
    $tmp        = $_FILES['gambar']['tmp_name'];

    // validasi gambar yang bole diupload
    $ekstensigambarvalid    = ['jpg', 'jpeg', 'png'];
    $ekstensigambar         = explode('.', $namafile);
    $ekstensigambar         = strtolower(end($ekstensigambar));
    if (!in_array($ekstensigambar, $ekstensigambarvalid)){
        if($url != null){
            echo '<script>
                alert("file bukan gambar, gagal update");
                document.location.href = "'. $url . '";
            </script>';
            die();
        return false;    
        } else {
            echo '<script>
            alert("file bukan gambar, tidak dapat ditambahkan!!");
            </script>';
            return false;
        }
    }


    //validasi ukuran maks 2 mb
    if ($ukuran > 2000000) {
        if($url != null){
            echo '<script>
                alert("Ukuran tidak sesuai, melebihi max");
                document.location.href = "'. $url . '";
            </script>';
            die();
        return false;    
        } else {
        echo '<script>
                alert("ukuran gambar tidak boleh melebihi 2 MB!!");
            </script>';
        return false;
    }
}

    if($name != null){

    } else {
        $namafilebaru   = $name . '-' . $ekstensigambar;
    }
    $namafilebaru   = rand(10, 1000) . '-' . $namafile;
    move_uploaded_file($tmp, '../asset/image/' . $namafilebaru);
    return $namafilebaru;
}

function getData($sql){
    global $koneksi;

    $result = mysqli_query($koneksi, $sql);
    $rows   = [];
    //looping
    while ($row = mysqli_fetch_assoc($result)){
        $rows[] = $row;
    }
    return $rows;
}

//cek login ke sistem level apa
function userlogin(){
    $useraktif = $_SESSION["ssuser"];
    $datauser  = getData("SELECT * FROM tb_user WHERE username = '$useraktif'")[0];
    return $datauser;
}

//gae pilih menu
function usermenu(){
    $uri_path   = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $uri_segmen = explode('/', $uri_path);
    $menu       = $uri_segmen[2];
    // seng dashboar iku masuk segmen 
    return $menu;
}

//lek user bukak halaman dashboar menu e kesorot
function menuhome(){
    if(usermenu() == 'dashboard.php') {
        $result = 'active';
        // iku class e admin lte
    } else {
        $result = null;
    }
    return $result;
}

//buka menu 
function menusetting(){
    if(usermenu() == 'user'){
        $result = 'menu-is-opening menu-open';
        //class e tek lte neh
    } else {
        $result = null;
    }
    return $result;
}

function menuuser(){
    if(usermenu() == 'user') {
        $result = 'active';
        // iku class e admin lte
    } else {
        $result = null;
    }
    return $result;
}

function menusupplier(){
    if(usermenu() == 'supplier') {
        $result = 'active';
        // iku class e admin lte
    } else {
        $result = null;
    }
    return $result;
}

function menukategori(){
    if(usermenu() == 'kategori') {
        $result = 'active';
        // this is a class for the admin lte
    } else {
        $result = null;
    }
    return $result;
}

function menuresep(){
    if(usermenu() == 'resep') {
        $result = 'active';
        // this is a class for the admin lte
    } else {
        $result = null;
    }
    return $result;
}

function menumaster(){
    if(usermenu() == 'supplier' or usermenu() == 'customer' or usermenu() == 'produk' or usermenu() == 'bahan' or usermenu() == 'kategori' or usermenu() == 'resep' ){
        $result = 'menu-is-opening menu-open';
        //class e tek lte neh
    } else {
        $result = null;
    }
    return $result;
}

function menucustomer(){
    if(usermenu() == 'customer') {
        $result = 'active';
        // this is a class for the admin lte
    } else {
        $result = null;
    }
    return $result;
}

function menubahan(){
    if(usermenu() == 'bahan') {
        $result = 'active';
        // this is a class for the admin lte
    } else {
        $result = null;
    }
    return $result;
}

function menuproduk(){
    if(usermenu() == 'produk') {
        $result = 'active';
        // this is a class for the admin lte
    } else {
        $result = null;
    }
    return $result;
}

function in_date($tgl) {
    $tg = substr($tgl, 8, 2);
    $bln = substr($tgl, 5, 2);
    $thn = substr($tgl, 0, 4);

    return $tg . "-" . $bln . "-" . $thn;
}

function menuggudang(){
    if(usermenu() == 'gproduk' or usermenu() == 'gbahan'){
        $result = 'menu-is-opening menu-open';
        //class e tek lte neh
    } else {
        $result = null;
    }
    return $result;
}

function menugproduk(){
    if(usermenu() == 'gproduk') {
        $result = 'active';
        // this is a class for the admin lte
    } else {
        $result = null;
    }
    return $result;
}

function menugbahan(){
    if(usermenu() == 'gbahan') {
        $result = 'active';
        // this is a class for the admin lte
    } else {
        $result = null;
    }
    return $result;
}

function menupenggunaan(){
    if(usermenu() == 'bmasuk' or usermenu() == 'bkeluar'){
        $result = 'menu-is-opening menu-open';
        //class e tek lte neh
    } else {
        $result = null;
    }
    return $result;
}

function menubmasuk(){
    if(usermenu() == 'bmasuk') {
        $result = 'active';
        // this is a class for the admin lte
    } else {
        $result = null;
    }
    return $result;
}

function menubkeluar(){
    if(usermenu() == 'bkeluar') {
        $result = 'active';
        // this is a class for the admin lte
    } else {
        $result = null;
    }
    return $result;
}

function menupenjualan(){
    if(usermenu() == 'pesan' or usermenu() == 'jual'){
        $result = 'menu-is-opening menu-open';
        //class e tek lte neh
    } else {
        $result = null;
    }
    return $result;
}

function menupemesanan(){
    if(usermenu() == 'pesan') {
        $result = 'active';
        // this is a class for the admin lte
    } else {
        $result = null;
    }
    return $result;
}

function menujual(){
    if(usermenu() == 'jual') {
        $result = 'active';
        // this is a class for the admin lte
    } else {
        $result = null;
    }
    return $result;
}

function menulstok(){
    if(usermenu() == 'lmasuk' or usermenu() == 'lkeluar'){
        $result = 'menu-is-opening menu-open';
        //class e tek lte neh
    } else {
        $result = null;
    }
    return $result;
}

function menulmasuk(){
    if(usermenu() == 'lmasuk') {
        $result = 'active';
        // this is a class for the admin lte
    } else {
        $result = null;
    }
    return $result;
}

function menulkeluar(){
    if(usermenu() == 'lkeluar') {
        $result = 'active';
        // this is a class for the admin lte
    } else {
        $result = null;
    }
    return $result;
}

?>