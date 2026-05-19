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
  <title>Tambah Petugas - PerpustakaanKu</title>
</head>

<body>
  <div class="sidebar">
    <h2>PerpustakaanKu</h2>
    <ul>
      <li><a href="Dashboard.php">Dashboard</a></li>
      <li><a href="Buku.php">Buku</a></li>
      <li><a href="Peminjam.php">Peminjam</a></li>
      <li><a href="Petugas.php" class="active">Petugas</a></li>
      <li><a href="Peminjaman.php">Peminjaman</a></li>
      <li><a href="user.php">User</a></li>
      <li><a href="Login.php">Logout</a></li>
    </ul>
  </div>

  <div class="main">
    <header>
      <h1>Tambah Petugas</h1>
    </header>

    <div class="content">
      <div class="form-container">
        <div class="form-header">
          <h2>Tambah Data Petugas</h2>
          <p>Masukkan informasi petugas baru di bawah ini</p>
        </div>

        <form method="post" action="petugas_tambah_aksi.php">
          <div class="form-group">
            <label for="nama">Nama</label>
            <input type="text" id="nama" name="NAMA" maxlength="50" required>
          </div>

          <div class="form-group">
            <label for="gender">Gender</label>
            <select id="gender" name="GENDER" required>
              <option value="">Pilih gender</option>
              <option value="PRIA">PRIA</option>
              <option value="WANITA">WANITA</option>
            </select>
          </div>

          <div class="form-group">
            <label for="alamat">Alamat</label>
            <input type="text" id="alamat" name="ALAMAT" maxlength="100" required>
          </div>

          <div class="form-group">
            <label for="hp">No. HP</label>
            <input type="text" id="hp" name="HP" maxlength="15" required>
          </div>

          <div class="form-actions">
            <a href="petugas.php" class="btn-cancel">Batal</a>
            <button type="submit" class="btn-submit">Simpan</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</body>

</html>
