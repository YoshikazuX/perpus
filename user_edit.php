<?php
session_start();

if (!isset($_SESSION['ID_USER'])) {
    header("Location: login.php");
    exit;
}

include 'koneksi.php';

$idUser = mysqli_real_escape_string($koneksi, $_GET['ID_USER'] ?? '');
$data = mysqli_query($koneksi, "SELECT * FROM user WHERE ID_USER='$idUser' LIMIT 1");
$tampil = $data ? mysqli_fetch_assoc($data) : null;

if (!$tampil) {
    header("Location: user.php");
    exit;
}

$detail = [
    'GENDER' => '',
    'ALAMAT' => '',
    'HP' => '',
];

$namaUserEscaped = mysqli_real_escape_string($koneksi, $tampil['NAMA_USER']);

if ($tampil['LEVEL'] === 'Petugas') {
    $detailResult = mysqli_query($koneksi, "SELECT GENDER, ALAMAT, HP FROM petugas WHERE NAMA='$namaUserEscaped' LIMIT 1");
    $detail = $detailResult && mysqli_num_rows($detailResult) > 0 ? mysqli_fetch_assoc($detailResult) : $detail;
} elseif ($tampil['LEVEL'] === 'Peminjam' || $tampil['LEVEL'] === 'Anggota') {
    $detailResult = mysqli_query($koneksi, "SELECT GENDER, ALAMAT, HP FROM peminjam WHERE NAMA='$namaUserEscaped' LIMIT 1");
    $detail = $detailResult && mysqli_num_rows($detailResult) > 0 ? mysqli_fetch_assoc($detailResult) : $detail;
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="style.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
  <title>Edit User - PerpustakaanKu</title>
</head>

<body class="user-form-page">
  <div class="sidebar">
    <h2>PerpustakaanKu</h2>
    <ul>
      <li><a href="dashboard.php">Dashboard</a></li>
      <li><a href="buku.php">Buku</a></li>
      <li><a href="peminjam.php">Peminjam</a></li>
      <li><a href="petugas.php">Petugas</a></li>
      <li><a href="peminjaman.php">Peminjaman</a></li>
      <li><a href="user.php" class="active">User</a></li>
      <li><a href="logout.php">Logout</a></li>
    </ul>
  </div>

  <div class="main">
    <header>
      <h1>Edit User</h1>
    </header>

    <div class="content">
      <div class="form-container">
        <div class="form-header">
          <h2>Edit Data User</h2>
          <p>Perbarui informasi user di bawah ini</p>
        </div>

        <form method="post" action="user_edit_aksi.php" class="user-form-grid">
          <input type="hidden" name="ID_USER" value="<?php echo htmlspecialchars($tampil['ID_USER'], ENT_QUOTES, 'UTF-8'); ?>">

          <div class="form-group">
            <label for="id_user_display">ID User</label>
            <input type="text" id="id_user_display" value="<?php echo htmlspecialchars($tampil['ID_USER'], ENT_QUOTES, 'UTF-8'); ?>" disabled>
          </div>

          <div class="form-group">
            <label for="nama_user">Nama Lengkap</label>
            <input type="text" id="nama_user" name="NAMA_USER" maxlength="50" value="<?php echo htmlspecialchars($tampil['NAMA_USER'], ENT_QUOTES, 'UTF-8'); ?>" required>
          </div>

          <div class="form-group">
            <label for="username">Username</label>
            <input type="text" id="username" name="USERNAME" maxlength="50" value="<?php echo htmlspecialchars($tampil['USERNAME'], ENT_QUOTES, 'UTF-8'); ?>" required>
          </div>

          <div class="form-group">
            <label for="password">Password Baru</label>
            <input type="password" id="password" name="PASSWORD" maxlength="255" placeholder="Kosongkan jika tidak diganti">
          </div>

          <div class="form-group">
            <label for="level">Level (Role)</label>
            <select id="level" name="LEVEL" required>
              <option value="">Pilih Level</option>
              <option value="Admin" <?php echo $tampil['LEVEL'] === 'Admin' ? 'selected' : ''; ?>>Admin</option>
              <option value="Petugas" <?php echo $tampil['LEVEL'] === 'Petugas' ? 'selected' : ''; ?>>Petugas</option>
              <option value="Peminjam" <?php echo ($tampil['LEVEL'] === 'Peminjam' || $tampil['LEVEL'] === 'Anggota') ? 'selected' : ''; ?>>Peminjam</option>
            </select>
          </div>

          <div class="form-group">
            <label for="gender">Gender</label>
            <select id="gender" name="GENDER">
              <option value="">Pilih gender</option>
              <option value="PRIA" <?php echo $detail['GENDER'] === 'PRIA' ? 'selected' : ''; ?>>PRIA</option>
              <option value="WANITA" <?php echo $detail['GENDER'] === 'WANITA' ? 'selected' : ''; ?>>WANITA</option>
            </select>
          </div>

          <div class="form-group">
            <label for="alamat">Alamat</label>
            <input type="text" id="alamat" name="ALAMAT" maxlength="100" value="<?php echo htmlspecialchars($detail['ALAMAT'], ENT_QUOTES, 'UTF-8'); ?>">
          </div>

          <div class="form-group">
            <label for="hp">No. HP</label>
            <input type="text" id="hp" name="HP" maxlength="20" value="<?php echo htmlspecialchars($detail['HP'], ENT_QUOTES, 'UTF-8'); ?>">
          </div>

          <div class="form-actions user-form-actions">
            <a href="user.php" class="btn-cancel">Batal</a>
            <button type="submit" class="btn-submit">Simpan Perubahan</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <script>
    const levelSelect = document.getElementById('level');
    const roleDetailFields = [
      document.getElementById('gender'),
      document.getElementById('alamat'),
      document.getElementById('hp')
    ];

    function syncRoleDetailRequired() {
      const needsDetail = levelSelect.value === 'Petugas' || levelSelect.value === 'Peminjam';
      roleDetailFields.forEach((field) => {
        field.required = needsDetail;
      });
    }

    levelSelect.addEventListener('change', syncRoleDetailRequired);
    syncRoleDetailRequired();
  </script>
</body>

</html>
