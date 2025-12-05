<!--  Main wrapper -->
<div class="body-wrapper">
    <!--  Header Start -->
    <header class="app-header">
        <nav class="navbar navbar-expand-lg navbar-light">
            <ul class="navbar-nav">
                <li class="nav-item d-block d-xl-none">
                    <a class="nav-link sidebartoggler nav-icon-hover" id="headerCollapse" href="#">
                        <i class="ti ti-menu-2"></i>
                    </a>
                </li>
            </ul>

            <div class="navbar-collapse justify-content-end px-0" id="navbarNav">
                <ul class="navbar-nav flex-row ms-auto align-items-center justify-content-end">
                    <li class="nav-item d-flex align-items-center">
                        <a href="#" data-bs-toggle="modal" data-bs-target="#logoutModal" class="d-flex align-items-center text-decoration-none">
                            <img src="<?= $main_url ?>src/assets/images/profile/user-1.jpg"
                                alt="profile" width="35" height="35" class="rounded-circle me-2">
                            <span class="fw-semibold text-dark">
                                <?= isset($_SESSION['ssUserLaundry']) ? htmlspecialchars($_SESSION['ssUserLaundry']) : 'Guest'; ?>
                        </a>
                    </li>
                </ul>
            </div>
        </nav>
    </header>
    <!--  Header End -->