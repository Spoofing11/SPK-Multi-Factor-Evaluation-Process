<?php
require_once '../layout/_top.php';
require_once '../helper/connection.php';

// Ambil semua karyawan
$karyawanQuery = mysqli_query($connection, "SELECT id_karyawan, nama_lengkap, jabatan_posisi FROM tb_karyawan");
$karyawan = [];
while ($row = mysqli_fetch_assoc($karyawanQuery)) {
  $karyawan[$row['id_karyawan']] = $row;
}


// Ambil semua kriteria
$kriteriaRes = mysqli_query($connection, "SELECT * FROM tb_kriteria ORDER BY id_kriteria");
$kriteria = [];
while ($row = mysqli_fetch_assoc($kriteriaRes)) {
  $kriteria[$row['id_kriteria']] = [
    'kriteria' => $row['kriteria'],
    'bobot' => $row['bobot']
  ];
}

// Ambil semua karyawan
$karyawanRes = mysqli_query($connection, "SELECT * FROM tb_karyawan ORDER BY id_karyawan");
$karyawan = [];
while ($row = mysqli_fetch_assoc($karyawanRes)) {
  $karyawan[$row['id_karyawan']] = $row;
}

// Ambil penilaian
$penilaianRes = mysqli_query($connection, "SELECT * FROM tb_penilaian");
$penilaian = [];
while ($row = mysqli_fetch_assoc($penilaianRes)) {
  $penilaian[$row['id_karyawan']][$row['id_kriteria']] = $row['nilai'];
}
?>

<section class="section">
  <div class="section-header d-flex justify-content-between">
    <h1>Perhitungan Multi-Factor Evaluation Process (MFEP)</h1>
  </div>

  <!-- ================== TABEL NILAI KRITERIA ================== -->
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h4>Tabel Nilai Kriteria</h4>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-bordered">
              <thead>
                <tr>
                  <th>No</th>
                  <th>Nama Karyawan</th>
                  <?php foreach ($kriteria as $k): ?>
                    <th><?= htmlspecialchars($k['kriteria']) ?></th>
                  <?php endforeach; ?>
                </tr>
              </thead>
              <tbody>
                <?php
                $no = 1;
                foreach ($karyawan as $id_karyawan => $kar): ?>
                  <tr>
                    <td><?= $no++ ?></td>
                    <td><?= htmlspecialchars($kar['nama_lengkap']) ?></td>
                    <?php foreach ($kriteria as $id_kriteria => $k): ?>
                      <td><?= $penilaian[$id_karyawan][$id_kriteria] ?? '-' ?></td>
                    <?php endforeach; ?>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- ================== TABEL BOBOT EVALUASI ================== -->
 <div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-header">
        <h4>Tabel Bobot Evaluasi</h4>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-bordered">
            <thead>
              <tr>
                <th>No</th>
                <th>Nama Karyawan</th>
                <?php foreach ($kriteria as $k): ?>
                  <th><?= htmlspecialchars($k['kriteria']) ?> (<?= $k['bobot'] ?>)</th>
                <?php endforeach; ?>
              </tr>
            </thead>
            <tbody>
              <?php 
              $no=1;
              foreach ($karyawan as $id_karyawan => $kar): ?>
                <tr>
                  <td><?= $no++ ?></td>
                  <td><?= htmlspecialchars($kar['nama_lengkap']) ?></td>
                  <?php foreach ($kriteria as $id_kriteria => $k): 
                    $nilai = $penilaian[$id_karyawan][$id_kriteria] ?? 0;
                    $bobotEvaluasi = $nilai * $k['bobot'];
                  ?>
                    <td><?= number_format($bobotEvaluasi, ) ?></td>
                  <?php endforeach; ?>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

  <!-- ================== TABEL TOTAL EVALUASI ================== -->
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h4>Tabel Total Evaluasi</h4>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-bordered">
              <thead>
                <tr>
                  <th>No</th>
                  <th>Nama Karyawan</th>
                  <th>Jabatan</th>
                  <th>Total Evaluasi</th>
                  <th>Keterangan</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $no = 1;
                $chartLabels = [];
                $chartData = [];
                foreach ($karyawan as $id_karyawan => $kar):
                  $total = 0;
                  foreach ($kriteria as $id_kriteria => $k):
                    $nilai = $penilaian[$id_karyawan][$id_kriteria] ?? 0;
                    $total += $nilai * $k['bobot'];
                  endforeach;
                  $keterangan = $total >= 75 ? 'Terus Dipertahankan' : 'Berusaha Lebih Keras';
                  $chartLabels[] = $kar['nama_lengkap'];
                  $chartData[] = $total;
                ?>
                  <tr>
                    <td><?= $no++ ?></td>
                    <td><?= htmlspecialchars($kar['nama_lengkap']) ?></td>
                    <td><?= htmlspecialchars($kar['jabatan_posisi'] ?? '-') ?></td>
                    <td><?= number_format($total, ) ?></td>
                    <td><?= $keterangan ?></td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- ================== GRAFIK ================== -->
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h4>Grafik Total Evaluasi</h4>
        </div>
        <div class="card-body">
          <canvas id="chartEvaluasi" width="400" height="150"></canvas>
        </div>
      </div>
    </div>
  </div>
</section>


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  const ctx = document.getElementById('chartEvaluasi').getContext('2d');
  const chartEvaluasi = new Chart(ctx, {
    type: 'bar',
    data: {
      labels: <?= json_encode($chartLabels) ?>,
      datasets: [{
        label: 'Total Evaluasi',
        data: <?= json_encode($chartData) ?>,
        backgroundColor: 'rgba(54, 162, 235, 0.6)',
        borderColor: 'rgba(54, 162, 235, 1)',
        borderWidth: 1
      }]
    },
    options: {
      scales: {
        y: {
          beginAtZero: true,
          max: 100
        }
      }
    }
  });
</script>

<?php require_once '../layout/_bottom.php'; ?>