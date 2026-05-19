<?php
function getGenreOptions($koneksi)
{
    $genreOptions = [];
    $genreResult = mysqli_query($koneksi, "SELECT DISTINCT GENRE FROM buku WHERE GENRE IS NOT NULL AND GENRE <> ''");

    if ($genreResult) {
        while ($row = mysqli_fetch_assoc($genreResult)) {
            $genre = trim($row['GENRE']);

            if ($genre !== '') {
                $genreOptions[$genre] = $genre;
            }
        }
    }

    $genreOptions = array_values($genreOptions);
    sort($genreOptions, SORT_NATURAL | SORT_FLAG_CASE);

    return $genreOptions;
}
