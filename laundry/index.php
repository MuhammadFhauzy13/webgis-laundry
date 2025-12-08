<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['ssLoginLaundry'])) {
    header("location: ../landing-page/index.php");
    exit();
}
require '../config.php';
$title = 'Laundry - Geolaundry';
require '../templates/header.php';
require '../templates/sidebar.php';
require '../templates/navbar.php';
?>
<div class="container-fluid">
    <div class="card shadow-lg border-0 rounded-4">
        <div class="card-body">
            <h5 class="card-title fw-semibold mb-4">Manajemen Laundry</h5>
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-body">
                    <!-- Tabs -->
                    <ul class="nav nav-tabs" id="laundryTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="data-tab" data-bs-toggle="tab" data-bs-target="#dataLaundry"
                                type="button" role="tab" aria-controls="dataLaundry" aria-selected="true">
                                Data Laundry
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="tambah-tab" data-bs-toggle="tab" data-bs-target="#tambahLaundry"
                                type="button" role="tab" aria-controls="tambahLaundry" aria-selected="false">
                                Tambah Laundry
                            </button>
                        </li>
                    </ul>
                    <!-- Tab Content -->
                    <div class="tab-content mt-3" id="laundryTabsContent">
                        <!-- Data Laundry -->
                        <div class="tab-pane fade show active" id="dataLaundry" role="tabpanel" aria-labelledby="data-tab">
                            <div class="card shadow-sm border-0 rounded-3">
                                <div class="card-body">
                                    <h6 class="card-title fw-semibold mb-3">Daftar Laundry</h6>
                                    <div class="table-responsive">
                                        <table class="datatable table table-hover align-middle text-center">
                                            <thead class="table-success text-center">
                                                <tr>
                                                    <th>No</th>
                                                    <th>Nama Laundry</th>
                                                    <th>Kategori</th>
                                                    <th>No. Telpon</th>
                                                    <th>Jam Buka</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody class="text-center">
                                                <?php
                                                $no = 1;
                                                $queryLaundry = mysqli_query(
                                                    $koneksi,
                                                    "SELECT 
                                                        laundry.*, 
                                                        layanan_khusus.nama_layanan_khusus,
                                                        layanan.nama_layanan,
                                                        layanan.harga
                                                    FROM laundry
                                                    INNER JOIN layanan_khusus 
                                                        ON laundry.id_layanan_khusus = layanan_khusus.id_layanan_khusus
                                                    INNER JOIN layanan
                                                        ON laundry.id_layanan = layanan.id_layanan
                                                    ORDER BY laundry.id_laundry DESC;"
                                                );
                                                while ($laundry = mysqli_fetch_assoc($queryLaundry)) {
                                                    $id_laundry = $laundry['id_laundry'];
                                                    $id_layanan = $laundry['id_layanan'];
                                                    $id_layanan_khusus = $laundry['id_layanan_khusus'];
                                                ?>
                                                    <tr class="align-middle">
                                                        <td><?= $no++ ?></td>
                                                        <td><?= htmlspecialchars($laundry['nama_laundry']) ?></td>
                                                        <td><?= $laundry['nama_layanan_khusus'] ?></td>
                                                        <td><?= $laundry['no_telp'] ?></td>
                                                        <td><?= $laundry['jam_buka'] ?></td>
                                                        <td>
                                                            <div class="d-inline-flex gap-1">
                                                                <!-- tombol layanan -->
                                                                <button type="button" title="Kelola Layanan" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#layananModal">
                                                                    <i class="ti ti-settings-2" style="font-size: 1.1rem;"></i>
                                                                </button>
                                                                <!-- Tombol Detail -->
                                                                <button type="button" title="Lihat Detail" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#detailModal<?= $id_laundry ?>">
                                                                    <i class="ti ti-eye" style="font-size: 1.1rem;"></i>
                                                                </button>
                                                                <!-- Tombol Edit -->
                                                                <button type="button" title="Edit Laundry" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editModal<?= $id_laundry ?>">
                                                                    <i class="ti ti-edit" style="font-size: 1.1rem;"></i>
                                                                </button>
                                                                <!-- Tombol Hapus -->
                                                                <a href="proses-laundry.php?id=<?= $id_laundry ?>"
                                                                    class="btn btn-sm btn-danger btn-hapus"
                                                                    title="Hapus Laundry">
                                                                    <i class="ti ti-trash" style="font-size: 1.1rem;"></i>
                                                                </a>

                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <!-- Modal Detail -->
                                                    <div class="modal fade" id="detailModal<?= $id_laundry ?>" tabindex="-1" aria-labelledby="detailModalLabel<?= $id_laundry ?>" aria-hidden="true">
                                                        <div class="modal-dialog modal-lg modal-dialog-centered">
                                                            <div class="modal-content border-0 shadow">
                                                                <div class="modal-header text-white" style="background-color: #02C3FE;">
                                                                    <h5 class="modal-title" id="detailModalLabel<?= $id_laundry ?>">Detail Laundry</h5>
                                                                    <button type="button" class="btn-close btn-close-dark" data-bs-dismiss="modal"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <div class="row g-3">
                                                                        <div class="col-md-6">
                                                                            <label class="form-label fw-semibold">Nama Laundry</label>
                                                                            <input type="text" class="form-control" value="<?= htmlspecialchars($laundry['nama_laundry']) ?>" readonly>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <label class="form-label fw-semibold">Nama Kecamatan</label>
                                                                            <input type="text" class="form-control" value="<?= htmlspecialchars($laundry['nama_kecamatan']) ?>" readonly>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <label class="form-label fw-semibold">Longitude</label>
                                                                            <input type="text" class="form-control" value="<?= htmlspecialchars($laundry['longitude']) ?>" readonly>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <label class="form-label fw-semibold">No. Telepon</label>
                                                                            <input type="text" class="form-control" value="<?= htmlspecialchars($laundry['no_telp']) ?>" readonly>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <label class="form-label fw-semibold">Latitude</label>
                                                                            <input type="text" class="form-control" value="<?= htmlspecialchars($laundry['latitude']) ?>" readonly>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <label class="form-label fw-semibold">Jam Buka</label>
                                                                            <input type="text" class="form-control" value="<?= htmlspecialchars($laundry['jam_buka']) ?>" readonly>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <label class="form-label fw-semibold">Alamat</label>
                                                                            <textarea class="form-control" rows="2" readonly><?= htmlspecialchars($laundry['alamat']) ?></textarea>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <label class="form-label fw-semibold">Profile</label>
                                                                            <textarea class="form-control" rows="2" readonly><?= htmlspecialchars($laundry['profile']) ?></textarea>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <label class="form-label fw-semibold">Layanan Khusus</label>
                                                                            <input type="text" class="form-control" value="<?= htmlspecialchars($laundry['nama_layanan_khusus']) ?>" readonly>
                                                                        </div>
                                                                        <?php
                                                                        $raw = $laundry['harga'];                 // "15.000"
                                                                        $clean = str_replace('.', '', $raw);      // "15000"
                                                                        $hargaFormat = number_format($clean, 0, ',', '.'); // "15.000"
                                                                        ?>
                                                                        <div class="col-md-6">
                                                                            <label class="form-label fw-semibold ">Layanan</label>
                                                                            <div class="p-2 border rounded bg-light">
                                                                                <div class="d-flex justify-content-between ">
                                                                                    <span><?= htmlspecialchars($laundry['nama_layanan']) ?></span>
                                                                                    <span>Rp <?= $hargaFormat ?></span>
                                                                                </div>
                                                                            </div>
                                                                        </div>


                                                                        <div class="col-md-6 text-center">
                                                                            <label class="form-label fw-semibold d-block">Foto Laundry</label>
                                                                            <img src="<?= htmlspecialchars($laundry['foto']) ?>"
                                                                                class="img-thumbnail rounded"
                                                                                width="150"
                                                                                alt="Foto Laundry">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer d-flex justify-content-end">
                                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- Modal Edit -->
                                                    <div class="modal fade" id="editModal<?= $id_laundry ?>" tabindex="-1" aria-labelledby="editModalLabel<?= $id_laundry ?>" aria-hidden="true">
                                                        <div class="modal-dialog modal-lg modal-dialog-centered">
                                                            <div class="modal-content border-0 shadow">
                                                                <div class="modal-header" style="background-color:#02C3FE ;">
                                                                    <h5 class="modal-title" id="editModalLabel<?= $id_laundry ?>">Edit Laundry</h5>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                                </div>
                                                                <form action="proses-laundry.php" method="POST" enctype="multipart/form-data">
                                                                    <div class="modal-body">
                                                                        <input type="hidden" name="id_laundry" value="<?= $id_laundry ?>">
                                                                        <div class="row g-3">
                                                                            <div class="col-md-6">
                                                                                <label class="form-label">Nama Laundry</label>
                                                                                <input type="text" name="namaLaundry" class="form-control" value="<?= htmlspecialchars($laundry['nama_laundry']) ?>" required>
                                                                            </div>
                                                                            <div class="col-md-6">
                                                                                <label class="form-label">Nama Kecamatan</label>
                                                                                <input type="text" name="namaKecamatan" class="form-control" value="<?= htmlspecialchars($laundry['nama_kecamatan']) ?>" required>
                                                                            </div>
                                                                            <div class="col-md-6">
                                                                                <label class="form-label">Longitude</label>
                                                                                <input type="number" step="any" name="longitude" class="form-control" value="<?= htmlspecialchars($laundry['longitude']) ?>" required>
                                                                            </div>
                                                                            <div class="col-md-6">
                                                                                <label class="form-label">No. Telepon</label>
                                                                                <input type="text" name="telpLaundry" class="form-control" value="<?= htmlspecialchars($laundry['no_telp']) ?>" required>
                                                                            </div>
                                                                            <div class="col-md-6">
                                                                                <label class="form-label">Latitude</label>
                                                                                <input type="number" step="any" name="latitude" class="form-control" value="<?= htmlspecialchars($laundry['latitude']) ?>" required>
                                                                            </div>
                                                                            <div class="col-md-6">
                                                                                <label class="form-label">Jam Buka</label>
                                                                                <input type="text" name="jamBuka" class="form-control" value="<?= htmlspecialchars($laundry['jam_buka']) ?>" required>
                                                                            </div>
                                                                            <div class="col-md-6">
                                                                                <label class="form-label">Alamat</label>
                                                                                <textarea name="alamatLaundry" class="form-control" rows="2" required><?= htmlspecialchars($laundry['alamat']) ?></textarea>
                                                                            </div>
                                                                            <div class="col-md-6">
                                                                                <label class="form-label">Profile</label>
                                                                                <textarea name="profile" class="form-control" rows="2" required><?= htmlspecialchars($laundry['profile']) ?></textarea>
                                                                            </div>
                                                                            <div class="col-md-6">
                                                                                <label class="form-label">Layanan Khusus</label>
                                                                                <select name="id_layanan_khusus" class="form-control" required>
                                                                                    <?php
                                                                                    $queryLayanan = mysqli_query($koneksi, "SELECT * FROM layanan_khusus");
                                                                                    while ($layanan = mysqli_fetch_assoc($queryLayanan)) {
                                                                                        $selected = ($layanan['id_layanan_khusus'] == $laundry['id_layanan_khusus']) ? 'selected' : '';
                                                                                        echo "<option value='{$layanan['id_layanan_khusus']}' $selected>{$layanan['nama_layanan_khusus']}</option>";
                                                                                    }
                                                                                    ?>
                                                                                </select>
                                                                            </div>
                                                                            <div class="col-md-6">
                                                                                <label class="form-label">Layanan</label>
                                                                                <select name="id_layanan" class="form-control" required>
                                                                                    <?php
                                                                                    $queryLayanan = mysqli_query($koneksi, "SELECT * FROM layanan");
                                                                                    while ($layanan = mysqli_fetch_assoc($queryLayanan)) {
                                                                                        $selected = ($layanan['id_layanan'] == $laundry['id_layanan']) ? 'selected' : '';
                                                                                        echo "<option value='{$layanan['id_layanan']}' $selected>{$layanan['nama_layanan']}</option>";
                                                                                    }
                                                                                    ?>
                                                                                </select>
                                                                            </div>
                                                                            <div class="col-md-6 text-center">
                                                                                <label class="form-label d-block">Foto Laundry</label>
                                                                                <img src="<?= htmlspecialchars($laundry['foto']) ?>" class="img-thumbnail mb-2 rounded" id="previewEdit<?= $id_laundry ?>" width="120">
                                                                                <input type="file" name="fotoLaundry" class="form-control form-control-sm" onchange="previewEditLaundry('previewEdit<?= $id_laundry ?>', this)">
                                                                                <input type="hidden" name="fotoLama" value="<?= htmlspecialchars($laundry['foto']) ?>">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer d-flex justify-content-end">
                                                                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Batal</button>
                                                                        <button type="submit" name="update" class="btn btn-secondary">Update</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                        <!-- Modal Layanan -->
                                        <?php
                                        // Ambil data layanan sekali saja
                                        $layananData = [];
                                        $queryL = mysqli_query($koneksi, "SELECT * FROM layanan ORDER BY id_layanan DESC");
                                        while ($row = mysqli_fetch_assoc($queryL)) {
                                            $layananData[] = $row;
                                        }
                                        ?>

                                        <div class="modal fade" id="layananModal" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                                <div class="modal-content border-0 shadow">

                                                    <div class="modal-header bg-secondary text-white">
                                                        <h5 class="modal-title">Manajemen Layanan</h5>
                                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                    </div>

                                                    <div class="modal-body">

                                                        <!-- Tabs -->
                                                        <ul class="nav nav-tabs mb-3" id="layananTab" role="tablist">
                                                            <li class="nav-item" role="presentation">
                                                                <button class="nav-link active"
                                                                    id="list-tab"
                                                                    data-bs-toggle="tab"
                                                                    data-bs-target="#listLayanan"
                                                                    type="button" role="tab">
                                                                    Daftar Layanan
                                                                </button>
                                                            </li>

                                                            <li class="nav-item" role="presentation">
                                                                <button class="nav-link"
                                                                    id="tambah-tab"
                                                                    data-bs-toggle="tab"
                                                                    data-bs-target="#tambahLayanan"
                                                                    type="button" role="tab">
                                                                    Tambah Layanan
                                                                </button>
                                                            </li>
                                                        </ul>

                                                        <div class="tab-content">

                                                            <!-- TAB 1: LIST LAYANAN -->
                                                            <div class="tab-pane fade show active" id="listLayanan" role="tabpanel">
                                                                <div class="table-responsive">
                                                                    <table class="datatable table table-bordered text-center align-middle">
                                                                        <thead class="table-success">
                                                                            <tr>
                                                                                <th>No</th>
                                                                                <th>Nama Layanan</th>
                                                                                <th>Harga / Kg</th>
                                                                                <th>Aksi</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            <?php $no = 1;
                                                                            foreach ($layananData as $layanan) : ?>
                                                                                <tr>
                                                                                    <td><?= $no++ ?></td>
                                                                                    <td><?= htmlspecialchars($layanan['nama_layanan']) ?></td>
                                                                                    <td><?= number_format($layanan['harga'], 0, ',', '.') ?></td>
                                                                                    <td>
                                                                                        <div class="d-inline-flex gap-1">

                                                                                            <!-- Tombol Edit -->
                                                                                            <button class="btn btn-sm btn-warning"
                                                                                                data-bs-toggle="modal"
                                                                                                data-bs-target="#editLayananModal<?= $layanan['id_layanan'] ?>">
                                                                                                <i class="ti ti-edit"></i>
                                                                                            </button>

                                                                                            <!-- Tombol Hapus -->
                                                                                            <a href="proses-layanan.php?hapus=<?= $layanan['id_layanan'] ?>"
                                                                                                class="btn btn-sm btn-danger btn-hapus">
                                                                                                <i class="ti ti-trash"></i>
                                                                                            </a>

                                                                                        </div>
                                                                                    </td>
                                                                                </tr>
                                                                            <?php endforeach; ?>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>

                                                            <!-- TAB 2: TAMBAH LAYANAN -->
                                                            <div class="tab-pane fade" id="tambahLayanan" role="tabpanel">
                                                                <form action="proses-layanan.php" method="POST">
                                                                    <div class="mb-3">
                                                                        <label>Nama Layanan</label>
                                                                        <input type="text" name="nama_layanan" class="form-control" required>
                                                                    </div>

                                                                    <div class="mb-3">
                                                                        <label>Harga / Kg</label>
                                                                        <input type="number" name="harga" class="form-control" min="100" required>
                                                                    </div>

                                                                    <button type="submit" name="tambah" class="btn btn-secondary mt-2">
                                                                        Tambah
                                                                    </button>
                                                                </form>
                                                            </div>

                                                        </div>

                                                        <!-- Modal Edit Layanan -->
                                                        <?php foreach ($layananData as $layanan) : ?>
                                                            <div class="modal fade" id="editLayananModal<?= $layanan['id_layanan'] ?>" tabindex="-1">
                                                                <div class="modal-dialog modal-dialog-centered">
                                                                    <div class="modal-content border-0 shadow">

                                                                        <div class="modal-header bg-warning">
                                                                            <h5 class="modal-title">Edit Layanan</h5>
                                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                                        </div>

                                                                        <form action="proses-layanan.php" method="POST">
                                                                            <div class="modal-body">

                                                                                <input type="hidden" name="id_layanan" value="<?= $layanan['id_layanan'] ?>">

                                                                                <div class="mb-3">
                                                                                    <label>Nama Layanan</label>
                                                                                    <input type="text" name="nama_layanan" class="form-control"
                                                                                        value="<?= htmlspecialchars($layanan['nama_layanan']) ?>" required>
                                                                                </div>

                                                                                <div class="mb-3">
                                                                                    <label>Harga / Kg</label>
                                                                                    <input type="number" name="harga" class="form-control"
                                                                                        value="<?= $layanan['harga'] ?>" required>
                                                                                </div>

                                                                            </div>

                                                                            <div class="modal-footer">
                                                                                <button type="submit" name="update" class="btn btn-warning">Update</button>
                                                                            </div>
                                                                        </form>

                                                                    </div>
                                                                </div>
                                                            </div>
                                                        <?php endforeach; ?>

                                                    </div>

                                                    <div class="modal-footer">
                                                        <button class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tambah Laundry -->
                        <div class="tab-pane fade" id="tambahLaundry" role="tabpanel" aria-labelledby="tambah-tab">
                            <div class="card shadow-sm border-0 rounded-3">
                                <div class="card-body">
                                    <h6 class="card-title fw-semibold mb-3">Tambah Laundry</h6>
                                    <form action="proses-laundry.php" method="POST" enctype="multipart/form-data" autocomplete="off">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label">Nama Laundry</label>
                                                <input type="text" name="namaLaundry" class="form-control" id="addNamaLaundry" required>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Nama Kecamatan</label>
                                                <input type="text" name="namaKecamatan" class="form-control" required>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Longitude</label>
                                                <input type="number" step="any" name="longitude" class="form-control" required>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">No. Telepon</label>
                                                <input type="text" name="telpLaundry" class="form-control" required>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Latitude</label>
                                                <input type="number" step="any" name="latitude" class="form-control" required>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Jam Buka</label>
                                                <input type="text" name="jamBuka" class="form-control" required>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Alamat</label>
                                                <textarea name="alamatLaundry" class="form-control" rows="2" required></textarea>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Profile</label>
                                                <textarea name="profile" class="form-control" rows="2" required></textarea>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Layanan Khusus</label>
                                                <select name="id_layanan_khusus" class="form-control" required>
                                                    <option value="">-- Pilih Layanan Khusus --</option>
                                                    <?php
                                                    $queryLayananKhusus = mysqli_query($koneksi, "SELECT * FROM layanan_khusus");
                                                    while ($layanan = mysqli_fetch_assoc($queryLayananKhusus)) {
                                                        echo "<option value='{$layanan['id_layanan_khusus']}'>{$layanan['nama_layanan_khusus']}</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Layanan</label>
                                                <select name="id_layanan" class="form-control" required>
                                                    <option value="">-- Pilih Layanan --</option>
                                                    <?php
                                                    $queryLayanan = mysqli_query($koneksi, "SELECT * FROM layanan");
                                                    while ($layanan = mysqli_fetch_assoc($queryLayanan)) {
                                                        echo "<option value='{$layanan['id_layanan']}'>{$layanan['nama_layanan']}</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="col-md-6 text-center">
                                                <label class="form-label d-block">Foto Laundry</label>
                                                <!-- Gambar Preview -->
                                                <img src="<?= $main_url ?>laundry/uploads/Geolaundry-removebg-preview.png" class="img-thumbnail mb-2 rounded" id="previewLaundry" width="120px">
                                                <!-- Input File -->
                                                <input type="file" name="fotoLaundry" class="form-control form-control-sm" id="inputLaundry" accept="image/*" onchange="imgLaundryView()">
                                            </div>
                                        </div>
                                        <div class="mt-3 d-flex gap-2">
                                            <button type="reset" class="btn btn-outline-danger" onclick="resetLaundry()">Reset</button>
                                            <button type="submit" name="simpan" class="btn btn-outline-primary">Simpan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div> <!-- end tab content -->
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Ambil semua modal edit laundry
        const modals = document.querySelectorAll('.modal.fade[id^="editModal"]');
        modals.forEach((modal) => {
            const fileInput = modal.querySelector('input[type="file"]');
            const previewImg = modal.querySelector('img[id^="previewEdit"]');
            const fotoLamaInput = modal.querySelector('input[name="fotoLama"]');
            if (!fileInput || !previewImg || !fotoLamaInput) return;
            // Tetap pakai fungsi previewEditLaundry
            fileInput.addEventListener('change', function() {
                previewEditLaundry(previewImg.id, this);
            });
            // Reset preview saat modal ditutup
            modal.addEventListener('hidden.bs.modal', () => {
                previewImg.src = fotoLamaInput.value; // kembali ke foto lama
                fileInput.value = ''; // kosongkan input file
            });
        });
    });

    function imgLaundryView() {
        const input = document.getElementById('inputLaundry');
        const preview = document.getElementById('previewLaundry');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
            };
            reader.readAsDataURL(input.files[0]);
        } else {
            // jika user batal pilih gambar, kembalikan ke default
            preview.src = '<?= $main_url ?>src/assets/images/logos/Geolaundry-removebg-preview.png';
        }
    }

    function resetLaundry() {
        // Reset preview ke default
        const preview = document.getElementById('previewLaundry');
        if (preview) {
            preview.src = '<?= $main_url ?>laundry/uploads/Geolaundry-removebg-preview.png';
        }
        // Reset input file
        const input = document.getElementById('inputLaundry');
        if (input) {
            input.value = '';
        }
    }

    function previewEditLaundry(id, input) {
        if (input.files && input.files[0]) {
            let preview = document.getElementById(id);
            let reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Tangkap semua tombol hapus
        const hapusButtons = document.querySelectorAll('.btn-hapus');
        hapusButtons.forEach(function(button) {
            button.addEventListener('click', function(e) {
                e.preventDefault(); // Jangan langsung menuju link
                const href = this.getAttribute('href'); // Ambil URL hapus.php?id=...
                Swal.fire({
                    title: 'Yakin ingin menghapus?',
                    text: "Data laundry akan dihapus permanen!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Kalau user klik "Ya"
                        window.location.href = href;
                    }
                });
            });
        });
    });
