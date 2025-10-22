<?php
require_once '../layout/_top.php';
require_once '../helper/connection.php';
?>

<section class="section">
  <div class="section-header">
    <h1 class="text-center w-100">Tambah Data Karyawan</h1>
  </div>

  <div class="row min-vh-100 d-flex justify-content-center align-items-center">
    <div class="col-12 col-md-8 col-lg-6">
      <div class="card shadow">
        <div class="card-header">
          <h4>Form Input</h4>
        </div>
        <form action="store.php" method="POST">
          <div class="card-body">
            <div class="form-group">
              <label>ID Karyawan</label>
              <input type="text" name="id_karyawan" class="form-control" required>
            </div>
            <div class="form-group">
              <label>Nama Lengkap</label>
              <input type="text" name="nama_lengkap" class="form-control" required>
            </div>
            <div class="form-group">
              <label>Alamat</label>
              <textarea name="alamat" class="form-control" rows="2"></textarea>
            </div>
            <div class="form-group">
              <label>No. Telp</label>
              <input type="text" name="no_telp" class="form-control">
            </div>
            <div class="form-group">
              <label>Jabatan / Posisi</label>
              <input type="text" name="jabatan_posisi" class="form-control">
            </div>
            <div class="form-group">
              <label>Status</label>
              <select name="status" class="form-control">
                <option value="Aktif">Kontrak</option>
                <option value="Tidak Aktif">Tetap</option>
              </select>
            </div>
          </div>
          <div class="card-footer text-right">
            <button type="submit" name="create" class="btn btn-primary">Simpan</button>
            <a href="index.php" class="btn btn-secondary">Kembali</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</section>

<?php require_once '../layout/_bottom.php'; ?>