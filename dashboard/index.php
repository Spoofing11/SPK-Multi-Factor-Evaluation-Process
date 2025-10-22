<?php
require_once '../layout/_top.php';
require_once '../helper/connection.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Proteksi akses hanya untuk admin
if (!isset($_SESSION['login']) || $_SESSION['login']['role'] !== 'admin') {
    header('Location: ../login.php');
    exit;
}

// Ambil data jumlah
$totalKaryawan = mysqli_fetch_assoc(mysqli_query($connection, "SELECT COUNT(*) as jml FROM tb_karyawan"))['jml'];
$totalKriteria = mysqli_fetch_assoc(mysqli_query($connection, "SELECT COUNT(*) as jml FROM tb_kriteria"))['jml'];
$totalPenilaian = mysqli_fetch_assoc(mysqli_query($connection, "SELECT COUNT(*) as jml FROM tb_penilaian"))['jml'];

// Ambil data penilaian & kriteria
$kriteria = mysqli_query($connection, "SELECT * FROM tb_kriteria");
$listKriteria = [];
while ($row = mysqli_fetch_assoc($kriteria)) {
    $listKriteria[$row['id_kriteria']] = $row;
}

// Ambil karyawan
$karyawan = mysqli_query($connection, "SELECT * FROM tb_karyawan");
$hasilEvaluasi = [];
while ($kar = mysqli_fetch_assoc($karyawan)) {
    $id_karyawan = $kar['id_karyawan'];
    $nilaiQry = mysqli_query($connection, "
        SELECT p.id_kriteria, p.nilai, k.bobot
        FROM tb_penilaian p
        JOIN tb_kriteria k ON p.id_kriteria = k.id_kriteria
        WHERE p.id_karyawan = '$id_karyawan'
    ");
    $total = 0;
    while ($n = mysqli_fetch_assoc($nilaiQry)) {
        $total += $n['nilai'] * $n['bobot'];
    }
    $keterangan = $total >= 75 ? "Terus Dipertahankan" : "Berusaha Lebih Keras";
    $hasilEvaluasi[] = [
        'nama' => $kar['nama_lengkap'],
        'jabatan' => $kar['jabatan_posisi'],
        'total' => $total,
        'status' => $keterangan
    ];
}

// Hitung jumlah status
$jumlahLayak = count(array_filter($hasilEvaluasi, fn($h) => $h['status'] === "Terus Dipertahankan"));
$jumlahTidakLayak = count($hasilEvaluasi) - $jumlahLayak;

// Sort untuk top 5
usort($hasilEvaluasi, fn($a, $b) => $b['total'] <=> $a['total']);
$top5 = array_slice($hasilEvaluasi, 0, 5);
?>

<section class="section">
  <div class="section-header row align-items-center">
    <div class="col">
      <h1 class="mb-0">Dashboard</h1>
    </div>
  </div>

  <!-- Card Statistik -->
  <div class="row">
    <div class="col-lg-3 col-md-6">
      <div class="card card-statistic-1">
        <div class="card-icon bg-primary"><i class="fas fa-users"></i></div>
        <div class="card-wrap">
          <div class="card-header"><h4>Total Karyawan</h4></div>
          <div class="card-body"><?= $totalKaryawan ?></div>
        </div>
      </div>
    </div>

    <div class="col-lg-3 col-md-6">
      <div class="card card-statistic-1">
        <div class="card-icon bg-info"><i class="fas fa-list"></i></div>
        <div class="card-wrap">
          <div class="card-header"><h4>Total Kriteria</h4></div>
          <div class="card-body"><?= $totalKriteria ?></div>
        </div>
      </div>
    </div>

    <div class="col-lg-3 col-md-6">
      <div class="card card-statistic-1">
        <div class="card-icon bg-warning"><i class="fas fa-clipboard-check"></i></div>
        <div class="card-wrap">
          <div class="card-header"><h4>Total Penilaian</h4></div>
          <div class="card-body"><?= $totalPenilaian ?></div>
        </div>
      </div>
    </div>

    <div class="col-lg-3 col-md-6">
      <div class="card card-statistic-1">
        <div class="card-icon bg-success"><i class="fas fa-check-circle"></i></div>
        <div class="card-wrap">
          <div class="card-header"><h4>Layak</h4></div>
          <div class="card-body"><?= $jumlahLayak ?></div>
        </div>
      </div>
    </div>
  </div>

  <!-- Grafik -->
  <div class="row">
    <div class="col-lg-6">
      <div class="card">
        <div class="card-header"><h4>Grafik Total Evaluasi</h4></div>
        <div class="card-body">
          <canvas id="chartEvaluasi"></canvas>
        </div>
      </div>
    </div>

    <div class="col-lg-6">
      <div class="card">
        <div class="card-header"><h4>Perbandingan Status</h4></div>
        <div class="card-body">
          <canvas id="chartStatus"></canvas>
        </div>
      </div>
    </div>
  </div>

  <!-- Tabel Top 5 -->
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header"><h4>Top 5 Karyawan</h4></div>
        <div class="card-body table-responsive">
          <table class="table table-bordered">
            <thead>
              <tr>
                <th>No</th><th>Nama</th><th>Jabatan</th><th>Total Evaluasi</th><th>Status</th>
              </tr>
            </thead>
            <tbody>
              <?php $no=1; foreach ($top5 as $t): ?>
              <tr>
                <td><?= $no++ ?></td>
                <td><?= htmlspecialchars($t['nama']) ?></td>
                <td><?= htmlspecialchars($t['jabatan']) ?></td>
                <td><?= number_format($t['total'],2) ?></td>
                <td><?= $t['status'] ?></td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</section>

<?php require_once '../layout/_bottom.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Data untuk grafik evaluasi
const labelsEvaluasi = <?= json_encode(array_column($hasilEvaluasi, 'nama')) ?>;
const dataEvaluasi = <?= json_encode(array_column($hasilEvaluasi, 'total')) ?>;

new Chart(document.getElementById('chartEvaluasi'), {
  type: 'bar',
  data: {
    labels: labelsEvaluasi,
    datasets: [{
      label: 'Total Evaluasi',
      data: dataEvaluasi,
      backgroundColor: 'rgba(54, 162, 235, 0.7)'
    }]
  }
});

// Data untuk grafik status
new Chart(document.getElementById('chartStatus'), {
  type: 'pie',
  data: {
    labels: ['Terus Dipertahankan', 'Berusaha Lebih Keras'],
    datasets: [{
      data: [<?= $jumlahLayak ?>, <?= $jumlahTidakLayak ?>],
      backgroundColor: ['#28a745', '#dc3545']
    }]
  }
});
</script>
