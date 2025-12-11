<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require '../config.php';
// tambah data
if (isset($_POST['simpan'])) {

    // --- Ambil input dasar ---
    $nama        = trim($_POST['namaLaundry']);
    $kecamatan   = trim($_POST['namaKecamatan']);
    $longitude   = trim($_POST['longitude']);
    $latitude    = trim($_POST['latitude']);
    $telp        = trim($_POST['telpLaundry']);
    $jam_buka    = trim($_POST['jamBuka']);
    $alamat      = trim($_POST['alamatLaundry']);
    $profile     = trim($_POST['profile']);
    $id_layananKhusus = intval($_POST['id_layanan_khusus']);
    $id_user     = 1; // tetap satu admin

    // --- Ambil layanan & harga ---
    $layanan_id_raw    = $_POST['layanan_id'] ?? [];
    $layanan_harga_raw = $_POST['layanan_harga'] ?? [];

    // --- Validasi koordinat ---
    if (!is_numeric($latitude) || !is_numeric($longitude)) {
        header("Location:index.php?msg=invalid_number");
        exit;
    }

    if ($latitude < -90 || $latitude > 90 || $longitude < -180 || $longitude > 180) {
        header("Location:index.php?msg=invalid_range");
        exit;
    }

    // --- Validasi layanan + harga ---
    $final_layanan_id = [];
    $final_harga      = [];

    foreach ($layanan_id_raw as $id) {
        $id    = intval($id);
        $harga = isset($layanan_harga_raw[$id]) ? intval($layanan_harga_raw[$id]) : 0;

        $final_layanan_id[] = $id;
        $final_harga[]      = $harga;
    }

    // --- Foto default ---
    $defaultFoto = 'uploads/Geolaundry-removebg-preview.png';
    $uploadDir   = __DIR__ . '/uploads/';

    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    $fotoPath = $defaultFoto;

    // --- Upload foto ---
    if (!empty($_FILES['fotoLaundry']['name']) && $_FILES['fotoLaundry']['error'] === UPLOAD_ERR_OK) {

        $ext = strtolower(pathinfo($_FILES['fotoLaundry']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

        if (in_array($ext, $allowed)) {
            $fotoName = 'laundry_' . uniqid() . '.' . $ext;
            $targetPath = $uploadDir . $fotoName;

            if (move_uploaded_file($_FILES['fotoLaundry']['tmp_name'], $targetPath)) {
                $fotoPath = 'uploads/' . $fotoName;
            }
        }
    }

    // --- Insert ke tabel laundry ---
    $stmt = mysqli_prepare(
        $koneksi,
        "INSERT INTO laundry 
        (nama_laundry, nama_kecamatan, longitude, latitude, no_telp, jam_buka, alamat, profile,
        id_layanan_khusus, id_user, foto)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
    );

    if ($stmt) {

        mysqli_stmt_bind_param(
            $stmt,
            "ssddssssiis",
            $nama,
            $kecamatan,
            $longitude,
            $latitude,
            $telp,
            $jam_buka,
            $alamat,
            $profile,
            $id_layananKhusus,
            $id_user,
            $fotoPath
        );

        if (mysqli_stmt_execute($stmt)) {

            // --- Ambil ID laundry baru ---
            $idLaundryBaru = mysqli_insert_id($koneksi);

            // --- Insert ke tabel LAUNDRY_LAYANAN ---
            $stmtRelasi = mysqli_prepare(
                $koneksi,
                "INSERT INTO laundry_layanan (id_laundry, id_layanan, harga) VALUES (?, ?, ?)"
            );

            foreach ($final_layanan_id as $index => $idLy) {
                $harga = $final_harga[$index];

                mysqli_stmt_bind_param($stmtRelasi, "iii", $idLaundryBaru, $idLy, $harga);
                mysqli_stmt_execute($stmtRelasi);
            }

            mysqli_stmt_close($stmtRelasi);

            header("Location:index.php?msg=add_success");
            exit;
        } else {
            header("Location:index.php?msg=add_error");
            exit;
        }

        mysqli_stmt_close($stmt);
    }

    header("Location:index.php?msg=query_fail");
    exit;
}



// update
if (isset($_POST['update'])) {

    $id_laundry  = intval($_POST['id_laundry']);
    $nama        = trim($_POST['namaLaundry']);
    $kecamatan   = trim($_POST['namaKecamatan']);
    $longitude   = trim($_POST['longitude']);
    $latitude    = trim($_POST['latitude']);
    $telp        = trim($_POST['telpLaundry']);
    $jam_buka    = trim($_POST['jamBuka']);
    $alamat      = trim($_POST['alamatLaundry']);
    $profile     = trim($_POST['profile']);
    $id_layananKhusus = intval($_POST['id_layanan_khusus']);
    $id_user     = 1; // tetap satu admin
    $fotoLama    = trim($_POST['fotoLama']);

    // ambil layanan dan harga
    $layanan_id_raw    = $_POST['layanan_id'] ?? [];
    $layanan_harga_raw = $_POST['layanan_harga'] ?? [];

    // validasi koordinat
    if (!is_numeric($latitude) || !is_numeric($longitude)) {
        header("Location:index.php?msg=invalid_number");
        exit;
    }
    if ($latitude < -90 || $latitude > 90 || $longitude < -180 || $longitude > 180) {
        header("Location:index.php?msg=invalid_range");
        exit;
    }

    // folder upload
    $defaultFoto = 'uploads/Geolaundry-removebg-preview.png';
    $uploadDir = __DIR__ . '/uploads/';
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

    $fotoPath = $fotoLama; // default pakai yang lama

    // upload foto baru
    if (!empty($_FILES['fotoLaundry']['name']) && $_FILES['fotoLaundry']['error'] === UPLOAD_ERR_OK) {
        $ext = strtolower(pathinfo($_FILES['fotoLaundry']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'];
        if (in_array($ext, $allowed)) {
            $fotoName = 'laundry_' . uniqid() . '.' . $ext;
            $targetPath = $uploadDir . $fotoName;
            if (move_uploaded_file($_FILES['fotoLaundry']['tmp_name'], $targetPath)) {
                $fotoPath = 'uploads/' . $fotoName;
                // hapus foto lama
                $oldFilePath = __DIR__ . '/' . $fotoLama;
                if ($fotoLama !== $defaultFoto && file_exists($oldFilePath)) {
                    unlink($oldFilePath);
                }
            }
        }
    }

    // update tabel laundry
    $stmt = mysqli_prepare(
        $koneksi,
        "UPDATE laundry 
        SET nama_laundry=?, nama_kecamatan=?, longitude=?, latitude=?, 
            no_telp=?, jam_buka=?, alamat=?, profile=?, 
            id_layanan_khusus=?, id_user=?, foto=?
        WHERE id_laundry=?"
    );

    if ($stmt) {
        mysqli_stmt_bind_param(
            $stmt,
            "ssddssssissi",
            $nama,
            $kecamatan,
            $longitude,
            $latitude,
            $telp,
            $jam_buka,
            $alamat,
            $profile,
            $id_layananKhusus,
            $id_user,
            $fotoPath,
            $id_laundry
        );

        if (mysqli_stmt_execute($stmt)) {

            // hapus layanan lama
            $del = mysqli_prepare($koneksi, "DELETE FROM laundry_layanan WHERE id_laundry=?");
            mysqli_stmt_bind_param($del, "i", $id_laundry);
            mysqli_stmt_execute($del);
            mysqli_stmt_close($del);

            // insert layanan baru (dengan harga)
            if (!empty($layanan_id_raw)) {
                $stmtLayanan = mysqli_prepare($koneksi, "INSERT INTO laundry_layanan (id_laundry, id_layanan, harga) VALUES (?, ?, ?)");
                foreach ($layanan_id_raw as $id) {
                    $id = intval($id);
                    $harga = isset($layanan_harga_raw[$id]) ? intval($layanan_harga_raw[$id]) : 0;
                    mysqli_stmt_bind_param($stmtLayanan, "iii", $id_laundry, $id, $harga);
                    mysqli_stmt_execute($stmtLayanan);
                }
                mysqli_stmt_close($stmtLayanan);
            }

            header("Location:index.php?msg=update_success");
            exit;
        } else {
            header("Location:index.php?msg=update_error");
            exit;
        }

        mysqli_stmt_close($stmt);
    } else {
        header("Location:index.php?msg=query_fail");
        exit;
    }
}




// delete

if (isset($_GET['id'])) {

    $id_laundry = intval($_GET['id']);
    $defaultFoto = 'uploads/Geolaundry-removebg-preview.png';

    $stmt = mysqli_prepare($koneksi, "SELECT foto FROM laundry WHERE id_laundry = ?");
    mysqli_stmt_bind_param($stmt, "i", $id_laundry);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $fotoLama);

    $dataAda = mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    if (!$dataAda) {
        header("Location:index.php?msg=not_found");
        exit;
    }

    $delRel = mysqli_prepare($koneksi, "DELETE FROM laundry_layanan WHERE id_laundry = ?");
    mysqli_stmt_bind_param($delRel, "i", $id_laundry);
    mysqli_stmt_execute($delRel);
    mysqli_stmt_close($delRel);


    $delLaundry = mysqli_prepare($koneksi, "DELETE FROM laundry WHERE id_laundry = ?");
    mysqli_stmt_bind_param($delLaundry, "i", $id_laundry);

    if (mysqli_stmt_execute($delLaundry)) {

        if (!empty($fotoLama) && $fotoLama !== $defaultFoto) {

            $filePath = __DIR__ . '/' . $fotoLama;

            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }

        header("Location:index.php?msg=delete_success");
        exit;
    } else {
        header("Location:index.php?msg=delete_error");
        exit;
    }

    mysqli_stmt_close($delLaundry);
}
