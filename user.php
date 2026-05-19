<?php
session_start();

if (!isset($_SESSION['ID_USER'])) {
    header("Location: login.php");
    exit;
}

include "koneksi.php";
require_once "pagination_helper.php";
require_once "search_helper.php";

$keyword = trim($_GET['keyword'] ?? '');
$keywordEscaped = mysqli_real_escape_string($koneksi, $keyword);
$whereClause = "";

if ($keyword !== '') {
    $whereClause = " WHERE ID_USER LIKE '%$keywordEscaped%'
        OR NAMA_USER LIKE '%$keywordEscaped%'
        OR USERNAME LIKE '%$keywordEscaped%'
        OR LEVEL LIKE '%$keywordEscaped%'";
}

$pagination = getPaginationData($koneksi, "SELECT COUNT(*) AS total FROM user$whereClause");
$data = mysqli_query($koneksi, "SELECT * FROM user$whereClause ORDER BY ID_USER DESC LIMIT {$pagination['per_page']} OFFSET {$pagination['offset']}");
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <title>Data User - PerpustakaanKu</title>
</head>

<body>
    <div class="sidebar">
        <h2>PerpustakaanKu</h2>
        <ul>
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="buku.php">Buku</a></li>
            <li><a href="peminjam.php">Peminjam</a></li>
            <li><a href="petugas.php">Petugas</a></li>
            <li><a href="peminjaman.php">Peminjaman</a></li>
            <li><a href="user.php" class="active">User</a></li>
            <li><a href="login.php">Logout</a></li>
        </ul>
    </div>

    <div class="main">
        <header>
            <h1>Data User</h1>
        </header>

        <div class="content">
            <h2>Daftar User Sistem</h2>
            <div class="tombol-tambah">
                <?php renderSearchForm('Cari data user...'); ?>
                <a href="user_tambah.php" class="btn-tambah">+ Tambah User</a>
            </div>
            <table border="0" cellspacing="0" cellpadding="8">
                <tr>
                    <th>ID USER</th>
                    <th>NAMA USER</th>
                    <th>USERNAME</th>
                    <th>PASSWORD</th>
                    <th>LEVEL</th>
                    <th>AKSI</th>
                </tr>

                <?php
                if (mysqli_num_rows($data) > 0) {
                    while ($tampil = mysqli_fetch_array($data)) {
                ?>
                    <tr>
                        <td><?php echo htmlspecialchars($tampil['ID_USER'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars($tampil['NAMA_USER'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars($tampil['USERNAME'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo str_repeat('*', strlen($tampil['PASSWORD'])); ?></td>
                        <td><?php echo htmlspecialchars($tampil['LEVEL'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td class="action-cell">
                            <a href="user_edit.php?ID_USER=<?php echo urlencode($tampil['ID_USER']); ?>" class="btn-action edit">Edit</a>
                            <a href="user_hapus.php?ID_USER=<?php echo urlencode($tampil['ID_USER']); ?>" class="btn-action delete" onclick="return confirm('Apakah Anda yakin ingin menghapus user ini?');">Hapus</a>
                        </td>
                    </tr>
                <?php
                    }
                } else {
                ?>
                    <tr>
                        <td colspan="6">Belum ada data user.</td>
                    </tr>
                <?php
                }
                ?>
            </table>
            <?php renderPagination($pagination); ?>
        </div>
    </div>
</body>

</html>
