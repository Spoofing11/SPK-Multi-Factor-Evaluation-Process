<?php
session_start();

if (isset($_SESSION['login'])) {
  if ($_SESSION['login']['role'] === 'admin') {
    header('Location: ./dashboard/index.php');
    exit;
  } elseif ($_SESSION['login']['role'] === 'karyawan') {
    header('Location: ./dashboard/dashboardk.php');
    exit;
  } else {
    header('Location: login.php');
    exit;
  }
} else {
  header('Location: login.php');
  exit;
}
