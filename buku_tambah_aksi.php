<?php
include 'koneksi.php';
$ISBN = $_POST['ISBN'];
$JUDUL = $_POST['JUDUL_BUKU'];
$PENGARANG = $_POST['PENGARANG'];
$PENERBIT = $_POST['PENERBIT'];
$TAHUN_TERBIT = $_POST['TAHUN_TERBIT'];

mysqli_query($koneksi, "INSERT INTO buku VALUES ('$ISBN','$JUDUL','$PENGARANG','$PENERBIT','$TAHUN_TERBIT')");
header("location:buku.php?pesan=input");

?>
