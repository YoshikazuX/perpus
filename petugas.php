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
    <title>Data Petugas - PerpustakaanKu</title>
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
            <li><a href="Login.php">Logout</a></li>
        </ul>
    </div>

    <div class="main">
        <header>
            <h1>Data Petugas</h1>
            <div class="user-info">
                <span>Admin</span>
                <img src="https://i.pravatar.cc/100" alt="User">
            </div>
        </header>

        <div class="content">
            <h2>Daftar Petugas</h2>
            <div class="tombol-tambah">
                <a href="petugas_tambah.php" class="btn-tambah">+ Tambah Data</a>
            </div>
            <table border="0" cellspacing="0" cellpadding="8">
                <tr>
                    <th>ID PETUGAS</th>
                    <th>NAMA</th>
                    <th>GENDER</th>
                    <th>ALAMAT</th>
                    <th>HP</th>
                    <th>AKSI</th>
                </tr>

                <?php
                include "koneksi.php";
                $data = mysqli_query($koneksi, "SELECT * FROM petugas");
                while ($tampil = mysqli_fetch_array($data)) {
                ?>
                    <tr>
                        <td><?php echo htmlspecialchars($tampil['ID_PETUGAS'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars($tampil['NAMA'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars($tampil['GENDER'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars($tampil['ALAMAT'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars($tampil['HP'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td class="action-cell">
                            <a href="petugas_edit.php?ID_PETUGAS=<?php echo urlencode($tampil['ID_PETUGAS']); ?>" class="btn-action edit">Edit</a>
                            <a href="petugas_hapus.php?ID_PETUGAS=<?php echo urlencode($tampil['ID_PETUGAS']); ?>" class="btn-action delete">Hapus</a>
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
