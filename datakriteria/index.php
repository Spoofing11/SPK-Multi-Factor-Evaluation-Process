<?php
require_once '../layout/_top.php';
require_once '../helper/connection.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<section class="section">
    <div class="section-header d-flex justify-content-between align-items-center">
        <h1>Data Kriteria</h1>
        <a href="create.php" class="btn btn-primary">
            + Tambah Kriteria
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
                                    <th>Kriteria</th>
                                    <th>Bobot</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $no = 1;
                                $query = mysqli_query($connection, "SELECT * FROM tb_kriteria");
                                while ($row = mysqli_fetch_array($query)) :
                                ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><?= htmlspecialchars($row['kriteria']) ?></td>
                                        <td><?= number_format($row['bobot'], 1) ?></td>
                                        <td>
                                            <a href="edit.php?id=<?= $row['id_kriteria'] ?>" class="btn btn-warning btn-sm">Edit</a>
                                            <a href="store.php?delete=<?= $row['id_kriteria'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus data ini?')">Hapus</a>
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