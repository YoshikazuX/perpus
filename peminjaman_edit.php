<?php
session_start();

if (!isset($_SESSION['ID_USER'])) {
    header("Location: login.php");
    exit;
}

include 'koneksi.php';

$idPeminjaman = mysqli_real_escape_string($koneksi, $_GET['ID_PEMINJAMAN']);
$data = mysqli_query($koneksi, "SELECT * FROM peminjaman WHERE ID_PEMINJAMAN='$idPeminjaman'");
$tampil = mysqli_fetch_array($data);

if (!$tampil) {
    header("Location: peminjaman.php");
    exit;
}

$buku = mysqli_query($koneksi, "SELECT ISBN, JUDUL_BUKU FROM buku");
$peminjam = mysqli_query($koneksi, "SELECT ID_PEMINJAM, NAMA FROM peminjam");
$petugas = mysqli_query($koneksi, "SELECT ID_PETUGAS, NAMA FROM petugas");
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
      <li><a href="Dashboard.php">Dashboard</a></li>
      <li><a href="Buku.php">Buku</a></li>
      <li><a href="Peminjam.php">Peminjam</a></li>
      <li><a href="Petugas.php">Petugas</a></li>
      <li><a href="Peminjaman.php" class="active">Peminjaman</a></li>
      <li><a href="Login.php">Logout</a></li>
    </ul>
  </div>

  <div class="main">
    <header>
      <h1>Edit Peminjaman</h1>
      <div class="user-info">
        <span>Admin</span>
        <img src="https://i.pravatar.cc/100" alt="User">
      </div>
    </header>

    <div class="content">
      <div class="form-container loan-form-wrapper">
        <div class="form-header">
          <h2>Edit Transaksi Peminjaman</h2>
          <p>Perbarui informasi peminjaman buku di bawah ini</p>
        </div>

        <form action="peminjaman_edit_aksi.php" method="POST" class="loan-form-grid">
          <div class="form-group">
            <label for="id_peminjaman">ID Peminjaman</label>
            <input type="text" id="id_peminjaman" name="id_peminjaman" value="<?= htmlspecialchars($tampil['ID_PEMINJAMAN'], ENT_QUOTES, 'UTF-8'); ?>" readonly>
          </div>

          <div class="form-group">
            <label for="jumlah">Jumlah</label>
            <input type="number" id="jumlah" name="jumlah" min="1" value="<?= htmlspecialchars($tampil['JUMLAH'], ENT_QUOTES, 'UTF-8'); ?>" required>
          </div>

          <div class="form-group full-row">
            <label for="isbn">Judul Buku</label>
            <select id="isbn" name="isbn" required>
              <option value="">-- Pilih Buku --</option>
              <?php while ($b = mysqli_fetch_assoc($buku)) : ?>
                <option value="<?= htmlspecialchars($b['ISBN'], ENT_QUOTES, 'UTF-8'); ?>" <?= $b['ISBN'] === $tampil['ISBN'] ? 'selected' : ''; ?>>
                  <?= htmlspecialchars($b['JUDUL_BUKU'], ENT_QUOTES, 'UTF-8'); ?>
                </option>
              <?php endwhile; ?>
            </select>
          </div>

          <div class="form-group">
            <label for="tgl_mulai">Tanggal Peminjaman</label>
            <input type="date" id="tgl_mulai" name="tgl_mulai" value="<?= htmlspecialchars($tampil['TANGGAL_MULAI'], ENT_QUOTES, 'UTF-8'); ?>" required>
          </div>

          <div class="form-group">
            <label for="tgl_selesai">Tanggal Kembali</label>
            <input type="date" id="tgl_selesai" name="tgl_selesai" value="<?= $tampil['TANGGAL_SELESAI'] ? htmlspecialchars($tampil['TANGGAL_SELESAI'], ENT_QUOTES, 'UTF-8') : ''; ?>">
          </div>

          <div class="form-group">
            <label for="id_peminjam">Nama Peminjam</label>
            <select id="id_peminjam" name="id_peminjam" required>
              <option value="">-- Pilih Peminjam --</option>
              <?php while ($p = mysqli_fetch_assoc($peminjam)) : ?>
                <option value="<?= htmlspecialchars($p['ID_PEMINJAM'], ENT_QUOTES, 'UTF-8'); ?>" <?= $p['ID_PEMINJAM'] === $tampil['ID_PEMINJAM'] ? 'selected' : ''; ?>>
                  <?= htmlspecialchars($p['NAMA'], ENT_QUOTES, 'UTF-8'); ?>
                </option>
              <?php endwhile; ?>
            </select>
          </div>

          <div class="form-group">
            <label for="id_petugas">Petugas</label>
            <select id="id_petugas" name="id_petugas" required>
              <option value="">-- Pilih Petugas --</option>
              <?php while ($pt = mysqli_fetch_assoc($petugas)) : ?>
                <option value="<?= htmlspecialchars($pt['ID_PETUGAS'], ENT_QUOTES, 'UTF-8'); ?>" <?= $pt['ID_PETUGAS'] === $tampil['ID_PETUGAS'] ? 'selected' : ''; ?>>
                  <?= htmlspecialchars($pt['NAMA'], ENT_QUOTES, 'UTF-8'); ?>
                </option>
              <?php endwhile; ?>
            </select>
          </div>

          <div class="form-actions loan-form-actions full-row">
            <a href="peminjaman.php" class="btn-cancel">Batal</a>
            <button type="submit" class="btn-submit">Simpan Perubahan</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</body>

</html>
