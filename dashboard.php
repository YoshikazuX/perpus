<?php
session_start();

if (!isset($_SESSION['ID_USER'])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="style.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
  <title>Dashboard Perpustakaan</title>
</head>

<body>
  <div class="sidebar">
    <h2>PerpustakaanKu</h2>
    <ul>
      <li><a href="dashboard.php">Dashboard</a></li>
      <li><a href="buku.php">Buku</a></li>
      <li><a href="Peminjam.php">Peminjam</a></li>
      <li><a href="Petugas.php">Petugas</a></li>
      <li><a href="Peminjaman.php">Peminjaman</a></li>
      <li><a href="Login.php">Logout</a></li>
    </ul>
  </div>

  <div class="main">
    <header>
      <h1>Selamat Datang di Dashboard</h1>
      <div class="user-info">
        <span>Admin</span>
        <img src="https://i.pravatar.cc/100" alt="User">
      </div>
    </header>

    <div class="content">
      <div class="cards">
        <div class="card">
          <h3>Koleksi Buku</h3>
          <p>1.245 Buku</p>
        </div>
        <div class="card">
          <h3>Anggota yang Aktif</h3>
          <p>327 Orang</p>
        </div>
        <div class="card">
          <h3>Total Peminjaman Hari Ini</h3>
          <p>52 Transaksi</p>
        </div>
        <div class="card">
          <h3>Buku Populer</h3>
          <p>"How To Fly"</p>
        </div>
      </div>
    </div>
  </div>
</body>

</html>