<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['ID_USER'])) {
    header("Location: login.php");
    exit;
}

$idPetugas = mysqli_real_escape_string($koneksi, trim($_POST['ID_PETUGAS']));
$nama = mysqli_real_escape_string($koneksi, trim($_POST['NAMA']));
$gender = mysqli_real_escape_string($koneksi, trim($_POST['GENDER']));
$alamat = mysqli_real_escape_string($koneksi, trim($_POST['ALAMAT']));
$hp = mysqli_real_escape_string($koneksi, trim($_POST['HP']));

mysqli_query($koneksi, "UPDATE petugas SET NAMA='$nama', GENDER='$gender', ALAMAT='$alamat', HP='$hp' WHERE ID_PETUGAS='$idPetugas'");

header("Location: petugas.php?pesan=update");
exit;
?>
