<?php
session_start();

if (!isset($_SESSION['ID_USER'])) {
  header("Location: login.php");
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
  <title>Edit Buku - PerpustakaanKu</title>
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
      <h1>Edit Buku</h1>
    </header>

    <div class="content">
      <?php
include 'koneksi.php';
require_once "genre_helper.php";
$ISBN = $_GET['ISBN'];
$data = mysqli_query($koneksi, "SELECT * FROM buku WHERE ISBN='$ISBN'");
$tampil = mysqli_fetch_array($data);
$genreOptions = getGenreOptions($koneksi);
$genreSekarang = $tampil['GENRE'] ?? '';
$tahunSekarang = (int) date('Y');

if ($genreSekarang !== '' && !in_array($genreSekarang, $genreOptions, true)) {
    $genreOptions[] = $genreSekarang;
}
?>

      <div class="form-container">
        <div class="form-header">
          <h2>Edit Data Buku</h2>
          <p>Perbarui informasi buku di bawah ini</p>
        </div>

        <form method="post" action="buku_edit_aksi.php" class="book-form-grid">
          <input type="hidden" name="ISBN" value="<?php echo $tampil['ISBN']; ?>">

          <div class="form-group">
            <label for="isbn_display">ISBN</label>
            <input type="text" id="isbn_display" value="<?php echo htmlspecialchars($tampil['ISBN'], ENT_QUOTES, 'UTF-8'); ?>" disabled>
          </div>

          <div class="form-group">
            <label for="stok">Stok</label>
            <input type="number" id="stok" name="STOK" min="0" value="<?php echo htmlspecialchars($tampil['STOK'], ENT_QUOTES, 'UTF-8'); ?>" required>
          </div>

          <div class="form-group book-form-full">
            <label for="judul">Judul Buku</label>
            <input type="text" id="judul" name="JUDUL_BUKU" value="<?php echo htmlspecialchars($tampil['JUDUL_BUKU'], ENT_QUOTES, 'UTF-8'); ?>" placeholder="Masukkan judul buku" required>
          </div>

          <div class="form-group">
            <label for="pengarang">Pengarang</label>
            <input type="text" id="pengarang" name="PENGARANG" value="<?php echo htmlspecialchars($tampil['PENGARANG'], ENT_QUOTES, 'UTF-8'); ?>" placeholder="Nama pengarang" required>
          </div>

          <div class="form-group">
            <label for="genre">Genre</label>
            <select id="genre" name="GENRE" required>
              <option value="">Pilih genre buku</option>
              <?php foreach ($genreOptions as $genre) { ?>
                <option value="<?php echo htmlspecialchars($genre, ENT_QUOTES, 'UTF-8'); ?>" <?php echo $genre === $genreSekarang ? 'selected' : ''; ?>>
                  <?php echo htmlspecialchars($genre, ENT_QUOTES, 'UTF-8'); ?>
                </option>
              <?php } ?>
            </select>
          </div>

          <div class="form-group">
            <label for="penerbit">Penerbit</label>
            <input type="text" id="penerbit" name="PENERBIT" value="<?php echo htmlspecialchars($tampil['PENERBIT'], ENT_QUOTES, 'UTF-8'); ?>" placeholder="Nama penerbit" required>
          </div>

          <div class="form-group">
            <label for="tahun">Tahun Terbit</label>
            <input type="number" id="tahun" name="TAHUN_TERBIT" min="1000" max="<?php echo $tahunSekarang; ?>" value="<?php echo htmlspecialchars($tampil['TAHUN_TERBIT'], ENT_QUOTES, 'UTF-8'); ?>" placeholder="<?php echo $tahunSekarang; ?>" required>
          </div>

          <div class="form-actions">
            <a href="buku.php" class="btn-cancel">Batal</a>
            <button type="submit" class="btn-submit">Simpan Perubahan</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</body>

</html>
