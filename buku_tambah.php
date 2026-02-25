<?php
session_start();

if (!isset($_SESSION['id_user'])) {
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Tambah Buku</title>
</head>
<body>
    <h2>Tambah Data Buku</h2>

    <form method="post" action="buku_tambah_aksi.php">
        <label>ISBN</label><br>
        <input type="text" name="ISBN"><br><br>

        <label>Judul Buku</label><br>
        <input type="text" name="JUDUL_BUKU"><br><br>

        <label>Penulis</label><br>
        <input type="text" name="PENGARANG"><br><br>

        <label>Penerbit</label><br>
        <input type="text" name="PENERBIT"><br><br>

        <label>Tahun Terbit</label><br>
        <input type="text" name="TAHUN_TERBIT"><br><br>

        <input type="submit" value="Simpan">
    </form>

</body>
</html>
