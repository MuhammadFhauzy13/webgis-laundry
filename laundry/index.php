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
                            <button class="nav-link active" id="data-tab" data-bs-toggle="tab" data-bs-target="#dataLaundry" type="button" role="tab" aria-controls="dataLaundry" aria-selected="true">
                                Data Laundry
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="tambah-tab" data-bs-toggle="tab" data-bs-target="#tambahLaundry" type="button" role="tab" aria-controls="tambahLaundry" aria-selected="false">
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
                                                    <th class=" text-center">No</th>
                                                    <th class=" text-center">Nama Laundry</th>
                                                    <th class=" text-center">Kategori</th>
                                                    <th class=" text-center">No. Telpon</th>
                                                    <th class=" text-center">Jam Buka</th>
                                                    <th class="text-center">Layanan</th>
                                                    <th class=" text-center">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody class="text-center">
                                                <?php
                                                $no = 1;
                                                $queryLaundry = mysqli_query(
                                                    $koneksi,
                                                    "SELECT 
                                                        l.*,
                                                        lk.nama_layanan_khusus,
                                                        GROUP_CONCAT(
                                                            DISTINCT CONCAT(ly.nama_layanan, ' (', ll.harga, ')')
                                                            ORDER BY ly.nama_layanan ASC
                                                            SEPARATOR ', '
                                                        ) AS layanan,
                                                        GROUP_CONCAT(DISTINCT ll.harga ORDER BY ly.nama_layanan ASC SEPARATOR ',') AS harga_list,
                                                        GROUP_CONCAT(DISTINCT ll.id_layanan ORDER BY ly.nama_layanan ASC SEPARATOR ',') AS id_layanan_list
                                                    FROM laundry l
                                                    LEFT JOIN layanan_khusus lk 
                                                        ON l.id_layanan_khusus = lk.id_layanan_khusus
                                                    LEFT JOIN laundry_layanan ll 
                                                        ON l.id_laundry = ll.id_laundry
                                                    LEFT JOIN layanan ly 
                                                        ON ll.id_layanan = ly.id_layanan
                                                    GROUP BY l.id_laundry
                                                    ORDER BY l.id_laundry DESC"
                                                );
                                                while ($laundry = mysqli_fetch_assoc($queryLaundry)) {
                                                    $id_laundry = $laundry['id_laundry'];
                                                    $id_layanan_khusus = $laundry['id_layanan_khusus'];
                                                    $layananText = !empty($laundry['layanan'])
                                                        ? htmlspecialchars($laundry['layanan'])
                                                        : '-';
                                                    $namaKhusus = !empty($laundry['nama_layanan_khusus']) ? htmlspecialchars($laundry['nama_layanan_khusus']) : '-';
                                                ?>
                                                    <tr class="align-middle">
                                                        <td><?= $no++ ?></td>
                                                        <td><?= htmlspecialchars($laundry['nama_laundry']) ?></td>
                                                        <td><?= $namaKhusus ?></td>
                                                        <td><?= htmlspecialchars($laundry['no_telp']) ?></td>
                                                        <td><?= htmlspecialchars($laundry['jam_buka']) ?></td>
                                                        <td><?= htmlspecialchars($layananText) ?></td>
                                                        <td>
                                                            <div class="d-inline-flex gap-1">
                                                                <!-- Tombol Detail -->
                                                                <button type="button" title="Detail Laundry" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#detailModal<?= $id_laundry ?>">
                                                                    <i class="ti ti-eye" style="font-size: 1.1rem;"></i>
                                                                </button>
                                                                <!-- Tombol Edit -->
                                                                <button type="button" title="Edit Laundry" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editModal<?= $id_laundry ?>">
                                                                    <i class="ti ti-edit" style="font-size: 1.1rem;"></i>
                                                                </button>
                                                                <!-- Tombol Hapus -->
                                                                <a href="proses-laundry.php?id=<?= $id_laundry ?>" class="btn btn-sm btn-danger btn-hapus" title="Hapus Laundry">
                                                                    <i class="ti ti-trash" style="font-size: 1.1rem;"></i>
                                                                </a>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <!-- Modal Detail -->
                                                    <div class="modal fade" id="detailModal<?= $id_laundry ?>" tabindex="-1" aria-hidden="true">
                                                        <div class="modal-dialog modal-lg modal-dialog-centered">
                                                            <div class="modal-content border-0 shadow">
                                                                <div class="modal-header text-white" style="background-color: #02C3FE;">
                                                                    <h5 class="modal-title">Detail Laundry</h5>
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
                                                                            <input type="text" class="form-control" value="<?= htmlspecialchars($laundry['nama_layanan_khusus'] ?: '-') ?>" readonly>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <label class="form-label fw-semibold">Layanan</label>
                                                                            <?php
                                                                            $layananRaw = $laundry['layanan'];
                                                                            $layananItems = $layananRaw ? explode(', ', $layananRaw) : [];
                                                                            ?>
                                                                            <div class="border rounded p-2 bg-light">
                                                                                <?php if (!empty($layananItems)) : ?>
                                                                                    <?php foreach ($layananItems as $item) : ?>
                                                                                        <?php
                                                                                        if (preg_match('/^(.*)\((\d+)\)$/', trim($item), $m)) {
                                                                                            $namaL = trim($m[1]);
                                                                                            $hargaL = number_format((int)$m[2], 0, ',', '.');
                                                                                        } else {
                                                                                            $namaL = trim($item);
                                                                                            $hargaL = '0';
                                                                                        }
                                                                                        ?>
                                                                                        <div class="d-flex justify-content-between border-bottom py-1">
                                                                                            <span><?= htmlspecialchars($namaL) ?></span>
                                                                                            <span>Rp <?= $hargaL ?></span>
                                                                                        </div>
                                                                                    <?php endforeach; ?>
                                                                                <?php else : ?>
                                                                                    <small class="text-muted fst-italic">Tidak ada layanan.</small>
                                                                                <?php endif; ?>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6 text-center">
                                                                            <label class="form-label fw-semibold d-block">Foto Laundry</label>
                                                                            <?php
                                                                            $foto = $laundry['foto'] ?: 'assets/no-image.png';
                                                                            ?>
                                                                            <img src="<?= htmlspecialchars($foto) ?>" class="img-thumbnail rounded shadow-sm" width="160" alt="Foto Laundry">
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
                                                                <div class="modal-header" style="background-color:#02C3FE;">
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
                                                                                    $queryLayananKhusus = mysqli_query($koneksi, "SELECT * FROM layanan_khusus");
                                                                                    while ($lk = mysqli_fetch_assoc($queryLayananKhusus)) {
                                                                                        $selected = ($lk['id_layanan_khusus'] == $laundry['id_layanan_khusus']) ? 'selected' : '';
                                                                                        echo "<option value='{$lk['id_layanan_khusus']}' $selected>{$lk['nama_layanan_khusus']}</option>";
                                                                                    }
                                                                                    ?>
                                                                                </select>
                                                                            </div>
                                                                            <div class="col-md-6">
                                                                                <label class="form-label">Layanan</label>
                                                                                <?php
                                                                                $selectedIds   = $laundry['id_layanan_list'] ? explode(',', $laundry['id_layanan_list']) : [];
                                                                                $selectedHarga = $laundry['harga_list'] ? explode(',', $laundry['harga_list']) : [];
                                                                                $hargaMap = [];
                                                                                foreach ($selectedIds as $i => $idLy) {
                                                                                    $hargaMap[(int)$idLy] = isset($selectedHarga[$i]) ? (int)$selectedHarga[$i] : 0;
                                                                                }
                                                                                ?>
                                                                                <div class="border rounded p-2 bg-light" style="max-height:180px; overflow-y:auto;">
                                                                                    <?php
                                                                                    $qLayanan = mysqli_query($koneksi, "SELECT * FROM layanan ORDER BY id_layanan ASC");
                                                                                    while ($ly = mysqli_fetch_assoc($qLayanan)) :
                                                                                        $idL = (int)$ly['id_layanan'];
                                                                                        $checked = in_array($idL, $selectedIds) ? "checked" : "";
                                                                                        $hargaValue = $hargaMap[$idL] ?? "";
                                                                                    ?>
                                                                                        <div class="d-flex align-items-center mb-1">
                                                                                            <input type="checkbox" name="layanan_id[]" value="<?= $idL ?>" class="form-check-input me-2 layanan-check" data-target="harga<?= $id_laundry ?>_<?= $idL ?>" <?= $checked ?>>
                                                                                            <label class="me-2"><?= htmlspecialchars($ly['nama_layanan']) ?></label>
                                                                                            <input type="number" name="layanan_harga[<?= $idL ?>]" id="harga<?= $id_laundry ?>_<?= $idL ?>" class="form-control form-control-sm" placeholder="Harga" style="width:120px;" value="<?= $hargaValue ?>" <?= $checked ? '' : 'readonly' ?>>
                                                                                        </div>
                                                                                    <?php endwhile; ?>
                                                                                </div>
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
                                                    <script>
                                                        document.querySelectorAll('.layanan-check').forEach(cb => {
                                                            cb.addEventListener('change', function() {
                                                                let target = document.getElementById(this.dataset.target);
                                                                if (this.checked) {
                                                                    target.readOnly = false;
                                                                } else {
                                                                    target.readOnly = true;
                                                                }
                                                            });
                                                        });
                                                    </script>
                                                <?php } ?>
                                            </tbody>
                                        </table>
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
                                                <input type="text" name="namaLaundry" class="form-control" required>
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
                                                    while ($lk = mysqli_fetch_assoc($queryLayananKhusus)) {
                                                        echo "<option value='{$lk['id_layanan_khusus']}'>{$lk['nama_layanan_khusus']}</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Layanan</label>
                                                <div class="border rounded p-2 bg-light" style="max-height:180px; overflow-y:auto;">
                                                    <?php
                                                    $qLayanan = mysqli_query($koneksi, "SELECT * FROM layanan ORDER BY id_layanan ASC");
                                                    while ($ly = mysqli_fetch_assoc($qLayanan)) :
                                                        $idL = $ly['id_layanan'];
                                                    ?>
                                                        <div class="d-flex align-items-center mb-1">
                                                            <input type="checkbox" name="layanan_id[]" value="<?= $idL ?>" class="form-check-input me-2 add-layanan-check" data-target="hargaAdd_<?= $idL ?>">
                                                            <label class="me-2"><?= htmlspecialchars($ly['nama_layanan']) ?></label>
                                                            <input type="number" name="layanan_harga[<?= $idL ?>]" id="hargaAdd_<?= $idL ?>" class="form-control form-control-sm" placeholder="Harga" style="width:120px;" disabled>
                                                        </div>
                                                    <?php endwhile; ?>
                                                </div>
                                            </div>
                                            <div class="col-md-6 text-center">
                                                <label class="form-label d-block">Foto Laundry</label>
                                                <img src="<?= $main_url ?>laundry/uploads/Geolaundry-removebg-preview.png" class="img-thumbnail mb-2 rounded" id="previewLaundry" width="120">
                                                <input type="file" name="fotoLaundry" id="inputLaundry" class="form-control form-control-sm" accept="image/*" onchange="imgLaundryView()">
                                            </div>
                                        </div>
                                        <div class="mt-3 d-flex gap-2">
                                            <button type="button" class="btn btn-outline-danger" onclick="resetLaundry()">Reset</button>
                                            <button type="submit" name="simpan" class="btn btn-outline-primary">Simpan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <script>
                            document.querySelectorAll('.add-layanan-check').forEach(cb => {
                                cb.addEventListener('change', function() {
                                    const target = document.getElementById(this.dataset.target);
                                    target.disabled = !this.checked;
                                    if (!this.checked) target.value = "";
                                });
                            });
                        </script>

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