<?php
function getPetugasPeminjamanValue(mysqli $koneksi): string
{
    $level = $_SESSION['LEVEL'] ?? '';

    if ($level === 'Admin') {
        return 'Admin';
    }

    if ($level === 'Petugas') {
        $namaUser = mysqli_real_escape_string($koneksi, $_SESSION['NAMA_USER'] ?? '');
        $petugas = mysqli_query($koneksi, "SELECT ID_PETUGAS FROM petugas WHERE NAMA='$namaUser' LIMIT 1");

        if ($petugas && mysqli_num_rows($petugas) === 1) {
            $row = mysqli_fetch_assoc($petugas);
            return $row['ID_PETUGAS'];
        }

        throw new Exception('Data petugas untuk akun ini tidak ditemukan.');
    }

    throw new Exception('Role ini tidak dapat menginput peminjaman.');
}
