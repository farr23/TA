<?php

session_start();

if(!isset($_SESSION["sslogin"])) {
    header("location: ../auth/login.php");
    exit();
  }

require "../config/config.php";
require "../config/function.php";
require "../module/mode_customer.php";

$id = $_GET['id'];

if(delete($id)){
    echo "
            <script>document.location.href = 'data_customer.php?msg=deleted'; </script>
    ";
} else {
    echo "
            <script>document.location.href = 'data_customer.php?msg=aborted'; </script>
    ";
}