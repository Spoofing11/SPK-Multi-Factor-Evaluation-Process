<?php
require_once '../layout/_top.php';
require_once '../helper/connection.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Proteksi akses hanya untuk karyawan
if (!isset($_SESSION['login']) || $_SESSION['login']['role'] !== 'karyawan') {
    header('Location: ../login.php');
    exit;
}

// Ambil ID karyawan dari session
$id_karyawan = $_SESSION['login']['id_karyawan'] ?? null;
if (!$id_karyawan) {
    die("Session 'id_karyawan' tidak tersedia. Pastikan login menyimpan data ini.");
}

// Ambil data karyawan
$karQuery = mysqli_query($connection, "SELECT * FROM tb_karyawan WHERE id_karyawan = '$id_karyawan'");
if (!$karQuery) {
    die("Query gagal: " . mysqli_error($connection));
}
$kar = mysqli_fetch_assoc($karQuery);
if (!$kar) {
    die("Data karyawan tidak ditemukan untuk ID: $id_karyawan");
}

// Ambil data penilaian karyawan
$nilaiQry = mysqli_query($connection, "
    SELECT p.id_kriteria, p.nilai, k.kriteria AS nama_kriteria, k.bobot
    FROM tb_penilaian p
    JOIN tb_kriteria k ON p.id_kriteria = k.id_kriteria
    WHERE p.id_karyawan = '$id_karyawan'
");

$nilaiPerKriteria = [];
$total = 0;

// Validasi hasil query penilaian
if ($nilaiQry && mysqli_num_rows($nilaiQry) > 0) {
    while ($n = mysqli_fetch_assoc($nilaiQry)) {
        $nilaiPerKriteria[] = $n;
        $total += $n['nilai'] * $n['bobot'];
    }

    // Tentukan status evaluasi
    $status = $total >= 75 ? "Terus Dipertahankan" : "Berusaha Lebih Keras";

    // Saran pengembangan untuk nilai < 70
    $saran = array_filter($nilaiPerKriteria, fn($n) => $n['nilai'] < 70);
} else {
    // Jika belum ada penilaian
    $status = "Belum Dinilai";
    $total = 0;
    $nilaiPerKriteria = [];
    $saran = [];
}
?>

<section class="section">
  <div class="section-header row align-items-center">
    <div class="col">
      <h1 class="mb-0">Dashboard Karyawan</h1>
      <p>Selamat datang, <strong><?= htmlspecialchars($kar['nama_lengkap']) ?></strong> (<?= htmlspecialchars($kar['jabatan_posisi']) ?>)</p>
    </div>
  </div>

  <!-- Card Evaluasi -->
  <div class="row">
    <div class="col-lg-6">
      <div class="card card-statistic-1">
        <div class="card-icon bg-info"><i class="fas fa-chart-line"></i></div>
        <div class="card-wrap">
          <div class="card-header"><h4>Total Evaluasi</h4></div>
          <div class="card-body"><?= number_format($total, 2) ?></div>
        </div>
      </div>
    </div>

    <div class="col-lg-6">
      <div class="card card-statistic-1">
        <div class="card-icon bg-success"><i class="fas fa-check-circle"></i></div>
        <div class="card-wrap">
          <div class="card-header"><h4>Status</h4></div>
          <div class="card-body">
            <span class="badge badge-<?= $status === 'Terus Dipertahankan' ? 'success' : 'danger' ?>">
              <?= $status ?>
            </span>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Grafik Nilai per Kriteria -->
  <div class="row">
    <div class="col-lg-12">
      <div class="card">
        <div class="card-header"><h4>Grafik Nilai per Kriteria</h4></div>
        <div class="card-body">
          <canvas id="chartEvaluasi"></canvas>
        </div>
      </div>
    </div>
  </div>

  <!-- Tabel Detail Penilaian -->
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header"><h4>Detail Penilaian Anda</h4></div>
        <div class="card-body table-responsive">
          <table class="table table-bordered">
            <thead>
              <tr>
                <th>Kriteria</th><th>Nilai</th><th>Bobot</th><th>Skor</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($nilaiPerKriteria as $n): ?>
              <tr>
                <td><?= htmlspecialchars($n['nama_kriteria']) ?></td>
                <td><?= $n['nilai'] ?></td>
                <td><?= $n['bobot'] ?></td>
                <td><?= number_format($n['nilai'] * $n['bobot'], 2) ?></td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <!-- Saran Pengembangan -->
  <?php if ($saran): ?>
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header"><h4>Saran Pengembangan</h4></div>
        <div class="card-body">
          <ul>
            <?php foreach ($saran as $s): ?>
            <li>Tingkatkan kemampuan di bidang <strong><?= htmlspecialchars($s['nama_kriteria']) ?></strong></li>
            <?php endforeach; ?>
          </ul>
        </div>
      </div>
    </div>
  </div>
  <?php endif; ?>
</section>

<?php require_once '../layout/_bottom.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const labelsKriteria = <?= json_encode(array_column($nilaiPerKriteria, 'nama_kriteria')) ?>;
const dataKriteria = <?= json_encode(array_column($nilaiPerKriteria, 'nilai')) ?>;

new Chart(document.getElementById('chartEvaluasi'), {
  type: 'bar',
  data: {
    labels: labelsKriteria,
    datasets: [{
      label: 'Nilai per Kriteria',
      data: dataKriteria,
      backgroundColor: 'rgba(75, 192, 192, 0.7)'
    }]
  }
});
</script>