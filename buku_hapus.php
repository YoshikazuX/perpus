<?php
include 'koneksi.php';
session_start();

if (!isset($_SESSION['ID_USER'])) {
    header("Location: login.php");
    exit;
}

$ISBN = $_GET['ISBN'];
mysqli_query($koneksi, "DELETE FROM buku WHERE isbn='$ISBN'");
header("location:buku.php?pesan=hapus");
