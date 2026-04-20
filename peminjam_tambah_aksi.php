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

mysqli_query($koneksi, "INSERT INTO peminjam VALUES ('$idPeminjam', '$nama', '$alamat', '$gender', '$hp')");

header("Location: peminjam.php?pesan=input");
exit;
?>
