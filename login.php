<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="style.css">
  <title>Login | PerpustakaanKu</title>
  \
</head>

<body class="login-body">
  <div class="login-container">
    <div class="logo">
      <span>📚</span>
    </div>
    <h2>PerpustakaanKu</h2>
    <p>Masuk ke akun Anda untuk melanjutkan</p>
    <form action="dashboard.php" method="post">
      <input type="text" placeholder="Username" required>
      <input type="password" placeholder="Password" required>
      <button type="submit">Masuk</button>
    </form>
    <div class="footer">
      <p>Belum punya akun? <a href="Registrasi.php">Daftar sekarang</a></p>
    </div>
  </div>
</body>

</html>