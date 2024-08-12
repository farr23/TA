<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="<?= $main_url ?>dashboard.php" class="brand-link">
      <img src="<?= $main_url ?>asset/image/cq.png" alt="Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">Cafe Qita</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="<?= $main_url ?>asset/image/<?= userlogin()['foto'] ?>" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
            <!-- diambil dari session -->
          <a href="#" class="d-block"></a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <li class="nav-item" >
                <a href="<?= $main_url ?>dashboard.php" class="nav-link <?= menuhome() ?> ">
                    <i class="nav-icon fas fa-tachometer-alt text-sm"></i>
                    <p>Dashboard</p>
                </a>
            </li>
            <!-- gae ngatur hak akses kasir gaole master-->
            <?php
            if(userlogin()['level'] != 3){
            ?>
            <li class="nav-item <?= menumaster() ?>">
                <a href="#" class="nav-link">
                    <i class="nav-icon fas fa-folder text-sm"></i>
                    <p>
                        Master
                        <i class="fas fa-angle-up right"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="<?= $main_url ?>supplier/data_supplier.php" class="nav-link <?= menusupplier() ?>">
                            <i class="far fa-circle nav-icon text-sm"></i>
                            <p>Supplier</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= $main_url ?>customer/data_customer.php" class="nav-link <?= menucustomer() ?>">
                            <i class="far fa-circle nav-icon text-sm"></i>
                            <p>Customer</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= $main_url ?>produk" class="nav-link <?= menuproduk() ?>">
                            <i class="far fa-circle nav-icon text-sm"></i>
                            <p>Produk</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= $main_url ?>bahan" class="nav-link <?= menubahan()?>">
                            <i class="far fa-circle nav-icon text-sm"></i>
                            <p>Bahan</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= $main_url ?>kategori/data_kategori.php" class="nav-link <?= menukategori()?> ">
                            <i class="far fa-circle nav-icon text-sm"></i>
                            <p>Kategori</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= $main_url ?>resep" class="nav-link <?= menuresep()?>">
                            <i class="far fa-circle nav-icon text-sm"></i>
                            <p>Resep</p>
                        </a>
                    </li>
                    <!-- iki sek gurung fiks -->
                </ul>
            </li>
            <!-- iki gandengane -->
            <?php } ?>
            
            <li class="nav-header">Transaksi</li>
            <?php
            if(userlogin()['level'] != 2){
            ?>
            <li class="nav-item">
                <a href="<?= $main_url ?>pembelian" class="nav-link">
                    <i class="nav-icon fas fa-shopping-cart text-sm"></i>
                    <p>Pembelian</p>
                </a>
            </li>
            <li class="nav-item <?= menupenjualan() ?>">
                <a href="#" class="nav-link">
                    <i class="nav-icon fas fa-shopping-cart text-sm"></i>
                    <p>
                        Penjualan
                        <i class="fas fa-angle-up right"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="<?= $main_url ?>pemesanan/form_pemesanan.php" class="nav-link <?= menupemesanan() ?>">
                            <i class="far fa-circle nav-icon text-sm"></i>
                            <p>Pesanan</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= $main_url ?>penjualan" class="nav-link <?= menujual() ?>">
                            <i class="far fa-circle nav-icon text-sm"></i>
                            <p>Penjualan</p>
                        </a>
                    </li>
                </ul>
                <?php }?>
            </li>
            <li class="nav-item">
                <a href="<?= $main_url ?>stok_opname" class="nav-link">
                    <i class="nav-icon fas fa-file-invoice text-sm"></i>
                    <p>Stok Opname</p>
                </a>
            </li>
            
            <li class="nav-item">
                <a href="<?= $main_url ?>produksi" class="nav-link">
                    <i class="nav-icon fas fa-user-clock text-sm"></i>
                    <p>Produksi</p>
                </a>
            </li>
            
            <li class="nav-header">Laporan</li>
            <li class="nav-item">
                <a href="<?= $main_url ?>laporan_pembelian/laporan.php" class="nav-link">
                    <i class="nav-icon fas fa-chart-line text-sm"></i>
                    <p>Laporan Pembelian</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?= $main_url ?>laporan_penjualan" class="nav-link">
                    <i class="nav-icon fas fa-chart-line text-sm"></i>
                    <p>Laporan Penjualan</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?= $main_url ?>laporan_produksi" class="nav-link">
                    <i class="nav-icon fas fa-chart-pie text-sm"></i>
                    <p>Laporan Produksi</p>
                </a>
            </li>
            <li class="nav-item <?= menulstok() ?>">
                <a href="#" class="nav-link">
                    <i class="nav-icon fas fa-warehouse text-sm"></i>
                    <p>
                        Laporan Stok
                        <i class="fas fa-angle-up right"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="<?= $main_url ?>penggunaan/laporan_stok_masuk.php" class="nav-link <?= menulmasuk() ?>">
                            <i class="far fa-circle nav-icon text-sm"></i>
                            <p>Laporan Stok Bahan Masuk</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= $main_url ?>penggunaan/laporan_stok_keluar.php" class="nav-link <?= menulkeluar() ?>">
                            <i class="far fa-circle nav-icon text-sm"></i>
                            <p>Laporan Stok Bahan Keluar</p>
                        </a>
                    </li>
                </ul>
            </li>
            <!-- gudang bahan dan produk -->
            <li class="nav-item <?= menuggudang() ?>">
                <a href="#" class="nav-link">
                    <i class="nav-icon fas fa-folder text-sm"></i>
                    <p>
                        Gudang
                        <i class="fas fa-angle-up right"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="<?= $main_url ?>gudang/gudang_produk.php" class="nav-link <?= menugproduk() ?>">
                            <i class="far fa-circle nav-icon text-sm"></i>
                            <p>Produk</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= $main_url ?>gudang/gudang_bahan.php" class="nav-link <?= menugbahan() ?>">
                            <i class="far fa-circle nav-icon text-sm"></i>
                            <p>Bahan</p>
                        </a>
                    </li>
                </ul>
            </li>
            <!-- penggunaan bahan -->
            <li class="nav-item <?= menupenggunaan() ?>">
                <a href="#" class="nav-link">
                    <i class="nav-icon fas fa-folder text-sm"></i>
                    <p>
                        Penggunaan Bahan
                        <i class="fas fa-angle-up right"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="<?= $main_url ?>penggunaan/bahan_masuk.php" class="nav-link <?= menubmasuk() ?>">
                            <i class="far fa-circle nav-icon text-sm"></i>
                            <p>Bahan Masuk</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= $main_url ?>penggunaan/bahan_keluar.php" class="nav-link <?= menubkeluar() ?>">
                            <i class="far fa-circle nav-icon text-sm"></i>
                            <p>Bahan Keluar</p>
                        </a>
                    </li>
                </ul>
            </li>
            <!-- selain admin gaiso buka iki -->
            <?php
            if(userlogin()['level'] == 1){
            ?>
            <li class="nav-item <?= menusetting() ?>">
                <a href="#" class="nav-link">
                    <i class="nav-icon fas fa-cog text-sm"></i>
                    <p>
                        Pengaturan
                        <i class="fas fa-angle-up right"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="<?= $main_url ?>user/data_user.php" class="nav-link <?= menuuser() ?>">
                            <i class="far fa-circle nav-icon text-sm"></i>
                            <p>Users</p>
                        </a>
                    </li>
                </ul>
            </li>
            <?php } ?>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>