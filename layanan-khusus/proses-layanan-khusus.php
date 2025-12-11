<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require '../config.php';

//tambah data
if (isset($_POST['simpan'])) {

    $namaLayanan = trim($_POST['nama_layanan'] ?? '');
    $deskripsi   = trim($_POST['deskripsi'] ?? '');

    $query = mysqli_prepare(
        $koneksi,
        "INSERT INTO layanan_khusus (nama_layanan_khusus, deskripsi)
        VALUES (?, ?)"
    );
    if (!$query) {
        header("Location:index.php?msg=query_fail");
        exit;
    }
    mysqli_stmt_bind_param($query, "ss", $namaLayanan, $deskripsi);
    if (mysqli_stmt_execute($query)) {
        mysqli_stmt_close($query);
        header("Location:index.php?msg=add_success");
        exit;
    }
    mysqli_stmt_close($query);
    header("Location:index.php?msg=add_error");
    exit;
}

//update
if (isset($_POST['update'])) {
    $idLayanan   = intval($_POST['id_layanan_khusus']);
    $namaLayanan = trim($_POST['khusus']);
    $deskripsi   = trim($_POST['deskripsi']);

    $query = mysqli_prepare(
        $koneksi,
        "UPDATE layanan_khusus
        SET nama_layanan_khusus = ?, deskripsi = ?
        WHERE id_layanan_khusus = ?"
    );
    if (!$query) {
        header("Location:index.php?msg=query_fail");
        exit;
    }
    mysqli_stmt_bind_param($query, "ssi", $namaLayanan, $deskripsi, $idLayanan);
    if (mysqli_stmt_execute($query)) {
        mysqli_stmt_close($query);
        header("Location:index.php?msg=update_success");
        exit;
    }
    mysqli_stmt_close($query);
    header("Location:index.php?msg=update_error");
    exit;
}

//hapus 
$idLayanan = intval($_GET['id']);

// Cek relasi
$cek = mysqli_prepare($koneksi, "SELECT COUNT(*) FROM laundry WHERE id_layanan_khusus = ?");
mysqli_stmt_bind_param($cek, "i", $idLayanan);
mysqli_stmt_execute($cek);
mysqli_stmt_bind_result($cek, $count);
mysqli_stmt_fetch($cek);
mysqli_stmt_close($cek);

if ($count > 0) {
    // Tidak bisa hapus
    header("Location:index.php?msg=delete_error_fk");
    exit;
}

// Jika aman, hapus
$query = mysqli_prepare($koneksi, "DELETE FROM layanan_khusus WHERE id_layanan_khusus = ?");
mysqli_stmt_bind_param($query, "i", $idLayanan);
if (mysqli_stmt_execute($query)) {
    mysqli_stmt_close($query);
    header("Location:index.php?msg=delete_success");
    exit;
}
mysqli_stmt_close($query);
header("Location:index.php?msg=delete_error");
exit;
