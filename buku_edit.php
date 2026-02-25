<?php
session_start();

if (!isset($_SESSION['id_user'])) {
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Buku</title>
</head>

<body>
  <?php
  include 'koneksi.php';
  $ISBN = $_GET['ISBN'];
  $data = mysqli_query($koneksi, "SELECT * FROM buku WHERE ISBN='$ISBN'");
  $tampil = mysqli_fetch_array($data);
  ?>

  <form method="post" action="buku_edit_aksi.php">
    <input type="hidden" name="ISBN" value="<?php echo $tampil['ISBN']; ?>">
    Judul <br>
    <input type="text" name="JUDUL_BUKU" value="<?php echo $tampil['JUDUL_BUKU']; ?>"><br><br>
    Penulis <br>
    <input type="text" name="PENGARANG" value="<?php echo $tampil['PENGARANG']; ?>"><br><br>
    Penerbit <br>
    <input type="text" name="PENERBIT" value="<?php echo $tampil['PENERBIT']; ?>"><br><br>
    Tahun Terbit <br>
    <input type="text" name="TAHUN_TERBIT" value="<?php echo $tampil['TAHUN_TERBIT']; ?>"><br><br>
    <input type="submit" value="Simpan">
  </form>
</body>

</html>