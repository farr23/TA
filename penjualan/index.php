<?php

session_start();

if (!isset($_SESSION["sslogin"])) {
    header("location: ../auth/login.php");
    exit();
}

require "../config/config.php";
require "../config/function.php";
require "../module/mode_jual.php"; // Pastikan file ini di-include

$title = "Transaksi Penjualan - Cafe Qita";
require "../partials/header.php";
require "../partials/navbar.php";
require "../partials/sidebar.php";


if (isset($_POST['simpan'])) {
    $tgl = $_POST['tglnota'];
    $nojual = $_POST['nojual'];
    $customer = $_POST['customer'];
    $keterangan = $_POST['keterangan'];
    $bayar = $_POST['bayar'];
    $kembalian = $_POST['kembalian'];
    $total = $_POST['total'];

    if ($bayar < $total) {
        echo "<script>
                alert('Pembayaran tidak cukup');
                window.history.back();
              </script>";
        exit();
    }

    $penjualan = [
        'nojual' => $nojual,
        'tglnota' => $tgl,
        'customer' => $customer,
        'total' => $total,
        'keterangan' => $keterangan,
        'bayar' => $bayar,
        'kembalian' => $kembalian
    ];

    if (insertPenjualan($penjualan)) {
        $idPesanan = $_GET['pilihpsn'];
        $queryGetPesanan = "SELECT * FROM tb_detail_pesanan
                                LEFT JOIN tb_pesanan ON tb_detail_pesanan.id_pesanan = tb_pesanan.id_pesanan
                                LEFT JOIN tb_produk ON tb_detail_pesanan.id_produk = tb_produk.id_produk
                                WHERE tb_detail_pesanan.id_pesanan='$idPesanan'";
        $getPesanan = $koneksi->query($queryGetPesanan);

        while ($dataPesanan = mysqli_fetch_assoc($getPesanan)) {
            kurangiStok($dataPesanan['id_produk'], $dataPesanan['jumlah']);
            insertDetail($dataPesanan, $nojual, $tgl);
        }
        $queryUpdatePesanan = "UPDATE tb_pesanan SET status = 'Selesai' WHERE id_pesanan = '$idPesanan'";
        $updatePesanan = $koneksi->query($queryUpdatePesanan);

        echo "<script>
                document.location = 'cetak_struk_penjualan.php?nojual=$nojual';
              </script>";
    }
}

$nomorNota = generateNomorNota();

