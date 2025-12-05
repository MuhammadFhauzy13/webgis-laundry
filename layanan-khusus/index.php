<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['ssLoginLaundry'])) {
    header("location: ../landing-page/index.php");
    exit();
}
require '../config.php';
$title = 'Ketegori - Geolaundry';

require '../templates/header.php';
require '../templates/sidebar.php';
require '../templates/navbar.php';

?>
<div class="container-fluid">
    <div class="card shadow-lg border-0 rounded-4">
        <div class="card-body">
            <h5 class="card-title fw-semibold mb-4">Manajemen Layanan</h5>
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-body">
                    <!-- Tabs -->
                    <ul class="nav nav-tabs" id="kategoriTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="data-kategori-tab" data-bs-toggle="tab" data-bs-target="#dataKategori"
                                type="button" role="tab" aria-controls="dataKategori" aria-selected="true">
                                Data Layanan
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="tambah-kategori-tab" data-bs-toggle="tab" data-bs-target="#tambahKategori"
                                type="button" role="tab" aria-controls="tambahKategori" aria-selected="false">
                                Tambah Layanan
                            </button>
                        </li>
                    </ul>
                    <!-- Tab Content -->
                    <div class="tab-content mt-3" id="kategoriTabsContent">
                        <!-- Data Kategori -->
                        <div class="tab-pane fade show active" id="dataKategori" role="tabpanel" aria-labelledby="data-kategori-tab">
                            <div class="card shadow-sm border-0 rounded-3">
                                <div class="card-body">
                                    <h6 class="card-title fw-semibold mb-3">Daftar Layanan Laundry</h6>
                                    <table class="datatable table table-hover align-middle table-responsive">
                                        <thead class="table-success text-center">
                                            <tr>
                                                <th>No</th>
                                                <th>Nama Layanan</th>
                                                <th>Deskripsi</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-center">
                                            <?php
                                            $no = 1;
                                            $queryLayanan = mysqli_query($koneksi, "SELECT * FROM layanan_laundry");
                                            while ($layanan = mysqli_fetch_assoc($queryLayanan)) {
                                                $id_layanan = $layanan['id_kategori'];
                                                $nama_layanan = $layanan['nama_kategori'];
                                                $deskripsi = $layanan['deskripsi'];
                                            ?>
                                                <tr>
                                                    <td><?= $no++ ?></td>
                                                    <td><?= htmlspecialchars($nama_layanan) ?></td>
                                                    <td><?= htmlspecialchars($deskripsi) ?></td>
                                                    <td>
                                                        <!-- Tombol Edit -->
                                                        <button type="button" class="btn btn-sm btn-warning" title="Edit Kategori" data-bs-toggle="modal" data-bs-target="#editModal<?= $id_layanan ?>">
                                                            <i class="ti ti-edit" style="font-size: 1.1rem;"></i>
                                                        </button>
                                                        <!-- Tombol Hapus -->
                                                        <a href="proses-layanan.php?id=<?= $id_layanan ?>"
                                                            class="btn btn-sm btn-danger btn-hapus"
                                                            title="Hapus Kategori">
                                                            <i class="ti ti-trash" style="font-size: 1.1rem;"></i>
                                                        </a>

                                                    </td>
                                                </tr>
                                                <!-- Modal Edit -->
                                                <div class="modal fade" id="editModal<?= $id_layanan ?>" tabindex="-1"
                                                    aria-labelledby="editModalLabel<?= $id_layanan ?>" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered">
                                                        <div class="modal-content border-0 rounded-3 shadow">
                                                            <div class="modal-header bg-opacity-75" style="background-color:#02C3FE;">
                                                                <h5 class="modal-title fw-semibold" id="editModalLabel<?= $id_layanan ?>">Edit Kategori</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <form action="proses-layanan.php" method="post" autocomplete="off">
                                                                <div class="modal-body">
                                                                    <!-- ID -->
                                                                    <input type="hidden" name="id_kategori" value="<?= $id_layanan ?>">
                                                                    <div class="mb-3">
                                                                        <label for="kategori<?= $id_layanan ?>" class="form-label fw-semibold">Nama Kategori</label>
                                                                        <input type="text" class="form-control" id="kategori<?= $id_layanan ?>"
                                                                            name="kategori" value="<?= htmlspecialchars($nama_layanan) ?>" required>
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label for="deskripsi<?= $id_layanan ?>" class="form-label fw-semibold">Deskripsi</label>
                                                                        <textarea class="form-control" required id="deskripsi<?= $id_layanan ?>" name="deskripsi"
                                                                            rows="3"><?= htmlspecialchars($deskripsi) ?></textarea>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-danger border" data-bs-dismiss="modal">Batal</button>
                                                                    <button type="submit" name="update" class="btn btn-secondary text-white fw-semibold">Update</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <!-- Tambah Layanan -->
                        <div class="tab-pane fade" id="tambahKategori" role="tabpanel" aria-labelledby="tambah-kategori-tab">
                            <div class="card shadow-sm border-0 rounded-3">
                                <div class="card-body">
                                    <h6 class="card-title fw-semibold mb-3">Tambah Layanan Laundry</h6>
                                    <form action="proses-layanan.php" method="post" autocomplete="off">
                                        <div class="mb-3">
                                            <label for="kategori" class="form-label">Nama Layanan</label>
                                            <input type="text" class="form-control" id="kategori" name="kategori" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="deskripsi" class="form-label">Deskripsi</label>
                                            <textarea class="form-control" id="deskripsi" name="deskripsi" required></textarea>
                                        </div>
                                        <button type="submit" name="simpan" class="btn btn-outline-primary">Simpan</button>
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

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Ambil semua tombol dengan class .btn-hapus
        const tombolHapus = document.querySelectorAll('.btn-hapus');

        tombolHapus.forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault(); // cegah link langsung berjalan
                const href = this.getAttribute('href');

                Swal.fire({
                    title: 'Yakin ingin menghapus layanan ini?',
                    text: 'Data yang dihapus tidak dapat dikembalikan!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // arahkan ke link hapus jika user menekan "Ya"
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

    // Daftar semua pesan untuk kategori
    $alerts = [
        'add_success'    => ['success', 'Berhasil!', 'Layanan laundry berhasil ditambahkan!', true],
        'add_error'      => ['error', 'Gagal!', 'Terjadi kesalahan saat menyimpan data!'],
        'update_success' => ['success', 'Berhasil!', 'Layanan laundry berhasil diupdate!', true],
        'update_error'   => ['error', 'Gagal!', 'Terjadi kesalahan saat mengupdate data!'],
        'delete_success' => ['success', 'Berhasil!', 'Layanan laundry berhasil dihapus!', true],
        'delete_error'   => ['error', 'Gagal!', 'Terjadi kesalahan saat menghapus data!'],
        'query_fail'     => ['warning', 'Kesalahan Sistem!', 'Query SQL gagal dijalankan!'],
        'delete_error_fk' => ['warning', 'Gagal Hapus!', 'Kategori ini masih memiliki data laundry terkait. Hapus atau pindahkan data terlebih dahulu!']
    ];

    // Jika kode msg ada di daftar
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
            // Hapus parameter dari URL setelah alert muncul
            window.history.replaceState(null, null, window.location.pathname);
        </script>
<?php
    }
}
?>


<?php
require '../templates/footer.php';
?>