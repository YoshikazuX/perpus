<?php
include 'koneksi.php';
$ISBN = $_POST['ISBN'];
$JUDUL = $_POST['JUDUL_BUKU'];
$PENGARANG = $_POST['PENGARANG'];
$PENERBIT = $_POST['PENERBIT'];
$TAHUN_TERBIT = $_POST['TAHUN_TERBIT'];
mysqli_query($koneksi, "UPDATE buku SET JUDUL_BUKU='$JUDUL', PENGARANG='$PENGARANG', PENERBIT='$PENERBIT', TAHUN_TERBIT='$TAHUN_TERBIT' WHERE ISBN='$ISBN'");

header("location:buku.php?pesan=update");
