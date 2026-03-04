<?php
include 'koneksi.php';

$buku = mysqli_query($koneksi, "SELECT ISBN, judul_buku FROM buku");
$peminjam = mysqli_query($koneksi, "SELECT ID_PEMINJAM, nama FROM peminjam");
$petugas = mysqli_query($koneksi, "SELECT ID_PETUGAS, nama FROM petugas");
?>

<h3>Form Transaksi Peminjaman Buku</h3>

<form action="simpan_peminjaman.php" method="POST">

    <!-- Buku -->
    <label>Judul Buku</label><br>
    <select name="isbn" required>
        <option value="">-- Pilih Buku --</option>
        <?php while ($b = mysqli_fetch_assoc($buku)) : ?>
            <option value="<?= $b['ISBN']; ?>">
                <?= $b['judul_buku']; ?>
            </option>
        <?php endwhile; ?>
    </select>
    <br><br>

    <!-- Peminjam -->
    <label>Nama Peminjam</label><br>
    <select name="id_peminjam" required>
        <option value="">-- Pilih Peminjam --</option>
        <?php while ($p = mysqli_fetch_assoc($peminjam)) : ?>
            <option value="<?= $p['ID_PEMINJAM']; ?>">
                <?= $p['nama']; ?>
            </option>
        <?php endwhile; ?>
    </select>
    <br><br>

    <!-- Petugas -->
    <label>Petugas</label><br>
    <select name="id_petugas" required>
        <option value="">-- Pilih Petugas --</option>
        <?php while ($pt = mysqli_fetch_assoc($petugas)) : ?>
            <option value="<?= $pt['ID_PETUGAS']; ?>">
                <?= $pt['nama']; ?>
            </option>
        <?php endwhile; ?>
    </select>
    <br><br>

    <!-- Tanggal -->
    <label>Tanggal Mulai</label><br>
    <input type="date" name="tgl_mulai" required>
    <br><br>

    <button type="submit">Simpan Peminjaman</button>
</form>