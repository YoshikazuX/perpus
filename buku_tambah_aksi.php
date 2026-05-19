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
    echo "<script>alert('Tahun terbit tidak boleh melebihi tahun sekarang.'); window.location='buku_tambah.php';</script>";
    exit;
}

mysqli_query($koneksi, "INSERT INTO buku (ISBN, JUDUL_BUKU, PENGARANG, PENERBIT, GENRE, TAHUN_TERBIT, STOK) VALUES ('$ISBN','$JUDUL','$PENGARANG','$PENERBIT','$GENRE','$TAHUN_TERBIT','$STOK')");
header("location:buku.php?pesan=input");

?>
