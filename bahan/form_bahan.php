<?php

session_start();

if (!isset($_SESSION["sslogin"])) {
    header("location: ../auth/login.php");
    exit();
}

require "../config/config.php";
require "../config/function.php";
require "../module/mode_bahan.php";

$title = "Form Bahan - Cafe Qita";
require "../partials/header.php";
require "../partials/navbar.php";
require "../partials/sidebar.php";

if (isset($_GET['msg'])) {
    $msg = $_GET['msg'];
    $id = $_GET['id'];
    $sqledit = "SELECT * FROM tb_bahan WHERE id_bahan = '$id'";
    $bahan = getData($sqledit)[0];
} else {
    $msg = "";
}

$alert = '';

if (isset($_POST['simpan'])) {
    if ($msg != '') {
        if (update($_POST)) {
            echo "
                <script>document.location.href = 'index.php?msg=updated';</script>
            ";
        } else {
            echo "
                <script>document.location.href = 'index.php';</script>
            ";
        }
    } else {
        if (insert($_POST)) {
            $alert = '<div class="alert alert-success alert-dismissible">
                      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                      <h5><i class="icon fas fa-check"></i> Alert!</h5>
                      Bahan berhasil ditambahkan
                    </div>';
        }
    }
}

?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Bahan</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= $main_url ?>dashboard.php">Home</a></li>
                        <li class="breadcrumb-item"><a href="<?= $main_url ?>bahan">Bahan</a></li>
                        <li class="breadcrumb-item active"><?= $msg != '' ? 'Edit Bahan' : 'Add Bahan' ?></li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <form action="" method="post" enctype="multipart/form-data">
                    <?php
                    if ($alert != '') {
                        echo $alert;
                    }
                    ?>
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-pen text-sm mr-2"></i> <?= $msg != '' ? 'Edit Bahan' : 'Input Bahan' ?></h3>
                        <button type="submit" name="simpan" class="btn btn-primary btn-sm float-right"><i class="fas fa-save icon-sm mr-1"></i> Simpan</button>
                        <button type="reset" class="btn btn-danger btn-sm float-right mr-2"><i class="fas fa-times icon-sm mr-1"></i> Reset</button>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-8 mb-3 pr3">
                                <div class="form-group">
                                    <label for="id_bahan">Kode Bahan</label>
                                    <input type="text" name="id_bahan" class="form-control" id="id_bahan" value="<?= $msg != '' ? $bahan['id_bahan'] : generateid() ?>" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="nm_bahan">Nama Bahan *</label>
                                    <input type="text" name="nm_bahan" class="form-control" id="nm_bahan" placeholder="Nama bahan" value="<?= $msg != '' ? $bahan['nm_bahan'] : '' ?>" autocomplete="off" autofocus required>
                                </div>
                                <div class="form-group">
                                    <label for="satuan">Satuan *</label>
                                    <select name="satuan" id="satuan" class="form-control" required>
                                        <?php
                                        $satuan = ["kg", "ml", "piece"];
                                        if ($msg != '') {
                                            foreach ($satuan as $sat) {
                                                echo '<option value="' . $sat . '"' . ($bahan['satuan'] == $sat ? ' selected' : '') . '>' . $sat . '</option>';
                                            }
                                        } else { ?>
                                            <option value="">-- Satuan Bahan --</option>
                                            <option value="kg">kg</option>
                                            <option value="ml">ml</option>
                                            <option value="piece">piece</option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="harga_beli">Harga Beli *</label>
                                    <input type="number" name="harga_beli" class="form-control" id="harga_beli" placeholder="Rp 0" value="<?= $msg != '' ? $bahan['harga_beli'] : '' ?>" autocomplete="off" required>
                                </div>
                                <div class="form-group">
                                    <label for="harga_jual">Harga Jual *</label>
                                    <input type="number" name="harga_jual" class="form-control" id="harga_jual" placeholder="Rp 0" value="<?= $msg != '' ? $bahan['harga_jual'] : '' ?>" autocomplete="off" required>
                                </div>
                                <div class="form-group">
                                    <label for="stok_min">Stok Minimal *</label>
                                    <input type="number" name="stok_min" class="form-control" id="stok_min" placeholder="0" value="<?= $msg != '' ? $bahan['stok_min'] : '' ?>" autocomplete="off" required>
                                </div>
                                <div class="form-group">
                                    <label for="status">Status *</label>
                                    <select name="status" id="status" class="form-control" required>
                                        <option value="">-- Pilih Status --</option>
                                        <option value="1">Aktif</option>
                                        <option value="0">Tidak Aktif</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-4 text-center px-3">
                                <input type="hidden" name="oldImg" value="<?= $msg != '' ? $bahan['gambar'] : '' ?>">
                                <img src="<?= $main_url ?>asset/image/<?= $msg != '' ? $bahan['gambar'] : 'default.png' ?>" class="profile-user-image mb-3 mt-4" alt="">
                                <input type="file" class="form-control" name="gambar">
                                <span class="text-sm">Type file gambar JPG | PNG</span>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>

<?php

require "../partials/footer.php";

?>
