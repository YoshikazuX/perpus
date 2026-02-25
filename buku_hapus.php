<?php
include 'koneksi.php';
session_start();

if (!isset($_SESSION['id_user'])) {
    header("Location: index.php");
    exit;
}

$ISBN = $_GET['ISBN'];
mysqli_query($koneksi, "DELETE FROM buku WHERE isbn='$ISBN'");
header("location:buku.php?pesan=hapus");
