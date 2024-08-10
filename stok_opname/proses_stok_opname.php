<?php
require "../config/config.php";

// Mengambil data dari form
$tanggal = mysqli_real_escape_string($koneksi, $_POST['tanggal']);
$id_bahan = mysqli_real_escape_string($koneksi, $_POST['id_bahan']);
$stok_sistem = mysqli_real_escape_string($koneksi, $_POST['stok_sistem']);
$stok_fisik = mysqli_real_escape_string($koneksi, $_POST['stok_fisik']);
$selisih = $stok_fisik - $stok_sistem;
$keterangan = mysqli_real_escape_string($koneksi, $_POST['keterangan']);

// Pengecekan apakah sudah ada entri untuk bahan ini pada tanggal yang sama
$check_sql = "SELECT * FROM tb_stok_opname WHERE tgl_opname = '$tanggal' AND id_bahan = '$id_bahan'";
$check_result = mysqli_query($koneksi, $check_sql);

if (mysqli_num_rows($check_result) > 0) {
    echo "<script>
            alert('Data stok opname untuk bahan ini pada tanggal tersebut sudah ada');
            window.location.href = '../stok_opname/index.php';
          </script>";
} else {
    // Query untuk menyimpan data ke tabel tb_stok_opname
    $sql = "INSERT INTO tb_stok_opname (tgl_opname, id_bahan, stok_sistem, stok_fisik, selisih, keterangan) 
            VALUES ('$tanggal', '$id_bahan', '$stok_sistem', '$stok_fisik', '$selisih', '$keterangan')";

    if (mysqli_query($koneksi, $sql)) {
        echo "<script>
                alert('Stok opname berhasil disimpan');
                window.location.href = '../stok_opname/index.php';
              </script>";
    } else {
        echo "<script>
                alert('Error: " . mysqli_error($koneksi) . "');
                window.location.href = '../stok_opname/index.php';
              </script>";
    }
}

// Menutup koneksi database
mysqli_close($koneksi);
?>
