<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['ID_USER'])) {
    header("Location: login.php");
    exit;
}

$idPeminjam = mysqli_real_escape_string($koneksi, trim($_POST['ID_PEMINJAM']));
$nama = mysqli_real_escape_string($koneksi, trim($_POST['NAMA']));
$alamat = mysqli_real_escape_string($koneksi, trim($_POST['ALAMAT']));
$gender = mysqli_real_escape_string($koneksi, trim($_POST['GENDER']));
$hp = mysqli_real_escape_string($koneksi, trim($_POST['HP']));

mysqli_query($koneksi, "UPDATE peminjam SET NAMA='$nama', ALAMAT='$alamat', GENDER='$gender', HP='$hp' WHERE ID_PEMINJAM='$idPeminjam'");

header("Location: peminjam.php?pesan=update");
exit;
?>
