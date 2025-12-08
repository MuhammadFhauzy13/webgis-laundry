<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require '../config.php';

// Tambah data
if (isset($_POST['simpan'])) {
    $namaLayanan = trim(htmlspecialchars($_POST['layanan']));
    $harga = trim(htmlspecialchars($_POST['harga']));

    $query = mysqli_prepare($koneksi, "INSERT INTO layanan (nama_layanan, harga) VALUES (?, ?)");
    if (!$query) {
        header("Location:index.php?msg=query_fail");
        exit;
    }
    mysqli_stmt_bind_param($query, "ss", $namaLayanan, $harga);
    if (mysqli_stmt_execute($query)) {
        mysqli_stmt_close($query);
        header("Location:index.php?msg=add_success");
        exit;
    } else {
        mysqli_stmt_close($query);
        header("Location:index.php?msg=add_error");
        exit;
    }
}
// update
if (isset($_POST['update'])) {
    $idLayanan = intval($_POST['id_layanan']);
    $namaLayanan = trim(htmlspecialchars($_POST['layanan']));
    $harga = trim(htmlspecialchars($_POST['harga']));

    $query = mysqli_prepare($koneksi, "UPDATE layanan SET nama_layanan = ?, harga = ? WHERE id_layanan = ?");
    if (!$query) {
        header("Location:index.php?msg=query_fail");
        exit;
    }
    mysqli_stmt_bind_param($query, "ssi", $namaLayanan, $harga, $idLayanan);
    if (mysqli_stmt_execute($query)) {
        mysqli_stmt_close($query);
        header("Location:index.php?msg=update_success");
        exit;
    } else {
        mysqli_stmt_close($query);
        header("Location:index.php?msg=update_error");
        exit;
    }
}

// Hapus
if (isset($_GET['id'])) {
    $idLayanan = intval($_GET['id']);

    // Cek apakah kategori ini masih digunakan di tabel laundry
    $cek = mysqli_prepare($koneksi, "SELECT COUNT(*) FROM laundry WHERE id_layanan = ?");
    if (!$cek) {
        header("Location:index.php?msg=query_fail");
        exit;
    }
    mysqli_stmt_bind_param($cek, "i", $idLayanan);
    mysqli_stmt_execute($cek);
    mysqli_stmt_bind_result($cek, $count);
    mysqli_stmt_fetch($cek);
    mysqli_stmt_close($cek);

    if ($count > 0) {
        // Jika masih ada data terkait, tampilkan alert
        header("Location:index.php?msg=delete_error_fk");
        exit;
    }

    // Jika tidak ada data terkait, lakukan delete
    $query = mysqli_prepare($koneksi, "DELETE FROM layanan WHERE id_layanan = ?");
    if (!$query) {
        header("Location:index.php?msg=query_fail");
        exit;
    }

    mysqli_stmt_bind_param($query, "i", $idLayanan);

    if (mysqli_stmt_execute($query)) {
        mysqli_stmt_close($query);
        header("Location:index.php?msg=delete_success");
        exit;
    } else {
        mysqli_stmt_close($query);
        header("Location:index.php?msg=delete_error");
        exit;
    }
}
