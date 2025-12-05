<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['ssLoginLaundry'])) {
    header("location: ../landing-page/index.php");
    exit();
}
require '../config.php';
$title = 'Rekap Laundry - GeoLaundry';
require '../templates/header.php';
require '../templates/sidebar.php';
require '../templates/navbar.php';
?>
<div class="container-fluid">
    <div class="card shadow-lg border-0 rounded-4">
        <div class="card-body">
            <h5 class="card-title fw-semibold mb-4">Rekap Data Laundry</h5>
            <!-- Filter Form -->
            <form action="rekap.php" method="GET" target="_blank" class="row g-3 align-items-end mb-4">
                <div class="col-md-4">
                    <label for="kategori" class="form-label">Kategori Laundry</label>
                    <select name="kategori" id="kategori" class="form-select">
                        <option value="">Semua Kategori</option>
                        <?php
                        $queryKategori = mysqli_query($koneksi, "SELECT * FROM layanan_laundry ORDER BY nama_kategori ASC");
                        while ($row = mysqli_fetch_assoc($queryKategori)) {
                            echo "<option value='{$row['id_kategori']}'>{$row['nama_kategori']}</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="kecamatan" class="form-label">Kecamatan</label>
                    <select name="kecamatan" id="kecamatan" class="form-select">
                        <option value="">Semua Kecamatan</option>
                        <?php
                        $queryKec = mysqli_query($koneksi, "SELECT DISTINCT nama_kecamatan FROM laundry ORDER BY nama_kecamatan ASC");
                        while ($row = mysqli_fetch_assoc($queryKec)) {
                            echo "<option value='{$row['nama_kecamatan']}'>{$row['nama_kecamatan']}</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-secondary w-100" title="Cetak Rekap PDF">
                        <i class="ti ti-printer" style="font-size: 1.2rem; margin-right: 6px;"></i>
                        Cetak PDF
                    </button>
                </div>
            </form>
            <!-- Daftar Laundry -->
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body">
                    <h6 class="card-title fw-semibold mb-3">Daftar Laundry</h6>
                    <div class="table-responsive">
                        <table class="datatable table table-hover align-middle text-center">
                            <thead class="table-success">
                                <tr>
                                    <th>No</th>
                                    <th>Nama Laundry</th>
                                    <th>Kategori</th>
                                    <th>No. Telpon</th>
                                    <th>Jam Buka</th>
                                    <th>Kecamatan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $no = 1;
                                $queryLaundry = mysqli_query($koneksi, "
                                    SELECT l.*, k.nama_kategori 
                                    FROM laundry l
                                    INNER JOIN layanan_laundry k ON l.id_kategori = k.id_kategori
                                    ORDER BY l.id_laundry DESC
                                ");
                                while ($laundry = mysqli_fetch_assoc($queryLaundry)) {
                                ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><?= htmlspecialchars($laundry['nama_laundry']) ?></td>
                                        <td><?= htmlspecialchars($laundry['nama_kategori']) ?></td>
                                        <td><?= htmlspecialchars($laundry['no_telp']) ?></td>
                                        <td><?= htmlspecialchars($laundry['jam_buka']) ?></td>
                                        <td><?= htmlspecialchars($laundry['nama_kecamatan']) ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
require '../templates/footer.php';
?>