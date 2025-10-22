<?php
require_once '../helper/connection.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// CREATE (single nilai)
if (isset($_POST['create'])) {
    $id_karyawan = $_POST['id_karyawan'];
    $id_kriteria = $_POST['id_kriteria'];
    $nilai       = floatval($_POST['nilai']);

    // Ambil nama karyawan
    $q_karyawan = mysqli_query($connection, "SELECT nama_lengkap FROM tb_karyawan WHERE id_karyawan = '$id_karyawan'");
    $data_karyawan = mysqli_fetch_assoc($q_karyawan);
    $nama_karyawan = $data_karyawan['nama_lengkap'];

    $query = "INSERT INTO tb_penilaian (id_karyawan, nama_karyawan, id_kriteria, nilai)
              VALUES ('$id_karyawan', '$nama_karyawan', '$id_kriteria', '$nilai')";
    $result = mysqli_query($connection, $query);

    $_SESSION['info'] = [
        'status' => $result ? 'success' : 'failed',
        'message' => $result ? 'Berhasil menyimpan data penilaian' : 'Gagal menyimpan data: ' . mysqli_error($connection)
    ];
    header('Location: index.php');
    exit;
}

// CREATE BATCH (semua kriteria sekaligus)
if (isset($_POST['create_batch'])) {
  $id_karyawan = $_POST['id_karyawan'];
  $id_kriteria = $_POST['id_kriteria'];
  $nilai       = $_POST['nilai'];

  // Ambil nama karyawan sekali saja
  $q_karyawan = mysqli_query($connection, "SELECT nama_lengkap FROM tb_karyawan WHERE id_karyawan = '$id_karyawan'");
  $data_karyawan = mysqli_fetch_assoc($q_karyawan);
  $nama_karyawan = $data_karyawan['nama_lengkap'];

  $success = true;
  foreach ($id_kriteria as $i => $id_kr) {
    $val = floatval($nilai[$i]);

    // Cek apakah penilaian sudah ada
    $cek = mysqli_query($connection, "
      SELECT * FROM tb_penilaian
      WHERE id_karyawan = '$id_karyawan' AND id_kriteria = '$id_kr'
    ");
    if (mysqli_num_rows($cek) > 0) {
      continue; // skip jika sudah ada
    }

    $query = "INSERT INTO tb_penilaian (id_karyawan, nama_karyawan, id_kriteria, nilai)
              VALUES ('$id_karyawan', '$nama_karyawan', '$id_kr', '$val')";
    $result = mysqli_query($connection, $query);
    if (!$result) {
      $success = false;
      break;
    }
  }

  $_SESSION['info'] = [
    'status' => $success ? 'success' : 'failed',
    'message' => $success ? 'Berhasil menyimpan semua penilaian' : 'Gagal menyimpan data: ' . mysqli_error($connection)
  ];
  header('Location: index.php');
  exit;
}

// UPDATE
if (isset($_POST['update'])) {
    $id_penilaian = intval($_POST['id_penilaian']);
    $id_karyawan  = $_POST['id_karyawan'];
    $id_kriteria  = $_POST['id_kriteria'];
    $nilai        = floatval($_POST['nilai']);

    // Ambil nama karyawan
    $q_karyawan = mysqli_query($connection, "SELECT nama_lengkap FROM tb_karyawan WHERE id_karyawan = '$id_karyawan'");
    $data_karyawan = mysqli_fetch_assoc($q_karyawan);
    $nama_karyawan = $data_karyawan['nama_lengkap'];

    $query = "UPDATE tb_penilaian
              SET id_karyawan = '$id_karyawan',
                  nama_karyawan = '$nama_karyawan',
                  id_kriteria = '$id_kriteria',
                  nilai = '$nilai'
              WHERE id_penilaian = '$id_penilaian'";
    $result = mysqli_query($connection, $query);

    $_SESSION['info'] = [
        'status' => $result ? 'success' : 'failed',
        'message' => $result ? 'Berhasil mengupdate data penilaian' : 'Gagal update data: ' . mysqli_error($connection)
    ];
    header('Location: index.php');
    exit;
}

// UPDATE BATCH
if (isset($_POST['update_batch'])) {
    $id_karyawan  = $_POST['id_karyawan'];
    $id_penilaian = $_POST['id_penilaian'];
    $id_kriteria  = $_POST['id_kriteria'];
    $nilai        = $_POST['nilai'];

    // Ambil nama karyawan
    $q_karyawan = mysqli_query($connection, "SELECT nama_lengkap FROM tb_karyawan WHERE id_karyawan = '$id_karyawan'");
    $data_karyawan = mysqli_fetch_assoc($q_karyawan);
    $nama_karyawan = $data_karyawan['nama_lengkap'];

    $success = true;
    foreach ($id_penilaian as $i => $id_pn) {
        $id_kr = $id_kriteria[$i];
        $val   = floatval($nilai[$i]);

        $query = "UPDATE tb_penilaian
                  SET id_karyawan = '$id_karyawan',
                      nama_karyawan = '$nama_karyawan',
                      id_kriteria = '$id_kr',
                      nilai = '$val'
                  WHERE id_penilaian = '$id_pn'";
        $result = mysqli_query($connection, $query);
        if (!$result) {
            $success = false;
            break;
        }
    }

    $_SESSION['info'] = [
        'status' => $success ? 'success' : 'failed',
        'message' => $success ? 'Berhasil update semua penilaian' : 'Gagal update data: ' . mysqli_error($connection)
    ];
    header('Location: index.php');
    exit;
}


// DELETE
if (isset($_GET['id'])) {
    $id_karyawan = $_GET['id'];

    $stmt = mysqli_prepare($connection, "DELETE FROM tb_penilaian WHERE id_karyawan = ?");
    mysqli_stmt_bind_param($stmt, "s", $id_karyawan); // pakai "s" karena id_karyawan berupa string (contoh: 002025001)
    $result = mysqli_stmt_execute($stmt);

    $_SESSION['info'] = [
        'status' => $result ? 'success' : 'failed',
        'message' => $result ? 'Berhasil menghapus ' : 'Gagal hapus data: ' . mysqli_error($connection)
    ];

    header('Location: index.php');
    exit;
}
?>
