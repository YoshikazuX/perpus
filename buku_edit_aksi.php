<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['ID_USER'])) {
    header("Location: login.php");
    exit;
}

$ISBN = $_POST['ISBN'];
$JUDUL = $_POST['JUDUL_BUKU'];
$PENGARANG = $_POST['PENGARANG'];
$PENERBIT = $_POST['PENERBIT'];
$TAHUN_TERBIT = $_POST['TAHUN_TERBIT'];
$STOK = max(0, (int) $_POST['STOK']);
mysqli_query($koneksi, "UPDATE buku SET JUDUL_BUKU='$JUDUL', PENGARANG='$PENGARANG', PENERBIT='$PENERBIT', TAHUN_TERBIT='$TAHUN_TERBIT', STOK='$STOK' WHERE ISBN='$ISBN'");

header("location:buku.php?pesan=update");
