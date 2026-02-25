<?php 
session_start();
include 'koneksi.php';

$username = mysqli_real_escape_string($koneksi, $_POST['username']);
$password = mysqli_real_escape_string($koneksi, $_POST['password']);

$query = "SELECT * FROM user
WHERE username='$username' 
AND password='$password'";
$result = mysqli_query($koneksi, $query);

if (mysqli_num_rows($result) == 1) {
    $user = mysqli_fetch_assoc($result);
    $_SESSION['id_user'] = $user['id_user'];
     $_SESSION['nama_user'] = $user['nama_user'];
      $_SESSION['level'] = $user['level'];

      if ($user['level'] == 'admin') {
        header("Location: dashboard.html");  
        } elseif ($user['level'] == 'petugas') {
        header("Location: dashboard_petugas.php");
        } else {
        header ("Location: dashboard_peminjam.php");
        }
        exit;
}
else {
    echo "<script>alert('Login Gagal: Periksa username dan password Anda!'); window.location='index.php';</script>";
}
        