<?php
session_start();

if (!isset($_SESSION['ID_USER'])) {
    header("Location: login.php");
    exit;
}

include "koneksi.php";
require_once "genre_helper.php";

$genreOptions = getGenreOptions($koneksi);
$tahunSekarang = (int) date('Y');
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="style.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
  <title>Tambah Buku - PerpustakaanKu</title>
</head>

<body class="book-form-page">
  <div class="sidebar">
    <h2>PerpustakaanKu</h2>
    <ul>
      <li><a href="Dashboard.php">Dashboard</a></li>
      <li><a href="Buku.php" class="active">Buku</a></li>
      <li><a href="Peminjam.php">Peminjam</a></li>
      <li><a href="Petugas.php">Petugas</a></li>
      <li><a href="Peminjaman.php">Peminjaman</a></li>
      <li><a href="user.php">User</a></li>
      <li><a href="Login.php">Logout</a></li>
    </ul>
  </div>

  <div class="main">
    <header>
      <h1>Tambah Buku</h1>
    </header>

    <div class="content">
      <div class="form-container">
        <div class="form-header">
          <h2>Tambah Data Buku</h2>
          <p>Masukkan informasi buku baru di bawah ini</p>
        </div>

        <form method="post" action="buku_tambah_aksi.php" class="book-form-grid">
          <div class="form-group">
            <label for="isbn">ISBN</label>
            <input type="text" id="isbn" name="ISBN" placeholder="Contoh: 9786020324789" required>
          </div>

          <div class="form-group">
            <label for="stok">Stok</label>
            <input type="number" id="stok" name="STOK" min="0" placeholder="0" required>
          </div>

          <div class="form-group book-form-full">
            <label for="judul">Judul Buku</label>
            <input type="text" id="judul" name="JUDUL_BUKU" placeholder="Masukkan judul buku" required>
          </div>

          <div class="form-group">
            <label for="pengarang">Pengarang</label>
            <input type="text" id="pengarang" name="PENGARANG" placeholder="Nama pengarang" required>
          </div>

          <div class="form-group">
            <label for="genre">Genre</label>
            <select id="genre" name="GENRE" required>
              <option value="">Pilih genre buku</option>
              <?php foreach ($genreOptions as $genre) { ?>
                <option value="<?php echo htmlspecialchars($genre, ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($genre, ENT_QUOTES, 'UTF-8'); ?></option>
              <?php } ?>
            </select>
          </div>

          <div class="form-group">
            <label for="penerbit">Penerbit</label>
            <input type="text" id="penerbit" name="PENERBIT" placeholder="Nama penerbit" required>
          </div>

          <div class="form-group">
            <label for="tahun">Tahun Terbit</label>
            <input type="number" id="tahun" name="TAHUN_TERBIT" min="1000" max="<?php echo $tahunSekarang; ?>" placeholder="<?php echo $tahunSekarang; ?>" required>
          </div>

          <div class="form-actions">
            <a href="buku.php" class="btn-cancel">Batal</a>
            <button type="submit" class="btn-submit">Simpan</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</body>

</html>
