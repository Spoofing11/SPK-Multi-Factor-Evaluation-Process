<?php
require_once '../helper/connection.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// CREATE
if (isset($_POST['create'])) {
    $kriteria = mysqli_real_escape_string($connection, $_POST['kriteria']);
    $bobot    = floatval($_POST['bobot']);

    if ($bobot <= 0 || $bobot > 1) {
        $_SESSION['info'] = [
            'status' => 'failed',
            'message' => 'Bobot harus antara 0 dan 1'
        ];
        header('Location: create.php');
        exit;
    }

    $query = "INSERT INTO tb_kriteria (kriteria, bobot) VALUES ('$kriteria', '$bobot')";
    $result = mysqli_query($connection, $query);

    $_SESSION['info'] = [
        'status' => $result ? 'success' : 'failed',
        'message' => $result ? 'Berhasil menyimpan data' : 'Gagal menyimpan data: ' . mysqli_error($connection)
    ];
    header('Location: index.php');
    exit;
}

// UPDATE
if (isset($_POST['update'])) {
    $id_kriteria = intval($_POST['id_kriteria']);
    $kriteria    = mysqli_real_escape_string($connection, $_POST['kriteria']);
    $bobot       = floatval($_POST['bobot']);

    if ($bobot <= 0 || $bobot > 1) {
        $_SESSION['info'] = [
            'status' => 'failed',
            'message' => 'Bobot harus antara 0 dan 1'
        ];
        header("Location: edit.php?id=$id_kriteria");
        exit;
    }

    $query = "UPDATE tb_kriteria SET kriteria = '$kriteria', bobot = '$bobot' WHERE id_kriteria = '$id_kriteria'";
    $result = mysqli_query($connection, $query);

    $_SESSION['info'] = [
        'status' => $result ? 'success' : 'failed',
        'message' => $result ? 'Berhasil mengupdate data' : 'Gagal update data: ' . mysqli_error($connection)
    ];
    header('Location: index.php');
    exit;
}

// DELETE
if (isset($_GET['delete'])) {
    $id_kriteria = intval($_GET['delete']);
    $query = "DELETE FROM tb_kriteria WHERE id_kriteria = '$id_kriteria'";
    $result = mysqli_query($connection, $query);

    $_SESSION['info'] = [
        'status' => $result ? 'success' : 'failed',
        'message' => $result ? 'Berhasil menghapus data' : 'Gagal hapus data: ' . mysqli_error($connection)
    ];
    header('Location: index.php');
    exit;
}
?>