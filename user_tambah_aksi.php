<?php
session_start();
include 'koneksi.php';
include 'id_generator_helper.php';

if (!isset($_SESSION['ID_USER'])) {
    header("Location: login.php");
    exit;
}

$namaUser = mysqli_real_escape_string($koneksi, trim($_POST['NAMA_USER']));
$username = mysqli_real_escape_string($koneksi, trim($_POST['USERNAME']));
$password = trim($_POST['PASSWORD']);
$level = mysqli_real_escape_string($koneksi, trim($_POST['LEVEL']));
$gender = mysqli_real_escape_string($koneksi, trim($_POST['GENDER']));
$alamat = mysqli_real_escape_string($koneksi, trim($_POST['ALAMAT']));
$hp = mysqli_real_escape_string($koneksi, trim($_POST['HP']));

// Cek apakah username sudah ada
$cekUsername = mysqli_query($koneksi, "SELECT 1 FROM user WHERE USERNAME='$username' LIMIT 1");
if ($cekUsername && mysqli_num_rows($cekUsername) > 0) {
    echo "<script>alert('Username sudah digunakan.'); window.location='user_tambah.php';</script>";
    exit;
}

$idUser = generateNumericId($koneksi, 'user', 'ID_USER');

mysqli_begin_transaction($koneksi);

try {
    // 1. Insert ke tabel user
    if (!mysqli_query($koneksi, "INSERT INTO user (ID_USER, NAMA_USER, USERNAME, PASSWORD, LEVEL) VALUES ('$idUser', '$namaUser', '$username', '$password', '$level')")) {
        throw new Exception(mysqli_error($koneksi));
    }

    // 2. Insert ke tabel sesuai role
    if ($level == 'Petugas') {
        $idPetugas = generateId($koneksi, 'petugas', 'ID_PETUGAS', 'PTG');

        if (!mysqli_query($koneksi, "INSERT INTO petugas (ID_PETUGAS, NAMA, GENDER, ALAMAT, HP) VALUES ('$idPetugas', '$namaUser', '$gender', '$alamat', '$hp')")) {
            throw new Exception(mysqli_error($koneksi));
        }

    } elseif ($level == 'Peminjam') {
        $idPeminjam = generateId($koneksi, 'peminjam', 'ID_PEMINJAM', 'PMJ');

        if (!mysqli_query($koneksi, "INSERT INTO peminjam (ID_PEMINJAM, NAMA, ALAMAT, GENDER, HP) VALUES ('$idPeminjam', '$namaUser', '$alamat', '$gender', '$hp')")) {
            throw new Exception(mysqli_error($koneksi));
        }
    }

    mysqli_commit($koneksi);
    header("Location: user.php?pesan=input");
    exit;

} catch (Throwable $e) {
    mysqli_rollback($koneksi);
    echo "<script>alert('Gagal menambahkan user: " . addslashes($e->getMessage()) . "'); window.location='user_tambah.php';</script>";
    exit;
}
?>
