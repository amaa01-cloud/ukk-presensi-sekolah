<?php
session_start();
if(!isset($_SESSION['admin'])) { header("Location: login.php"); exit; }
require_once 'Kelas.php';

$pesan_error = "";
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_kelas = trim($_POST['nama_kelas']);
    $jurusan = trim($_POST['jurusan']);

    $kelasObj = new Kelas();
    $result = $kelasObj->tambah($nama_kelas, $jurusan);

    if($result === true) {
        header("Location: kelas_tampil.php");
        exit;
    } else {
        $pesan_error = $result;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head><title>Tambah Kelas</title></head>
<body>
    <h2>Tambah Kelas Baru</h2>
    <?php if($pesan_error): ?><p style="color: red;"><?= $pesan_error ?></p><?php endif; ?>
    <form method="POST">
        <label>Nama Kelas (Contoh: XII PPLG 1):</label><br>
        <input type="text" name="nama_kelas" required value="<?= isset($_POST['nama_kelas']) ? htmlspecialchars($_POST['nama_kelas']) : '' ?>"><br><br>
        
        <label>Jurusan:</label><br>
        <input type="text" name="jurusan" required value="<?= isset($_POST['jurusan']) ? htmlspecialchars($_POST['jurusan']) : '' ?>"><br><br>
        
        <button type="submit">Simpan</button>
        <a href="kelas_tampil.php">Kembali</a>
    </form>
</body>
</html>