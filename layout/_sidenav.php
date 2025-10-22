<div class="main-sidebar sidebar-style-2">
  <aside id="sidebar-wrapper">
    <div class="sidebar-brand">
      <a href="index.php">
        <img src="../assets/img/logo-1.png" alt="logo" width="70">
      </a>
    </div>
    <div class="sidebar-brand sidebar-brand-sm">
      <a href="index.php">EF</a>
    </div>
    <ul class="sidebar-menu">
      <li class="menu-header">Dashboard</li>
      <li><a class="nav-link" href="../"><i class="fas fa-fire"></i> <span>Home</span></a></li>

      <?php if ($_SESSION['login']['role'] === 'admin'): ?>
        <li class="menu-header">Admin Feature</li>
        <li class="dropdown">
          <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-columns"></i> <span>Operasional Data</span></a>
          <ul class="dropdown-menu">
            <li><a class="nav-link" href="../datakaryawan/index.php">Data Karyawan</a></li>
            <li><a class="nav-link" href="../datakriteria/index.php">Data Kriteria</a></li>
            <li><a class="nav-link" href="../datapenilaian/index.php">Data Penilaian</a></li>
          </ul>
        </li>
        <li class="dropdown">
          <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-columns"></i> <span>Perhitungan</span></a>
          <ul class="dropdown-menu">
            <li><a class="nav-link" href="../perhitungan/index.php">Perhitungan</a></li>
          </ul>
        </li>
      <?php endif; ?>
    </ul>
    <ul class="sidebar-menu">
      <?php if ($_SESSION['login']['role'] === 'karyawan'): ?>
        <li class="menu-header">Karyawan Feature</li>
        <li class="dropdown">
          <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-columns"></i> <span>Karyawan</span></a>
          <ul class="dropdown-menu">
            <li><a class="nav-link" href="../fiturkaryawan/index.php">Data Karyawan</a></li>
          </ul>
        </li>
      <?php endif; ?>
    </ul>
  </aside>
</div>