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
  <title>Tambah Peminjam - PerpustakaanKu</title>
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
      <li><a href="Login.php">Logout</a></li>
    </ul>
  </div>

  <div class="main">
    <header>
      <h1>Tambah Peminjam</h1>
      <div class="user-info">
        <span>Admin</span>
        <img src="https://i.pravatar.cc/100" alt="User">
      </div>
    </header>

    <div class="content">
      <div class="form-container">
        <div class="form-header">
          <h2>Tambah Data Peminjam</h2>
          <p>Masukkan informasi peminjam baru di bawah ini</p>
        </div>

        <form method="post" action="peminjam_tambah_aksi.php">
          <div class="form-group">
            <label for="id_peminjam">ID Peminjam</label>
            <input type="text" id="id_peminjam" name="ID_PEMINJAM" maxlength="20" required>
          </div>

          <div class="form-group">
            <label for="nama">Nama</label>
            <input type="text" id="nama" name="NAMA" maxlength="100" required>
          </div>

          <div class="form-group">
            <label for="alamat">Alamat</label>
            <input type="text" id="alamat" name="ALAMAT" maxlength="100" required>
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
            <label for="hp">No. HP</label>
            <input type="text" id="hp" name="HP" maxlength="20" required>
          </div>

          <div class="form-actions">
            <a href="peminjam.php" class="btn-cancel">Batal</a>
            <button type="submit" class="btn-submit">Simpan</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</body>

</html>
