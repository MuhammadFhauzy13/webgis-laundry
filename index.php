<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['ssLoginLaundry'])) {
    header("location: landing-page/index.php");
    exit();
}

require 'config.php';
$title = 'Dashboard - GeoLaundry';

require 'templates/header.php';
require 'templates/sidebar.php';
require 'templates/navbar.php';

?>

<style>
    .card-blue-solid {
        background-color: #02C3FE;
        /* warna card */
        color: #fff;
        /* teks putih untuk semua konten */
        border-radius: 0.5rem;
        transition: transform 0.25s ease,
            box-shadow 0.25s ease,
            background-color 0.25s ease;
        will-change: transform, box-shadow;
    }

    /* Hover efek halus */
    .card-blue-solid:hover {
        background-color: #02B5EC;
        /* sedikit lebih gelap */
        transform: translateY(-3px) scale(1.01) rotateX(0.5deg) rotateY(0.3deg);
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.12),
            0 3px 6px rgba(0, 0, 0, 0.08);
    }

    /* Judul dan angka */
    .card-blue-solid .card-title {
        font-size: 0.9rem;
        font-weight: 500;
        margin-bottom: 0.25rem;
        color: #fff;
        /* pastikan teks tetap putih */
    }

    .card-blue-solid .fw-bold {
        font-size: 1.3rem;
        color: #fff;
        /* pastikan angka tetap putih */
    }

    /* Jika ada teks tambahan dalam card */
    .card-blue-solid p,
    .card-blue-solid span,
    .card-blue-solid small {
        color: #fff;
    }
</style>

<div class="container-fluid">
    <div class="card shadow-lg border-0 rounded-4 ">
        <div class="card-body">
            <h5 class="card-title fw-semibold mb-4">Dashboard</h5>
            <p class="mb-3">Selamat datang di GeoLaundry!</p>

            <div class="card ">
                <div class="card-body">
                    <div class="row">
                        <!-- Total Laundry -->
                        <div class="col-md-4 mb-3">
                            <div class="card card-blue-solid card-hover shadow">
                                <div class="card-body p-3">
                                    <h6 class="card-title mb-1"><i class="ti ti-wash-machine" style="font-size: 1.2rem; margin-right: 6px;"></i> Total Laundry</h6>
                                    <h4 class="fw-bold mb-0"> <?php
                                                                $query = "SELECT COUNT(*) AS total_laundry FROM laundry;";
                                                                $result = mysqli_query($koneksi, $query);
                                                                $data = mysqli_fetch_assoc($result);
                                                                echo $data['total_laundry'];
                                                                ?></h4>
                                </div>
                            </div>
                        </div>

                        <!-- Total layanan khusus -->
                        <div class="col-md-4 mb-3">
                            <div class="card card-blue-solid card-hover shadow">
                                <div class="card-body p-3">
                                    <h6 class="card-title mb-1"><i class="ti ti-category" style="font-size: 1.2rem; margin-right: 6px;"></i> Total Layanan Khusus</h6>
                                    <h4 class="fw-bold mb-0"><?php
                                                                $query1 = "SELECT COUNT(*) AS total_kategori FROM layanan_khusus;";
                                                                $resultKategori = mysqli_query($koneksi, $query1);
                                                                $dataKateogri = mysqli_fetch_assoc($resultKategori);
                                                                echo $dataKateogri['total_kategori'];
                                                                ?></h4>
                                </div>
                            </div>
                        </div>
                        <!-- total layanan -->
                        <div class="col-md-4 mb-3">
                            <div class="card card-blue-solid card-hover shadow">
                                <div class="card-body p-3">
                                    <h6 class="card-title mb-1">
                                        <i class="ti ti-tag" style="font-size: 1.2rem; margin-right: 6px;"></i> Total Layanan Biasa
                                    </h6>
                                    <h4 class="fw-bold mb-0">
                                        <?php
                                        $query = "SELECT COUNT(*) AS total_layanan FROM layanan";
                                        $result = mysqli_query($koneksi, $query);

                                        if ($result) {
                                            $data = mysqli_fetch_assoc($result);
                                            echo $data['total_layanan'];
                                        } else {
                                            echo "0"; // fallback jika query gagal
                                        }
                                        ?>
                                    </h4>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>


        </div>
    </div>
</div>


<?php
require 'templates/footer.php';
?>