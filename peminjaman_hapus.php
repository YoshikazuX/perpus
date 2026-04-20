<?php
session_start();
include 'koneksi.php';
include 'stok_buku_helper.php';

if (!isset($_SESSION['ID_USER'])) {
    header("Location: login.php");
    exit;
}

$idPeminjaman = mysqli_real_escape_string($koneksi, $_GET['ID_PEMINJAMAN']);
$data = mysqli_query($koneksi, "SELECT * FROM peminjaman WHERE ID_PEMINJAMAN='$idPeminjaman' LIMIT 1");
$tampil = $data ? mysqli_fetch_assoc($data) : null;

if (!$tampil) {
    header("Location: peminjaman.php");
    exit;
}

mysqli_begin_transaction($koneksi);

try {
    if ($tampil['STATUS'] === 'Dipinjam' && !tambahStokBuku($koneksi, $tampil['ISBN'], (int) $tampil['JUMLAH'], $pesanStok)) {
        throw new Exception($pesanStok);
    }

    if (!mysqli_query($koneksi, "DELETE FROM peminjaman WHERE ID_PEMINJAMAN='$idPeminjaman'")) {
        throw new Exception(mysqli_error($koneksi));
    }

    mysqli_commit($koneksi);
} catch (Throwable $e) {
    mysqli_rollback($koneksi);
    echo "<script>alert('" . addslashes($e->getMessage()) . "'); window.location='peminjaman.php';</script>";
    exit;
}

header("Location: peminjaman.php?pesan=hapus");
exit;
?>
