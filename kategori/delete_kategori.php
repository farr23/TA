<?php

session_start();

if(!isset($_SESSION["sslogin"])) {
    header("location: ../auth/login.php");
    exit();
  }

require "../config/config.php";
require "../config/function.php";
require "../module/mode_kategori.php";

$id = $_GET['id'];

if(delete($id)){
    echo "
            <script>document.location.href = 'data_kategori.php?msg=deleted'; </script>
    ";
} else {
    echo "
            <script>document.location.href = 'data_kategori.php?msg=aborted'; </script>
    ";
}