<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require '../config.php';

if (isset($_POST['simpan'])) {
    $nama_layanan = trim($_POST['nama_layanan']);
    $stmt = mysqli_prepare(
        $koneksi,
        "INSERT INTO layanan (nama_layanan) VALUES (?)"
    );
    mysqli_stmt_bind_param($stmt, "s", $nama_layanan);
    if (mysqli_stmt_execute($stmt)) {
        header("Location:index.php?msg=add_success");
    } else {
        header("Location:index.php?msg=add_error");
    }
    exit;
}

if (isset($_POST['update'])) {
    $id_layanan   = intval($_POST['id_layanan']);
    $nama_layanan = trim($_POST['nama_layanan']);
    $stmt = mysqli_prepare(
        $koneksi,
        "UPDATE layanan SET nama_layanan=? WHERE id_layanan=?"
    );
    mysqli_stmt_bind_param($stmt, "si", $nama_layanan, $id_layanan);
    if (mysqli_stmt_execute($stmt)) {
        header("Location:index.php?msg=update_success");
    } else {
        header("Location:index.php?msg=update_error");
    }
    exit;
}

if (isset($_GET['id'])) {

    $id_layanan = intval($_GET['id']);

    // cek layanan dipake dilaundry
    $cek = mysqli_prepare(
        $koneksi,
        "SELECT COUNT(*) FROM laundry_layanan WHERE id_layanan=?"
    );
    mysqli_stmt_bind_param($cek, "i", $id_layanan);
    mysqli_stmt_execute($cek);
    mysqli_stmt_bind_result($cek, $count);
    mysqli_stmt_fetch($cek);
    mysqli_stmt_close($cek);

    if ($count > 0) {
        header("Location:index.php?msg=delete_error_fk");
        exit;
    }
    $del = mysqli_prepare(
        $koneksi,
        "DELETE FROM layanan WHERE id_layanan=?"
    );
    mysqli_stmt_bind_param($del, "i", $id_layanan);
    if (mysqli_stmt_execute($del)) {
        header("Location:index.php?msg=delete_success");
    } else {
        header("Location:index.php?msg=delete_error");
    }
    exit;
}
