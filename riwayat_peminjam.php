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
$namaFilter = mysqli_real_escape_string($koneksi, $_SESSION['NAMA_USER'] ?? '');
$usernameFilter = mysqli_real_escape_string($koneksi, $_SESSION['USERNAME'] ?? '');

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
  WHERE peminjam.NAMA = '$namaFilter' OR peminjam.NAMA = '$usernameFilter'
  ORDER BY peminjaman.TANGGAL_MULAI DESC
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
      <div class="user-info">
        <span><?php echo $namaUser; ?></span>
        <img src="https://i.pravatar.cc/100" alt="User">
      </div>
    </header>

    <div class="content">
      <h2>Riwayat Akun Anda</h2>
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
    </div>
  </div>
</body>

</html>
