<?php
session_start();
include 'koneksi.php';
include 'stok_buku_helper.php';

if (!isset($_SESSION['ID_USER'])) {
    header("Location: login.php");
    exit;
}

$peminjamanListPage = ($_SESSION['LEVEL'] ?? '') === 'Petugas' ? 'peminjaman_petugas.php' : 'peminjaman.php';

$idPeminjaman = mysqli_real_escape_string($koneksi, trim($_POST['id_peminjaman']));
$tglMulai = mysqli_real_escape_string($koneksi, trim($_POST['tgl_mulai']));
$tglSelesai = trim($_POST['tgl_selesai'] ?? '');

if ($tglSelesai !== '' && $tglSelesai < $tglMulai) {
    echo "<script>alert('Tanggal kembali tidak boleh lebih awal dari tanggal pinjam.'); window.location='peminjaman_edit.php?ID_PEMINJAMAN=" . urlencode($idPeminjaman) . "';</script>";
    exit;
}

$tglSelesaiValue = $tglSelesai === '' ? "NULL" : "'" . mysqli_real_escape_string($koneksi, $tglSelesai) . "'";
$statusBaru = $tglSelesai === '' ? 'Dipinjam' : 'Dikembalikan';

$peminjamanLama = mysqli_query($koneksi, "SELECT * FROM peminjaman WHERE ID_PEMINJAMAN='$idPeminjaman' LIMIT 1");
$dataLama = $peminjamanLama ? mysqli_fetch_assoc($peminjamanLama) : null;

if (!$dataLama) {
    header("Location: $peminjamanListPage");
    exit;
}

mysqli_begin_transaction($koneksi);

try {
    $jumlah = max(1, (int) $dataLama['JUMLAH']);

    if ($dataLama['STATUS'] === 'Dipinjam' && $statusBaru === 'Dikembalikan' && !tambahStokBuku($koneksi, $dataLama['ISBN'], $jumlah, $pesanStok)) {
        throw new Exception($pesanStok);
    }

    if ($dataLama['STATUS'] === 'Dikembalikan' && $statusBaru === 'Dipinjam' && !kurangiStokBuku($koneksi, $dataLama['ISBN'], $jumlah, $pesanStok)) {
        throw new Exception($pesanStok);
    }

    $query = "UPDATE peminjaman SET
    TANGGAL_MULAI='$tglMulai',
    TANGGAL_SELESAI=$tglSelesaiValue,
    STATUS='$statusBaru'
    WHERE ID_PEMINJAMAN='$idPeminjaman'";

    if (!mysqli_query($koneksi, $query)) {
        throw new Exception(mysqli_error($koneksi));
    }

    mysqli_commit($koneksi);
    header("Location: $peminjamanListPage?pesan=update");
    exit;
} catch (Throwable $e) {
    mysqli_rollback($koneksi);
    echo "<script>alert('" . addslashes($e->getMessage()) . "'); window.location='peminjaman_edit.php?ID_PEMINJAMAN=" . urlencode($idPeminjaman) . "';</script>";
    exit;
}
?>
