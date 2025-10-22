<?php
require_once '../layout/_top.php';
require_once '../helper/connection.php';
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

if (!isset($_GET['id'])) {
  header('Location: index.php');
  exit;
}

$id_karyawan = mysqli_real_escape_string($connection, $_GET['id']);


// Ambil semua penilaian + nama karyawan
$query = mysqli_query($connection, "
  SELECT p.id_penilaian, p.id_kriteria, p.nilai, k.kriteria, kar.nama_lengkap
  FROM tb_penilaian p
  JOIN tb_kriteria k ON p.id_kriteria = k.id_kriteria
  JOIN tb_karyawan kar ON p.id_karyawan = kar.id_karyawan
  WHERE p.id_karyawan = '$id_karyawan'
  ORDER BY k.id_kriteria
");


if (mysqli_num_rows($query) === 0) {
  $_SESSION['info'] = [
    'status' => 'failed',
    'message' => 'Data penilaian untuk karyawan ini tidak ditemukan'
  ];
  header('Location: index.php');
  exit;
}

// Ambil 1 baris untuk dapat nama karyawan
$firstRow = mysqli_fetch_assoc($query);
$nama = $firstRow['nama_lengkap'];

// Reset pointer hasil query biar bisa dipakai ulang di while
mysqli_data_seek($query, 0);
?>

<?php require_once '../layout/_top.php'; ?>

<section class="section">
  <div class="section-header">
    <h1 class="text-center w-100">Edit Penilaian: <?= htmlspecialchars($nama) ?></h1>
  </div>

  <div class="row d-flex justify-content-center">
    <div class="col-12 col-md-10">
      <div class="card shadow">
        <div class="card-header">
          <h4>Form Edit Penilaian</h4>
        </div>
        <form action="store.php" method="POST">
          <input type="hidden" name="id_karyawan" value="<?= $id_karyawan ?>">
          <div class="card-body">
            <table class="table table-bordered table-striped text-center">
              <thead>
                <tr>
                  <th>No</th>
                  <th>Kriteria</th>
                  <th>Nilai</th>
                </tr>
              </thead>
              <tbody>
                <?php $no = 1; while ($row = mysqli_fetch_assoc($query)) : ?>
                  <tr>
                    <td><?= $no++ ?></td>
                    <td>
                      <?= htmlspecialchars($row['kriteria']) ?>
                      <input type="hidden" name="id_penilaian[]" value="<?= $row['id_penilaian'] ?>">
                      <input type="hidden" name="id_kriteria[]" value="<?= $row['id_kriteria'] ?>">
                    </td>
                    <td>
                      <input type="number" name="nilai[]" 
                             class="form-control text-center" 
                             value="<?= $row['nilai'] ?>" 
                             step="0.01" min="0" required>
                    </td>
                  </tr>
                <?php endwhile; ?>
              </tbody>
            </table>
          </div>
          <div class="card-footer text-right">
            <button type="submit" name="update_batch" class="btn btn-primary">Update Semua</button>
            <a href="index.php" class="btn btn-secondary">Kembali</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</section>


<?php require_once '../layout/_bottom.php'; ?>
