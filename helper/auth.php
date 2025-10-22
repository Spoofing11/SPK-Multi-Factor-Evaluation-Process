<?php
session_start();

function isLogin()
{
  if (!isset($_SESSION['login'])) {
    header('Location: ../login.php');
    exit;
  }
}

function isAdmin()
{
  isLogin();
  if ($_SESSION['login']['role'] !== 'admin') {
    header('Location: ../karyawan/index.php'); // lempar ke dashboard karyawan
    exit;
  }
}

function isKaryawan()
{
  isLogin();
  if ($_SESSION['login']['role'] !== 'karyawan') {
    header('Location: ../dashboard/dashboardk.php'); // lempar ke dashboard admin
    exit;
  }
}
