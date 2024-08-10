<?php

if(userlogin()['level'] != 1 ) {
    header("location:" . $main_url . "error_page.php");
    exit();
}

function insert($data){
    global $koneksi;

    $username   = strtolower(mysqli_real_escape_string($koneksi, $data ['username']));
    $fullname   = mysqli_real_escape_string($koneksi, $data ['fullname']);
    $password   = mysqli_real_escape_string($koneksi, $data ['password']);
    $password2   = mysqli_real_escape_string($koneksi, $data ['password2']);
    $level      = mysqli_real_escape_string($koneksi, $data ['level']);
    $alamat      = mysqli_real_escape_string($koneksi, $data ['alamat']);
    $gambar      = mysqli_real_escape_string($koneksi, $_FILES ['image']['name']);

    if ($password !== $password2) {
        echo "<script>
                alert('konfirmasi password tidak sesuai');
            </script>";
        return false;
    }

    $pass   = password_hash($password, PASSWORD_DEFAULT);

    $cekusername    = mysqli_query($koneksi, "SELECT username FROM tb_user WHERE username = '$username'");
    if (mysqli_num_rows($cekusername) > 0) {
        echo "<script>
                alert('username telah terpakai');
            </script>";
        return false;
    }

    if ($gambar != null) {
        $gambar = uploadimg();
    } else {
        $gambar = 'default.png';
    }

    //jika gambar tidak sesuai validasi
    if ($gambar == '') {
        return false;
    }

    $sqluser    = "INSERT INTO tb_user VALUE (null, '$username', '$fullname', '$pass', '$alamat', '$level', '$gambar')";
    mysqli_query($koneksi, $sqluser);

    return mysqli_affected_rows($koneksi);
}

//hapus data
function delete($id, $foto){
    global $koneksi;

    $sqldel = "DELETE FROM tb_user WHERE user_id = $id";
    mysqli_query($koneksi, $sqldel);
    if ($foto != 'default.png') {
        unlink('../asset/image/' . $foto);
    }

    return  mysqli_affected_rows($koneksi);
}

//pilih level
function selectUser1($level){
    $result = null;
    if ($level == 1) {
        $result = "selected";
    }
    return $result;
}

function selectUser2($level){
    $result = null;
    if ($level == 2) {
        $result = "selected";
    }
    return $result;
}

function selectUser3($level){
    $result = null;
    if ($level == 3) {
        $result = "selected";
    }
    return $result;
}

//update data
function update($data){
    global $koneksi;

    $iduser     = mysqli_real_escape_string($koneksi, $data ['id']);
    $username   = strtolower(mysqli_real_escape_string($koneksi, $data ['username']));
    $fullname   = mysqli_real_escape_string($koneksi, $data ['fullname']);
    $level      = mysqli_real_escape_string($koneksi, $data ['level']);
    $alamat      = mysqli_real_escape_string($koneksi, $data ['alamat']);
    $gambar      = mysqli_real_escape_string($koneksi, $_FILES ['image']['name']);
    $fotolama   = mysqli_real_escape_string($koneksi, $data ['oldimg']);

    //cek username saiki
    $queryusername  = mysqli_query($koneksi, "SELECT * FROM tb_user WHERE user_id = $iduser");
    $datausername   = mysqli_fetch_assoc($queryusername);
    //untuk simpan username
    $curusername   = $datausername['username'];

    //cek username anyar
    $newusername    = mysqli_query($koneksi, "SELECT username FROM tb_user WHERE username = '$username'");

    //validasi
    if ($username != $curusername){
        if(mysqli_num_rows($newusername)){
            echo "<script>
                alert('username telah terpakai, update gagal');
                document.location.href = 'data_user.php';
            </script>";
        return false;
        }
    }

    //cek gambar
    if ($gambar != null){
        $url        = "data_user.php";
        $imguser    = uploadimg($url);
        if($fotolama != 'default.png'){
            @unlink('../asset/image' . $fotolama);
        } 
    } else {
        $imguser = $fotolama;
    }

    mysqli_query($koneksi, "UPDATE tb_user SET
                            username    = '$username', 
                            fullname    = '$fullname', 
                            alamat      = '$alamat', 
                            level       = '$level', 
                            foto        = '$imguser'
                            WHERE user_id = $iduser
                            ");

    return mysqli_affected_rows($koneksi);
}
?>