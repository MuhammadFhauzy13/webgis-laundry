<?php
session_start();
require_once dirname(__DIR__) . '/config.php';

// Handle CRUD Ulasan
if (isset($_POST['simpan_ulasan'])) {
  $id_laundry = intval($_POST['id_laundry']);
  $nama = trim($_POST['nama_pengguna']);
  $rating = intval($_POST['rating']);
  $komentar = trim($_POST['komentar']);

  $stmt = mysqli_prepare($koneksi, "INSERT INTO ulasan (id_laundry, nama_pengguna, rating, komentar) VALUES (?, ?, ?, ?)");
  mysqli_stmt_bind_param($stmt, "isis", $id_laundry, $nama, $rating, $komentar);
  if (mysqli_stmt_execute($stmt)) {
    $_SESSION['msg'] = 'add_success';
  } else {
    $_SESSION['msg'] = 'add_error';
  }
  mysqli_stmt_close($stmt);
  header("Location: index.php?id_detail=" . $id_laundry . "&tab=ulasan");
  exit;
}

if (isset($_POST['update_ulasan'])) {
  $id_ulasan = intval($_POST['id_ulasan']);
  $id_laundry = intval($_POST['id_laundry']);
  $nama = trim($_POST['nama_pengguna']);
  $rating = intval($_POST['rating']);
  $komentar = trim($_POST['komentar']);

  $stmt = mysqli_prepare($koneksi, "UPDATE ulasan SET nama_pengguna = ?, rating = ?, komentar = ? WHERE id_ulasan = ?");
  mysqli_stmt_bind_param($stmt, "sisi", $nama, $rating, $komentar, $id_ulasan);
  if (mysqli_stmt_execute($stmt)) {
    $_SESSION['msg'] = 'update_success';
  } else {
    $_SESSION['msg'] = 'update_error';
  }
  mysqli_stmt_close($stmt);
  header("Location: index.php?id_detail=" . $id_laundry . "&tab=ulasan");
  exit;
}

if (isset($_GET['hapus_ulasan'])) {
  $id_ulasan = intval($_GET['hapus_ulasan']);
  $id_laundry = intval($_GET['id_laundry']);

  $stmt = mysqli_prepare($koneksi, "DELETE FROM ulasan WHERE id_ulasan = ?");
  mysqli_stmt_bind_param($stmt, "i", $id_ulasan);
  if (mysqli_stmt_execute($stmt)) {
    $_SESSION['msg'] = 'delete_success';
  } else {
    $_SESSION['msg'] = 'delete_error';
  }
  mysqli_stmt_close($stmt);
  header("Location: index.php?id_detail=" . $id_laundry . "&tab=ulasan");
  exit;
}
