<?php

date_default_timezone_set('Asia/Jakarta');

$host='localhost';
$user='root';
$pass='';
$db='db_cqmalang';

$koneksi = mysqli_connect($host, $user, $pass, $db);

// if (mysqli_connect_errno()) {
//     echo "gagal";
//     exit;
// } else {
//     echo "berhasil";
// }


$main_url = 'http://localhost/cqmalang/';
?>