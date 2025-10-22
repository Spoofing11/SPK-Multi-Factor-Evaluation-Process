<?php
require_once '../layout/_top.php';
require_once '../helper/connection.php';

if (!isset($_GET['id'])) {
  header('Location: index.php');
  exit;
}

$id = $_GET['id'];
$query = mysqli_query($connection, "SELECT * FROM tb_karyawan WHERE id_karyawan='$id'");
$data  = mysqli_fetch_array($query);
?>

<section class="section">
  <div class="section-header">
    <h1 class="text-center w-100">Edit Data Karyawan</h1>
  </div>

  <div class="row min-vh-100 d-flex justify-content-center align-items-center">
    <div class="col-12 col-md-8 col-lg-6">
      <div class="card shadow">
        <div class="card-header">
          <h4>Form Edit</h4>
        </div>
        <form action="store.php" method="POST">
          <div class="card-body">
            <div class="form-group">
              <label>ID Karyawan</label>
              <input type="text" name="id_karyawan" class="form-control"
                     value="<?php echo $data['id_karyawan']; ?>" readonly>
            </div>
            <div class="form-group">
              <label>Nama Lengkap</label>
              <input type="text" name="nama_lengkap" class="form-control"
                     value="<?php echo $data['nama_lengkap']; ?>" required>
            </div>
            <div class="form-group">
              <label>Alamat</label>
              <textarea name="alamat" class="form-control" rows="2"><?php echo $data['alamat']; ?></textarea>
            </div>
            <div class="form-group">
              <label>No. Telp</label>
              <input type="text" name="no_telp" class="form-control"
                     value="<?php echo $data['no_telp']; ?>">
            </div>
            <div class="form-group">
              <label>Jabatan / Posisi</label>
              <input type="text" name="jabatan_posisi" class="form-control"
                     value="<?php echo $data['jabatan_posisi']; ?>">
            </div>
            <div class="form-group">
              <label>Status</label>
              <select name="status" class="form-control">
                <option value="Aktif" <?php echo ($data['status'] == 'Aktif') ? 'selected' : ''; ?>>Aktif</option>
                <option value="Tidak Aktif" <?php echo ($data['status'] == 'Tidak Aktif') ? 'selected' : ''; ?>>Tidak Aktif</option>
              </select>
            </div>
          </div>
          <div class="card-footer text-right">
            <button type="submit" name="update" class="btn btn-primary">Update</button>
            <a href="index.php" class="btn btn-secondary">Kembali</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</section>

<?php require_once '../layout/_bottom.php'; ?>
