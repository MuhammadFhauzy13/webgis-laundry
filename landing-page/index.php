<?php

session_start();

require '../config.php';
$title = 'Geolaundry';
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title><?= $title ?></title>
  <meta name="description" content="Geolaundry Ternate - Temukan laundry terbaik di sekitar Anda dengan mudah.">
  <meta name="keywords" content="laundry, ternate, geolaundry, peta laundry, cuci baju">
  <!-- sweet alert -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <!-- Favicons -->
  <link href="<?= $main_url ?>src/assets/images/logos/Geolaundry-removebg-preview.png" rel="icon">
  <link href="<?= $main_url ?>src/assets/images/logos/Geolaundry-removebg-preview.png" rel="apple-touch-icon">

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&family=Open+Sans:wght@300;400;600;700&family=Montserrat:wght@400;500;600;700;900&display=swap" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="<?= $main_url ?>landing-page/assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="<?= $main_url ?>landing-page/assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="<?= $main_url ?>landing-page/assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="<?= $main_url ?>landing-page/assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="<?= $main_url ?>landing-page/assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

  <!-- Main CSS File -->
  <link href="<?= $main_url ?>landing-page/assets/css/main.css" rel="stylesheet">

  <!--leaflet  -->
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
    integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
    crossorigin="" />
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
  <!-- Leaflet Fullscreen Plugin -->
  <link rel="stylesheet" href="https://unpkg.com/leaflet.fullscreen/Control.FullScreen.css" />
  <script src="https://unpkg.com/leaflet.fullscreen/Control.FullScreen.js"></script>

  <style>
    /* === NAV MENU (PUTIH → HITAM SAAT HOVER) === */
    #navmenu a {
      color: #fff !important;
      /* teks putih default */
      text-decoration: none;
      font-weight: 500;
      padding: 8px 12px;
      transition: color 0.3s ease;
    }

    /* Saat diarahkan atau aktif: hitam */
    #navmenu a:hover,
    #navmenu a.active {
      color: #000 !important;
    }

    /* Ikon dropdown (panah) */
    #navmenu .bi {
      color: #fff;
      transition: color 0.3s ease;
    }

    /* Saat hover dropdown utama, ikut hitam */
    #navmenu .dropdown:hover>a,
    #navmenu .dropdown:hover .bi {
      color: #000 !important;
    }

    /* === DROPDOWN (TETAP PUTIH TANPA HOVER WARNA) === */
    #navmenu .dropdown ul {
      background-color: #1a1a1a;
      /* latar dropdown gelap */
      border-top: 2px solid #02C3FE;
      /* aksen biru khas Geolaundry */
      border-radius: 8px;
      padding: 8px 0;
      list-style: none;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.25);
    }

    /* Teks dropdown putih stabil (tidak berubah saat hover) */
    #navmenu .dropdown ul li a {
      color: #fff !important;
      padding: 10px 16px;
      display: block;
      text-decoration: none;
    }

    /* warna khusus geolaundry */
    .span,
    h2,
    .description-title,
    #scroll-top {
      color: #02C3FE !important;
    }

    /* tombol scroll-top background */
    #scroll-top {
      background-color: #02C3FE;
      border: none;
      width: 40px;
      height: 40px;
      border-radius: 50%;
      transition: background-color 0.3s ease;
    }

    #scroll-top:hover {
      background-color: #028fc0;
      /* warna lebih gelap saat hover */
    }

    /* fix zoom & scrollbar issue */
    html,
    body {
      margin: 0;
      padding: 0;
      overflow-x: hidden;
      /* cegah scroll horizontal */
    }

    iframe {
      width: 100%;
      display: block;
    }

    .hero {
      height: 100dvh;
      /* lebih stabil daripada 100vh di mobile */
    }

    .footer {
      background-color: #02C3FE;
      color: #fff;
      position: relative;
      bottom: 0;
      width: 100%;
      padding: 8px 0;
      /* rapatkan dari sebelumnya py-4 */
      font-size: 14px;
      /* kecilkan teks sedikit biar lebih enak */
    }

    .footer .container {
      display: flex;
      justify-content: space-between;
      align-items: center;
      flex-wrap: wrap;
      /* supaya responsif */
    }

    .footer img {
      max-height: 40px;
      /* kecilkan logo */
    }

    .footer a {
      color: #fff;
      transition: color 0.3s ease;
      font-size: 16px;
    }

    .footer a:hover {
      color: #000;
    }
  </style>
