<?php

session_start();

if(!isset($_SESSION["sslogin"])) {
    header("location: ../auth/login.php");
    exit();
  }

require "../config/config.php";
require "../config/function.php";
require "../module/mode_user.php";

$id     = $_GET['id'];
$foto     = $_GET['foto'];

if(delete($id, $foto)) {
    echo "
            <script>
                alert('user berhasil dihapus');
                document.location.href = 'data_user.php';
            </script>
        ";
} else{
    echo "
            <script>
                alert('user gagal dihapus');
                document.location.href = 'data_user.php';
            </script>
         ";
}

?>