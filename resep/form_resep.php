<?php

session_start();

if (!isset($_SESSION["sslogin"])) {
    header("location: ../auth/login.php");
    exit();
}

require "../config/config.php";
require "../config/function.php";
require "../module/mode_resep.php";

$title = "Form Resep - Coffee Shop";
require "../partials/header.php";
require "../partials/navbar.php";
require "../partials/sidebar.php";

$id = isset($_GET['id']) ? $_GET['id'] : '';
$msg = isset($_GET['msg']) ? $_GET['msg'] : '';

if ($id) {
    $sqledit = "SELECT * FROM tb_resep WHERE id_resep = '$id'";
    $resep = getData($sqledit)[0];
} else {
    $resep = ['id_resep' => generateid(), 'id_produk' => '', 'id_bahan' => '', 'jumlah' => '', 'status' => ''];
}

$alert = '';

if (isset($_POST['simpan'])) {
    if ($id) {
        if (updateResep($_POST)) {
            $alert = '<div class="alert alert-success">Resep berhasil diperbarui.</div>';
        } else {
            $alert = '<div class="alert alert-danger">Gagal memperbarui resep.</div>';
        }
    } else {
        if (insertResep($_POST)) {
            $alert = '<div class="alert alert-success">Resep berhasil ditambahkan.</div>';
        } else {
            $alert = '<div class="alert alert-danger">Gagal menambahkan resep.</div>';
        }
    }
}


?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Resep</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= $main_url ?>dashboard.php">Home</a></li>
                        <li class="breadcrumb-item"><a href="<?= $main_url ?>resep">Resep</a></li>
                        <li class="breadcrumb-item active"><?= $id ? 'Edit Resep' : 'Add Resep' ?></li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <form action="" method="post">
                    <?php if ($alert) echo $alert; ?>
                    <div class="card-header">
                        <h3 class="card-title"><?= $id ? 'Edit Resep' : 'Input Resep' ?></h3>
                        <button type="submit" name="simpan" class="btn btn-primary float-right">Simpan</button>
                        <button type="reset" class="btn btn-danger float-right mr-2">Reset</button>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="id_resep">Kode Resep</label>
                            <input type="text" name="id_resep" class="form-control" id="id_resep" value="<?= $resep['id_resep'] ?>" readonly>
                        </div>
                        <div class="form-group">
                            <label for="id_produk">Produk *</label>
                            <select name="id_produk" id="id_produk" class="form-control" required>
                                <option value="">-- Pilih Produk --</option>
                                <?php
                                $produk = getData("SELECT * FROM tb_produk");
                                foreach ($produk as $prd) {
                                    echo '<option value="' . $prd['id_produk'] . '"' . ($resep['id_produk'] == $prd['id_produk'] ? ' selected' : '') . '>' . $prd['nm_produk'] . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="id_bahan">Bahan *</label>
                            <select name="id_bahan" id="id_bahan" class="form-control" required>
                                <option value="">-- Pilih Bahan --</option>
                                <?php
                                $bahan = getData("SELECT * FROM tb_bahan");
                                foreach ($bahan as $bhn) {
                                    echo '<option value="' . $bhn['id_bahan'] . '"' . ($resep['id_bahan'] == $bhn['id_bahan'] ? ' selected' : '') . '>' . $bhn['nm_bahan'] . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="jumlah">Jumlah *</label>
                            <input type="number" name="jumlah" class="form-control" id="jumlah" value="<?= $resep['jumlah'] ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="status">Status *</label>
                            <select name="status" id="status" class="form-control" required>
                                <option value="1" <?= $resep['status'] == '1' ? 'selected' : '' ?>>Aktif</option>
                                <option value="0" <?= $resep['status'] == '0' ? 'selected' : '' ?>>Tidak Aktif</option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>

<?php

require "../partials/footer.php";

?>
