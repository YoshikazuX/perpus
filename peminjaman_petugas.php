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
    $whereClause = " WHERE peminjaman.ID_PEMINJAMAN LIKE '%$keywordEscaped%'
        OR buku.JUDUL_BUKU LIKE '%$keywordEscaped%'
        OR peminjam.NAMA LIKE '%$keywordEscaped%'
        OR peminjaman.ID_PETUGAS LIKE '%$keywordEscaped%'
        OR petugas.NAMA LIKE '%$keywordEscaped%'
        OR peminjaman.TANGGAL_MULAI LIKE '%$keywordEscaped%'
        OR peminjaman.TANGGAL_SELESAI LIKE '%$keywordEscaped%'
        OR peminjaman.STATUS LIKE '%$keywordEscaped%'";
}

$pagination = getPaginationData($koneksi, "
  SELECT COUNT(*) AS total
  FROM peminjaman
  JOIN buku ON peminjaman.ISBN = buku.ISBN
  JOIN peminjam ON peminjaman.ID_PEMINJAM = peminjam.ID_PEMINJAM
  LEFT JOIN petugas ON peminjaman.ID_PETUGAS = petugas.ID_PETUGAS
  $whereClause
");
$data = mysqli_query($koneksi, "
  SELECT
    peminjaman.*,
    buku.JUDUL_BUKU,
    peminjam.NAMA AS NAMA_PEMINJAM,
    CASE
      WHEN peminjaman.ID_PETUGAS = 'Admin' THEN 'Admin'
      ELSE COALESCE(petugas.NAMA, peminjaman.ID_PETUGAS)
    END AS NAMA_PETUGAS
  FROM peminjaman
  JOIN buku ON peminjaman.ISBN = buku.ISBN
  JOIN peminjam ON peminjaman.ID_PEMINJAM = peminjam.ID_PEMINJAM
  LEFT JOIN petugas ON peminjaman.ID_PETUGAS = petugas.ID_PETUGAS
  $whereClause
  ORDER BY peminjaman.ID_PEMINJAMAN DESC
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
  <title>Data Peminjaman Petugas - PerpustakaanKu</title>
</head>

<body>
  <div class="sidebar">
    <h2>PerpustakaanKu</h2>
    <ul>
      <li><a href="dashboard_petugas.php">Dashboard Utama</a></li>
      <li><a href="buku_petugas.php">Buku</a></li>
      <li><a href="peminjaman_petugas.php" class="active">Peminjaman</a></li>
      <li><a href="logout.php">Logout</a></li>
    </ul>
  </div>

  <div class="main">
    <header>
      <h1>Data Peminjaman</h1>
    </header>

    <div class="content">
      <h2>Daftar Peminjaman</h2>
      <div class="tombol-tambah">
        <?php renderSearchForm('Cari data peminjaman...'); ?>
        <a href="peminjaman_tambah.php" class="btn-tambah">+ Tambah Data</a>
      </div>
      <table border="0" cellspacing="0" cellpadding="8">
        <tr>
          <th>ID PEMINJAMAN</th>
          <th>JUDUL BUKU</th>
          <th>NAMA PEMINJAM</th>
          <th>PETUGAS</th>
          <th>TANGGAL MULAI</th>
          <th>TANGGAL SELESAI</th>
          <th>STATUS</th>
          <th>OPSI</th>
          <th>AKSI</th>
        </tr>

        <?php if (mysqli_num_rows($data) > 0) { ?>
          <?php while ($tampil = mysqli_fetch_array($data)) { ?>
            <tr>
              <td><?php echo htmlspecialchars($tampil['ID_PEMINJAMAN'], ENT_QUOTES, 'UTF-8'); ?></td>
              <td><?php echo htmlspecialchars($tampil['JUDUL_BUKU'], ENT_QUOTES, 'UTF-8'); ?></td>
              <td><?php echo htmlspecialchars($tampil['NAMA_PEMINJAM'], ENT_QUOTES, 'UTF-8'); ?></td>
              <td><?php echo htmlspecialchars($tampil['NAMA_PETUGAS'], ENT_QUOTES, 'UTF-8'); ?></td>
              <td><?php echo htmlspecialchars($tampil['TANGGAL_MULAI'], ENT_QUOTES, 'UTF-8'); ?></td>
              <td><?php echo $tampil['TANGGAL_SELESAI'] ? htmlspecialchars($tampil['TANGGAL_SELESAI'], ENT_QUOTES, 'UTF-8') : '-'; ?></td>
              <td><?php echo htmlspecialchars($tampil['STATUS'], ENT_QUOTES, 'UTF-8'); ?></td>
              <td class="option-cell">
                <div class="action-buttons">
                  <a href="peminjaman_edit.php?ID_PEMINJAMAN=<?= urlencode($tampil['ID_PEMINJAMAN']); ?>" class="btn-action edit">Edit</a>
                  <a href="peminjaman_hapus.php?ID_PEMINJAMAN=<?= urlencode($tampil['ID_PEMINJAMAN']); ?>" class="btn-action delete">Hapus</a>
                </div>
              </td>
              <td class="action-cell">
                <div class="option-buttons">
                  <?php if ($tampil['STATUS'] === 'Dipinjam') { ?>
                    <a href="peminjaman_kembalikan.php?ID_PEMINJAMAN=<?= urlencode($tampil['ID_PEMINJAMAN']); ?>" class="btn-action return">Kembalikan</a>
                  <?php } else { ?>
                    <span class="option-placeholder">-</span>
                  <?php } ?>
                </div>
              </td>
            </tr>
          <?php } ?>
        <?php } else { ?>
          <tr>
            <td colspan="9">Belum ada data peminjaman.</td>
          </tr>
        <?php } ?>
      </table>
      <?php renderPagination($pagination); ?>
    </div>
  </div>
</body>

</html>