</head>

<body class="index-page">
  <?php
  if (isset($_SESSION['flash_logout'])) {
    echo '<script>
        Swal.fire({
            icon: "success",
            title: "Berhasil Logout!",
            text: "Anda telah keluar dari sistem.",
            timer: 2000,
            showConfirmButton: false
        });
    </script>';
    unset($_SESSION['flash_logout']); // Hapus pesan agar tidak muncul lagi saat refresh
  }
  ?>
  <!-- modal detail -->
  <div class="modal fade" id="detailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content border-0 shadow">
        <div class="modal-header text-white" style="background-color:#02C3FE;">
          <h5 class="modal-title">Detail Laundry</h5>
          <button type="button" class="btn-close btn-close-dark" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <!-- Tabs Header -->
          <ul class="nav nav-tabs mb-3" id="detailTab" role="tablist">
            <li class="nav-item">
              <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tabInfo" type="button">Info Umum</button>
            </li>
            <li class="nav-item">
              <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tabLokasi" type="button">Lokasi & Kontak</button>
            </li>
          </ul>
          <!-- Tabs Content -->
          <div class="tab-content">
            <!-- INFO UMUM -->
            <div class="tab-pane fade show active" id="tabInfo">
              <div class="row g-3">
                <div class="col-md-12 ">
                  <div class="foto-container d-flex justify-content-left">
                    <img id="m_foto" src="" class="rounded foto-laundry" width="150" alt="Foto Laundry">
                  </div>
                </div>
                <div class="col-md-6">
                  <label class="form-label fw-semibold">Nama Laundry</label>
                  <input id="m_nama" type="text" class="form-control form-control-plaintext" readonly>
                </div>
                <div class="col-md-6">
                  <label class="form-label fw-semibold">Layanan Khusus</label>
                  <input id="m_khusus" type="text" class="form-control form-control-plaintext" readonly>
                </div>
                <div class="col-md-6">
                  <label class="form-label fw-semibold">Layanan</label>
                  <input id="m_layanan" type="text" class="form-control form-control-plaintext" readonly>
                </div>
                <div class="col-md-6">
                  <label class="form-label fw-semibold">Alamat</label>
                  <textarea id="m_alamat" class="form-control form-control-plaintext" rows="1" readonly></textarea>
                </div>
                <div class="col-md-6">
                  <label class="form-label fw-semibold">Profil / Deskripsi</label>
                  <textarea id="m_profile" class="form-control form-control-plaintext" rows="3" readonly></textarea>
                </div>
              </div>
            </div>
            <!-- LOKASI & KONTAK -->
            <div class="tab-pane fade" id="tabLokasi">
              <div class="row g-3">
                <div class="col-md-6">
                  <label class="form-label fw-semibold">Latitude</label>
                  <input id="m_lat" type="text" class="form-control" readonly>
                </div>
                <div class="col-md-6">
                  <label class="form-label fw-semibold">Longitude</label>
                  <input id="m_lng" type="text" class="form-control" readonly>
                </div>
                <div class="col-md-6">
                  <label class="form-label fw-semibold">No. Telepon</label>
                  <input id="m_telp" type="text" class="form-control" readonly>
                </div>
                <div class="col-md-6">
                  <label class="form-label fw-semibold">Jam Buka</label>
                  <input id="m_jam" type="text" class="form-control" readonly>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
        </div>
      </div>
    </div>
  </div>
  <!-- ======= Header ======= -->
  <header id="header" class="header sticky-top">
    <!-- Branding & Nav -->
    <div class="branding d-flex align-items-center" style="background-color: #02C3FE;">
      <div class="container position-relative d-flex align-items-center justify-content-between">
        <a href="index.php" class="logo d-flex align-items-center">
          <h1 class="sitename text-white">Geolaundry Ternate</h1>
        </a>
        <nav id="navmenu" class="navmenu">
          <ul>
            <li><a href="#hero" class="active">Home</a></li>
            <li class="dropdown">
              <a href="#"><span>Login</span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
              <ul>
                <li><a href="<?= $main_url ?>autentikasi/index.php">Login Admin</a></li>
              </ul>
            </li>
            <li><a href="#contact">Peta</a></li>
          </ul>
          <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
        </nav>
      </div>
    </div>
  </header>
  <main class="main">
    <!-- Hero Section -->
    <section id="hero" class="hero section light-background d-flex align-items-center"
      style="background: url('<?= $main_url ?>landing-page/assets/img/hero-bg.jpg') no-repeat center center / cover;">
      <div class="container text-white">
        <div class="row gy-4">
          <div class="col-lg-6 d-flex flex-column justify-content-center" data-aos="zoom-out">
            <h1>Persebaran Laundry di <span class="span">Ternate</span></h1>
            <p>Temukan Laundry terdekat di sekitar anda</p>
          </div>
        </div>
      </div>
    </section>
    <!-- Contact / Map Section -->
    <section id="contact" class="contact section">
      <div class="container section-title" data-aos="fade-up">
        <h2>Map</h2>
        <p><span>Map</span> <span class="description-title">Geolaundry Ternate</span></p>
      </div>
      <div class="container-fluid" data-aos="fade-up" data-aos-delay="100">
        <div class="row justify-content-center">
          <div class="col-lg-10 col-md-12">
            <div class="card shadow-sm border-0 rounded-3">
              <div class="card-header text-white fw-bold" style="background-color: #02C3FE;">
                Peta Lokasi
              </div>
              <div class="card-body p-0">
                <!-- Container Map -->
                <div id="map" style="height: 400px; width: 100%; border-radius: 0 0 0.5rem 0.5rem; overflow: hidden;"></div>
              </div>
            </div>
          </div>
        </div>
      </div>

    </section>
  </main>
  <!-- ======= Footer ======= -->
  <footer id="footer" class="footer">
    <div class="container">
      <div class="footer-logo">
        <img src="<?= $main_url ?>src/assets/images/logos/Geolaundry-removebg-preview.png" alt="Geolaundry">
      </div>
      <p class="mb-0">
        © <span>Unkhair</span> <strong class="sitename">2025</strong>
      </p>
      <div class="footer-social">
        <a href="#"><i class="bi bi-facebook"></i></a>
        <a href="#"><i class="bi bi-instagram"></i></a>
        <a href="#"><i class="bi bi-twitter-x"></i></a>
      </div>
    </div>
  </footer>
  <!-- Scroll Top -->
  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center">
    <i class="bi bi-arrow-up-short"></i>
  </a>
  <?php
  // Ambil data dari database
  $result = mysqli_query($koneksi, "SELECT 
                                          laundry.*, 
                                          layanan_khusus.nama_layanan_khusus,
                                          layanan.nama_layanan,
                                          layanan.harga
                                      FROM laundry
                                      INNER JOIN layanan_khusus 
                                          ON laundry.id_layanan_khusus = layanan_khusus.id_layanan_khusus
                                      INNER JOIN layanan 
                                          ON laundry.id_layanan = layanan.id_layanan
                                      ORDER BY laundry.id_laundry DESC");
  ?>
  <script src="https://unpkg.com/leaflet.fullscreen@1.6.0/Control.FullScreen.js"></script>
  <script>
    // Inisialisasi peta di Ternate
    var map = L.map('map', {
      center: [0.80825206, 127.34063399],
      zoom: 13,
      fullscreenControl: true
    });
    // Hilangkan tulisan "Leaflet"
    map.attributionControl.setPrefix(false);
    // Basemap OpenStreetMap
    var osm = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      maxZoom: 19,
      attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap contributors</a>'
    }).addTo(map);
    // Basemap Satelit (Esri)
    var satellite = L.tileLayer(
      'https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
        attribution: 'Tiles &copy; <a href="https://www.esri.com/">Esri</a>, Maxar, Earthstar Geographics'
      }
    );
    // Kontrol untuk ganti basemap
    var baseMaps = {
      "OpenStreetMap": osm,
      "Satelit": satellite
    };
    L.control.layers(baseMaps).addTo(map);

    // ===============================
    // Tambahkan marker dari database
    // ===============================
    <?php
    mysqli_data_seek($result, 0); // reset pointer query
    while ($row = mysqli_fetch_assoc($result)) {
      $nama = addslashes($row['nama_laundry']);
      $lat  = $row['latitude'];
      $lng  = $row['longitude'];
      $no_telp  = $row['no_telp'];
      $jam_buka  = $row['jam_buka'];
      $nama_khusus  = $row['nama_layanan_khusus'];
      $nama_layanan = $row['nama_layanan'];
      $foto = $row["foto"];
      $foto_url = "../laundry/" . $foto;
      // Validasi agar hanya koordinat valid yang ditampilkan
      if (
        is_numeric($lat) && is_numeric($lng) &&
        $lat >= -90 && $lat <= 90 &&
        $lng >= -180 && $lng <= 180
      ) {
    ?>
        var marker = L.marker([<?= $lat ?>, <?= $lng ?>]).addTo(map);
        marker.bindPopup(`
            <b><?= $nama ?></b><br><br>
            No Telpon: <?= $no_telp ?><br>
            Jam Buka: <?= $jam_buka ?><br>
            Layanan Khusus: <?= $nama_khusus ?><br><br>
            <img src= "../laundry/<?= $foto ?>" width="120" alt="Foto laundry"><br><br>
            <button 
            class="btn btn-sm btn-info openModal"
            data-nama="<?= $nama ?>"
            data-khusus="<?= $nama_khusus ?>"
            data-layanan= "<?= $nama_layanan ?>"
            data-harga="<?= number_format($row['harga'] * 1000, 0, ',', '.') ?>"
            data-lat="<?= $lat ?>"
            data-lng="<?= $lng ?>"
            data-telp="<?= $no_telp ?>"
            data-jam="<?= $jam_buka ?>"
            data-alamat="<?= addslashes($row['alamat']) ?>"
            data-profile="<?= addslashes($row['profile']) ?>"
            data-foto="../laundry/<?= $foto ?>">
            Detail Laundry
            </button>
  
            `);
    <?php
      }
    }
    ?>
    // =====================================
    // EVENT LISTENER UNTUK BUTTON MODAL
    // =====================================
    map.on("popupopen", function(e) {
      const btn = e.popup._container.querySelector(".openModal");
      if (btn) {
        btn.addEventListener("click", function() {

          document.getElementById("m_nama").value = this.dataset.nama;
          document.getElementById("m_khusus").value = this.dataset.khusus;
          document.getElementById("m_layanan").value = `${this.dataset.layanan} - Rp ${this.dataset.harga}`;
          document.getElementById("m_lat").value = this.dataset.lat;
          document.getElementById("m_lng").value = this.dataset.lng;
          document.getElementById("m_telp").value = this.dataset.telp;
          document.getElementById("m_jam").value = this.dataset.jam;
          document.getElementById("m_alamat").value = this.dataset.alamat;
          document.getElementById("m_profile").value = this.dataset.profile;
          document.getElementById("m_foto").src = this.dataset.foto;

          let modal = new bootstrap.Modal(document.getElementById("detailModal"));
          modal.show();
        });
      }
    });
  </script>

  <!-- Vendor JS Files -->
  <script src="<?= $main_url ?>landing-page/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="<?= $main_url ?>landing-page/assets/vendor/php-email-form/validate.js"></script>
  <script src="<?= $main_url ?>landing-page/assets/vendor/aos/aos.js"></script>
  <script src="<?= $main_url ?>landing-page/assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="<?= $main_url ?>landing-page/assets/vendor/waypoints/noframework.waypoints.js"></script>
  <script src="<?= $main_url ?>landing-page/assets/vendor/purecounter/purecounter_vanilla.js"></script>
  <script src="<?= $main_url ?>landing-page/assets/vendor/swiper/swiper-bundle.min.js"></script>
  <script src="<?= $main_url ?>landing-page/assets/vendor/imagesloaded/imagesloaded.pkgd.min.js"></script>
  <script src="<?= $main_url ?>landing-page/assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
  <!-- Main JS File -->
  <script src="<?= $main_url ?>landing-page/assets/js/main.js"></script>

</body>

</html>