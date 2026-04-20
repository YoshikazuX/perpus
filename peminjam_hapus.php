<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['ID_USER'])) {
    header("Location: login.php");
    exit;
}

$idPeminjam = mysqli_real_escape_string($koneksi, $_GET['ID_PEMINJAM']);
mysqli_query($koneksi, "DELETE FROM peminjam WHERE ID_PEMINJAM='$idPeminjam'");

header("Location: peminjam.php?pesan=hapus");
exit;
?>
