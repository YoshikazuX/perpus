<?php
include 'koneksi.php';
include 'id_generator_helper.php';

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

$idUser = generateNumericId($koneksi, 'user', 'ID_USER');
$idPeminjam = generateId($koneksi, 'peminjam', 'ID_PEMINJAM', 'PMJ');

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
