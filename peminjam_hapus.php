<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['ID_USER'])) {
    header("Location: login.php");
    exit;
}

$idPeminjam = mysqli_real_escape_string($koneksi, $_GET['ID_PEMINJAM']);

mysqli_begin_transaction($koneksi);

try {
    $dataPeminjam = mysqli_query($koneksi, "SELECT NAMA FROM peminjam WHERE ID_PEMINJAM='$idPeminjam' LIMIT 1");
    $peminjam = $dataPeminjam ? mysqli_fetch_assoc($dataPeminjam) : null;

    if ($peminjam) {
        $namaPeminjam = mysqli_real_escape_string($koneksi, $peminjam['NAMA']);

        if (!mysqli_query($koneksi, "DELETE FROM peminjam WHERE ID_PEMINJAM='$idPeminjam'")) {
            throw new Exception(mysqli_error($koneksi));
        }

        if (!mysqli_query($koneksi, "DELETE FROM user WHERE NAMA_USER='$namaPeminjam' AND LEVEL='Peminjam'")) {
            throw new Exception(mysqli_error($koneksi));
        }
    }

    mysqli_commit($koneksi);
} catch (Throwable $e) {
    mysqli_rollback($koneksi);
    echo "<script>alert('Gagal menghapus peminjam: " . addslashes($e->getMessage()) . "'); window.location='peminjam.php';</script>";
    exit;
}

header("Location: peminjam.php?pesan=hapus");
exit;
?>
