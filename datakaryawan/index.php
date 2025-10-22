<?php

require_once '../layout/_top.php';
require_once '../helper/connection.php';
?>

<section class="section">
    <div class="section-header d-flex justify-content-between align-items-center">
        <h1>Data Karyawan</h1>
        <a href="create.php" class="btn btn-primary">
            + Tambah Karyawan
        </a>
    </div>

    <!-- Tabel Hasil -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped w-100" id="table-1">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>ID Karyawan</th>
                                    <th>Nama Lengkap</th>
                                    <th>Alamat</th>
                                    <th>No. Telp</th>
                                    <th>Jabatan / Posisi</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $no = 1;
                                $query = mysqli_query($connection, "SELECT * FROM tb_karyawan");
                                while ($row = mysqli_fetch_array($query)) {
                                ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><?= $row['id_karyawan'] ?></td>
                                        <td><?= $row['nama_lengkap'] ?></td>
                                        <td><?= $row['alamat'] ?></td>
                                        <td><?= $row['no_telp'] ?></td>
                                        <td><?= $row['jabatan_posisi'] ?></td>
                                        <td><?= $row['status'] ?></td>
                                        <td>
                                            <a href="edit.php?id=<?= $row['id_karyawan'] ?>" class="btn btn-warning btn-sm">Edit</a>
                                            <a href="store.php?delete=<?= $row['id_karyawan'] ?>"
                                                class="btn btn-danger btn-sm"
                                                onclick="return confirm('Yakin ingin hapus?')">Hapus</a>
                                        </td>
                                    </tr>
                                <?php } ?>
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
<?php
if (isset($_SESSION['info'])) :
    if ($_SESSION['info']['status'] == 'success') {
?>
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
<?php
    }

    unset($_SESSION['info']);
    $_SESSION['info'] = null;
endif;
?>
<script src="../assets/js/page/modules-datatables.js"></script>