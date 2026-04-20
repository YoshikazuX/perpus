<?php
session_start();
include 'koneksi.php';
include 'stok_buku_helper.php';

if (!isset($_SESSION['ID_USER'])) {
    header("Location: login.php");
    exit;
}

$idPeminjaman = mysqli_real_escape_string($koneksi, trim($_POST['id_peminjaman']));
$isbn = mysqli_real_escape_string($koneksi, trim($_POST['isbn']));
$idPeminjam = mysqli_real_escape_string($koneksi, trim($_POST['id_peminjam']));
$idPetugas = mysqli_real_escape_string($koneksi, trim($_POST['id_petugas']));
$jumlah = (int) $_POST['jumlah'];
$tglMulai = mysqli_real_escape_string($koneksi, trim($_POST['tgl_mulai']));
$tglSelesai = trim($_POST['tgl_selesai']);
$status = $tglSelesai === '' ? 'Dipinjam' : 'Dikembalikan';

if ($jumlah < 1) {
    $jumlah = 1;
}

if ($tglSelesai !== '' && $tglSelesai < $tglMulai) {
    echo "<script>alert('Tanggal kembali tidak boleh lebih awal dari tanggal peminjaman.'); window.location='peminjaman_edit.php?ID_PEMINJAMAN=" . urlencode($idPeminjaman) . "';</script>";
    exit;
}

$tglSelesaiValue = $tglSelesai === '' ? "NULL" : "'" . mysqli_real_escape_string($koneksi, $tglSelesai) . "'";

$peminjamanLama = mysqli_query($koneksi, "SELECT * FROM peminjaman WHERE ID_PEMINJAMAN='$idPeminjaman' LIMIT 1");
$dataLama = $peminjamanLama ? mysqli_fetch_assoc($peminjamanLama) : null;

if (!$dataLama) {
    header("Location: peminjaman.php");
    exit;
}

mysqli_begin_transaction($koneksi);

try {
    if ($dataLama['STATUS'] === 'Dipinjam' && !tambahStokBuku($koneksi, $dataLama['ISBN'], (int) $dataLama['JUMLAH'], $pesanStok)) {
        throw new Exception($pesanStok);
    }

    if ($status === 'Dipinjam' && !kurangiStokBuku($koneksi, $isbn, $jumlah, $pesanStok)) {
        throw new Exception($pesanStok);
    }

    $query = "UPDATE peminjaman SET
    ISBN='$isbn',
    ID_PEMINJAM='$idPeminjam',
    ID_PETUGAS='$idPetugas',
    JUMLAH='$jumlah',
    TANGGAL_MULAI='$tglMulai',
    TANGGAL_SELESAI=$tglSelesaiValue,
    STATUS='$status'
    WHERE ID_PEMINJAMAN='$idPeminjaman'";

    if (!mysqli_query($koneksi, $query)) {
        throw new Exception(mysqli_error($koneksi));
    }

    mysqli_commit($koneksi);
    header("Location: peminjaman.php?pesan=update");
    exit;
} catch (Throwable $e) {
    mysqli_rollback($koneksi);
    echo "<script>alert('" . addslashes($e->getMessage()) . "'); window.location='peminjaman_edit.php?ID_PEMINJAMAN=" . urlencode($idPeminjaman) . "';</script>";
    exit;
}
?>
