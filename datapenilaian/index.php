<?php
require_once '../layout/_top.php';
require_once '../helper/connection.php';

// Ambil semua kriteria
$kriteria = mysqli_query($connection, "SELECT id_kriteria, kriteria FROM tb_kriteria");

// Ambil semua karyawan yang punya penilaian
$karyawan = mysqli_query($connection, "
  SELECT DISTINCT k.id_karyawan, k.nama_lengkap
  FROM tb_penilaian p
  JOIN tb_karyawan k ON p.id_karyawan = k.id_karyawan
  ORDER BY k.nama_lengkap
");
?>

<section class="section">
  <div class="section-header d-flex justify-content-between align-items-center">
    <h1>Data Penilaian</h1>
    <a href="create.php" class="btn btn-primary">+ Tambah Penilaian</a>
  </div>

  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-bordered table-striped w-100" id="table-1">
              <thead class="thead-light">
                <tr>
                  <th>No</th>
                  <th>Nama Karyawan</th>
                  <?php
                  $list_kriteria = [];
                  while ($kr = mysqli_fetch_assoc($kriteria)) {
                    $list_kriteria[] = $kr;
                    echo "<th>{$kr['kriteria']}</th>";
                  }
                  ?>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody>
                <?php $no = 1; ?>
                <?php while ($kar = mysqli_fetch_assoc($karyawan)) : ?>
                  <tr>
                    <td><?= $no++ ?></td>
                    <td><?= htmlspecialchars($kar['nama_lengkap']) ?></td>
                    <?php foreach ($list_kriteria as $kr) : ?>
                      <?php
                      $id_karyawan = $kar['id_karyawan'];
                      $id_kriteria = $kr['id_kriteria'];
                      $nilai = mysqli_query($connection, "
                        SELECT nilai FROM tb_penilaian
                        WHERE id_karyawan = '$id_karyawan' AND id_kriteria = '$id_kriteria'
                      ");
                      $row_nilai = mysqli_fetch_assoc($nilai);
                      ?>
                      <td><?= isset($row_nilai['nilai']) ? number_format($row_nilai['nilai'], 2) : '-' ?></td>
                    <?php endforeach; ?>
                    <td>
                      <a href="edit.php?id=<?= $kar['id_karyawan'] ?>" class="btn btn-warning btn-sm">Edit</a>
                      <a href="store.php?id=<?= $kar['id_karyawan'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Hapus semua penilaian karyawan ini?')">Hapus</a>
                    </td>
                  </tr>
                <?php endwhile; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<?php require_once '../layout/_bottom.php'; ?>
<!-- Page Specific JS File -->
<?php if (isset($_SESSION['info'])) :
    if ($_SESSION['info']['status'] == 'success') { ?>
        <script>
            iziToast.success({
                title: 'Sukses',
                message: `<?= $_SESSION['info']['message'] ?>`,
                position: 'topCenter',
                timeout: 5000
            });
        </script>
    <?php } else { ?>
        <script>
            iziToast.error({
                title: 'Gagal',
                message: `<?= $_SESSION['info']['message'] ?>`,
                timeout: 5000,
                position: 'topCenter'
            });
        </script>
<?php }
    unset($_SESSION['info']);
endif; ?>

<script src="../assets/js/page/modules-datatables.js"></script>