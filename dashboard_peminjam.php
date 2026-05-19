<?php
session_start();

if (!isset($_SESSION['ID_USER'])) {
    header("Location: login.php");
    exit;
}

if ($_SESSION['LEVEL'] === 'Admin') {
    header("Location: dashboard.php");
    exit;
}

include 'koneksi.php';
require_once "pagination_helper.php";
require_once "search_helper.php";

$namaFilter = mysqli_real_escape_string($koneksi, $_SESSION['NAMA_USER'] ?? '');
$usernameFilter = mysqli_real_escape_string($koneksi, $_SESSION['USERNAME'] ?? '');

$bukuResult = mysqli_query($koneksi, "SELECT COUNT(*) AS total_buku, COALESCE(SUM(STOK), 0) AS total_stok FROM buku");
$bukuStat = mysqli_fetch_assoc($bukuResult);

$riwayatResult = mysqli_query($koneksi, "
  SELECT 
    COUNT(*) AS total_riwayat,
    SUM(CASE WHEN STATUS = 'Dipinjam' THEN 1 ELSE 0 END) AS masih_dipinjam
  FROM peminjaman
  JOIN peminjam ON peminjaman.ID_PEMINJAM = peminjam.ID_PEMINJAM
  WHERE peminjam.NAMA = '$namaFilter' OR peminjam.NAMA = '$usernameFilter'
");
$riwayatStat = mysqli_fetch_assoc($riwayatResult);

$keyword = trim($_GET['keyword'] ?? '');
$keywordEscaped = mysqli_real_escape_string($koneksi, $keyword);
$whereBuku = "";

if ($keyword !== '') {
    $whereBuku = " WHERE JUDUL_BUKU LIKE '%$keywordEscaped%'
      OR PENGARANG LIKE '%$keywordEscaped%'
      OR GENRE LIKE '%$keywordEscaped%'
      OR STOK LIKE '%$keywordEscaped%'";
}

$pagination = getPaginationData($koneksi, "SELECT COUNT(*) AS total FROM buku$whereBuku");
$terbaru = mysqli_query($koneksi, "SELECT JUDUL_BUKU, PENGARANG, GENRE, STOK FROM buku$whereBuku ORDER BY ISBN DESC LIMIT {$pagination['per_page']} OFFSET {$pagination['offset']}");
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="style.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
  <title>Dashboard Peminjam - PerpustakaanKu</title>
</head>

<body>
  <div class="sidebar">
    <h2>PerpustakaanKu</h2>
    <ul>
      <li><a href="dashboard_peminjam.php" class="active">Dashboard</a></li>
      <li><a href="buku_peminjam.php">Buku</a></li>
      <li><a href="riwayat_peminjam.php">Riwayat</a></li>
      <li><a href="logout.php">Logout</a></li>
    </ul>
  </div>

  <div class="main">
    <header>
      <h1>Dashboard Peminjam</h1>
    </header>

    <div class="content">
      <div class="cards">
        <div class="card">
          <h3>Katalog Buku</h3>
          <p><?php echo number_format((int) ($bukuStat['total_buku'] ?? 0)); ?> Buku</p>
        </div>
        <div class="card">
          <h3>Total Stok</h3>
          <p><?php echo number_format((int) ($bukuStat['total_stok'] ?? 0)); ?> Eksemplar</p>
        </div>
        <div class="card">
          <h3>Riwayat Anda</h3>
          <p><?php echo number_format((int) ($riwayatStat['total_riwayat'] ?? 0)); ?> Transaksi</p>
        </div>
        <div class="card">
          <h3>Sedang Dipinjam</h3>
          <p><?php echo number_format((int) ($riwayatStat['masih_dipinjam'] ?? 0)); ?> Buku</p>
        </div>
      </div>

      <h2>Koleksi Terbaru</h2>
      <?php renderSearchForm('Cari koleksi buku...'); ?>
      <table border="0" cellspacing="0" cellpadding="8">
        <tr>
          <th>JUDUL BUKU</th>
          <th>PENGARANG</th>
          <th>GENRE</th>
          <th>STOK</th>
        </tr>
        <?php if (mysqli_num_rows($terbaru) > 0) { ?>
          <?php while ($row = mysqli_fetch_assoc($terbaru)) { ?>
          <tr>
            <td><?php echo htmlspecialchars($row['JUDUL_BUKU'], ENT_QUOTES, 'UTF-8'); ?></td>
            <td><?php echo htmlspecialchars($row['PENGARANG'], ENT_QUOTES, 'UTF-8'); ?></td>
            <td><?php echo htmlspecialchars($row['GENRE'], ENT_QUOTES, 'UTF-8'); ?></td>
            <td><?php echo htmlspecialchars($row['STOK'], ENT_QUOTES, 'UTF-8'); ?></td>
          </tr>
          <?php } ?>
        <?php } else { ?>
          <tr>
            <td colspan="4">Koleksi buku yang dicari tidak ditemukan.</td>
          </tr>
        <?php } ?>
      </table>
      <?php renderPagination($pagination); ?>
    </div>
  </div>
</body>

</html>
