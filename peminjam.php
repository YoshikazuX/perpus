<?php
session_start();

if (!isset($_SESSION['ID_USER'])) {
    header("Location: login.php");
    exit;
}

include "koneksi.php";
require_once "pagination_helper.php";
require_once "search_helper.php";

$keyword = trim($_GET['keyword'] ?? '');
$keywordEscaped = mysqli_real_escape_string($koneksi, $keyword);
$whereClause = "";

if ($keyword !== '') {
    $whereClause = " WHERE ID_PEMINJAM LIKE '%$keywordEscaped%'
        OR NAMA LIKE '%$keywordEscaped%'
        OR ALAMAT LIKE '%$keywordEscaped%'
        OR GENDER LIKE '%$keywordEscaped%'
        OR HP LIKE '%$keywordEscaped%'";
}

$pagination = getPaginationData($koneksi, "SELECT COUNT(*) AS total FROM peminjam$whereClause");
$data = mysqli_query($koneksi, "SELECT * FROM peminjam$whereClause ORDER BY ID_PEMINJAM DESC LIMIT {$pagination['per_page']} OFFSET {$pagination['offset']}");
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="style.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
  <title>Data Peminjam - PerpustakaanKu</title>
</head>

<body>
  <div class="sidebar">
    <h2>PerpustakaanKu</h2>
    <ul>
      <li><a href="Dashboard.php">Dashboard</a></li>
      <li><a href="Buku.php">Buku</a></li>
      <li><a href="Peminjam.php" class="active">Peminjam</a></li>
      <li><a href="Petugas.php">Petugas</a></li>
      <li><a href="Peminjaman.php">Peminjaman</a></li>
      <li><a href="user.php">User</a></li>
      <li><a href="Login.php">Logout</a></li>
    </ul>
  </div>

  <div class="main">
    <header>
      <h1>Data Peminjam</h1>
    </header>

    <div class="content">
      <h2>Daftar Peminjam</h2>
      <div class="tombol-tambah">
        <?php renderSearchForm('Cari data peminjam...'); ?>
        <a href="peminjam_tambah.php" class="btn-tambah">+ Tambah Data</a>
      </div>
      <table border="0" cellspacing="0" cellpadding="8">
        <tr>
          <th>ID PEMINJAM</th>
          <th>NAMA</th>
          <th>ALAMAT</th>
          <th>GENDER</th>
          <th>HP</th>
          <th>AKSI</th>
        </tr>

        <?php
        if (mysqli_num_rows($data) > 0) {
          while ($tampil = mysqli_fetch_array($data)) {
        ?>
          <tr>
            <td><?php echo htmlspecialchars($tampil['ID_PEMINJAM'], ENT_QUOTES, 'UTF-8'); ?></td>
            <td><?php echo htmlspecialchars($tampil['NAMA'], ENT_QUOTES, 'UTF-8'); ?></td>
            <td><?php echo htmlspecialchars($tampil['ALAMAT'], ENT_QUOTES, 'UTF-8'); ?></td>
            <td><?php echo htmlspecialchars($tampil['GENDER'], ENT_QUOTES, 'UTF-8'); ?></td>
            <td><?php echo htmlspecialchars($tampil['HP'], ENT_QUOTES, 'UTF-8'); ?></td>
            <td class="action-cell">
              <a href="peminjam_edit.php?ID_PEMINJAM=<?php echo urlencode($tampil['ID_PEMINJAM']); ?>" class="btn-action edit">Edit</a>
              <a href="peminjam_hapus.php?ID_PEMINJAM=<?php echo urlencode($tampil['ID_PEMINJAM']); ?>" class="btn-action delete">Hapus</a>
            </td>
          </tr>
        <?php
          }
        } else {
        ?>
          <tr>
            <td colspan="6">Belum ada data peminjam.</td>
          </tr>
        <?php
        }
        ?>
      </table>
      <?php renderPagination($pagination); ?>
    </div>
  </div>
</body>

</html>
