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

    <form action="Dashboard.php" method="post">
      <input type="text" placeholder="Nama Lengkap" required>
      <input type="email" placeholder="Email" required>
      <input type="text" placeholder="Username" required>
      <input type="password" placeholder="Password" required>
      <input type="password" placeholder="Konfirmasi Password" required>
      <button type="submit">Daftar</button>
    </form>

    <div class="footer">
      <p>Sudah punya akun? <a href="Login.php">Kembali login</a></p>
    </div>
  </div>
</body>

</html>