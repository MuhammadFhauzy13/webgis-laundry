<?php
require '../config.php';
require '../vendor/autoload.php'; // pastikan composer/autoload sudah ada

use Dompdf\Dompdf;
use Dompdf\Options;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['ssLoginLaundry'])) {
    header("location: ../landing-page/index.php");
    exit();
}

// Ambil filter dari GET
$id_khusus = isset($_GET['khusus']) ? $_GET['khusus'] : '';
$kecamatan = isset($_GET['kecamatan']) ? $_GET['kecamatan'] : '';

// Buat query dinamis
$query = "
    SELECT l.*, k.nama_layanan_khusus
    FROM laundry l
    INNER JOIN layanan_khusus k ON l.id_layanan_khusus = k.id_layanan_khusus
    WHERE 1=1
";

if (!empty($id_khusus)) {
    $query .= " AND l.id_layanan_khusus = '" . mysqli_real_escape_string($koneksi, $id_khusus) . "'";
}
if (!empty($kecamatan)) {
    $query .= " AND l.nama_kecamatan = '" . mysqli_real_escape_string($koneksi, $kecamatan) . "'";
}

$query .= " ORDER BY l.nama_laundry ASC";
$result = mysqli_query($koneksi, $query);

// Ambil nama kategori kalau difilter
$namaKhusus = '-';
if ($id_khusus) {
    $dataKhusus = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT nama_layanan_khusus FROM layanan_khusus WHERE id_layanan_khusus='$id_khusus'"));
    $namaKhusus = $dataKhusus['nama_layanan_khusus'] ?? '-';
}

// Buat HTML untuk PDF
ob_start();
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Rekap Laundry - GeoLaundry</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #333;
        }

        .header {
            text-align: center;
            margin-bottom: 10px;
        }

        .header img {
            width: 80px;
            margin-bottom: 5px;
        }

        h2 {
            text-align: center;
            margin: 0;
        }

        p.filter {
            text-align: center;
            margin: 5px 0 0 0;
            font-size: 11px;
            color: #555;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th,
        td {
            border: 1px solid #999;
            padding: 6px;
            text-align: center;
        }

        th {
            background-color: #d4edda;
        }

        .footer {
            margin-top: 20px;
            text-align: right;
            font-style: italic;
            font-size: 11px;
        }
    </style>
</head>

<body>
    <div class="header">
        <h2>Rekap Data Laundry</h2>
        <p class="filter">
            <?= ($id_khusus ? "Layanan Khusus: <b>$namaKhusus</b> | " : '') ?>
            <?= ($kecamatan ? "Kecamatan     : <b>$kecamatan</b>" : '') ?>
            <?= (!$id_khusus && !$kecamatan ? "Semua Data Laundry" : '') ?>
        </p>
    </div>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Laundry</th>
                <th>Layanan Khusus</th>
                <th>No. Telp</th>
                <th>Jam Buka</th>
                <th>Kecamatan</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>
                            <td>{$no}</td>
                            <td>{$row['nama_laundry']}</td>
                            <td>{$row['nama_layanan_khusus']}</td>
                            <td>{$row['no_telp']}</td>
                            <td>{$row['jam_buka']}</td>
                            <td>{$row['nama_kecamatan']}</td>
                        </tr>";
                    $no++;
                }
            } else {
                echo "<tr><td colspan='6'>Tidak ada data ditemukan.</td></tr>";
            }
            ?>
        </tbody>
    </table>
    <div class="footer">
        Dicetak pada: <?= date('d-m-Y H:i:s') ?>
    </div>
</body>

</html>
<?php
$html = ob_get_clean();
// ====== Konfigurasi Dompdf ======
$options = new Options();
$options->set('isRemoteEnabled', true); // kalau ada logo/gambar eksternal
$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);

// Ukuran & orientasi
$dompdf->setPaper('A4', 'portrait');

// Render ke PDF
$dompdf->render();

// Output ke browser
$dompdf->stream("Rekap_Laundry.pdf", ["Attachment" => false]);
exit;
?>