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
$GENRE = $_POST['GENRE'];
$TAHUN_TERBIT = (int) $_POST['TAHUN_TERBIT'];
$STOK = max(0, (int) $_POST['STOK']);
$tahunSekarang = (int) date('Y');

if ($TAHUN_TERBIT > $tahunSekarang) {
    echo "<script>alert('Tahun terbit tidak boleh melebihi tahun sekarang.'); window.location='buku_edit.php?ISBN=" . urlencode($ISBN) . "';</script>";
    exit;
}

mysqli_query($koneksi, "UPDATE buku SET JUDUL_BUKU='$JUDUL', PENGARANG='$PENGARANG', PENERBIT='$PENERBIT', GENRE='$GENRE', TAHUN_TERBIT='$TAHUN_TERBIT', STOK='$STOK' WHERE ISBN='$ISBN'");

header("location:buku.php?pesan=update");
