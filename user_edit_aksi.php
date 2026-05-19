<?php
session_start();
include 'koneksi.php';
include 'id_generator_helper.php';

if (!isset($_SESSION['ID_USER'])) {
    header("Location: login.php");
    exit;
}

$idUser = mysqli_real_escape_string($koneksi, trim($_POST['ID_USER'] ?? ''));
$namaUser = mysqli_real_escape_string($koneksi, trim($_POST['NAMA_USER'] ?? ''));
$username = mysqli_real_escape_string($koneksi, trim($_POST['USERNAME'] ?? ''));
$password = trim($_POST['PASSWORD'] ?? '');
$level = mysqli_real_escape_string($koneksi, trim($_POST['LEVEL'] ?? ''));
$gender = mysqli_real_escape_string($koneksi, trim($_POST['GENDER'] ?? ''));
$alamat = mysqli_real_escape_string($koneksi, trim($_POST['ALAMAT'] ?? ''));
$hp = mysqli_real_escape_string($koneksi, trim($_POST['HP'] ?? ''));

$dataUser = mysqli_query($koneksi, "SELECT * FROM user WHERE ID_USER='$idUser' LIMIT 1");
$userLama = $dataUser ? mysqli_fetch_assoc($dataUser) : null;

if ($namaUser === '' || $username === '' || $level === '') {
    echo "<script>alert('Nama, username, dan level wajib diisi.'); window.location='user_edit.php?ID_USER=" . urlencode($idUser) . "';</script>";
    exit;
}

if (($level === 'Petugas' || $level === 'Peminjam') && ($gender === '' || $alamat === '' || $hp === '')) {
    echo "<script>alert('Gender, alamat, dan No. HP wajib diisi untuk role petugas atau peminjam.'); window.location='user_edit.php?ID_USER=" . urlencode($idUser) . "';</script>";
    exit;
}

if (!$userLama) {
    header("Location: user.php");
    exit;
}

$cekUsername = mysqli_query($koneksi, "SELECT 1 FROM user WHERE USERNAME='$username' AND ID_USER <> '$idUser' LIMIT 1");
if ($cekUsername && mysqli_num_rows($cekUsername) > 0) {
    echo "<script>alert('Username sudah digunakan.'); window.location='user_edit.php?ID_USER=" . urlencode($idUser) . "';</script>";
    exit;
}

$oldName = mysqli_real_escape_string($koneksi, $userLama['NAMA_USER']);
$oldLevel = $userLama['LEVEL'];
$passwordQuery = '';

if ($password !== '') {
    $passwordEscaped = mysqli_real_escape_string($koneksi, $password);
    $passwordQuery = ", PASSWORD='$passwordEscaped'";
}

mysqli_begin_transaction($koneksi);

try {
    if (!mysqli_query($koneksi, "UPDATE user SET NAMA_USER='$namaUser', USERNAME='$username', LEVEL='$level'$passwordQuery WHERE ID_USER='$idUser'")) {
        throw new Exception(mysqli_error($koneksi));
    }

    if ($level === 'Petugas') {
        if ($oldLevel === 'Peminjam' || $oldLevel === 'Anggota') {
            mysqli_query($koneksi, "DELETE FROM peminjam WHERE NAMA='$oldName' LIMIT 1");
        }

        $petugasLama = null;
        if ($oldLevel === 'Petugas') {
            $petugasResult = mysqli_query($koneksi, "SELECT ID_PETUGAS FROM petugas WHERE NAMA='$oldName' LIMIT 1");
            $petugasLama = $petugasResult ? mysqli_fetch_assoc($petugasResult) : null;
        }

        if ($petugasLama) {
            $idPetugas = mysqli_real_escape_string($koneksi, $petugasLama['ID_PETUGAS']);
            $queryPetugas = "UPDATE petugas SET NAMA='$namaUser', GENDER='$gender', ALAMAT='$alamat', HP='$hp' WHERE ID_PETUGAS='$idPetugas'";
        } else {
            $idPetugas = generateId($koneksi, 'petugas', 'ID_PETUGAS', 'PTG');
            $queryPetugas = "INSERT INTO petugas (ID_PETUGAS, NAMA, GENDER, ALAMAT, HP) VALUES ('$idPetugas', '$namaUser', '$gender', '$alamat', '$hp')";
        }

        if (!mysqli_query($koneksi, $queryPetugas)) {
            throw new Exception(mysqli_error($koneksi));
        }
    } elseif ($level === 'Peminjam') {
        if ($oldLevel === 'Petugas') {
            mysqli_query($koneksi, "DELETE FROM petugas WHERE NAMA='$oldName' LIMIT 1");
        }

        $peminjamLama = null;
        if ($oldLevel === 'Peminjam' || $oldLevel === 'Anggota') {
            $peminjamResult = mysqli_query($koneksi, "SELECT ID_PEMINJAM FROM peminjam WHERE NAMA='$oldName' LIMIT 1");
            $peminjamLama = $peminjamResult ? mysqli_fetch_assoc($peminjamResult) : null;
        }

        if ($peminjamLama) {
            $idPeminjam = mysqli_real_escape_string($koneksi, $peminjamLama['ID_PEMINJAM']);
            $queryPeminjam = "UPDATE peminjam SET NAMA='$namaUser', ALAMAT='$alamat', GENDER='$gender', HP='$hp' WHERE ID_PEMINJAM='$idPeminjam'";
        } else {
            $idPeminjam = generateId($koneksi, 'peminjam', 'ID_PEMINJAM', 'PMJ');
            $queryPeminjam = "INSERT INTO peminjam (ID_PEMINJAM, NAMA, ALAMAT, GENDER, HP) VALUES ('$idPeminjam', '$namaUser', '$alamat', '$gender', '$hp')";
        }

        if (!mysqli_query($koneksi, $queryPeminjam)) {
            throw new Exception(mysqli_error($koneksi));
        }
    } else {
        if ($oldLevel === 'Petugas') {
            mysqli_query($koneksi, "DELETE FROM petugas WHERE NAMA='$oldName' LIMIT 1");
        } elseif ($oldLevel === 'Peminjam' || $oldLevel === 'Anggota') {
            mysqli_query($koneksi, "DELETE FROM peminjam WHERE NAMA='$oldName' LIMIT 1");
        }
    }

    mysqli_commit($koneksi);

    if (($_SESSION['ID_USER'] ?? '') === $idUser) {
        $_SESSION['NAMA_USER'] = $_POST['NAMA_USER'] ?? '';
        $_SESSION['USERNAME'] = $_POST['USERNAME'] ?? '';
        $_SESSION['LEVEL'] = $_POST['LEVEL'] ?? '';
    }

    header("Location: user.php?pesan=update");
    exit;
} catch (Throwable $e) {
    mysqli_rollback($koneksi);
    echo "<script>alert('Gagal mengubah user: " . addslashes($e->getMessage()) . "'); window.location='user_edit.php?ID_USER=" . urlencode($idUser) . "';</script>";
    exit;
}
?>
