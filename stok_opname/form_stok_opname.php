<?php

session_start();

if (!isset($_SESSION["sslogin"])) {
    header("location: ../auth/login.php");
    exit();
}

require "../config/config.php";
require "../config/function.php";

$title = "Form Stok Opname - Cafe Qita";
require "../partials/header.php";
require "../partials/navbar.php";
require "../partials/sidebar.php";

$sql = "SELECT id_bahan, nm_bahan FROM tb_bahan";
$result = mysqli_query($koneksi, $sql);
?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Form Stok Opname</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= $main_url ?>dashboard.php">Home</a></li>
                        <li class="breadcrumb-item"><a href="<?= $main_url ?>stok_opname">Stok Opname</a></li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>

    <section>
        <div class="container-fluid mt-5">
            <form action="proses_stok_opname.php" method="POST">
                <div class="form-group">
                    <label for="tanggal">Tanggal</label>
                    <input type="date" class="form-control" id="tanggal" name="tanggal" value="<?= date('Y-m-d') ?>" required>
                </div>
                <div class="form-group">
                    <label for="id_bahan">Bahan</label>
                    <select class="form-control" id="id_bahan" name="id_bahan" required>
                    <?php
                        if (mysqli_num_rows($result) > 0) {
                            while($row = mysqli_fetch_assoc($result)) {
                                echo "<option value='".$row['id_bahan']."'>".$row['nm_bahan']."</option>";
                            }
                        }
                    ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="stok_sistem">Stok Sistem</label>
                    <input type="number" step="0.01" class="form-control" id="stok_sistem" name="stok_sistem" readonly required>
                </div>
                <div class="form-group">
                    <label for="stok_fisik">Stok Fisik</label>
                    <input type="number" step="0.01" class="form-control" id="stok_fisik" name="stok_fisik" required>
                </div>
                <div class="form-group">
                    <label for="keterangan">Keterangan</label>
                    <input type="text" class="form-control" id="keterangan" name="keterangan">
                </div>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </form>
        </div>
    </section>

    <script>
    document.getElementById('id_bahan').addEventListener('change', function() {
        var idBahan = this.value;
        fetch('get_stok_sistem.php?id_bahan=' + idBahan)
            .then(response => response.json())
            .then(data => {
                document.getElementById('stok_sistem').value = data.stok_sistem;
            });
    });
    </script>

<?php
require "../partials/footer.php";
?>
