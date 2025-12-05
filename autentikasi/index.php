<?php
session_start();
require '../config.php';
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - Geolaundry</title>
    <link rel="shortcut icon" type="image/png" href="<?= $main_url ?>src/assets/images/logos/Geolaundry-removebg-preview.png" />
    <link rel="stylesheet" href="<?= $main_url ?>src/assets/css/styles.min.css" />
    <!-- sweet alert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<style>
    .btn-sidebar {
        background-color: #02C3FE;
        color: #fff;
        border: 2px solid #02C3FE;
        transition: all 0.3s ease;
    }

    .btn-sidebar:hover,
    .btn-sidebar:focus,
    .btn-sidebar:active {
        background-color: #028FD9;
        color: #fff;
        border-color: #028FD9;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        transform: translateY(-3px);
    }
</style>

<body>
    <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6"
        data-sidebartype="full" data-sidebar-position="fixed" data-header-position="fixed">
        <div class="position-relative overflow-hidden radial-gradient min-vh-100 d-flex align-items-center justify-content-center">
            <div class="d-flex align-items-center justify-content-center w-100">
                <div class="row justify-content-center w-100">
                    <div class="col-md-6 col-lg-5 col-xl-4">
                        <div class="card mb-0" style="max-width: 400px; margin: auto;">
                            <div class="card-body p-4">
                                <a href="#" class="text-center d-block py-2 w-100">
                                    <img src="<?= $main_url ?>src/assets/images/logos/Geolaundry-removebg-preview.png" width="110" alt="">
                                </a>
                                <p class="text-center mb-4">“Temukan laundry terbaik di sekitarmu”</p>
                                <form action="proses-login.php" method="post">
                                    <div class="mb-3">
                                        <label for="username" class="form-label">Username</label>
                                        <input type="text" class="form-control" id="username" name="username" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="password" class="form-label">Password</label>
                                        <input type="password" class="form-control" id="password" name="password" required>
                                    </div>
                                    <button type="submit" class="btn btn-sidebar w-100 py-2 rounded-2" name="login">Login</button>
                                    <div class="d-flex align-items-center justify-content-center mt-3">
                                        <p class="fs-4 mb-0 fw-bold">Lihat map ?</p>
                                        <a class="text-primary fw-bold ms-2" href="<?= $main_url ?>landing-page/index.php">Kembali</a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="<?= $main_url ?>src/assets/libs/jquery/dist/jquery.min.js"></script>
    <script src="<?= $main_url ?>src/assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>

    <?php if (isset($_SESSION['loginError'])): ?>
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Login Gagal',
                text: '<?= $_SESSION['loginError']; ?>'
            });
        </script>
        <?php unset($_SESSION['loginError']); ?>
    <?php endif; ?>
</body>

</html>