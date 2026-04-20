<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="style.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
  <title>Registrasi</title>
</head>

<body class="register-body">
  <div class="register-container">
    <div class="logo">
      <span>📖</span>
    </div>
    <h2>Daftar Akun</h2>
    <p>Buat akun baru untuk mengakses Perpustakaan</p>

    <form action="registrasi_aksi.php" method="post" class="register-form-grid">
      <input type="text" name="NAMA_USER" placeholder="Nama Lengkap" required>
      <input type="text" name="USERNAME" placeholder="Username" required>
      <input type="text" name="ALAMAT" placeholder="Alamat" class="register-full-row" required>
      <select name="GENDER" required>
        <option value="">Pilih Gender</option>
        <option value="PRIA">PRIA</option>
        <option value="WANITA">WANITA</option>
      </select>
      <input type="text" name="HP" placeholder="No. HP" required>
      <input type="password" name="PASSWORD" placeholder="Password" required>
      <input type="password" name="KONFIRMASI_PASSWORD" placeholder="Konfirmasi Password" required>
      <button type="submit" class="register-full-row">Daftar</button>
    </form>

    <div class="footer">
      <p>Sudah punya akun? <a href="login.php">Kembali login</a></p>
    </div>
  </div>
</body>

</html>