?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Penjualan Produk</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= $main_url ?>dashboard.php">Home</a></li>
                        <li class="breadcrumb-item active">Tambah Penjualan</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>

    <section>
        <div class="container-fluid">
            <form action="" method="post">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="card card-outline card-warning p-3">
                            <div class="form-group row mb-2">
                                <label for="nojual" class="col-sm-2 col-form-label">No Nota</label>
                                <div class="col-sm-4">
                                    <input type="text" name="nojual" class="form-control" id="nojual"
                                        value="<?= $nomorNota ?>" readonly>
                                </div>
                                <label for="tglnota" class="col-sm-2 col-form-label">Tgl Nota</label>
                                <div class="col-sm-4">
                                    <input type="date" name="tglnota" class="form-control" id="tglnota"
                                        value="<?= @$_GET['tgl'] ? $_GET['tgl'] : date('Y-m-d') ?>" required>
                                </div>
                            </div>
                            <div class="form-group row mb-2">
                                <label for="pesanan" class="col-sm-2 col-form-label">Pesanan</label>
                                <div class="col-sm-10">
                                    <select name="pesanan" id="pesanan" class="form-control" required>
                                        <option value="">-- Pilih Pesanan --</option>
                                        <?php
                                        $pesanan = getData("SELECT * FROM tb_pesanan WHERE status = 'DiProduksi'");
                                        foreach ($pesanan as $psn) {
                                            if (!isset($_GET['pilihpsn'])) {
                                                ?>
                                                <option value="<?= $psn['id_pesanan'] ?>">
                                                    <?= $psn['id_pesanan'] . " | " . $psn['nm_customer'] ?>
                                                </option>
                                                <?php
                                            } else {
                                                if ($_GET['pilihpsn'] == $psn['id_pesanan']) {
                                                    ?>
                                                    <option selected value="<?= $psn['id_pesanan'] ?>">
                                                        <?= $psn['id_pesanan'] . " | " . $psn['nm_customer'] ?>
                                                    </option><!--  -->
                                                    <?php
                                                } else {
                                                    ?>
                                                    <option value="<?= $psn['id_pesanan'] ?>">
                                                        <?= $psn['id_pesanan'] . " | " . $psn['nm_customer'] ?>
                                                    </option>
                                                    <?php
                                                }
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="card card-outline card-danger pt-3 px-3 pb-2">
                            <h6 class="font-weight-bold text-right">Total Penjualan</h6>
                            <h1 class="font-weight-bold text-right" style="font-size: 40pt;">
                                <?php
                                if (isset($_GET['pilihpsn'])) {
                                    $idPesanan = $_GET['pilihpsn'];
                                    $queryGetPesanan = "SELECT * FROM tb_pesanan
                                                        WHERE id_pesanan='$idPesanan'";
                                    $getPesanan = $koneksi->query($queryGetPesanan);
                                    $dataPesanan = mysqli_fetch_assoc($getPesanan);

                                    $customer = $dataPesanan['nm_customer'];
                                    ?>
                                    <input type="hidden" name="total" id="total"
                                        value="<?= $dataPesanan['total'] ?>"><?= number_format($dataPesanan['total'], 0, ',', '.') ?>
                                    <?php
                                }
                                ?>
                            </h1>
                        </div>
                    </div>
                </div>

                <?php
                if (isset($_GET['pilihpsn'])) {
                    $idPesanan = $_GET['pilihpsn'];
                    ?>
                    <div class="card card-outline card-success table-responsive px-2">
                        <table class="table table-sm table-hover text-nowrap">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kode Produk</th>
                                    <th>Nama Produk</th>
                                    <th class="text-right">Harga</th>
                                    <th class="text-right">Qty</th>
                                    <th class="text-center">Jumlah Harga</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $queryGetPesanan = "SELECT * FROM tb_detail_pesanan
                                LEFT JOIN tb_pesanan ON tb_detail_pesanan.id_pesanan = tb_pesanan.id_pesanan
                                WHERE tb_detail_pesanan.id_pesanan='$idPesanan'";
                                $getPesanan = $koneksi->query($queryGetPesanan);
                                $no = 1;
                                while ($dataPesanan = mysqli_fetch_assoc($getPesanan)) { ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><?= $dataPesanan['id_produk'] ?></td>
                                        <td><?= $dataPesanan['nm_produk'] ?></td>
                                        <td class="text-right"><?= number_format($dataPesanan['harga_jual'], 0, ',', '.') ?>
                                        </td>
                                        <td class="text-right"><?= $dataPesanan['jumlah'] ?></td>
                                        <td class="text-right"><?= number_format($dataPesanan['jml_harga'], 0, ',', '.') ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                    <?php
                }
                ?>

                <div class="row">
                    <div class="col-lg-4 p-2">
                        <div class="form-group row mb-2">
                            <label for="customer" class="col-sm-3 col-form-label col-form-label">Customer</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="customer" id="customer"
                                    value="<?= isset($customer) ? $customer : '' ?>" readonly required />
                            </div>
                        </div>
                        <div class="form-group row mb-2">
                            <label for="keterangan" class="col-sm-3 col-form-label">Keterangan</label>
                            <div class="col-sm-9">
                                <textarea required name="keterangan" id="keterangan"
                                    class="form-control form-control-sm"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 py-2 px-3">
                        <div class="form-group row mb-2">
                            <label for="bayar" class="col-sm-3 col-form-label">Bayar</label>
                            <div class="col-sm-9">
                                <input required type="number" name="bayar"
                                    class="form-control form-control-sm text-right" id="bayar">
                            </div>
                        </div>
                        <div class="form-group row mb-2">
                            <label for="kembalian" class="col-sm-3 col-form-label">Kembalian</label>
                            <div class="col-sm-9">
                                <input type="number" name="kembalian" class="form-control form-control-sm text-right"
                                    id="kembalian" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 p-2">
                        <button type="submit" name="simpan" id="simpan" class="btn btn-primary btn-sm btn-block"><i
                                class="fas fa-save fa-sm mr-2"></i>Simpan</button>
                    </div>
                </div>
            </form>
    </section>

    <script>
        $(document).ready(function () {
            $('#pesanan').select2({
                theme: "classic"
            });
            $('#pesanan').on('change', function () {
                if (this.value != '') {
                    // Ambil nilai yang dipilih dari select box
                    const selectedValue = this.value;

                    // Dapatkan URL saat ini
                    const currentUrl = new URL(window.location.href);

                    console.log(selectedValue);

                    // Setel atau perbarui parameter pilihpsn
                    currentUrl.searchParams.set('pilihpsn', selectedValue);

                    // Update URL tanpa reload halaman
                    window.history.replaceState({}, '', currentUrl);

                    // Redirect ke URL baru dengan parameter yang diperbarui
                    window.location.href = currentUrl.href;
                }

            });

            $('#bayar').on('keyup mouseup', function () {
                const bayar = parseInt(this.value);
                const totalPembayaran = parseInt($('#total').val());

                if (bayar > totalPembayaran) {
                    const kembalian = bayar - totalPembayaran;
                    $('#kembalian').val(kembalian);
                    console.log(bayar - totalPembayaran);
                } else {
                    $('#kembalian').val('');
                }
            })
        });
    </script>

    <?php

    require "../partials/footer.php";

    ?>