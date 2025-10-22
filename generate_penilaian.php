<?php
require_once './helper/connection.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Ambil semua karyawan
$karyawan = mysqli_query($connection, "SELECT id_karyawan, nama_lengkap FROM tb_karyawan");

// Ambil semua kriteria
$kriteria = mysqli_query($connection, "SELECT id_kriteria FROM tb_kriteria");

if (!$karyawan || !$kriteria) {
    die("Query gagal: " . mysqli_error($connection));
}

$inserted = 0;
while ($kar = mysqli_fetch_assoc($karyawan)) {
    mysqli_data_seek($kriteria, 0); // reset pointer hasil query kriteria

    while ($kr = mysqli_fetch_assoc($kriteria)) {
        $id_karyawan = $kar['id_karyawan'];
        $nama_karyawan = $kar['nama_lengkap']; // ambil nama juga
        $id_kriteria = $kr['id_kriteria'];

        // Cek apakah sudah ada penilaian untuk karyawan ini + kriteria ini
        $cek = mysqli_query($connection, "
            SELECT id_penilaian FROM tb_penilaian 
            WHERE id_karyawan = '$id_karyawan' AND id_kriteria = '$id_kriteria'
        ");

        if (mysqli_num_rows($cek) == 0) {
            // Random nilai antara 60–95, kelipatan 5
            $nilai = rand(12, 19) * 5; // hasilnya 60, 65, ..., 95

            $query = mysqli_query($connection, "
                INSERT INTO tb_penilaian (id_karyawan, nama_karyawan, id_kriteria, nilai) 
                VALUES ('$id_karyawan', '$nama_karyawan', '$id_kriteria', '$nilai')
            ");

            if ($query) {
                $inserted++;
            }
        }
    }
}

echo "Berhasil Ditambahkan";
?>
