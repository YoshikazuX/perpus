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
require_once "pagination_helper.php";
require_once "search_helper.php";

$keyword = trim($_GET['keyword'] ?? '');
$keywordEscaped = mysqli_real_escape_string($koneksi, $keyword);
$whereClause = "";

if ($keyword !== '') {
    $whereClause = " WHERE ISBN LIKE '%$keywordEscaped%'
        OR JUDUL_BUKU LIKE '%$keywordEscaped%'
        OR PENGARANG LIKE '%$keywordEscaped%'
        OR PENERBIT LIKE '%$keywordEscaped%'
        OR GENRE LIKE '%$keywordEscaped%'";
}

$pagination = getPaginationData($koneksi, "SELECT COUNT(*) AS total FROM buku$whereClause");
$data = mysqli_query($koneksi, "SELECT * FROM buku$whereClause ORDER BY ISBN DESC LIMIT {$pagination['per_page']} OFFSET {$pagination['offset']}");
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="style.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
  <title>Data Buku Petugas - PerpustakaanKu</title>
</head>

<body>
  <div class="sidebar">
    <h2>PerpustakaanKu</h2>
    <ul>
      <li><a href="dashboard_petugas.php">Dashboard Utama</a></li>
      <li><a href="buku_petugas.php" class="active">Buku</a></li>
      <li><a href="peminjaman_petugas.php">Peminjaman</a></li>
      <li><a href="logout.php">Logout</a></li>
    </ul>
  </div>

  <div class="main">
    <header>
      <h1>Data Buku</h1>
    </header>

    <div class="content">
      <h2>Daftar Buku</h2>
      <div class="toolbar-row">
        <?php renderSearchForm('Cari data buku...'); ?>
      </div>
      <table border="0" cellspacing="0" cellpadding="8">
        <tr>
          <th>ISBN</th>
          <th>JUDUL BUKU</th>
          <th>PENGARANG</th>
          <th>PENERBIT</th>
          <th>GENRE</th>
          <th>TAHUN TERBIT</th>
          <th>STOK</th>
        </tr>

        <?php if (mysqli_num_rows($data) > 0) { ?>
          <?php while ($tampil = mysqli_fetch_array($data)) { ?>
            <tr>
              <td><?php echo htmlspecialchars($tampil['ISBN'], ENT_QUOTES, 'UTF-8'); ?></td>
              <td><?php echo htmlspecialchars($tampil['JUDUL_BUKU'], ENT_QUOTES, 'UTF-8'); ?></td>
              <td><?php echo htmlspecialchars($tampil['PENGARANG'], ENT_QUOTES, 'UTF-8'); ?></td>
              <td><?php echo htmlspecialchars($tampil['PENERBIT'], ENT_QUOTES, 'UTF-8'); ?></td>
              <td><?php echo htmlspecialchars($tampil['GENRE'], ENT_QUOTES, 'UTF-8'); ?></td>
              <td><?php echo htmlspecialchars($tampil['TAHUN_TERBIT'], ENT_QUOTES, 'UTF-8'); ?></td>
              <td><?php echo htmlspecialchars($tampil['STOK'], ENT_QUOTES, 'UTF-8'); ?></td>
            </tr>
          <?php } ?>
        <?php } else { ?>
          <tr>
            <td colspan="7">Buku yang dicari tidak ditemukan.</td>
          </tr>
        <?php } ?>
      </table>
      <?php renderPagination($pagination); ?>
    </div>
  </div>
</body>

</html>
