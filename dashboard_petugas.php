<?php
session_start();

if (!isset($_SESSION['ID_USER'])) {
    header("Location: login.php");
    exit;
}

if ($_SESSION['LEVEL'] !== 'Petugas') {
    header("Location: " . ($_SESSION['LEVEL'] === 'Peminjam' ? 'dashboard_peminjam.php' : 'dashboard.php'));
    exit;
}

include "koneksi.php";

$bukuResult = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM buku");
$bukuStat = mysqli_fetch_assoc($bukuResult);

$peminjamanResult = mysqli_query($koneksi, "
  SELECT
    COUNT(*) AS total,
    SUM(CASE WHEN STATUS='Dipinjam' THEN 1 ELSE 0 END) AS dipinjam,
    SUM(CASE WHEN STATUS='Dikembalikan' THEN 1 ELSE 0 END) AS dikembalikan
  FROM peminjaman
");
$peminjamanStat = mysqli_fetch_assoc($peminjamanResult);
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="style.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
  <title>Dashboard Petugas - PerpustakaanKu</title>
</head>

<body>
  <div class="sidebar">
    <h2>PerpustakaanKu</h2>
    <ul>
      <li><a href="dashboard_petugas.php" class="active">Dashboard Utama</a></li>
      <li><a href="buku_petugas.php">Buku</a></li>
      <li><a href="peminjaman_petugas.php">Peminjaman</a></li>
      <li><a href="logout.php">Logout</a></li>
    </ul>
  </div>

  <div class="main">
    <header>
      <h1>Dashboard Petugas</h1>
    </header>

    <div class="content">
      <div class="cards">
        <a href="dashboard_petugas.php" class="card nav-card">
          <h3>Dashboard Utama</h3>
          <p><?php echo number_format((int) ($peminjamanStat['total'] ?? 0)); ?> Transaksi</p>
        </a>
        <a href="buku_petugas.php" class="card nav-card">
          <h3>Buku</h3>
          <p><?php echo number_format((int) ($bukuStat['total'] ?? 0)); ?> Koleksi</p>
        </a>
        <a href="peminjaman_petugas.php" class="card nav-card">
          <h3>Peminjaman</h3>
          <p><?php echo number_format((int) ($peminjamanStat['dipinjam'] ?? 0)); ?> Sedang Dipinjam</p>
        </a>
      </div>

      <div class="cards" style="margin-top: 20px;">
        <div class="card">
          <h3>Dikembalikan</h3>
          <p><?php echo number_format((int) ($peminjamanStat['dikembalikan'] ?? 0)); ?> Transaksi</p>
        </div>
      </div>
    </div>
  </div>
</body>

</html>
