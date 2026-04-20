<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['ID_USER'])) {
    header("Location: login.php");
    exit;
}

$idPetugas = mysqli_real_escape_string($koneksi, $_GET['ID_PETUGAS']);
mysqli_query($koneksi, "DELETE FROM petugas WHERE ID_PETUGAS='$idPetugas'");

header("Location: petugas.php?pesan=hapus");
exit;
?>
