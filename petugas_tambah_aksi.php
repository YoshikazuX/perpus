<?php
session_start();
include 'koneksi.php';
include 'id_generator_helper.php';

if (!isset($_SESSION['ID_USER'])) {
    header("Location: login.php");
    exit;
}

$idPetugas = generateId($koneksi, 'petugas', 'ID_PETUGAS', 'PTG');
$nama = mysqli_real_escape_string($koneksi, trim($_POST['NAMA']));
$gender = mysqli_real_escape_string($koneksi, trim($_POST['GENDER']));
$alamat = mysqli_real_escape_string($koneksi, trim($_POST['ALAMAT']));
$hp = mysqli_real_escape_string($koneksi, trim($_POST['HP']));

mysqli_query($koneksi, "INSERT INTO petugas (ID_PETUGAS, NAMA, GENDER, ALAMAT, HP) VALUES ('$idPetugas', '$nama', '$gender', '$alamat', '$hp')");

header("Location: petugas.php?pesan=input");
exit;
?>
