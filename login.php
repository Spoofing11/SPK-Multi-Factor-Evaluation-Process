<?php
require_once 'helper/connection.php';
session_start();

// Untuk debugging saat pengembangan
error_reporting(E_ALL);
ini_set('display_errors', 1);

$error = "";

if (isset($_POST['submit'])) {
  $username = mysqli_real_escape_string($connection, $_POST['username']);
  $password = $_POST['password'];
  $role     = $_POST['role'];

  // Query login
  $sql = "SELECT * FROM login WHERE username='$username' AND role='$role' LIMIT 1";
  $result = mysqli_query($connection, $sql);

  if (!$result) {
    $error = "Terjadi kesalahan saat mengakses database: " . mysqli_error($connection);
  } elseif (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);

    if (password_verify($password, $row['password'])) {
      // Siapkan session dasar
      $_SESSION['login'] = [
        'id'       => $row['id'],
        'username' => $row['username'],
        'role'     => $row['role']
      ];

      // Jika role karyawan, ambil data tambahan dari tb_karyawan
      if ($row['role'] === 'karyawan') {
        $karyawan = mysqli_fetch_assoc(mysqli_query($connection, "
          SELECT * FROM tb_karyawan WHERE nama_lengkap = '{$row['username']}'
        "));

        if ($karyawan) {
          $_SESSION['login']['id_karyawan']    = $karyawan['id_karyawan'];
          $_SESSION['login']['nama_lengkap']   = $karyawan['nama_lengkap'];
          $_SESSION['login']['jabatan_posisi'] = $karyawan['jabatan_posisi'];
        } else {
          $error = "Data karyawan tidak ditemukan.";
        }
      }

      // Redirect sesuai role
      if (empty($error)) {
        if ($row['role'] === 'admin') {
          header('Location: dashboard/index.php');
        } else {
          header('Location: dashboard/dashboardk.php');
        }
        exit;
      }
    } else {
      $error = "Password salah!";
    }
  } else {
    $error = "Username atau role tidak ditemukan!";
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>Login &mdash; SPK | MFEP</title>

  <!-- General CSS Files -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">

  <!-- CSS Libraries -->
  <link rel="stylesheet" href="assets/modules/bootstrap-social/bootstrap-social.css">

  <!-- Template CSS -->
  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="stylesheet" href="assets/css/components.css">
</head>

<body>
  <div id="app">
    <section class="section">
      <div class="container mt-5">
        <div class="row">
          <div class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4">
            <div class="login-brand">
              <img src="./assets/img/logo-1.png" alt="logo" width="300">
            </div>

            <div class="card card-primary">
              <div class="card-header">
                <h4>Login</h4>
              </div>

              <div class="card-body">
                <?php if ($error): ?>
                  <div class="alert alert-danger">
                    <?= $error ?>
                  </div>
                <?php endif; ?>

                <form method="POST" action="" class="needs-validation" novalidate="">
                  <div class="form-group">
                    <label for="username">Username</label>
                    <input id="username" type="text" class="form-control" name="username" required autofocus>
                  </div>

                  <div class="form-group">
                    <label for="password">Password</label>
                    <input id="password" type="password" class="form-control" name="password" required>
                  </div>

                  <div class="form-group">
                    <label for="role">Login sebagai</label>
                    <select id="role" name="role" class="form-control" required>
                      <option value="">-- Pilih Role --</option>
                      <option value="admin">Admin</option>
                      <option value="karyawan">Karyawan</option>
                    </select>
                  </div>

                  <div class="form-group">
                    <button name="submit" type="submit" class="btn btn-primary btn-lg btn-block">
                      Login
                    </button>
                  </div>
                </form>

              </div>
            </div>
            <div class="simple-footer">
              Copyright &copy; Thomi
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>

  <!-- General JS Scripts -->
  <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.nicescroll/3.7.6/jquery.nicescroll.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
  <script src="assets/js/stisla.js"></script>

  <!-- JS Libraies -->

  <!-- Template JS File -->
  <script src="assets/js/scripts.js"></script>
  <script src="assets/js/custom.js"></script>

  <!-- Page Specific JS File -->
</body>

</html>