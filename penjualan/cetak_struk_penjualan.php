<?php
require "../config/config.php";
require "../config/function.php";

// Ambil nojual dari URL
$nojual = $_GET['nojual'];

// Ambil data penjualan berdasarkan nojual
$penjualan = getData("SELECT * FROM trans_penjualan WHERE no_transjual = '$nojual'");

// Cek apakah data penjualan ditemukan
if (empty($penjualan)) {
    echo "<script>alert('Transaksi tidak ditemukan!'); window.close();</script>";
    exit;
}

$penjualan = $penjualan[0];
$detail = getData("SELECT * FROM trans_penjudetail WHERE no_transjual = '$nojual'");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Struk Penjualan</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .struk-container { width: 300px; margin: auto; border: 1px solid #ddd; padding: 20px; }
        h2, p { text-align: center; margin: 0; padding: 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        table, th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .text-right { text-align: right; }
        .footer { text-align: center; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="struk-container">
        <h2>Cafe Qita</h2>
        <p>Struk Penjualan</p>
        <p>No. Nota: <?= $penjualan['no_transjual'] ?></p>
        <p>Tanggal: <?= $penjualan['tgl_transjual'] ?></p>
        <p>Customer: <?= $penjualan['nm_customer'] ?></p>
        
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Produk</th>
                    <th class="text-right">Harga</th>
                    <th class="text-right">Qty</th>
                    <th class="text-right">Jumlah</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                foreach ($detail as $item) { ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= $item['nm_produk'] ?></td>
                        <td class="text-right"><?= number_format($item['harga_jual'], 0, ',', '.') ?></td>
                        <td class="text-right"><?= $item['jumlah'] ?></td>
                        <td class="text-right"><?= number_format($item['jml_harga'], 0, ',', '.') ?></td>
                    </tr>
                <?php } ?>
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="4" class="text-right">Total</th>
                    <th class="text-right"><?= number_format($penjualan['total'], 0, ',', '.') ?></th>
                </tr>
                <tr>
                    <th colspan="4" class="text-right">Bayar</th>
                    <th class="text-right"><?= number_format($penjualan['jml_bayar'], 0, ',', '.') ?></th>
                </tr>
                <tr>
                    <th colspan="4" class="text-right">Kembalian</th>
                    <th class="text-right"><?= number_format($penjualan['kembalian'], 0, ',', '.') ?></th>
                </tr>
            </tfoot>
        </table>
        
        <div class="footer">
            <p>Terima kasih telah berbelanja!</p>
        </div>
    </div>

    <script>
        window.print();
    </script>
</body>
</html>
