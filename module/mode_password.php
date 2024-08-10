<?php

function update($data){
    global $koneksi;

    // buang spasi kanan kiri (trim)
    $curpass    = trim(mysqli_real_escape_string($koneksi, $_POST['curpass']));
    $newpass    = trim(mysqli_real_escape_string($koneksi, $_POST['newpass']));
    $confpass   = trim(mysqli_real_escape_string($koneksi, $_POST['confpass']));
    $useraktif  = userlogin()['username'];

    if($newpass != $confpass){
        echo "<script>
            alert('Password gagal diperbarui');
            document.location= '?msg=err1'; 
        </script>";
        return false;
    }

    //cek password sekarang sama dengan database atau tidak
    if(!password_verify($curpass, userlogin()['password'])){
        echo "<script>
            alert('Password gagal diperbarui');
            document.location= '?msg=err2'; 
        </script>";
        return false;
    } else {
        $pass   = password_hash($newpass, PASSWORD_DEFAULT);
        //gae update password
        mysqli_query($koneksi, "UPDATE tb_user SET password = '$pass' WHERE username = '$useraktif'");
        return mysqli_affected_rows($koneksi);
    }
}

?>