<?php
require_once '../layout/_top.php';
require_once '../helper/connection.php';
?>

<section class="section">
  <div class="section-header">
    <h1 class="text-center w-100">Tambah Data Kriteria</h1>
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
              <label>Nama Kriteria</label>
              <input type="text" name="kriteria" class="form-control" required>
            </div>
            <div class="form-group">
              <label>Bobot</label>
              <input type="number" name="bobot" class="form-control" step="0.01" min="0" max="1" required>
              <small class="form-text text-muted">Gunakan angka desimal, misalnya 0.2</small>
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