<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['ID_USER'])) {
    header("Location: login.php");
    exit;
}

if (isset($_GET['ID_USER'])) {
    $idUser = mysqli_real_escape_string($koneksi, $_GET['ID_USER']);

    mysqli_begin_transaction($koneksi);
    try {
        $queryUser = mysqli_query($koneksi, "SELECT * FROM user WHERE ID_USER='$idUser'");
        $dataUser = mysqli_fetch_assoc($queryUser);
        
        if ($dataUser) {
            $namaUser = $dataUser['NAMA_USER'];
            $level = $dataUser['LEVEL'];

            mysqli_query($koneksi, "DELETE FROM user WHERE ID_USER='$idUser'");

            if ($level == 'Petugas') {
                mysqli_query($koneksi, "DELETE FROM petugas WHERE NAMA='$namaUser'");
            } elseif ($level == 'Peminjam' || $level == 'Anggota') {
                mysqli_query($koneksi, "DELETE FROM peminjam WHERE NAMA='$namaUser'");
            }
        }
        
        mysqli_commit($koneksi);
        header("Location: user.php?pesan=hapus");
        exit;
    } catch (Exception $e) {
        mysqli_rollback($koneksi);
        echo "<script>alert('Gagal menghapus user: " . addslashes($e->getMessage()) . "'); window.location='user.php';</script>";
        exit;
    }
} else {
    header("Location: user.php");
    exit;
}
?>
