<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $title ?></title>
    <link rel="shortcut icon" type="image/png" href="<?= $main_url ?>src/assets/images/logos/Geolaundry-removebg-preview.png" />
    <link rel="stylesheet" href="<?= $main_url ?>src/assets/css/styles.min.css" />
    <!-- data tables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css">
    <!--leaflet  -->
    <link rel="stylesheet" href="<?= $main_url ?>/leaflet/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
        crossorigin="" />
    <script src="<?= $main_url ?>/leaflet/dist/leaflet.js"></script>
    <!-- Leaflet Fullscreen Plugin -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet.fullscreen@1.6.0/Control.FullScreen.css" />





    <style>
        :root {
            --theme-blue: #02C3FE;
            /* warna utama */
            --theme-blue-soft: rgba(2, 195, 254, 0.15);
            --theme-blue-hover: rgba(2, 195, 254, 0.25);
            --theme-blue-strong: rgba(2, 195, 254, 0.85);
            --transition-fast: 0.25s ease;
        }

        .btn,
        .sidebar-link,
        .nav-link,
        .page-link {
            transition: background-color var(--transition-fast), color var(--transition-fast);
        }

        .nav-link {
            color: var(--theme-blue) !important;
            background-color: transparent !important;
            border: none !important;
            box-shadow: none !important;
            transform: none !important;
        }

        .nav-link:hover {
            color: #fff !important;
            background-color: var(--theme-blue-hover) !important;
        }

        .nav-link.active,
        .nav-tabs .nav-link.active {
            color: #fff !important;
            background-color: var(--theme-blue) !important;
            border-radius: 0.375rem;
            font-weight: 500;
            box-shadow: none !important;
            transform: none !important;
        }

        .sidebar-nav .sidebar-item.active>.sidebar-link {
            background-color: var(--theme-blue) !important;
            color: #fff !important;
            box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.1);
        }

        .sidebar-nav .sidebar-link {
            color: var(--theme-blue) !important;
        }

        .sidebar-nav .sidebar-link:hover {
            background-color: var(--theme-blue-hover) !important;
            color: #fff !important;
        }

        .sidebar-nav .sidebar-link:active {
            background-color: var(--theme-blue-strong) !important;
        }

        .page-item .page-link {
            color: var(--theme-blue) !important;
            background-color: transparent !important;
            border: 1px solid #dee2e6;
        }

        .page-item .page-link:hover {
            background-color: var(--theme-blue-hover) !important;
            color: #fff !important;
            border-color: var(--theme-blue);
        }

        .page-item.active .page-link {
            background-color: var(--theme-blue) !important;
            border-color: var(--theme-blue) !important;
            color: #fff !important;
        }

        .page-item.disabled .page-link {
            color: #6c757d !important;
            background-color: #e9ecef !important;
            border-color: #dee2e6 !important;
            pointer-events: none;
            cursor: not-allowed;
        }

        .btn-outline-primary {
            color: var(--theme-blue) !important;
            border-color: var(--theme-blue) !important;
        }

        .btn-outline-primary:hover {
            background-color: var(--theme-blue) !important;
            color: #fff !important;
        }

        .card.card-hover {
            background-color: var(--theme-blue) !important;
            color: #fff !important;
            border: none;
        }

        .table-success {
            --bs-table-bg: var(--theme-blue-soft);
            --bs-table-striped-bg: rgba(2, 195, 254, 0.08);
            --bs-table-striped-color: #000;
            --bs-table-active-bg: rgba(2, 195, 254, 0.2);
            --bs-table-hover-bg: rgba(2, 195, 254, 0.12);
            color: #000;
        }

        thead.table-success {
            background-color: var(--theme-blue-soft) !important;
            color: #000 !important;
            box-shadow: none !important;
            transform: none !important;
        }
    </style>

</head>

<body>
    <!--  Body Wrapper -->
    <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
        data-sidebar-position="fixed" data-header-position="fixed">