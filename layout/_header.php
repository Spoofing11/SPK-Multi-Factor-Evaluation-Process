<?php
require_once '../helper/connection.php';

// Default nama pengguna
$nama_pengguna = 'User';

// Pastikan session login ada
if (isset($_SESSION['login'])) {
  $username = $_SESSION['login']['username'];
  $role     = $_SESSION['login']['role'];

  if ($role === 'karyawan') {
    // Ambil nama karyawan dari tabel karyawan
    $username_safe = mysqli_real_escape_string($connection, $username);
    $query = "SELECT nama_lengkap FROM tb_karyawan WHERE id_karyawan = '$username_safe'";
    $result = mysqli_query($connection, $query);

    if ($row = mysqli_fetch_assoc($result)) {
      $nama_pengguna = $row['nama_lengkap'];
    }
  } elseif ($role === 'admin') {
    // Admin: cukup pakai username saja
    $nama_pengguna = $username;
  }
}
?>




<div class="navbar-bg"></div>
<nav class="navbar navbar-expand-lg main-navbar">
  <form class="form-inline mr-auto">
    <ul class="navbar-nav mr-3">
      <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg"><i class="fas fa-bars"></i></a></li>
      <li><a href="#" data-toggle="search" class="nav-link nav-link-lg d-sm-none"><i class="fas fa-search"></i></a></li>
    </ul>
  </form>
  <ul class="navbar-nav navbar-right">
    <li class="dropdown"><a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user">
        <img alt="image" src="../assets/img/avatar/avatar-1.png" class="rounded-circle mr-1">
        <div class="d-sm-none d-lg-inline-block">Hi, <?= $_SESSION['login']['username'] ?></div>
      </a>
      <div class="dropdown-menu dropdown-menu-right">
        <a href="../logout.php" class="dropdown-item has-icon text-danger">
          <i class="fas fa-sign-out-alt"></i> Logout
        </a>
      </div>
    </li>
  </ul>
</nav>