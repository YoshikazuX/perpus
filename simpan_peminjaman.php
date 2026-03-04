<?php
include 'koneksi.php';

$isbn         = $_POST['isbn'];
$id_peminjam  = $_POST['id_peminjam'];
$id_petugas   = $_POST['id_petugas'];
$tgl_mulai    = $_POST['tgl_mulai'];
$status       = 'dipinjam';

$query = "INSERT INTO peminjaman
(ISBN, ID_PEMINJAM, ID_PETUGAS, TANGGAL_MULAI, status)
VALUES
('$isbn', '$id_peminjam', '$id_petugas', '$tgl_mulai', '$status')";

if (mysqli_query($koneksi, $query)) {
    header("Location: Peminjaman.php");
    exit;
} else {
    echo "Gagal menyimpan peminjaman: " . mysqli_error($koneksi);
}
?>