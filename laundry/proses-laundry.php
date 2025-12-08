<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require '../config.php';
// tambah data
if (isset($_POST['simpan'])) {
    $nama        = trim($_POST['namaLaundry']);
    $kecamatan   = trim($_POST['namaKecamatan']);
    $longitude   = trim($_POST['longitude']);
    $latitude    = trim($_POST['latitude']);
    $telp        = trim($_POST['telpLaundry']);
    $jam_buka    = trim($_POST['jamBuka']);
    $alamat      = trim($_POST['alamatLaundry']);
    $profile     = trim($_POST['profile']);
    $id_layananKhusus = intval($_POST['id_layanan_khusus']);
    $id_layanan = intval($_POST['id_layanan']);
    $id_user     = 1;

    //Validasi koordinat
    if (!is_numeric($latitude) || !is_numeric($longitude)) {
        header("Location:index.php?msg=invalid_number");
        exit;
    }

    if ($latitude < -90 || $latitude > 90 || $longitude < -180 || $longitude > 180) {
        header("Location:index.php?msg=invalid_range");
        exit;
    }

    //Upload foto
    $defaultFoto = 'uploads/Geolaundry-removebg-preview.png';
    // Tentukan folder upload
    $uploadDir = __DIR__ . '/uploads/';
    $fotoPath = $defaultFoto;
    // Pastikan folder uploads ada
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    if (isset($_FILES['fotoLaundry']) && $_FILES['fotoLaundry']['error'] === UPLOAD_ERR_OK) {
        $ext = strtolower(pathinfo($_FILES['fotoLaundry']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'];
        if (in_array($ext, $allowed)) {
            // Buat nama file unik
            $fotoName = 'laundry_' . uniqid() . '.' . $ext;
            $targetPath = $uploadDir . $fotoName;
            // Pindahkan file ke folder uploads/
            if (move_uploaded_file($_FILES['fotoLaundry']['tmp_name'], $targetPath)) {
                // Simpan path relatif (agar bisa dipakai di <img src="...">)
                $fotoPath = 'uploads/' . $fotoName;
            }
        }
    }

    $stmt = mysqli_prepare(
        $koneksi,
        "INSERT INTO laundry 
        (nama_laundry, nama_kecamatan, longitude, latitude, no_telp, jam_buka, alamat, profile, id_layanan_khusus, id_user, id_layanan, foto)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
    );
    if ($stmt) {
        mysqli_stmt_bind_param(
            $stmt,
            "ssddssssiiis",
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
            $id_layanan,
            $fotoPath
        );
        if (mysqli_stmt_execute($stmt)) {
            header("Location:index.php?msg=add_success");
            exit;
        } else {
            header("Location:index.php?msg=add_error");
            exit;
        }
        mysqli_stmt_close($stmt);
    } else {
        header("Location:index.php?msg=query_fail");
        exit;
    }
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
    $id_layanan = intval($_POST['id_layanan']);
    $id_user     = 1;
    $fotoLama    = trim($_POST['fotoLama']);

    // Validasi koordinat
    if (!is_numeric($latitude) || !is_numeric($longitude)) {
        header("Location:index.php?msg=invalid_number");
        exit;
    }

    if ($latitude < -90 || $latitude > 90 || $longitude < -180 || $longitude > 180) {
        header("Location:index.php?msg=invalid_range");
        exit;
    }

    //Upload foto baru (jika ada)
    $defaultFoto = 'uploads/Geolaundry-removebg-preview.png';
    $uploadDir = __DIR__ . '/uploads/';
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
    $fotoPath = $fotoLama;
    if (isset($_FILES['fotoLaundry']) && $_FILES['fotoLaundry']['error'] === UPLOAD_ERR_OK) {
        $ext = strtolower(pathinfo($_FILES['fotoLaundry']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'];
        if (in_array($ext, $allowed)) {
            $fotoName = 'laundry_' . uniqid() . '.' . $ext;
            $targetPath = $uploadDir . $fotoName;
            if (move_uploaded_file($_FILES['fotoLaundry']['tmp_name'], $targetPath)) {
                $fotoPath = 'uploads/' . $fotoName;
                $oldFilePath = __DIR__ . '/' . $fotoLama;
                if ($fotoLama !== $defaultFoto && file_exists($oldFilePath)) {
                    unlink($oldFilePath);
                }
            }
        }
    }

    $stmt = mysqli_prepare(
        $koneksi,
        "UPDATE laundry 
        SET nama_laundry=?, nama_kecamatan=?, longitude=?, latitude=?, 
            no_telp=?, jam_buka=?, alamat=?, profile=?, 
            id_layanan_khusus=?, id_user=?, id_layanan=?, foto=?
        WHERE id_laundry=?"
    );

    if ($stmt) {
        mysqli_stmt_bind_param(
            $stmt,
            "ssddssssiissi",
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
            $id_layanan,
            $fotoPath,
            $id_laundry
        );

        if (mysqli_stmt_execute($stmt)) {
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

    // Ambil data foto lama untuk dihapus dari folder
    $query = mysqli_prepare($koneksi, "SELECT foto FROM laundry WHERE id_laundry = ?");
    mysqli_stmt_bind_param($query, "i", $id_laundry);
    mysqli_stmt_execute($query);
    mysqli_stmt_bind_result($query, $fotoLama);
    mysqli_stmt_fetch($query);
    mysqli_stmt_close($query);

    // Path default
    $defaultFoto = 'uploads/Geolaundry-removebg-preview.png';

    // Hapus record dari database
    $stmt = mysqli_prepare($koneksi, "DELETE FROM laundry WHERE id_laundry = ?");
    mysqli_stmt_bind_param($stmt, "i", $id_laundry);

    if (mysqli_stmt_execute($stmt)) {
        // Jika berhasil delete data, hapus juga file fotonya (jika bukan default)
        $filePath = __DIR__ . '/' . $fotoLama;
        if ($fotoLama !== $defaultFoto && file_exists($filePath)) {
            unlink($filePath);
        }

        header("Location:index.php?msg=delete_success");
        exit;
    } else {
        header("Location:index.php?msg=delete_error");
        exit;
    }

    mysqli_stmt_close($stmt);
}
