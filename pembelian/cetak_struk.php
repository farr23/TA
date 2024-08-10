<?php
require "../config/config.php";
require "../config/function.php";

// Ambil no_transbeli dari URL
$no_transbeli = $_GET['no_transbeli'];

// Ambil data pembelian berdasarkan no_transbeli
$transaksi = getData("SELECT * FROM trans_pembelian WHERE no_transbeli = '$no_transbeli'")[0];
$detail = getData("SELECT * FROM trans_pemdetail WHERE no_transbeli = '$no_transbeli'");

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Struk Pembelian</title>
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
        <p>Struk Pembelian</p>
        <p>No. Nota: <?= $transaksi['no_transbeli'] ?></p>
        <p>Tanggal: <?= $transaksi['tgl_transbeli'] ?></p>
        <p>Supplier: <?= $transaksi['nm_supplier'] ?></p>
        
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Bahan</th>
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
                        <td><?= $item['nm_bahan'] ?></td>
                        <td class="text-right"><?= number_format($item['harga_beli'], 0, ',', '.') ?></td>
                        <td class="text-right"><?= $item['jumlah'] ?></td>
                        <td class="text-right"><?= number_format($item['jml_harga'], 0, ',', '.') ?></td>
                    </tr>
                <?php } ?>
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="4" class="text-right">Total</th>
                    <th class="text-right"><?= number_format($transaksi['total'], 0, ',', '.') ?></th>
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
