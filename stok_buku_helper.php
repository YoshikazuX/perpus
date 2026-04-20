<?php
function ambilDataBuku(mysqli $koneksi, string $isbn): ?array
{
    $isbnEscaped = mysqli_real_escape_string($koneksi, $isbn);
    $result = mysqli_query($koneksi, "SELECT ISBN, JUDUL_BUKU, STOK FROM buku WHERE ISBN='$isbnEscaped' LIMIT 1");

    if ($result && mysqli_num_rows($result) === 1) {
        return mysqli_fetch_assoc($result);
    }

    return null;
}

function kurangiStokBuku(mysqli $koneksi, string $isbn, int $jumlah, ?string &$pesan = null): bool
{
    $jumlah = max(1, $jumlah);
    $buku = ambilDataBuku($koneksi, $isbn);

    if (!$buku) {
        $pesan = 'Data buku tidak ditemukan.';
        return false;
    }

    if ((int) $buku['STOK'] < $jumlah) {
        $pesan = 'Stok buku "' . $buku['JUDUL_BUKU'] . '" tidak mencukupi.';
        return false;
    }

    $isbnEscaped = mysqli_real_escape_string($koneksi, $isbn);
    $query = mysqli_query($koneksi, "UPDATE buku SET STOK = STOK - $jumlah WHERE ISBN='$isbnEscaped'");

    if (!$query) {
        $pesan = mysqli_error($koneksi);
        return false;
    }

    return true;
}

function tambahStokBuku(mysqli $koneksi, string $isbn, int $jumlah, ?string &$pesan = null): bool
{
    $jumlah = max(1, $jumlah);
    $buku = ambilDataBuku($koneksi, $isbn);

    if (!$buku) {
        $pesan = 'Data buku tidak ditemukan.';
        return false;
    }

    $isbnEscaped = mysqli_real_escape_string($koneksi, $isbn);
    $query = mysqli_query($koneksi, "UPDATE buku SET STOK = STOK + $jumlah WHERE ISBN='$isbnEscaped'");

    if (!$query) {
        $pesan = mysqli_error($koneksi);
        return false;
    }

    return true;
}
?>
