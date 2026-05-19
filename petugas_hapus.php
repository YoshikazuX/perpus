<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['ID_USER'])) {
    header("Location: login.php");
    exit;
}

$idPetugas = mysqli_real_escape_string($koneksi, $_GET['ID_PETUGAS']);

mysqli_begin_transaction($koneksi);

try {
    $dataPetugas = mysqli_query($koneksi, "SELECT NAMA FROM petugas WHERE ID_PETUGAS='$idPetugas' LIMIT 1");
    $petugas = $dataPetugas ? mysqli_fetch_assoc($dataPetugas) : null;

    if ($petugas) {
        $namaPetugas = mysqli_real_escape_string($koneksi, $petugas['NAMA']);

        if (!mysqli_query($koneksi, "DELETE FROM petugas WHERE ID_PETUGAS='$idPetugas'")) {
            throw new Exception(mysqli_error($koneksi));
        }

        if (!mysqli_query($koneksi, "DELETE FROM user WHERE NAMA_USER='$namaPetugas' AND LEVEL='Petugas'")) {
            throw new Exception(mysqli_error($koneksi));
        }
    }

    mysqli_commit($koneksi);
} catch (Throwable $e) {
    mysqli_rollback($koneksi);
    echo "<script>alert('Gagal menghapus petugas: " . addslashes($e->getMessage()) . "'); window.location='petugas.php';</script>";
    exit;
}

header("Location: petugas.php?pesan=hapus");
exit;
?>
