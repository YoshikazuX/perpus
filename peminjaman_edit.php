<?php
session_start();

if (!isset($_SESSION['ID_USER'])) {
    header("Location: login.php");
    exit;
}

include 'koneksi.php';

$isPetugas = ($_SESSION['LEVEL'] ?? '') === 'Petugas';
$dashboardLink = $isPetugas ? 'dashboard_petugas.php' : 'dashboard.php';
$bukuLink = $isPetugas ? 'buku_petugas.php' : 'buku.php';
$peminjamanListPage = $isPetugas ? 'peminjaman_petugas.php' : 'peminjaman.php';

$idPeminjaman = mysqli_real_escape_string($koneksi, $_GET['ID_PEMINJAMAN']);
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
  WHERE peminjaman.ID_PEMINJAMAN='$idPeminjaman'
  LIMIT 1
");
$tampil = mysqli_fetch_array($data);

if (!$tampil) {
    header("Location: $peminjamanListPage");
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
  <title>Edit Peminjaman - PerpustakaanKu</title>
</head>

<body class="loan-page">
  <div class="sidebar">
    <h2>PerpustakaanKu</h2>
    <ul>
      <li><a href="<?= $dashboardLink; ?>"><?= $isPetugas ? 'Dashboard Utama' : 'Dashboard'; ?></a></li>
      <li><a href="<?= $bukuLink; ?>">Buku</a></li>
      <?php if (!$isPetugas) { ?>
        <li><a href="peminjam.php">Peminjam</a></li>
        <li><a href="petugas.php">Petugas</a></li>
      <?php } ?>
      <li><a href="<?= $peminjamanListPage; ?>" class="active">Peminjaman</a></li>
      <?php if (!$isPetugas) { ?>
        <li><a href="user.php">User</a></li>
      <?php } ?>
      <li><a href="logout.php">Logout</a></li>
    </ul>
  </div>

  <div class="main">
    <header>
      <h1>Edit Peminjaman</h1>
    </header>

    <div class="content">
      <div class="form-container loan-form-wrapper">
        <div class="form-header">
          <h2>Edit Transaksi Peminjaman</h2>
          <p>Perbarui informasi peminjaman buku di bawah ini</p>
        </div>

        <form action="peminjaman_edit_aksi.php" method="POST" class="loan-form-grid">
          <input type="hidden" name="id_peminjaman" value="<?= htmlspecialchars($tampil['ID_PEMINJAMAN'], ENT_QUOTES, 'UTF-8'); ?>">

          <div class="form-group full-row">
            <label for="judul_buku">Judul Buku</label>
            <input type="text" id="judul_buku" value="<?= htmlspecialchars($tampil['JUDUL_BUKU'], ENT_QUOTES, 'UTF-8'); ?>" readonly>
          </div>

          <div class="form-group full-row">
            <label for="nama_peminjam">Nama Peminjam</label>
            <input type="text" id="nama_peminjam" value="<?= htmlspecialchars($tampil['NAMA_PEMINJAM'], ENT_QUOTES, 'UTF-8'); ?>" readonly>
          </div>

          <div class="form-group full-row">
            <label for="nama_petugas">Petugas</label>
            <input type="text" id="nama_petugas" value="<?= htmlspecialchars($tampil['NAMA_PETUGAS'], ENT_QUOTES, 'UTF-8'); ?>" readonly>
          </div>

          <div class="form-group">
            <label for="tgl_mulai">Tanggal Pinjam</label>
            <input type="date" id="tgl_mulai" name="tgl_mulai" value="<?= htmlspecialchars($tampil['TANGGAL_MULAI'], ENT_QUOTES, 'UTF-8'); ?>" required>
          </div>

          <div class="form-group">
            <label for="tgl_selesai">Tanggal Kembali</label>
            <input type="date" id="tgl_selesai" name="tgl_selesai" value="<?= $tampil['TANGGAL_SELESAI'] ? htmlspecialchars($tampil['TANGGAL_SELESAI'], ENT_QUOTES, 'UTF-8') : ''; ?>">
          </div>

          <div class="form-actions loan-form-actions full-row">
            <a href="<?= $peminjamanListPage; ?>" class="btn-cancel">Batal</a>
            <button type="submit" class="btn-submit">Simpan Perubahan</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</body>

</html>
