<?php
require_once '../layout/_top.php';
require_once '../helper/connection.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['login']) || $_SESSION['login']['role'] !== 'karyawan') {
    header('Location: ../login.php');
    exit;
}

$id_karyawan = $_SESSION['login']['id_karyawan'] ?? null;
if (!$id_karyawan) {
    die("Session 'id_karyawan' tidak tersedia.");
}

// Ambil data karyawan
$karQuery = mysqli_query($connection, "SELECT * FROM tb_karyawan WHERE id_karyawan = '$id_karyawan'");
$kar = mysqli_fetch_assoc($karQuery);

// Ambil data penilaian
$nilaiQry = mysqli_query($connection, "
    SELECT p.id_penilaian, k.kriteria, p.nilai, k.bobot
    FROM tb_penilaian p
    JOIN tb_kriteria k ON p.id_kriteria = k.id_kriteria
    WHERE p.id_karyawan = '$id_karyawan'
");

$nilaiPerKriteria = [];
$total = 0;

if ($nilaiQry && mysqli_num_rows($nilaiQry) > 0) {
    while ($n = mysqli_fetch_assoc($nilaiQry)) {
        $nilaiPerKriteria[] = $n;
        $total += $n['nilai'] * $n['bobot'];
    }
    $status = $total >= 75 ? "Terus Dipertahankan" : "Berusaha Lebih Keras";
    $saran = array_filter($nilaiPerKriteria, fn($n) => $n['nilai'] < 70);
} else {
    $status = "Belum Dinilai";
    $total = 0;
    $saran = [];
}
?>

<section class="section">
  <div class="section-header">
    <h1>Informasi & Evaluasi Karyawan</h1>
  </div>

  <!-- Profil Karyawan -->
  <div class="card mb-4">
    <div class="card-header"><h4>Profil Anda</h4></div>
    <div class="card-body">
      <table class="table table-bordered">
        <tr><th>Nama Lengkap</th><td><?= htmlspecialchars($kar['nama_lengkap']) ?></td></tr>
        <tr><th>Jabatan</th><td><?= htmlspecialchars($kar['jabatan_posisi']) ?></td></tr>
        <tr><th>Alamat</th><td><?= htmlspecialchars($kar['alamat']) ?></td></tr>
        <tr><th>No. Telepon</th><td><?= htmlspecialchars($kar['no_telp']) ?></td></tr>
        <tr><th>Status</th><td><span class="badge badge-<?= $kar['status'] === 'Aktif' ? 'success' : 'secondary' ?>"><?= $kar['status'] ?></span></td></tr>
      </table>
    </div>
  </div>

  <!-- Tabel Penilaian -->
  <div class="card mb-4">
    <div class="card-header"><h4>Penilaian Anda</h4></div>
    <div class="card-body table-responsive">
      <?php if ($nilaiPerKriteria): ?>
      <table class="table table-bordered">
        <thead>
          <tr><th>No</th><th>Kriteria</th><th>Nilai</th><th>Bobot</th><th>Skor</th></tr>
        </thead>
        <tbody>
          <?php $no = 1; foreach ($nilaiPerKriteria as $n): ?>
          <tr>
            <td><?= $no++ ?></td>
            <td><?= htmlspecialchars($n['kriteria']) ?></td>
            <td><?= $n['nilai'] ?></td>
            <td><?= $n['bobot'] ?></td>
            <td><?= number_format($n['nilai'] * $n['bobot'], 2) ?></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
      <?php else: ?>
        <div class="alert alert-warning">Belum ada data penilaian untuk Anda.</div>
      <?php endif; ?>
    </div>
  </div>

  <!-- Total & Status -->
  <div class="card mb-4">
    <div class="card-header"><h4>Hasil Evaluasi</h4></div>
    <div class="card-body">
      <p>Total Evaluasi: <strong><?= number_format($total, 2) ?></strong></p>
      <p>Status: <span class="badge badge-<?= $status === 'Terus Dipertahankan' ? 'success' : ($status === 'Berusaha Lebih Keras' ? 'danger' : 'secondary') ?>">
        <?= $status ?>
      </span></p>
    </div>
  </div>

  <!-- Saran Pengembangan -->
  <?php if ($saran): ?>
  <div class="card mb-4">
    <div class="card-header"><h4>Saran Pengembangan</h4></div>
    <div class="card-body">
      <ul>
        <?php foreach ($saran as $s): ?>
        <li>Tingkatkan kemampuan di bidang <strong><?= htmlspecialchars($s['kriteria']) ?></strong></li>
        <?php endforeach; ?>
      </ul>
    </div>
  </div>
  <?php endif; ?>
</section>

<?php require_once '../layout/_bottom.php'; ?>