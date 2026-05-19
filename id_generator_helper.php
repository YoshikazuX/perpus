<?php
function generateId($koneksi, $table, $column, $prefix, $length = 3)
{
    $tableEscaped = preg_replace('/[^a-zA-Z0-9_]/', '', $table);
    $columnEscaped = preg_replace('/[^a-zA-Z0-9_]/', '', $column);
    $prefixEscaped = mysqli_real_escape_string($koneksi, $prefix);

    $result = mysqli_query(
        $koneksi,
        "SELECT $columnEscaped FROM $tableEscaped WHERE $columnEscaped LIKE '$prefixEscaped%' ORDER BY $columnEscaped DESC LIMIT 1"
    );

    $nextNumber = 1;

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $lastNumber = (int) preg_replace('/\D/', '', $row[$column]);
        $nextNumber = $lastNumber + 1;
    }

    return $prefix . str_pad((string) $nextNumber, $length, '0', STR_PAD_LEFT);
}

function generateNumericId($koneksi, $table, $column, $startNumber = 1001)
{
    $tableEscaped = preg_replace('/[^a-zA-Z0-9_]/', '', $table);
    $columnEscaped = preg_replace('/[^a-zA-Z0-9_]/', '', $column);

    $result = mysqli_query(
        $koneksi,
        "SELECT $columnEscaped
         FROM $tableEscaped
         WHERE $columnEscaped REGEXP '^[0-9]+$'
         ORDER BY CAST($columnEscaped AS UNSIGNED) DESC
         LIMIT 1"
    );

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        return (string) ((int) $row[$column] + 1);
    }

    return (string) $startNumber;
}
