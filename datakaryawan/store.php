<?php
session_start();
require_once '../helper/connection.php';

// CREATE
if (isset($_POST['create'])) {
  $id      = $_POST['id_karyawan'];
  $nama    = $_POST['nama_lengkap'];
  $alamat  = $_POST['alamat'];
  $telp    = $_POST['no_telp'];
  $jabatan = $_POST['jabatan_posisi'];
  $status  = $_POST['status'];

  $query = mysqli_query(
    $connection,
    "INSERT INTO tb_karyawan (id_karyawan, nama_lengkap, alamat, no_telp, jabatan_posisi, status)
     VALUES ('$id', '$nama', '$alamat', '$telp', '$jabatan', '$status')"
  );

  if ($query) {
    // Tambahkan akun login otomatis
    $username = $id;
    $password = password_hash($id, PASSWORD_DEFAULT); // password = id_karyawan
    $role     = 'karyawan';

    $loginQuery = mysqli_query(
      $connection,
      "INSERT INTO login (username, password, role)
       VALUES ('$username', '$password', '$role')"
    );

    if ($loginQuery) {
      $_SESSION['info'] = [
        'status' => 'success',
        'message' => 'Berhasil menambah data karyawan dan akun login'
      ];
    } else {
      $_SESSION['info'] = [
        'status' => 'failed',
        'message' => 'Karyawan berhasil ditambah, tapi gagal membuat akun login: ' . mysqli_error($connection)
      ];
    }
  } else {
    $_SESSION['info'] = [
      'status' => 'failed',
      'message' => mysqli_error($connection)
    ];
  }

  header('Location: index.php');
  exit;
}

// UPDATE
if (isset($_POST['update'])) {
  $id      = $_POST['id_karyawan'];
  $nama    = $_POST['nama_lengkap'];
  $alamat  = $_POST['alamat'];
  $telp    = $_POST['no_telp'];
  $jabatan = $_POST['jabatan_posisi'];
  $status  = $_POST['status'];

  $query = mysqli_query(
    $connection,
    "UPDATE tb_karyawan
     SET nama_lengkap='$nama', alamat='$alamat', no_telp='$telp', jabatan_posisi='$jabatan', status='$status'
     WHERE id_karyawan='$id'"
  );

  $_SESSION['info'] = [
    'status' => $query ? 'success' : 'failed',
    'message' => $query ? 'Berhasil mengupdate data' : mysqli_error($connection)
  ];

  header('Location: index.php');
  exit;
}

// DELETE
if (isset($_GET['delete'])) {
  $id = $_GET['delete'];

  $query = mysqli_query($connection, "DELETE FROM tb_karyawan WHERE id_karyawan='$id'");
  $loginQuery = mysqli_query($connection, "DELETE FROM login WHERE username='$id'");

  if ($query && $loginQuery) {
    $_SESSION['info'] = [
      'status' => 'success',
      'message' => 'Berhasil menghapus data karyawan dan akun login'
    ];
  } else {
    $_SESSION['info'] = [
      'status' => 'failed',
      'message' => 'Gagal menghapus data: ' . mysqli_error($connection)
    ];
  }

  header('Location: index.php');
  exit;
}
?>