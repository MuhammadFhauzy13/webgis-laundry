<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['ssLoginLaundry'])) {
    header("location: ../landing-page/index.php");
    exit();
}
require '../config.php';
$title = 'Map - GeoLaundry';

require '../templates/header.php';
require '../templates/sidebar.php';
require '../templates/navbar.php';

?>

<div class="container-fluid">
    <div class="card shadow-lg border-0 rounded-4">
        <div class="card-body">
            <h5 class="card-title fw-semibold mb-4 ">Map</h5>
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-body">
                    <div class="card shadow-sm border-0 rounded-3">
                        <div class="card-header bg-secondary text-white fw-bold">
                            Peta Lokasi
                        </div>
                        <div class="card-body p-0">
                            <!-- Container Map -->
                            <div id="map" style="height: 400px; width: 100%;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
// Ambil data dari database
$result = mysqli_query($koneksi, "SELECT laundry.*, layanan_khusus.nama_layanan_khusus 
                                                        FROM laundry 
                                                        INNER JOIN layanan_khusus
                                                        ON laundry.id_layanan_khusus = layanan_khusus.id_layanan_khusus
                                                        ORDER BY laundry.id_laundry DESC");
?>

<script>
    // pas buka muncul ternate
    var map = L.map('map', {
        center: [0.80825206, 127.34063399],
        zoom: 13,
        fullscreenControl: true //
    });

    map.attributionControl.setPrefix(false);
    // Basemap OpenStreetMap
    var osm = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap contributors</a>'
    }).addTo(map);
    // Basemap Satelit
    var satellite = L.tileLayer(
        'https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
            attribution: 'Tiles &copy; <a href="https://www.esri.com/">Esri</a>, Maxar, Earthstar Geographics'
        }
    );
    //ganti basemap
    var baseMaps = {
        "OpenStreetMap": osm,
        "Satelit": satellite
    };
    L.control.layers(baseMaps).addTo(map);
    // tambah marker
    <?php
    mysqli_data_seek($result, 0);
    while ($row = mysqli_fetch_assoc($result)) {
        $nama = addslashes($row['nama_laundry']);
        $lat  = $row['latitude'];
        $lng  = $row['longitude'];
        $noTelp = $row['no_telp'];
        $jamBuka = $row['jam_buka'];
        $kategori = $row['nama_layanan_khusus'];
        $foto = $row["foto"];
        $foto_url = "../laundry/" . $foto;
        // hany koordinat valid yang ditampilkan
        if (
            is_numeric($lat) && is_numeric($lng) &&
            $lat >= -90 && $lat <= 90 &&
            $lng >= -180 && $lng <= 180
        ) {
    ?>
            var marker = L.marker([<?= $lat ?>, <?= $lng ?>]).addTo(map);
            marker.bindPopup(`
        <b><?= $nama ?></b><br><br>
        
        No Telp : <?= $noTelp ?><br>
        Jam Buka : <?= $jamBuka ?><br>
        Nama Layanan Khusus : <?= $kategori ?><br> <br>
        <img src= "../laundry/<?= $foto ?>" width="120" alt="Foto laundry"><br>
        
        
    `);
    <?php
        }
    }
    ?>
</script>

<?php
require '../templates/footer.php';
?>