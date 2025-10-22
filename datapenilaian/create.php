<?php
require_once '../layout/_top.php';
require_once '../helper/connection.php';

// Ambil status dari form (jika sudah dipilih)
$status = isset($_GET['status']) ? $_GET['status'] : 'Tetap';

// Ambil data karyawan berdasarkan status
$karyawan = $karyawan = mysqli_query($connection, "
  SELECT k.id_karyawan, k.nama_lengkap
  FROM tb_karyawan k
  WHERE k.status = '$status'
  AND (
    SELECT COUNT(*) FROM tb_penilaian p WHERE p.id_karyawan = k.id_karyawan
  ) < (
    SELECT COUNT(*) FROM tb_kriteria
  )
");


// Ambil semua kriteria
$kriteria = mysqli_query($connection, "SELECT id_kriteria, kriteria FROM tb_kriteria");
?>

<section class="section">
  <div class="section-header">
    <h1 class="text-center w-100">Tambah Data Penilaian</h1>
  </div>

  <div class="row d-flex justify-content-center">
    <div class="col-12 col-md-10 col-lg-8">
      <div class="card shadow">
        <div class="card-header">
          <h4>Form Penilaian</h4>
        </div>

        <!-- Filter Status -->
        <div class="card-body">
          <form method="GET" action="">
            <div class="form-group">
              <label>Status Karyawan</label>
              <select name="status" class="form-control" onchange="this.form.submit()">
                <option value="Tetap" <?= $status == 'Tetap' ? 'selected' : '' ?>>Tetap</option>
                <option value="Kontrak" <?= $status == 'Kontrak' ? 'selected' : '' ?>>Kontrak</option>
              </select>
            </div>
          </form>
        </div>

        <!-- Form Penilaian -->
        <form action="store.php" method="POST">
          <div class="card-body">
            <div class="form-group">
              <label>Nama Karyawan</label>
              <select name="id_karyawan" class="form-control" required>
                <option value="" disabled selected>-- Pilih Karyawan --</option>
                <?php while ($row = mysqli_fetch_assoc($karyawan)) : ?>
                  <option value="<?= $row['id_karyawan'] ?>"><?= $row['nama_lengkap'] ?></option>
                <?php endwhile; ?>
              </select>
            </div>

            <hr>
            <h5 class="mb-3">Nilai Penilaian per Kriteria</h5>
            <div class="table-responsive">
              <table class="table table-bordered">
                <thead class="thead-light">
                  <tr>
                    <?php
                    // Ambil ulang kriteria karena sebelumnya sudah habis di while
                    $kriteria = mysqli_query($connection, "SELECT id_kriteria, kriteria FROM tb_kriteria");
                    while ($kr = mysqli_fetch_assoc($kriteria)) :
                    ?>
                      <th><?= $kr['kriteria'] ?></th>
                    <?php endwhile; ?>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <?php
                    // Ambil ulang kriteria untuk input nilai
                    $kriteria = mysqli_query($connection, "SELECT id_kriteria FROM tb_kriteria");
                    while ($kr = mysqli_fetch_assoc($kriteria)) :
                    ?>
                      <td>
                        <input type="hidden" name="id_kriteria[]" value="<?= $kr['id_kriteria'] ?>">
                        <input type="number" name="nilai[]" class="form-control" step="0.01" min="0" required>
                      </td>
                    <?php endwhile; ?>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>

          <div class="card-footer text-right">
            <button type="submit" name="create_batch" class="btn btn-primary">Simpan Semua</button>
            <a href="index.php" class="btn btn-secondary">Kembali</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</section>

<?php require_once '../layout/_bottom.php'; ?>