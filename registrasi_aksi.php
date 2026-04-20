<?php
include 'koneksi.php';

$namaUser = mysqli_real_escape_string($koneksi, trim($_POST['NAMA_USER']));
$alamat = mysqli_real_escape_string($koneksi, trim($_POST['ALAMAT']));
$gender = mysqli_real_escape_string($koneksi, trim($_POST['GENDER']));
$hp = mysqli_real_escape_string($koneksi, trim($_POST['HP']));
$username = mysqli_real_escape_string($koneksi, trim($_POST['USERNAME']));
$password = trim($_POST['PASSWORD']);
$konfirmasiPassword = trim($_POST['KONFIRMASI_PASSWORD']);
$level = 'Peminjam';

if ($password !== $konfirmasiPassword) {
    echo "<script>alert('Konfirmasi password tidak cocok.'); window.location='registrasi.php';</script>";
    exit;
}

$cekUsername = mysqli_query($koneksi, "SELECT 1 FROM user WHERE USERNAME='$username' LIMIT 1");
if ($cekUsername && mysqli_num_rows($cekUsername) > 0) {
    echo "<script>alert('Username sudah digunakan.'); window.location='registrasi.php';</script>";
    exit;
}

$idUser = 'USR001';
$userTerakhir = mysqli_query($koneksi, "SELECT ID_USER FROM user ORDER BY ID_USER DESC LIMIT 1");
if ($userTerakhir && mysqli_num_rows($userTerakhir) > 0) {
    $rowUser = mysqli_fetch_assoc($userTerakhir);
    $angkaUser = (int) preg_replace('/\D/', '', $rowUser['ID_USER']);
    $idUser = 'USR' . str_pad((string) ($angkaUser + 1), 3, '0', STR_PAD_LEFT);
}

$idPeminjam = 'PMJ001';
$peminjamTerakhir = mysqli_query($koneksi, "SELECT ID_PEMINJAM FROM peminjam ORDER BY ID_PEMINJAM DESC LIMIT 1");
if ($peminjamTerakhir && mysqli_num_rows($peminjamTerakhir) > 0) {
    $rowPeminjam = mysqli_fetch_assoc($peminjamTerakhir);
    $angkaPeminjam = (int) preg_replace('/\D/', '', $rowPeminjam['ID_PEMINJAM']);
    $idPeminjam = 'PMJ' . str_pad((string) ($angkaPeminjam + 1), 3, '0', STR_PAD_LEFT);
}

mysqli_begin_transaction($koneksi);

try {
    if (!mysqli_query($koneksi, "INSERT INTO user (ID_USER, NAMA_USER, USERNAME, PASSWORD, LEVEL) VALUES ('$idUser', '$namaUser', '$username', '$password', '$level')")) {
        throw new Exception(mysqli_error($koneksi));
    }

    if (!mysqli_query($koneksi, "INSERT INTO peminjam (ID_PEMINJAM, NAMA, ALAMAT, GENDER, HP) VALUES ('$idPeminjam', '$namaUser', '$alamat', '$gender', '$hp')")) {
        throw new Exception(mysqli_error($koneksi));
    }

    mysqli_commit($koneksi);
    echo "<script>alert('Registrasi berhasil. Silakan login.'); window.location='login.php';</script>";
    exit;
} catch (Throwable $e) {
    mysqli_rollback($koneksi);
    echo "<script>alert('Registrasi gagal: " . addslashes($e->getMessage()) . "'); window.location='registrasi.php';</script>";
    exit;
}
?>
