<?php

session_start();

if (!isset($_SESSION["sslogin"])) {
    header("location: ../auth/login.php");
    exit();
}

require "../config/config.php";
require "../config/function.php";
require "../module/mode_produksi.php"; // Pastikan file ini di-include

$title = "Quotation Siap Diproses - Cafe Qita";
require "../partials/header.php";
require "../partials/navbar.php";
require "../partials/sidebar.php";

// Ambil semua quotation yang belum diproses
$quotations = getQuotations();

if (isset($_POST['proses'])) {
    $no_quotation = $_POST['no_quotation'];
    if (updateQuotationStatus($no_quotation, 'processed')) {
        echo "<script>
                alert('Quotation berhasil diproses');
                document.location = 'quotation_proses.php';
              </script>";
    } else {
        echo "<script>
                alert('Gagal memproses quotation');
                document.location = 'quotation_proses.php';
              </script>";
    }
}

?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Quotation Siap Diproses</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= $main_url ?>dashboard.php">Home</a></li>
                        <li class="breadcrumb-item active">Quotation Siap Diproses</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section>
        <div class="container-fluid">
            <div class="card card-outline card-primary table-responsive px-2">
                <table class="table table-sm table-hover text-nowrap">
                    <thead>
                        <tr>
                            <th>No Quotation</th>
                            <th>Tanggal</th>
                            <th>Customer</th>
                            <th>Total</th>
                            <th>Keterangan</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($quotations as $quotation) { ?>
                        <tr>
                            <td><?= $quotation['no_quotation'] ?></td>
                            <td><?= $quotation['tgl_quotation'] ?></td>
                            <td><?= $quotation['nm_customer'] ?></td>
                            <td><?= number_format($quotation['total'], 0, ',', '.') ?></td>
                            <td><?= $quotation['keterangan'] ?></td>
                            <td><?= $quotation['status'] ?></td>
                            <td>
                                <?php if ($quotation['status'] == 'pending') { ?>
                                    <form action="" method="post">
                                        <input type="hidden" name="no_quotation" value="<?= $quotation['no_quotation'] ?>">
                                        <button type="submit" name="proses" class="btn btn-sm btn-success">Proses</button>
                                    </form>
                                <?php } else { ?>
                                    <span class="badge badge-success">Diproses</span>
                                <?php } ?>
                            </td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>

<?php

require "../partials/footer.php";

?>
