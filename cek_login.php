<?php
session_start();
include 'koneksi.php';

$USERNAME = mysqli_real_escape_string($koneksi, $_POST['USERNAME']);
$PASSWORD = mysqli_real_escape_string($koneksi, $_POST['PASSWORD']);

$query = "SELECT * FROM user
WHERE USERNAME='$USERNAME' 
AND PASSWORD ='$PASSWORD'";
$result = mysqli_query($koneksi, $query);

if (mysqli_num_rows($result) == 1) {
    $user = mysqli_fetch_assoc($result);


    $_SESSION['ID_USER'] = $user['ID_USER'];
    $_SESSION['USERNAME'] = $user['USERNAME'];
    $_SESSION['LEVEL'] = $user['LEVEL'];

    if ($user['LEVEL'] == 'admin') {
        header("Location: dashboard.php");
    } elseif ($user['LEVEL'] == 'petugas') {
        header("Location: dashboard_petugas.php");
    } else {
        header("Location: dashboard_peminjam.php");
    }
    exit;
} else {
    var_dump($result);
    echo "<script>alert('Login Gagal: Periksa username dan password Anda!'); window.location='login.php';</script>";
}
