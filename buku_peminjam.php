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

$namaUser = htmlspecialchars($_SESSION['NAMA_USER'] ?? $_SESSION['USERNAME'], ENT_QUOTES, 'UTF-8');
$keyword = trim($_GET['keyword'] ?? '');
$keywordEscaped = mysqli_real_escape_string($koneksi, $keyword);
$query = "SELECT * FROM buku";

if ($keyword !== '') {
    $query .= " WHERE ISBN LIKE '%$keywordEscaped%'
        OR JUDUL_BUKU LIKE '%$keywordEscaped%'
        OR PENGARANG LIKE '%$keywordEscaped%'
        OR PENERBIT LIKE '%$keywordEscaped%'";
}

$query .= " ORDER BY JUDUL_BUKU ASC";
$data = mysqli_query($koneksi, $query);
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="style.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
  <title>Katalog Buku - PerpustakaanKu</title>
</head>

<body>
  <div class="sidebar">
    <h2>PerpustakaanKu</h2>
    <ul>
      <li><a href="dashboard_peminjam.php">Dashboard</a></li>
      <li><a href="buku_peminjam.php" class="active">Buku</a></li>
      <li><a href="riwayat_peminjam.php">Riwayat</a></li>
      <li><a href="logout.php">Logout</a></li>
    </ul>
  </div>

  <div class="main">
    <header>
      <h1>Katalog Buku</h1>
      <div class="user-info">
        <span><?php echo $namaUser; ?></span>
        <img src="https://i.pravatar.cc/100" alt="User">
      </div>
    </header>

    <div class="content">
      <h2>Daftar Buku Perpustakaan</h2>
      <div class="toolbar-row">
        <form method="get" class="search-form">
          <input type="text" name="keyword" class="search-input" placeholder="Cari judul, ISBN, pengarang..." value="<?php echo htmlspecialchars($keyword, ENT_QUOTES, 'UTF-8'); ?>">
          <button type="submit" class="btn-search">Cari</button>
          <?php if ($keyword !== '') { ?>
            <a href="buku_peminjam.php" class="btn-reset">Reset</a>
          <?php } ?>
        </form>
      </div>
      <table border="0" cellspacing="0" cellpadding="8">
        <tr>
          <th>ISBN</th>
          <th>JUDUL BUKU</th>
          <th>PENGARANG</th>
          <th>PENERBIT</th>
          <th>TAHUN TERBIT</th>
          <th>STOK</th>
        </tr>
        <?php if (mysqli_num_rows($data) > 0) { ?>
          <?php while ($row = mysqli_fetch_assoc($data)) { ?>
            <tr>
              <td><?php echo htmlspecialchars($row['ISBN'], ENT_QUOTES, 'UTF-8'); ?></td>
              <td><?php echo htmlspecialchars($row['JUDUL_BUKU'], ENT_QUOTES, 'UTF-8'); ?></td>
              <td><?php echo htmlspecialchars($row['PENGARANG'], ENT_QUOTES, 'UTF-8'); ?></td>
              <td><?php echo htmlspecialchars($row['PENERBIT'], ENT_QUOTES, 'UTF-8'); ?></td>
              <td><?php echo htmlspecialchars($row['TAHUN_TERBIT'], ENT_QUOTES, 'UTF-8'); ?></td>
              <td><?php echo htmlspecialchars($row['STOK'], ENT_QUOTES, 'UTF-8'); ?></td>
            </tr>
          <?php } ?>
        <?php } else { ?>
          <tr>
            <td colspan="6">Buku yang dicari tidak ditemukan.</td>
          </tr>
        <?php } ?>
      </table>
    </div>
  </div>
</body>

</html>
