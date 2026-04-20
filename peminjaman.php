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
  <title>Data Peminjaman - PerpustakaanKu</title>
</head>

<body>
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
      <h1>Data Peminjaman</h1>
      <div class="user-info">
        <span>Admin</span>
        <img src="https://i.pravatar.cc/100" alt="User">
      </div>
    </header>

    <div class="content">
      <h2>Daftar Peminjaman</h2>
      <div class="tombol-tambah">
        <a href="form_peminjaman.php" class="btn-tambah">+ Tambah Data</a>
      </div>
      <table border="0" cellspacing="0" cellpadding="8">
        <tr>
          <th>ID PEMINJAMAN</th>
          <th>JUDUL BUKU</th>
          <th>NAMA PEMINJAM</th>
          <th>ID PETUGAS</th>
          <th>TANGGAL MULAI</th>
          <th>TANGGAL SELESAI</th>
          <th>STATUS</th>
          <th>OPSI</th>
          <th>AKSI</th>
        </tr>

        <?php
        include "koneksi.php";

        $data = mysqli_query($koneksi, "
          SELECT 
            peminjaman.*,
            buku.JUDUL_BUKU,
            peminjam.nama AS NAMA_PEMINJAM
          FROM peminjaman
          JOIN buku ON peminjaman.ISBN = buku.ISBN
          JOIN peminjam ON peminjaman.ID_PEMINJAM = peminjam.ID_PEMINJAM
        ");

        while ($tampil = mysqli_fetch_array($data)) {
        ?>
          <tr>
            <td><?php echo htmlspecialchars($tampil['ID_PEMINJAMAN'], ENT_QUOTES, 'UTF-8'); ?></td>
            <td><?php echo htmlspecialchars($tampil['JUDUL_BUKU'], ENT_QUOTES, 'UTF-8'); ?></td>
            <td><?php echo htmlspecialchars($tampil['NAMA_PEMINJAM'], ENT_QUOTES, 'UTF-8'); ?></td>
            <td><?php echo htmlspecialchars($tampil['ID_PETUGAS'], ENT_QUOTES, 'UTF-8'); ?></td>
            <td><?php echo htmlspecialchars($tampil['TANGGAL_MULAI'], ENT_QUOTES, 'UTF-8'); ?></td>
            <td><?php echo $tampil['TANGGAL_SELESAI'] ? htmlspecialchars($tampil['TANGGAL_SELESAI'], ENT_QUOTES, 'UTF-8') : '-'; ?></td>
            <td><?php echo htmlspecialchars($tampil['STATUS'], ENT_QUOTES, 'UTF-8'); ?></td>
            <td class="option-cell">
              <div class="action-buttons">
                <a href="peminjaman_edit.php?ID_PEMINJAMAN=<?= urlencode($tampil['ID_PEMINJAMAN']); ?>" class="btn-action edit">Edit</a>
                <a href="peminjaman_hapus.php?ID_PEMINJAMAN=<?= urlencode($tampil['ID_PEMINJAMAN']); ?>" class="btn-action delete">Hapus</a>
              </div>
            </td>
            <td class="action-cell">
              <div class="option-buttons">
                <?php if ($tampil['STATUS'] === 'Dipinjam') { ?>
                  <a href="peminjaman_kembalikan.php?ID_PEMINJAMAN=<?= urlencode($tampil['ID_PEMINJAMAN']); ?>" class="btn-action return">Kembalikan</a>
                <?php } else { ?>
                  <span class="option-placeholder">-</span>
                <?php } ?>
              </div>
            </td>
          </tr>
        <?php
        }
        ?>
      </table>
    </div>
  </div>
</body>

</html>