</script>
<?php
if (isset($_GET['msg'])) {
    $msg = $_GET['msg'];
    // Semua jenis pesan dijadikan satu tempat
    $alerts = [
        'add_success'    => ['success', 'Berhasil!', 'Data laundry berhasil ditambahkan!', true],
        'add_error'      => ['error', 'Gagal!', 'Terjadi kesalahan saat menyimpan data!'],
        'update_success' => ['success', 'Berhasil!', 'Data laundry berhasil diupdate!', true],
        'update_error'   => ['error', 'Gagal!', 'Terjadi kesalahan saat mengupdate data!'],
        'delete_success' => ['success', 'Berhasil!', 'Data laundry berhasil dihapus!', true],
        'delete_error'   => ['error', 'Gagal!', 'Terjadi kesalahan saat menghapus data!'],
        'query_fail'     => ['warning', 'Kesalahan Sistem!', 'Query SQL gagal dijalankan!'],
        'invalid_number' => ['error', 'Input Tidak Valid!', 'Latitude dan Longitude harus berupa angka!'],
        'invalid_range'  => ['error', 'Koordinat Tidak Valid!', 'Latitude harus antara -90 s.d. 90, dan Longitude antara -180 s.d. 180!']
    ];
    // Kalau kode msg ditemukan di daftar
    if (array_key_exists($msg, $alerts)) {
        [$icon, $title, $text, $autoClose] = $alerts[$msg] + [null, null, null, false];
?>
        <script>
            Swal.fire({
                icon: '<?= $icon ?>',
                title: '<?= $title ?>',
                text: '<?= $text ?>',
                showConfirmButton: <?= $autoClose ? 'false' : 'true' ?>,
                timer: <?= $autoClose ? 2000 : 'undefined' ?>
            });
            // Bersihkan URL biar ?msg=... hilang setelah tampil
            window.history.replaceState(null, null, window.location.pathname);
        </script>
<?php
    }
}
?>

<?php
require '../templates/footer.php';
?>