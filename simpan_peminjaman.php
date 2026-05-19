<?php
session_start();
include 'koneksi.php';
include 'stok_buku_helper.php';
include 'petugas_operator_helper.php';

if (!isset($_SESSION['ID_USER'])) {
    header("Location: login.php");
    exit;
}

$idPeminjaman = mysqli_real_escape_string($koneksi, trim($_POST['id_peminjaman']));
$isbn = mysqli_real_escape_string($koneksi, trim($_POST['isbn']));
$idPeminjam = mysqli_real_escape_string($koneksi, trim($_POST['id_peminjam']));
$jumlah = 1;
$tglMulai = date('Y-m-d');
$tglSelesaiValue = "NULL";
$status = 'Dipinjam';

mysqli_begin_transaction($koneksi);

try {
    $idPetugas = mysqli_real_escape_string($koneksi, getPetugasPeminjamanValue($koneksi));

    if ($status === 'Dipinjam' && !kurangiStokBuku($koneksi, $isbn, $jumlah, $pesanStok)) {
        throw new Exception($pesanStok);
    }

    $query = "INSERT INTO peminjaman
    (ID_PEMINJAMAN, ISBN, ID_PEMINJAM, ID_PETUGAS, JUMLAH, TANGGAL_MULAI, TANGGAL_SELESAI, STATUS)
    VALUES
    ('$idPeminjaman', '$isbn', '$idPeminjam', '$idPetugas', '$jumlah', '$tglMulai', $tglSelesaiValue, '$status')";

    if (!mysqli_query($koneksi, $query)) {
        throw new Exception(mysqli_error($koneksi));
    }

    mysqli_commit($koneksi);
    header("Location: peminjaman.php");
    exit;
} catch (Throwable $e) {
    mysqli_rollback($koneksi);
    echo "<script>alert('" . addslashes($e->getMessage()) . "'); window.location='form_peminjaman.php';</script>";
    exit;
}
?>
