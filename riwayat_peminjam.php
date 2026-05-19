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
$whereRiwayat = "(peminjam.NAMA = '$namaFilter' OR peminjam.NAMA = '$usernameFilter')";
$keyword = trim($_GET['keyword'] ?? '');
$keywordEscaped = mysqli_real_escape_string($koneksi, $keyword);
$keywordClause = "";

if ($keyword !== '') {
    $keywordClause = " AND (
        peminjaman.ID_PEMINJAMAN LIKE '%$keywordEscaped%'
        OR buku.JUDUL_BUKU LIKE '%$keywordEscaped%'
        OR peminjam.NAMA LIKE '%$keywordEscaped%'
        OR peminjaman.TANGGAL_MULAI LIKE '%$keywordEscaped%'
        OR peminjaman.TANGGAL_SELESAI LIKE '%$keywordEscaped%'
        OR peminjaman.STATUS LIKE '%$keywordEscaped%'
    )";
}

$pagination = getPaginationData($koneksi, "
  SELECT COUNT(*) AS total
  FROM peminjaman
  JOIN buku ON peminjaman.ISBN = buku.ISBN
  JOIN peminjam ON peminjaman.ID_PEMINJAM = peminjam.ID_PEMINJAM
  WHERE $whereRiwayat
  $keywordClause
");
$data = mysqli_query($koneksi, "
  SELECT 
    peminjaman.ID_PEMINJAMAN,
    buku.JUDUL_BUKU,
    peminjam.NAMA AS NAMA_PEMINJAM,
    peminjaman.TANGGAL_MULAI,
    peminjaman.TANGGAL_SELESAI,
    peminjaman.STATUS
  FROM peminjaman
  JOIN buku ON peminjaman.ISBN = buku.ISBN
  JOIN peminjam ON peminjaman.ID_PEMINJAM = peminjam.ID_PEMINJAM
  WHERE $whereRiwayat
  $keywordClause
  ORDER BY peminjaman.TANGGAL_MULAI DESC, peminjaman.ID_PEMINJAMAN DESC
  LIMIT {$pagination['per_page']} OFFSET {$pagination['offset']}
");
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="style.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
  <title>Riwayat Peminjaman - PerpustakaanKu</title>
</head>

<body>
  <div class="sidebar">
    <h2>PerpustakaanKu</h2>
    <ul>
      <li><a href="dashboard_peminjam.php">Dashboard</a></li>
      <li><a href="buku_peminjam.php">Buku</a></li>
      <li><a href="riwayat_peminjam.php" class="active">Riwayat</a></li>
      <li><a href="logout.php">Logout</a></li>
    </ul>
  </div>

  <div class="main">
    <header>
      <h1>Riwayat Peminjaman</h1>
    </header>

    <div class="content">
      <h2>Riwayat Akun Anda</h2>
      <?php renderSearchForm('Cari riwayat peminjaman...'); ?>
      <table border="0" cellspacing="0" cellpadding="8">
        <tr>
          <th>ID PEMINJAMAN</th>
          <th>JUDUL BUKU</th>
          <th>NAMA PEMINJAM</th>
          <th>TANGGAL MULAI</th>
          <th>TANGGAL SELESAI</th>
          <th>STATUS</th>
        </tr>
        <?php if (mysqli_num_rows($data) > 0) { ?>
          <?php while ($row = mysqli_fetch_assoc($data)) { ?>
            <tr>
              <td><?php echo htmlspecialchars($row['ID_PEMINJAMAN'], ENT_QUOTES, 'UTF-8'); ?></td>
              <td><?php echo htmlspecialchars($row['JUDUL_BUKU'], ENT_QUOTES, 'UTF-8'); ?></td>
              <td><?php echo htmlspecialchars($row['NAMA_PEMINJAM'], ENT_QUOTES, 'UTF-8'); ?></td>
              <td><?php echo htmlspecialchars($row['TANGGAL_MULAI'], ENT_QUOTES, 'UTF-8'); ?></td>
              <td><?php echo $row['TANGGAL_SELESAI'] ? htmlspecialchars($row['TANGGAL_SELESAI'], ENT_QUOTES, 'UTF-8') : '-'; ?></td>
              <td><?php echo htmlspecialchars($row['STATUS'], ENT_QUOTES, 'UTF-8'); ?></td>
            </tr>
          <?php } ?>
        <?php } else { ?>
          <tr>
            <td colspan="6">Belum ada riwayat peminjaman yang cocok dengan akun ini.</td>
          </tr>
        <?php } ?>
      </table>
      <?php renderPagination($pagination); ?>
    </div>
  </div>
</body>

</html>
