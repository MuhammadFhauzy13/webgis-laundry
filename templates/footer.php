<!-- Modal Logout -->
<div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="logoutModalLabel">Konfirmasi Keluar</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Apakah anda yakin mau keluar ?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <a href="<?= $main_url ?>autentikasi/logout.php" class="btn btn-danger">Keluar</a>
            </div>
        </div>
    </div>
</div>
</div>
</div>

<script src="<?= $main_url ?>src/assets/libs/jquery/dist/jquery.min.js"></script>
<script src="<?= $main_url ?>src/assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= $main_url ?>src/assets/js/sidebarmenu.js"></script>
<script src="<?= $main_url ?>src/assets/js/app.min.js"></script>
<script src="<?= $main_url ?>src/assets/libs/simplebar/dist/simplebar.js"></script>

<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
<!-- sweet alert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://unpkg.com/leaflet.fullscreen@1.6.0/Control.FullScreen.js"></script>
<script>
    $(".datatable").DataTable();
</script>
</body>

</html>