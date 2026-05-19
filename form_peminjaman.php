<?php
session_start();

if (!isset($_SESSION['ID_USER'])) {
    header("Location: login.php");
    exit;
}

include 'koneksi.php';

$buku = mysqli_query($koneksi, "SELECT ISBN, JUDUL_BUKU, STOK FROM buku ORDER BY JUDUL_BUKU ASC");
$peminjam = mysqli_query($koneksi, "SELECT ID_PEMINJAM, NAMA FROM peminjam");

$idBaru = 'PMJM001';
$dataId = mysqli_query($koneksi, "SELECT ID_PEMINJAMAN FROM peminjaman ORDER BY ID_PEMINJAMAN DESC LIMIT 1");

if ($dataId && mysqli_num_rows($dataId) > 0) {
    $lastRow = mysqli_fetch_assoc($dataId);
    $angkaId = (int) preg_replace('/\D/', '', $lastRow['ID_PEMINJAMAN']);
    $idBaru = 'PMJM' . str_pad((string) ($angkaId + 1), 3, '0', STR_PAD_LEFT);
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="style.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
  <title>Tambah Peminjaman - PerpustakaanKu</title>
</head>

<body class="loan-page">
  <div class="sidebar">
    <h2>PerpustakaanKu</h2>
    <ul>
      <li><a href="Dashboard.php">Dashboard</a></li>
      <li><a href="Buku.php">Buku</a></li>
      <li><a href="Peminjam.php">Peminjam</a></li>
      <li><a href="Petugas.php">Petugas</a></li>
      <li><a href="Peminjaman.php" class="active">Peminjaman</a></li>
      <li><a href="user.php">User</a></li>
      <li><a href="Login.php">Logout</a></li>
    </ul>
  </div>

  <div class="main">
    <header>
      <h1>Tambah Peminjaman</h1>
    </header>

    <div class="content">
      <div class="form-container loan-form-wrapper">
        <div class="form-header">
          <h2>Form Transaksi Peminjaman</h2>
          <p>Masukkan informasi peminjaman buku di bawah ini</p>
        </div>

        <form action="simpan_peminjaman.php" method="POST" class="loan-form-grid">
          <input type="hidden" name="id_peminjaman" value="<?= htmlspecialchars($idBaru, ENT_QUOTES, 'UTF-8'); ?>">

          <div class="form-group full-row">
            <label for="isbn">Judul Buku</label>
            <select id="isbn" name="isbn" required>
              <option value="">-- Pilih Buku --</option>
              <?php while ($b = mysqli_fetch_assoc($buku)) : ?>
                <?php $stok = (int) $b['STOK']; ?>
                <option value="<?= htmlspecialchars($b['ISBN'], ENT_QUOTES, 'UTF-8'); ?>" data-stock="<?= $stok; ?>" <?= $stok < 1 ? 'disabled' : ''; ?>>
                  <?= htmlspecialchars($b['JUDUL_BUKU'], ENT_QUOTES, 'UTF-8'); ?> (Stok: <?= $stok; ?>)
                </option>
              <?php endwhile; ?>
            </select>
          </div>

          <div class="form-group full-row">
            <label for="id_peminjam">Nama Peminjam</label>
            <select id="id_peminjam" name="id_peminjam" required>
              <option value="">-- Pilih Peminjam --</option>
              <?php while ($p = mysqli_fetch_assoc($peminjam)) : ?>
                <option value="<?= htmlspecialchars($p['ID_PEMINJAM'], ENT_QUOTES, 'UTF-8'); ?>">
                  <?= htmlspecialchars($p['NAMA'], ENT_QUOTES, 'UTF-8'); ?>
                </option>
              <?php endwhile; ?>
            </select>
          </div>

          <div class="form-actions loan-form-actions full-row">
            <a href="peminjaman.php" class="btn-cancel">Batal</a>
            <button type="submit" class="btn-submit">Simpan</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</body>

</html>
