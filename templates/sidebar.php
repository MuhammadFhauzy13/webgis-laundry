<!-- Sidebar Start -->
<?php
$current_folder = basename(dirname($_SERVER['PHP_SELF']));
?>
<aside class="left-sidebar">
    <div>
        <div class="brand-logo d-flex align-items-center justify-content-center border-bottom border-3">
            <a href="#" class="d-flex align-items-center text-nowrap logo-img">
                <img src="<?= $main_url ?>src/assets/images/logos/Geolaundry-removebg-preview.png"
                    width="100" alt="Geolaundry" />
                <!-- <span class="ms-2 h4 fw-bold text-dark">Geolaundry</span> -->
            </a>
        </div>


        <!-- Sidebar navigation -->

        <nav class="sidebar-nav scroll-sidebar mt-0" data-simplebar="">
            <ul id="sidebarnav">
                <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                    <span class="hide-menu">Home</span>
                </li>
                <!-- Dashboard -->
                <li class="sidebar-item <?= ($current_folder == 'webgis_laundry') ? 'active' : '' ?>">
                    <a class="sidebar-link" href="<?= $main_url ?>index.php" aria-expanded="false">
                        <span>
                            <!-- Icon Dashboard -->
                            <i class="ti ti-home" style="font-size: 1.5rem;"></i>
                        </span>
                        <span class="hide-menu">Dashboard</span>
                    </a>
                </li>
                <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                    <span class="hide-menu">DATA</span>
                </li>
                <!-- Laundry -->
                <li class="sidebar-item <?= ($current_folder == 'laundry') ? 'active' : '' ?>">
                    <a class="sidebar-link" href="<?= $main_url ?>laundry/index.php" aria-expanded="false">
                        <span>
                            <!-- Icon Laundry -->
                            <i class="ti ti-wash-machine" style="font-size: 1.5rem;"></i>
                        </span>
                        <span class="hide-menu">Laundry</span>
                    </a>
                </li>

                <!-- Kategori -->
                <li class="sidebar-item <?= ($current_folder == 'layanan-khusus') ? 'active' : '' ?>">
                    <a class="sidebar-link" href="<?= $main_url ?>layanan-khusus/index.php" aria-expanded="false">
                        <span>
                            <!-- Icon Kategori -->
                            <i class="ti ti-wash-gentle" style="font-size: 1.5rem;"></i>
                        </span>
                        <span class="hide-menu">Layanan Khusus</span>
                    </a>
                </li>
                <!-- layanan -->
                <li class="sidebar-item <?= ($current_folder == 'layanan') ? 'active' : '' ?>">
                    <a class="sidebar-link" href="<?= $main_url ?>layanan/index.php" aria-expanded="false">
                        <span>
                            <!-- Icon Kategori -->
                            <i class="ti ti-category" style="font-size: 1.5rem;"></i>
                        </span>
                        <span class="hide-menu">Layanan</span>
                    </a>
                </li>
                <!-- Map -->
                <li class="sidebar-item <?= ($current_folder == 'map') ? 'active' : '' ?>">
                    <a class="sidebar-link" href="<?= $main_url ?>map/index.php" aria-expanded="false">
                        <span>
                            <!-- Icon Map -->
                            <i class="ti ti-map-pin" style="font-size: 1.5rem;"></i>
                        </span>
                        <span class="hide-menu">Peta</span>
                    </a>
                </li>
                <!-- <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                    <span class="hide-menu">Laporan</span>
                </li> -->
                <!-- Rekap -->
                <!-- <li class="sidebar-item <?= ($current_folder == 'rekap') ? 'active' : '' ?>">
                    <a class="sidebar-link" href="<?= $main_url ?>rekap/index.php" aria-expanded="false">
                        <span>
                            <i class="ti ti-clipboard-text" style="font-size: 1.5rem;"></i>
                        </span>
                        <span class="hide-menu">Rekap</span>
                    </a>
                </li> -->
            </ul>
        </nav>

    </div>
</aside>

<!--  Sidebar End -->