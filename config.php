<?php

date_default_timezone_set('Asia/Tokyo');

$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'webgis_laundry';

$koneksi = mysqli_connect($host, $user, $pass, $dbname);

if (!$koneksi) {
    die('Koneksi gagal: ' . mysqli_connect_error());
}

$main_url = 'http://localhost/webgis_laundry/';
